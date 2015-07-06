<?php
class BaseLessonsModel extends AbstractSysclassModel implements ISyncronizableModel {

    protected $lesson_type = null;

    public function init()
    {
        $this->table_name = "mod_lessons";
        $this->id_field = "id";
        $this->mainTablePrefix = "l";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            l.id, l.permission_access_mode, l.class_id, c.name as class, l.name, l.info, l.active, l.`type`,
            l.`has_text_content`, l.`text_content`, l.`text_content_language_id`, l.`has_video_content`,
            IFNULL(l.instructor_id, c.instructor_id) as instructor_id
        FROM mod_lessons l
        LEFT JOIN mod_classes c ON (c.id = l.class_id)";

        $this->order = array("-l.position DESC");

        parent::init();
    }

    protected function parseItem($item)
    {
        $userModel =  $this->model("users/collection");

        $item['instructor_id'] = json_decode($item['instructor_id'], true);

        if (is_array($item['instructor_id'])) {
            $item['instructors'] = $userModel->clear()->addFilter(array(
                'can_be_coordinator' => true,
                'id'    =>  $item['instructor_id']
            ))->getItems();
        } else {
            $item['instructors'] = array();
        }

        return $item;
    }

    public function getItems()
    {
        if (!is_null()) {
            $this->where[] = "l.type = '{$this->lesson_type}'";
        }


        $data = parent::getItems();

        // LOAD INSTRUCTORS
        foreach($data as $key => $item) {
            $data[$key] = $this->parseItem($item);
        }
        return $data;
    }

    public function getItem($identifier)
    {
        if (!is_null()) {
            $this->where[] = "l.type = '{$this->lesson_type}'";
        }

        $item = parent::getItem($identifier);
        return $this->parseItem($item);
    }


    public function addItem($data)
    {
        $data['type'] = $this->lesson_type;
        $data['instructor_id'] = json_encode($data['instructor_id']);
        return parent::addItem($data, $identifier);
    }

    public function setItem($data, $identifier)
    {
        $data['type'] = $this->lesson_type;
        $data['instructor_id'] = json_encode($data['instructor_id']);
        return parent::setItem($data, $identifier);
    }

    protected function resetOrder($class_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'class_id' => $class_id
        ));
    }

    public function setOrder($class_id, array $order_ids) {
        $this->resetContentOrder($class_id);
        foreach($order_ids as $index => $lesson_id) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $lesson_id,
                'class_id' => $class_id
            ));
        }

        return true;

    }
}
