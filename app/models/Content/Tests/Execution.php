<?php
namespace Sysclass\Models\Content\Tests;

use Plico\Mvc\Model,
    Sysclass\Models\Users\User,
    Sysclass\Models\Courses\Tests\Unit as TestLesson,
    Sysclass\Models\Courses\Tests\TestQuestions,
    Sysclass\Models\Courses\Grades\Grade,
    Sysclass\Models\Content\Progress\Unit as UnitProgress,
    Sysclass\Models\Content\Progress\Course as CourseProgress;

class Execution extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests_execution");

		$this->belongsTo(
            "test_id",
            "Sysclass\\Models\\Courses\\Tests\\Test",
            "id",
            array('alias' => 'Test')
        );

        $this->belongsTo(
            "user_id",
            "Sysclass\\Models\\Users\\User",
            "id",
            array('alias' => 'User')
        );

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Courses\\Tests\ExecutionQuestions",
            "unit_id", 
            array('alias' => 'ExecutionQuestions')
        );
    }

    public function beforeSave() {
        $this->answers = json_encode($this->answers, true);
    }

    public function canExecuteAgain(User $user) {

        $test = $this->getTest();
        $test_repetition = @isset($test->test_repetition) ? $test->test_repetition : false;

        if (is_numeric($test_repetition)) {
            $test_repetition = intval($test_repetition);

            $total_executions = self::count([
                'conditions' => "test_id = ?0 AND user_id = ?1",
                'bind' => [$this->test_id, $user->id]
            ]);

            if ($test_repetition <= 0 || $test_repetition > $total_executions) {
                return true;
            } else {
                return false;
            }

        }
        // ASSUMES NO LIMIT
        return true;
    }

    protected function rollProgress($progress) {
        $this->time_elapsed = $progress['time_elapsed'];

        //$this->save();
        /*        
        $this->update(
            array(
                'time_elapsed' => $progress['time_elapsed']
            ),
            $test_try['id']
        );
        */
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


    public function calculateUserScore($execution) {
        $executionData = $this->toArray();

        //$questionModel = $this->model("tests/question");

        //$testData = $this->model("tests")->getItem($executionData['test_id']);

        $testModel = TestLesson::findFirstById($executionData['test_id']);

        if ($testModel) {

            $testData = $testModel->getTest()->toArray();

            //var_dump($executionData);

            $testQuestions = $testModel->shuffleTestQuestions($execution['id']);


            /*
            $questionsData = $questionModel->addFilter(array(
                'unit_id' => $executionData['test_id']
            ))->getItems();

            */

            $testPoints = 0;
            $totalPoints = 0;

            foreach($testQuestions as $question) {
                //var_dump(get_class($question));
                //exit;
                //$questionData = $question->toArray();
                //$questionData['question'] = $question->getQuestion()->toArray();

                $testPoints += $question->points * $question->weight;

                $totalPoints += $question->correct($execution['answers'][$question->id]);
            }

            $userScore = $totalPoints / $testPoints;

            if (@isset($execution['test']['grade_id']) && is_numeric($execution['test']['grade_id'])) {
                $gradeId = $execution['test']['grade_id'];

                $gradesModel = Grade::findFirstById($gradeId);

                //var_dump($gradesModel->toArray());
                //exit;

                //$gradesModel = $this->model("grades");
                //$gradeData = $gradesModel->getItem($gradeId);

                if ($gradesModel) {

                    $ranges = $gradesModel->getRanges();

                    $checkScore = $userScore * 100;

                    foreach ($ranges as $key => $range) {
                        if ($checkScore >= $range->range_start && $checkScore <= $range->range_end) {

                            $userGrade = $range->grade;
                            break;
                        }
                    }
                }
            }

            $pass = $userScore > (floatval($testData['minimum_score']) / 100);


            $this->assign(
                array(
                    'user_points' => $totalPoints,
                    'user_score'  => $userScore,
                    'user_grade'  => !is_null($userGrade) ? $userGrade : $userScore * 100 . "%",
                    'pass'        => ($pass) ? 1 : 0
                )
            );

            //$this->save();

            return $pass;
        }
        return false;
    }


    protected function completeTest($progress, $data) {
        $now = time();
        if ($now - $progress['started'] > $progress['time_elapsed']) {
            $progress['time_elapsed'] = $now - $progress['started'];
        }

        $this->pending = 0;
        $this->time_elapsed = $progress['time_elapsed'];
        $this->completed = time();

        //$this->save();
        /*
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
        */

        $pass = $this->calculateUserScore($data);

        //$evManager = \Phalcon\DI::getDefault()->get("eventsManager");


        $UnitProgress = UnitProgress::findFirst(array(
            'conditions' => 'user_id = ?0 and unit_id = ?1',
            'bind' => array($this->user_id, $this->test_id)
        ));

        if (!$UnitProgress) {
            $UnitProgress = new UnitProgress();
            $UnitProgress->factor = 0;
            $UnitProgress->user_id = $this->user_id;
            $UnitProgress->unit_id = $this->test_id;
        }

        if ($pass) {
            // COMPLETE UNIT
            $UnitProgress->factor = 1;
        } else {
            if ($UnitProgress->factor != 1) {
                $UnitProgress->factor = 0;
            }
        }
        $UnitProgress->save();
        $unit = $UnitProgress->getUnit();

        $CourseProgress = CourseProgress::findFirst(array(
            'conditions' => 'user_id = ?0 AND class_id = ?1',
            'bind' => array($UnitProgress->user_id, $unit->class_id)
        ));

        if (!$CourseProgress) {
            $CourseProgress = new CourseProgress();
            $CourseProgress->user_id = $this->user_id;
            $CourseProgress->class_id = $unit->class_id;
            $CourseProgress->save();
        }

        $status = $CourseProgress->updateProgress();
    }

    public function updateProgress($data, $identifier)
    {
        $progress = $this->progressInfo($data);

        $test_try = $data;

        if (@isset($progress['invalidate']) && $progress['invalidate'] === TRUE) {
            $this->completeTest($progress, $data);
            $this->save();
            return true;
        } else {
            $this->rollProgress($progress);

            if ($data['complete'] == "1") {
                $this->completeTest($progress, $data);
            }
            /*
            unset($data['user_score']);
            unset($data['user_points']);
            unset($data['user_grade']);
            unset($data['user_grade']);
            unset($data['pending']);
            unset($data['completed']);
            unset($data['time_elapsed']);
            unset($data['pass']);
            */


            $this->answers = $data['answers'];

            return $this->save();
        }
    }

    public function afterSave()
    {
        $this->refresh();
    }
}
