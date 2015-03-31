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
}
