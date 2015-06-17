<?php
class RoadmapGroupingModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_roadmap_courses_grouping";
        $this->id_field = "id";
        $this->mainTablePrefix = "cg";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            cg.`id`,
            cg.`course_id`,
            cg.`name`,
            cg.`start_date`,
            cg.`end_date`,
            cg.`position`,
            cg.`active`,
            c.`id` as 'course#id',
            c.`name` as 'course#name',
            c.`active` as 'course#active'
        FROM mod_roadmap_courses_grouping cg
        LEFT JOIN mod_courses c ON(cg.course_id = c.id)";

        $this->order = array("-cg.`position` DESC");

        $this->group_by = array("cg.`id`");

        parent::init();
    }
    /*
    protected function parseItem($item) {

        $userModel =  $this->model("users/collection");

        $item['class']['instructor_id'] = json_decode($item['class']['instructor_id'], true);

        if (is_array($item['class']['instructor_id'])) {
            $item['class']['instructors'] = $userModel->clear()->addFilter(array(
                'can_be_coordinator' => true,
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
        $item = parent::getItem($identifier);
        return $this->parseItem($item);
    }
    */
    /*
    public function addItem($data) {
        $classModel = $this->model("classes");
        if (!array_key_exists('course_id', $data)) {
            $data['course_id'] = $classModel->addItem($data['course']);
        }
        return parent::addItem($data);
        //return array($data['class_id'], $data['class_id']);
    }

    public function setItem($data, $identifier) {
        $classModel = $this->model("classes");
        if (array_key_exists('class_id', $data)) {
            $classModel->setItem($data['class'], $data['class_id']);
        }
        return parent::setItem($data, $identifier);
        //return array($data['class_id'], $data['class_id']);
    }
    */
    protected function resetOrder($course_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'course_id' => $course_id
        ));
    }

    public function setOrder($course_id, array $order_ids) {
        $this->resetOrder($course_id);
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
