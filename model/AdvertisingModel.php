<?php
class AdvertisingModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_advertising";
        $this->id_field = "id";
        $this->mainTablePrefix = "a";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, placement, view_type, active FROM mod_advertising a";

        parent::init();
    }

    protected function parseItem($item)
    {
        if (strpos($item['placement'], 'leftbar')) {
            $item['placement_name'] = "Left Bar";
        } elseif (strpos($item['placement'], 'rightbar')) {
            $item['placement_name'] = "Right Bar";
        } elseif (strpos($item['placement'], 'topbar')) {
            $item['placement_name'] = "Top Bar";
        } else {
            $item['placement_name'] = "None";
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


    /*
    public function getItem($identifier) {
        $data = parent::getItem($identifier);
        //$data['instructor_id'] = json_decode($data['instructor_id'], true);
        return $data;
    }

    public function addItem($data)
    {
        $data['instructor_id'] = json_encode($data['instructor_id']);
        return parent::addItem($data);
    }

    public function setItem($data, $identifier)
    {
        $data['instructor_id'] = json_encode($data['instructor_id']);
        return parent::setItem($data, $identifier);
    }
    */

    /*
    public function addItem($item) {
        $id = parent::addItem($item);
        // INJECT INTO
        $roadmap = $this->model("roadmap/courses/classes/collection");
        if (is_numeric($id) && is_numeric($item['course_id'])) {
            //$roadmap->removeClassInAllCourses($id);
            $roadmap->addClassInCourse($item['course_id'], $id);
        }
        return $id;
    }
    */
    /*
    public function setItem($item, $id) {
        $result = parent::setItem($item, $id);
        $roadmap = $this->model("roadmap/courses/classes/collection");

        if (is_numeric($id) && is_numeric($item['course_id'])) {
            //$roadmap->removeClassInAllCourses($id);
            $roadmap->addClassInCourse($item['course_id'], $id);
        }
        return $result;
    }
    */
}
