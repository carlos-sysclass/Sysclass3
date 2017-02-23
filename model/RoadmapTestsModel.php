<?php
class RoadmapTestsModel extends TestsModel implements ISyncronizableModel {

    public function init()
    {
        //$this->unit_type =  "test";

        parent::init();
    }
    protected function parseItem($item) {
        $item = parent::parseItem($item);

        if (!is_null($this->getUserFilter())) {
            $item['executions'] = $this->model("tests/execution")->addFilter(array(
                'test_id' => $item['id'],
                'user_id' => $this->getUserFilter()
            ))->getItems();
        }
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
        $data = parent::getItem($identifier);
        if (count($data) == 0) {
            return $data;
        }

        // GET CLASSES
        //  TODO CREATE A ROADMAP/LESSON MODEL, TO GET ALL LESSONS FROM THIS CLASS
        $data['questions'] = $this->model("tests/question")->addFilter(array(
            'unit_id' => $identifier
        ))->getItems();



        return $this->parseItem($data);
    }

    public function calculateTestScore($testData) {
        if (!array_key_exists('questions', $testData)) {
            $testData['questions'] = $this->model("tests/question")->addFilter(array(
                'unit_id' => $testData['id']
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
