<?php
class ClassesModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_classes";
        $this->id_field = "id";
        $this->mainTablePrefix = "cl";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            cl.`id`,
            c2c.`course_id`,
            clp.`period_id`,
            c2c.`start_date`,
            c2c.`end_date`,
            c2c.`position`,
            cl.`area_id`,
            cl.`name`,
            cl.`description`,
            cl.`instructor_id`,
            COUNT(l.id) as 'total_lessons',
            cl.`active`,
            c.`id` as 'course#id',
            c.`name` as 'course#name',
            c.`active` as 'course#active',
            cp.`id` as 'period#id',
            cp.`name` as 'period#name',
            cp.`max_classes` as 'period#max_classes',
            cp.`active` as 'period#active'
        FROM mod_roadmap_courses_to_classes c2c
        LEFT JOIN mod_courses c ON(c2c.course_id = c.id)
        LEFT JOIN mod_classes cl ON(c2c.class_id = cl.id)
        LEFT JOIN mod_lessons l ON(cl.id = l.class_id)
        LEFT JOIN mod_roadmap_classes_to_periods clp ON(c2c.class_id = clp.class_id)
        LEFT JOIN mod_roadmap_courses_periods cp ON(clp.period_id = cp.id AND cp.course_id = c.id)";

        parent::init();

        $this->group_by = array("cl.`id`");

    }

    protected function parseItem($item)
    {
        $data['instructor_id'] = json_decode($data['instructor_id'], true);

        if ($this->getUserFilter()) {
            $progress = $this->model("classes/progress")->clear()->addFilter(array(
                'user_id'       => $this->getUserFilter(),
                'class_id'    => $item['id']
            ))->getItems();

            $item['progress'] = reset($progress);
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
