<?php
/**
 * @deprecated 3.2.0
 */
class EnrollmentCourseModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_enroll_course_to_users";
        $this->id_field = "id";
        $this->mainTablePrefix = "e";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT *
            FROM mod_enroll_course_to_users e
            LEFT JOIN mod_courses c ON (c.id = e.course_id)
            LEFT JOIN users u ON (u.id = e.user_id)
        ";

        //$this->order = array("-e.`position` DESC");

        //$this->group_by = array("e.`id`");

        parent::init();
    }


    public function switchUser($course_id, $user_id) {
        $sql = "SELECT COUNT(*) FROM mod_enroll_course_to_users WHERE course_id = %d AND user_id = '%s'";
        $checkSql = sprintf(
            $sql,
            $course_id,
            $user_id
        );

        $exists = $this->db->GetOne($checkSql);
        $exists = ($exists == 1);

        if ($exists) {
            $sql = sprintf("DELETE FROM mod_enroll_course_to_users WHERE course_id = %d AND user_id = '%s'", $course_id, $user_id);
            $result = -1;
        } else {
            /**
             * @todo REMOVE THIS HARD_CODED enroll_id = 10. MOVE TO THE NEXT MODEL PATTERN
             */
            $sql = sprintf("INSERT INTO mod_enroll_course_to_users (enroll_id, course_id, user_id) VALUES (10, %d, '%s')", $course_id, $user_id);
            $result = 1;
        }
        $this->db->Execute($sql);
        return $result;
    }

    protected function parseItem($item) {
        /*
        $userModel =  $this->model("users/collection");

        $item['class']['instructor_id'] = json_decode($item['class']['instructor_id'], true);

        if (is_array($item['class']['instructor_id'])) {
            $item['class']['instructors'] = $userModel->clear()->addFilter(array(
                'can_be_instructor' => true,
                'id'    =>  $item['class']['instructor_id']
            ))->getItems();
        } else {
            $item['class']['instructors'] = array();
        }
        */
        return $item;
    }

    public function getItems()
    {
        $data = parent::getItems();


        // LOAD INSTRUCTORS
        foreach($data as $key => $item) {
            $data[$key] = $this->parseItem($item);
        }
        return $data;
    }

    public function getItem($identifier)
    {
        $data = parent::getItem($identifier);
        if (count($data) == 0) {
            return $data;
        }

        // GET CLASSES
        //  TODO CREATE A ROADMAP/LESSON MODEL, TO GET ALL LESSONS FROM THIS CLASS
        /*
        $data['units'] = $this->model("roadmap/units")->addFilter(array(
            'class_id' => $data['class_id']
        ))->getItems();

        $data['tests'] = $this->model("roadmap/tests")->addFilter(array(
            'class_id' => $data['class_id']
        ))->getItems();
        */

        return $this->parseItem($data);
    }
}
