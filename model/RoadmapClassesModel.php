<?php
/**
 * @deprecated
 */
class RoadmapClassesModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_roadmap_courses_to_classes";
        $this->id_field = "id";
        $this->mainTablePrefix = "c2c";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            c2c.`id`,
            c2c.`course_id`,
            c2c.`class_id`,
            c2c.`start_date`,
            c2c.`end_date`,
            c2c.`position`,
            c2c.`active`,
            cl.`area_id` as 'class#area_id',
            cl.`name` as 'class#name',
            cl.`description` as 'class#description',
            cl.`instructor_id` as 'class#instructor_id',
            COUNT(l.id) as 'class#total_lessons',
            cl.`active` as 'class#active',
            c.`id` as 'course#id',
            c.`name` as 'course#name',
            c.`active` as 'course#active',
            cp.`name` as 'period#name',
            cp.`max_classes` as 'period#max_classes',
            cp.`active` as 'period#active'
        FROM mod_roadmap_courses_to_classes c2c
        LEFT JOIN mod_courses c ON(c2c.course_id = c.id)
        LEFT JOIN mod_classes cl ON(c2c.class_id = cl.id)
        LEFT JOIN mod_lessons l ON(cl.id = l.class_id)
        LEFT JOIN mod_roadmap_classes_to_periods clp ON(c2c.class_id = clp.class_id)
        LEFT JOIN mod_roadmap_courses_periods cp ON(clp.period_id = cp.id AND cp.course_id = c.id)";

        $this->order = array("-c2c.`position` DESC");

        $this->group_by = array("c2c.`id`");

        parent::init();
    }

    protected function parseItem($item) {
        $userModel =  $this->model("users/collection");

        $item['class']['instructor_id'] = json_decode($item['class']['instructor_id'], true);

        if (is_array($item['class']['instructor_id'])) {
            $item['class']['instructors'] = $userModel->clear()->addFilter(array(
                'can_be_coordinator' => true,
                'id'    =>  $item['class']['instructor_id']
            ))->getItems();
        } else {
            $item['class']['instructors'] = array();
        }

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
        $item = parent::getItem($identifier);
        return $this->parseItem($item);
    }

    public function addItem($data) {
        $classModel = $this->model("classes");
        if (!array_key_exists('class_id', $data)) {
            $data['class_id'] = $classModel->addItem($data['class']);
        }
        return parent::addItem($data);
        //return array($data['class_id'], $data['class_id']);
    }

    public function setItem($data, $identifier) {
        $classModel = $this->model("classes");
        if (array_key_exists('class_id', $data)) {
            $classModel->setItem($data['class'], $data['class_id']);
        }
        return parent::setItem($data, $identifier);
        //return array($data['class_id'], $data['class_id']);
    }

    protected function resetOrder($course_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'class_id' => $course_id
        ));
    }

    public function setOrder($course_id, array $order_ids) {
        $this->resetOrder($course_id);
        foreach($order_ids as $index => $identifier) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $identifier
            ));
        }

        return true;

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
    */

}
