<?php
/**
 * @deprecated 3.0.0.17
 */
class ClassesUnitsCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_lessons";
        $this->id_field = "id";
        $this->mainTablePrefix = "l";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            l.id, l.class_id, c.name as class, l.name, l.info, l.active,
            l.`has_text_content`, l.`text_content`, l.`text_content_language_id`, l.`has_video_content`
        FROM mod_lessons l
        LEFT JOIN mod_classes c ON (c.id = l.class_id)";

        $this->order = array("-l.position DESC");

        parent::init();

    }

    public function loadContentFiles($id, $type = null) {
        $filehelper = $this->helper("file/wrapper");
        $path = $filehelper->getUnitPath($id, $type);

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
