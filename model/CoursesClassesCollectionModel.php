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
}
