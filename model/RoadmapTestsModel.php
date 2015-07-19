<?php
class RoadmapTestsModel extends TestsModel implements ISyncronizableModel {

    public function init()
    {
        //$this->lesson_type =  "test";

        parent::init();
    }
    /*
    protected function parseItem($item) {
        $userModel =  $this->model("users/collection");

        $item['class']['instructor_id'] = json_decode($item['class']['instructor_id'], true);

        if (is_array($item['class']['instructor_id'])) {
            $item['class']['instructors'] = $userModel->clear()->addFilter(array(
                'can_be_instructor' => true,
                'id'    =>  $item['class']['instructor_id']
            ))->getItems();
        } else {
            $item['class']['instructors'] = array();
        }

        return $item;
    }
    /*
    public function getItems()
    {
        $data = parent::getItems();

        // LOAD INSTRUCTORS
        foreach($data as $key => $item) {
            $data[$key] = $this->parseItem($item);
        }
        return $data;
    }
    */
    public function getItem($identifier)
    {
        $data = parent::getItem($identifier);
        if (count($data) == 0) {
            return $data;
        }

        // GET CLASSES
        //  TODO CREATE A ROADMAP/LESSON MODEL, TO GET ALL LESSONS FROM THIS CLASS
        $data['questions'] = $this->model("tests/question")->addFilter(array(
            'lesson_id' => $identifier
        ))->getItems();

        return $this->parseItem($data);
    }

    public function calculateTestScore($testData) {
        if (!array_key_exists('questions', $testData)) {
            $testData['questions'] = $this->model("tests/question")->addFilter(array(
                'lesson_id' => $testData['id']
            ))->getItems();
        }
        $testScore = 0;
        foreach($testData['questions'] as $question) {
            $testScore += $question['points'] * $question['weight'];
        }

        $testData['score'] = $testScore;
        return $testData;

    }

}
