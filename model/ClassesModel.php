<?php
class ClassesModel extends AbstractSysclassModel implements ISyncronizableModel {

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
				cour.name as course_name,
				CONCAT_WS(' ', u.name, u.surname) AS instructor_name
			FROM
				mod_classes cl
			LEFT JOIN
				mod_courses cour
			ON
				(cour.id = cl.course_id)
			LEFT JOIN
				users u
			ON
				(cl.instructor_id = u.id)
		";

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
        return parent::addItem($data);
    }

    public function setItem($data, $identifier)
    {
        $data['instructor_id'] = json_encode($data['instructor_id']);
        return parent::setItem($data, $identifier);
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
