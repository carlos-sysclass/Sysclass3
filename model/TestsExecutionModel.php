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
            te.try_index,
            te.start_timestamp,
            te.paused,
            te.pending,
            t.time_limit as 'test#timelimit'/*,
            t.**/
        FROM `mod_tests_execution` te
        LEFT JOIN mod_tests t ON (t.id = te.test_id)";

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

    protected function invalidate($test_try, $progress) {
        $this->update(
            array(
                'pending' => '0',
                'time_elapsed' => $progress['time_elapsed']
            ),
            $test_try['id']
        );

        // CALCULATE SCORES
    }

    protected function progressInfo($test_try) {
        $result = array();
        $result['started'] = $started = $test_try['start_timestamp'];
        $now = time();
        $result['time_elapsed'] = $time_elapsed = $now - $started;
        $timelimit = @isset($test_try['test']['timelimit']) ? $test_try['test']['timelimit'] : false;

        if (!$timelimit) {
            // CONTINUE IN THIS TEST.. NO TIME LIMIT, RETURN THE $ID
            $result['invalidate'] = false;
        } else {
            $result['expire_in'] = $timelimit = ($timelimit * 60);
            $result['expire_in'] = $expiresIn = $started + ($timelimit * 60);
            if ($expiresIn < $now) {
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
                'pending' => 1
            ))->getItems();

            if(count($executions) > 0) {
                $test_try = end($executions);

                if (@isset($test_try['progress']['invalidate']) && $test_try['progress']['invalidate'] === TRUE) {
                    $this->invalidate($test_try, $test_try['progress']);
                    // EXECUTION EXPIRED, CHECK IF THE USER CAN TAKE ANOTHER (PROBABLY CHECKED BEFORE)
                } else {
                    $this->rollProgress($test_try, $test_try['progress']);
                    return $test_try['id'];
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
            $this->invalidate($test_try, $progress);
            // EXECUTION EXPIRED, CHECK IF THE USER CAN TAKE ANOTHER (PROBABLY CHECKED BEFORE)
        } else {
            $this->rollProgress($test_try, $progress);
        }
        $result = parent::setItem($data, $identifier);

        return $result;
    }
}
