<?php
class ClassesLessonsCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_lessons";
        $this->id_field = "id";
        $this->mainTablePrefix = "l";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            l.id, l.permission_access_mode, l.class_id, c.name as class, l.name, l.info, l.active
        FROM mod_lessons l
        LEFT JOIN mod_classes c ON (c.id = l.class_id)";

        parent::init();

    }

    public function loadContentFiles($id, $type = null) {
        $filehelper = $this->helper("file/wrapper");
        $path = $filehelper->getLessonPath($id, $type);

        return $filehelper->listFiles($path);

    }
}
