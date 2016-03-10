<?php
/**
 * @deprecated 3.0.0.17
 */
class RoadmapCoursesClassesCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_roadmap_courses_to_classes";
        $this->id_field = "id";
        $this->mainTablePrefix = "cl";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT cl.id, cl.name, c.id as course_id, c2c.start_date, c2c.end_date
        FROM mod_roadmap_courses_to_classes c2c
        LEFT JOIN mod_courses c ON(c2c.course_id = c.id)
        LEFT JOIN mod_classes cl ON(c2c.class_id = cl.id)";

        parent::init();
    }
    /*
    public function getUsersInGroup($group_id) {
        $sql = sprintf(
            "SELECT
                groups_ID as group_id,
                users_LOGIN as user_login,
                u.name,
                u.surname,
                u.login,
                u.email
            FROM users_to_groups ug
            LEFT JOIN users u ON (u.login = ug.users_LOGIN)
            WHERE groups_ID = %d",
            $group_id
        );

        return $this->db->GetArray($sql);
    }

    */
    public function switchClassInCourse($course_id, $class_id) {
        $sql = "SELECT COUNT(*) FROM mod_roadmap_courses_to_classes WHERE course_id = %d AND class_id = %d";
        $checkSql = sprintf(
            $sql,
            $course_id,
            $class_id
        );

        $exists = $this->db->GetOne($checkSql);
        $exists = ($exists == 1);

        if ($exists) {
            $sql = sprintf("DELETE FROM mod_roadmap_courses_to_classes WHERE course_id = %d AND class_id = %d", $course_id, $class_id);
            $result = -1;
        } else {
            $sql = sprintf("INSERT INTO mod_roadmap_courses_to_classes (course_id, class_id) VALUES (%d, %d)", $course_id, $class_id);
            $result = 1;
        }
        $this->db->Execute($sql);
        return $result;
    }

    public function removeClassInAllCourses($class_id) {
        $sql = sprintf("DELETE FROM mod_roadmap_courses_to_classes WHERE class_id = %d", $class_id);
        $this->db->Execute($sql);
        return $result;
    }

    public function addClassInCourse($course_id, $class_id) {
        $sql = "SELECT COUNT(*) FROM mod_roadmap_courses_to_classes WHERE course_id = %d AND class_id = %d";
        $checkSql = sprintf(
            $sql,
            $course_id,
            $class_id
        );

        $exists = $this->db->GetOne($checkSql);
        $exists = ($exists == 1);

        if ($exists) {
            //$sql = sprintf("DELETE FROM mod_roadmap_courses_to_classes WHERE course_id = %d AND class_id = %d", $course_id, $class_id);
            $result = -1;
        } else {
            $sql = sprintf("INSERT INTO mod_roadmap_courses_to_classes (course_id, class_id) VALUES (%d, %d)", $course_id, $class_id);
            $result = 1;
            $this->db->Execute($sql);
        }

        return $result;
    }





}
