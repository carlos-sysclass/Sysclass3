<?php
class TestsExecutionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_tests_execution";
        $this->id_field = "id";
        $this->mainTablePrefix = "te";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            te.id,
            te.test_id,
            te.user_id,
            te.try_index,
            te.start_timestamp,
            te.paused,
            te.pending,
            te.answers,
            te.completed,
            te.user_score,
            u.name as 'user#name',
            u.surname as 'user#surname',
            u.login as 'user#login',
            t.time_limit as 'test#timelimit',
            t.test_repetition as 'test#test_repetition'/*,
            t.**/
        FROM `mod_tests_execution` te
        LEFT JOIN mod_tests t ON (t.id = te.test_id)
        LEFT JOIN users u ON (u.id = te.user_id)";

        $this->order = array("try_index ASC");

        parent::init();
    }

    protected function rollProgress($test_try, $progress) {
        $this->update(
            array(
                'time_elapsed' => $progress['time_elapsed']
            ),
            $test_try['id']
        );
    }

    protected function completeTest($test_try, $progress) {
        $now = time();
        if ($now - $progress['started'] > $progress['time_elapsed']) {
            $progress['time_elapsed'] = $now - $progress['started'];
        }
        $this->update(
            array(
                'pending' => '0',
                'time_elapsed' => $progress['time_elapsed'],
                'completed' => time()
            ),
            array(
                'id' => $test_try['id'],
                'pending' => 1
            )
        );

        $this->calculateUserScore($test_try);
    }

    public function calculateUserScore($execution) {
        if (is_array($execution)) {
            $executionData = $execution;
        } else {
            $executionData = $this->getItem($execution);
        }

        $questionModel = $this->model("tests/question");

        $testData = $this->model("tests")->getItem($executionData['test_id']);

        $questionsData = $questionModel->addFilter(array(
            'lesson_id' => $executionData['test_id']
        ))->getItems();

        $testPoints = 0;
        $totalPoints = 0;

        foreach($questionsData as $question) {
            $testPoints += $question['points'] * $question['weight'];
            $totalPoints += $questionModel->correct($question, $executionData['answers'][$question['id']]);
        }

        $this->update(
            array(
                'user_points' => $totalPoints,
                'user_score'  => $totalPoints / $testPoints
            ),
            array(
                'id' => $executionData['id'],
            )
        );

        return true;
    }




    protected function canExecuteAgain($test_try) {
        $test_repetition = @isset($test_try['test']['test_repetition']) ? $test_try['test']['test_repetition'] : false;

        if (is_numeric($test_repetition)) {
            $executions = $this->clear()->addFilter(array(
                'test_id' => $test_try['test_id'],
                'user_id' => $test_try['user_id']
            ))->getItems();

            $test_repetition = intval($test_repetition);

            if ($test_repetition > count($executions)) {
                return count($executions)+1;
            } else {
                return false;
            }

        }
        // ASSUMES NO LIMIT
        return true;
    }

    protected function progressInfo($test_try) {
        $result = array();
        $result['started'] = $started = $test_try['start_timestamp'];



        if ($test_try['pending'] == 0) {
            $completed = $test_try['completed'];
        } else {
            $completed = time();
        }
        $result['time_elapsed'] = $time_elapsed = $completed - $started;
        $timelimit = @isset($test_try['test']['timelimit']) ? $test_try['test']['timelimit'] : false;

        if (!$timelimit) {
            // CONTINUE IN THIS TEST.. NO TIME LIMIT, RETURN THE $ID
            $result['invalidate'] = false;
        } else {
            $timelimit = ($timelimit * 60);
            $result['expire_in'] = $expiresIn = $started + ($timelimit);
            //var_dump($ex);
            $now = time();
            if ($expiresIn <= $now) {
                // EXECUTION EXPIRED, CHECK IF THE USER CAN TAKE ANOTHER (PROBABLY CHECKED BEFORE)
                $result['invalidate'] = true;
            } else {
                $result['invalidate'] = false;
            }
        }

        return $result;
    }

    protected function parseItem($item)
    {
        $item['progress'] = $this->progressInfo($item);
        $item['answers'] = json_decode($item['answers']);

        return $item;
    }

    public function getItems()
    {
        $data = parent::getItems();

        // LOAD INSTRUCTORS
        foreach($data as $key => $item) {
            $data[$key] = $this->parseItem($item);
        }
        return $data;
    }

    public function getItem($identifier)
    {
        $item = parent::getItem($identifier);
        return $this->parseItem($item);
    }

    public function addItem($data)
    {
        // CHECK IF THERE'S ANY EXECUTION PENDING
        if (array_key_exists('test_id', $data)) {
            $executions = $this->addFilter(array(
                'test_id' => $data['test_id'],
                'user_id' => $data['user_id']
            ))->getItems();

            if(count($executions) > 0) {
                $test_try = end($executions);

                if ($test_try['pending'] == "1") {
                    if (@isset($test_try['progress']['invalidate']) && $test_try['progress']['invalidate'] === TRUE) {
                        $this->completeTest($test_try, $test_try['progress']);
                    } else {
                        $this->rollProgress($test_try, $test_try['progress']);
                        return $test_try['id'];
                    }
                }

                // EXECUTION EXPIRED, CHECK IF THE USER CAN TAKE ANOTHER (PROBABLY CHECKED BEFORE)
                if ($try_index = $this->canExecuteAgain($test_try)) {
                    $data['start_timestamp'] = time();
                    $data['try_index'] = $try_index;

                    return parent::addItem($data);
                }
            } else {
                $data['start_timestamp'] = time();
                $execution_id = parent::addItem($data);

                return $execution_id;
            }
        }

        return false;
    }

    public function setItem($data, $identifier)
    {
        $progress = $this->progressInfo($data);

        $test_try = $data;

        if (@isset($progress['invalidate']) && $progress['invalidate'] === TRUE) {
            $this->completeTest($test_try, $progress);
            return true;
            // EXECUTION EXPIRED, CHECK IF THE USER CAN TAKE ANOTHER (PROBABLY CHECKED BEFORE)
        } else {
            $this->rollProgress($test_try, $progress);

            $data['answers'] = json_encode($data['answers']);

            if ($data['complete'] == "1") {
                $this->completeTest($data, $progress);
                return true;
            }

            return parent::setItem($data, $identifier);
        }
        //var_dump($data);
        //exit;
        //$this->saveAnswers($data['answer'], $data['id']);
    }

}
