<?php
/**
 * @deprecated 3.0.0.17
 */
class LessonsModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_lessons";
        $this->id_field = "id";
        $this->mainTablePrefix = "l";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            l.id, l.permission_access_mode, l.class_id, c.name as class, l.name, l.info, l.active,
            l.`has_text_content`, l.`text_content`, l.`text_content_language_id`, l.`has_video_content`,
            IFNULL(l.instructor_id, c.instructor_id) as instructor_id
        FROM mod_lessons l
        LEFT JOIN mod_classes c ON (c.id = l.class_id)";

        $this->order = array("-l.position DESC");

        parent::init();

    }
    public function getItem($identifier) {
        $data = parent::getItem($identifier);
        $data['instructor_id'] = json_decode($data['instructor_id'], true);
        return $data;
    }

    public function addItem($data)
    {
        $data['instructor_id'] = json_encode($data['instructor_id']);
        return parent::addItem($data, $identifier);
    }

    public function setItem($data, $identifier)
    {
        $data['instructor_id'] = json_encode($data['instructor_id']);
        return parent::setItem($data, $identifier);
    }

    public function loadContentFiles($id, $type = null) {
        $filehelper = $this->helper("file/wrapper");
        $path = $filehelper->getLessonPath($id, $type);

        return $filehelper->listFiles($path);

    }

    protected function resetContentOrder($class_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'class_id' => $class_id
        ));
    }

    public function setContentOrder($class_id, array $order_ids) {
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
