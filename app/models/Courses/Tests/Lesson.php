<?php
namespace Sysclass\Models\Courses\Tests;

use Sysclass\Models\Courses\Lesson as BaseLesson;

class Lesson extends BaseLesson
{
    protected $assignedData = null;

    public function initialize()
    {
        parent::initialize();

        $this->setSource("mod_lessons");

        $this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\Tests\\Test",
            "id",
            array('alias' => 'Test')
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Courses\\Tests\TestQuestions",
            "lesson_id", "question_id",
            "Sysclass\\Models\\Courses\\Questions\Question",
            "id",
            array('alias' => 'Questions')
        );
    }

    public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
        $this->assignedData = $data;
        return parent::assign($data, $dataColumnMap, $whiteList);
    }

    public function beforeSave() {
        $this->type = 'test';
    }
    
    public function afterSave() {
        // SAVE THE LINKED TEST
        if (array_key_exists('test', $this->assignedData) && is_array($this->assignedData['test'])) {
            $testModel = new Test();
            $testModel->assign($this->assignedData['test']);
            $testModel->id = $this->id;
            $testModel->save();
        }
        
    }

    protected function resetOrder($lesson_id) {
        $manager = \Phalcon\DI::GetDefault()->get("modelsManager");

        $phql = "UPDATE Sysclass\\Models\\Courses\\Tests\TestQuestions 
            SET position = -1 WHERE lesson_id = :lesson_id:";

        return $manager->executeQuery(
            $phql,
            array(
                'lesson_id' => $this->id
            )
        );
    }

    public function setQuestionOrder(array $order_ids) {
        $status = self::resetOrder();
        $manager = \Phalcon\DI::GetDefault()->get("modelsManager");

        foreach($order_ids as $index => $question_id) {
            $phql = "UPDATE Sysclass\\Models\\Courses\\Tests\TestQuestions
                SET position = :position: 
                WHERE id = :id: AND lesson_id = :lesson_id:";

            $status->success() && $status = $manager->executeQuery(
                $phql,
                array(
                    'position' => $index + 1,
                    'id' => $question_id,
                    'lesson_id' => $this->id
                )
            );

        }

        return $status->success();
    }

    public function calculateTestScore() {
        $questions = $this->getQuestions();
        /*
        if (!array_key_exists('questions', $testData)) {
            $testData['questions'] = $this->model("tests/question")->addFilter(array(
                'lesson_id' => $testData['id']
            ))->getItems();
        }
        */
        $testScore = 0;
        foreach($questions as $question) {
            $testScore += $question->points * $question->weight;
        }

        return $testScore;
        //return $testData;

    }

    public function getUserPendingTests() {
        $di = $this->getDI();
        $user_id = $di->get("user")->id;

        $manager = $di->get("modelsManager");

        $phql = "SELECT tl.*
                FROM 
                    Sysclass\\Models\\Enrollments\\CourseUsers ecu
                LEFT JOIN Sysclass\\Models\\Courses\\Course cc
                    ON (ecu.course_id = cc.id)
                LEFT JOIN Sysclass\\Models\\Courses\\CourseClasses ccc
                    ON (ccc.course_id = cc.id)
                LEFT JOIN Sysclass\\Models\\Courses\\Classe ccl
                    ON (ccc.class_id = ccl.id)
                LEFT JOIN Sysclass\\Models\\Courses\\Tests\\Lesson tl
                    ON (tl.class_id = ccl.id)
                LEFT JOIN Sysclass\\Models\\Courses\\Tests\\Execution cte
                    ON (tl.id = cte.test_id AND ecu.user_id = :user_id:)
                WHERE ecu.user_id = :user_id: AND tl.type = 'test'
                    AND (cte.user_id IS NULL OR cte.user_id <> :user_id:)";

        $status = $manager->executeQuery(
            $phql,
            array(
                'user_id' => $user_id
            )
        );
        
        return $status;

    }


}
