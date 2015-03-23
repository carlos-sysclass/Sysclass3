<?php
class UserCoursesItemModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "groups";
        $this->id_field = "id";
        $this->mainTablePrefix = "g";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, name, description, active, `behaviour_allow_messages` FROM groups g";

        parent::init();

    }
    public function getUsersInCourse($course_id) {
        $sql = sprintf(
            "SELECT
                courses_ID as course_id,
                users_LOGIN as user_login,
                u.name,
                u.surname,
                u.login,
                u.email
            FROM users_to_courses uc
            LEFT JOIN users u ON (u.login = uc.users_LOGIN)
            WHERE courses_ID = %d",
            $course_id
        );

        return $this->db->GetArray($sql);
    }

    public function switchUserInCourse($course_id, $user_login) {
        $sql = "SELECT COUNT(*) FROM users_to_courses WHERE courses_ID = %d AND users_LOGIN = '%s'";
        $checkSql = sprintf(
            $sql,
            $course_id,
            $user_login
        );

        $exists = $this->db->GetOne($checkSql);
        $exists = ($exists == 1);

        if ($exists) {
            $sql = sprintf("DELETE FROM users_to_courses WHERE courses_ID = %d AND users_LOGIN = '%s'", $course_id, $user_login);
            $result = -1;
        } else {
            $sql = sprintf("INSERT INTO users_to_courses (courses_ID, users_LOGIN) VALUES (%d, '%s')", $course_id, $user_login);
            $result = 1;
        }
        $this->db->Execute($sql);
        return $result;
    }
}
