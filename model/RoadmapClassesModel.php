<?php
class RoadmapClassesModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_roadmap_courses_to_classes";
        $this->id_field = "id";
        $this->mainTablePrefix = "c2c";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            c2c.`id`,
            c2c.`course_id`,
            c2c.`class_id`,
            clp.`period_id`,
            c2c.`start_date`,
            c2c.`end_date`,
            c2c.`position`,
            c2c.`active`,
            cl.`area_id` as 'class#area_id',
            cl.`name` as 'class#name',
            cl.`description` as 'class#description',
            cl.`instructor_id` as 'class#instructor_id',
            COUNT(l.id) as 'class#total_lessons',
            cl.`active` as 'class#active',
            c.`id` as 'course#id',
            c.`name` as 'course#name',
            c.`active` as 'course#active',
            cp.`id` as 'period#id',
            cp.`name` as 'period#name',
            cp.`max_classes` as 'period#max_classes',
            cp.`active` as 'period#active'
        FROM mod_roadmap_courses_to_classes c2c
        LEFT JOIN mod_courses c ON(c2c.course_id = c.id)
        LEFT JOIN mod_classes cl ON(c2c.class_id = cl.id)
        LEFT JOIN mod_lessons l ON(cl.id = l.class_id)
        LEFT JOIN mod_roadmap_classes_to_periods clp ON(c2c.class_id = clp.class_id)
        LEFT JOIN mod_roadmap_courses_periods cp ON(clp.period_id = cp.id AND cp.course_id = c.id)";

        $this->order = array("-c2c.`position` DESC");

        $this->group_by = array("c2c.`id`");

        parent::init();
    }

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
        $data['lessons'] = $this->model("roadmap/lessons")->addFilter(array(
            'class_id' => $data['class_id']
        ))->getItems();

        $data['tests'] = $this->model("roadmap/tests")->addFilter(array(
            'class_id' => $data['class_id']
        ))->setUserFilter($this->getUserFilter())->getItems();


        return $this->parseItem($data);
    }

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
}
