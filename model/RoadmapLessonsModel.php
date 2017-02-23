<?php
class RoadmapUnitsModel extends BaseUnitsModel implements ISyncronizableModel {

    public function init()
    {
        $this->unit_type =  "unit";

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
    */
    public function getItems()
    {
        $data = parent::getItems();

        // LOAD INSTRUCTORS
        foreach($data as $key => $item) {
            $data[$key] = $this->parseItem($item);

            if ($this->getUserFilter()) {
                $progress = $this->model("units/progress")->clear()
                    ->setUserFilter($this->getUserFilter())
                    ->addFilter(array(
                        'unit_id'     => $item['id']
                    ))->getItems();
                $data[$key]['progress'] = reset($progress);
            }

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
        $data['contents'] = $this->model("units/content")
            ->setUserFilter($this->getUserFilter())
            ->addFilter(array(
                'unit_id' => $identifier
            ))->getItems();

        if ($this->getUserFilter()) {
            /*
            $this->model("units/progress")
                ->setUserFilter($this->getUserFilter())
                ->recalculateProgress($identifier);
            */
            $progress = $this->model("units/progress")->clear()
                ->setUserFilter($this->getUserFilter())
                ->addFilter(array(
                    'unit_id'     => $identifier
                ))->getItems();

            $data['progress'] = reset($progress);
        }

        return $this->parseItem($data);
    }

    /*
    public function addItem($data) {
        $classModel = $this->model("classes");
        if (!array_key_exists('class_id', $data)) {
            $data['class_id'] = $classModel->addItem($data['class']);
        }
        $identifier = parent::addItem($data);

        if (array_key_exists('period_id', $data)) {
            $periodsModel = $this->model("roadmap/periods");
            $periodsModel->addClass($data['course_id'], $data['period_id'], $data['class_id']);
        }

        return $identifier;
    }

    public function setItem($data, $identifier, $quote = true) {
        $classModel = $this->model("classes");
        if (array_key_exists('class_id', $data)) {
            $classModel->setItem($data['class'], $data['class_id']);
        }

        if (array_key_exists('period_id', $data)) {
            $periodsModel = $this->model("roadmap/periods");
            $periodsModel->addClass($data['course_id'], $data['period_id'], $data['class_id']);
        }

        return parent::setItem($data, $identifier, $quote);
        //return array($data['class_id'], $data['class_id']);
    }

    protected function resetOrder($course_id, $period_id = null) {
        $filter = array(
            'course_id' => $course_id
        );
        if (!is_null($period_id)) {
            $filter['class_id'] = "SELECT class_id FROM mod_roadmap_classes_to_periods WHERE period_id = {$period_id}";
        }
        $this->setItem(array(
            'position' => -1
        ), $filter, false);
    }

    public function setOrder($course_id, array $order_ids, $period_id = null) {
        $this->resetOrder($course_id, $period_id);
        foreach($order_ids as $index => $identifier) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $identifier
            ));
        }

        return true;

    }
    */
}
