<?php
class DropboxModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_dropbox";
        $this->id_field = "id";
        $this->mainTablePrefix = "d";
        //$this->fieldsMap = array();

        $this->selectSql = "
			SELECT
                `id`,
                `upload_type`,
                `name`,
                `type`,
                `size`,
                `url`,
                `active`
            FROM `mod_dropbox` d
		";

        parent::init();

    }
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
