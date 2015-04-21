<?php
class CoursesClassesCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_classes";
        $this->id_field = "id";
        $this->mainTablePrefix = "cl";
        //$this->fieldsMap = array();

        $this->selectSql = "
			SELECT
				cl.id,
				cl.permission_access_mode,
				cl.ies_id,
				cl.area_id,
				cl.name,
				cl.description,
				cl.info,
				cl.active,
				cl.course_id,
                cl.instructor_id,
				cour.name as course_name
			FROM
				mod_classes cl
			LEFT JOIN
				mod_courses cour
			ON
				(cour.id = cl.course_id)
		";

        parent::init();

    }


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
}
