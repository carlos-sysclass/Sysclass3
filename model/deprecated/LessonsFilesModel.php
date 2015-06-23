<?php
/**
 * @deprecated 3.0.0.18
 */
class LessonsFilesModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_lessons_files";
        $this->id_field = "id";
        $this->mainTablePrefix = "lf";
        //$this->fieldsMap = array();

        $this->selectSql = "
			SELECT
                `id`,
                `lesson_id`,
                `upload_type`,
                `name`,
                `type`,
                `size`,
                `url`,
                `active`
            FROM `mod_lessons_files` lf
		";

        parent::init();

    }
    public function setVideo($item) {
        $result = $this->addFilter(array(
            'lesson_id' => $item['lesson_id'],
            'upload_type' => 'video'
        ))->getItems();

        if (count($result) > 0) {
            $sql = "UPDATE {$this->table_name} SET active = 0 WHERE lesson_id = {$item['lesson_id']} AND upload_type = 'video'";
            $this->db->Execute($sql);

        }
        return $this->addItem($item);
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
