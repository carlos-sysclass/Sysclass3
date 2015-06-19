<?php
class RoadmapPeriodsModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_roadmap_courses_periods";
        $this->id_field = "id";
        $this->mainTablePrefix = "cp";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            cp.`id`,
            cp.`course_id`,
            cp.`name`,
            cp.`position`,
            cp.`max_classes`,
            cp.`active`,
            c.`id` as 'course#id',
            c.`name` as 'course#name',
            c.`active` as 'course#active'
        FROM mod_roadmap_courses_periods cp
        LEFT JOIN mod_courses c ON(cp.course_id = c.id)";

        $this->order = array("-cp.`position` DESC");

        $this->group_by = array("cp.`id`");

        parent::init();
    }


    protected function parseItem($item) {
        $classModel =  $this->model("roadmap/classes");

        $item['classes'] = $classModel->clear()->addFilter(array(
            'class_id' => sprintf(
                'SELECT class_id FROM mod_roadmap_classes_to_periods WHERE period_id = %d',
                $item['id']
            )
        ), array('quote' => false))->getItems();

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
    /*
    public function addItem($data) {
        $classModel = $this->model("classes");
        if (!array_key_exists('class_id', $data)) {
            $data['class_id'] = $classModel->addItem($data['class']);
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

    public function addClass($course_id, $period_id, $class_id) {
        $deleteSql = "DELETE FROM mod_roadmap_classes_to_periods
            WHERE class_id = {$class_id}
            AND period_id IN (SELECT id FROM mod_roadmap_courses_periods WHERE course_id = {$course_id})
        ";
        $this->db->Execute($deleteSql);
        if (!is_null($period_id)) {
            $insertSql = "INSERT INTO mod_roadmap_classes_to_periods (period_id, class_id) VALUES ({$period_id}, {$class_id})";
            $this->db->Execute($insertSql);
        }

        return true;
    }
}
