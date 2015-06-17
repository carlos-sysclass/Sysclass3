<?php
/**
 * @deprecated 3.0.0.17
 */
class RoadmapCoursesSeasonsCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_roadmap_courses_seasons";
        $this->id_field = "id";
        $this->mainTablePrefix = "cr";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT cr.id, cr.name, cr.course_id, c.name as course_name, cr.active,
        GROUP_CONCAT(cs.class_id SEPARATOR ',') as classes
        FROM mod_roadmap_courses_seasons cr
        LEFT JOIN mod_courses c ON (cr.course_id = c.id)
        LEFT JOIN mod_roadmap_classes_to_seasons cs ON (cr.id = cs.season_id)";

        $this->group_by = array("cr.id");

        parent::init();

    }

    public function getItems() {
        $seasons = parent::getItems();

        foreach($seasons as $key => $season) {
            if (is_null($season['classes'])) {
                $seasons[$key]['classes'] = array();
                continue;
            }
            $classesCollection = $this->model("roadmap/courses/classes/collection");

            $itemsData = $classesCollection->addFilter(array(
                'cl.id'      => sprintf('SELECT class_id FROM mod_roadmap_classes_to_seasons WHERE season_id = %s', $season['id'])
            ), array("operator" => "IN", 'quote' => false))->getItems();

            $seasons[$key]['classes'] = $itemsData;
        }
        return $seasons;
    }

    public function getItem($id) {
        $season = parent::getItem($id);

        if (is_null($season['classes'])) {
            $season['classes'] = array();
            return $season;
        }
        $classesCollection = $this->model("roadmap/courses/classes/collection");

        $itemsData = $classesCollection->addFilter(array(
            'cl.id'      => sprintf('SELECT class_id FROM mod_roadmap_classes_to_seasons WHERE season_id = %s', $season['id'])
        ), array("operator" => "IN", 'quote' => false))->getItems();

        $season['classes'] = $itemsData;

        return $season;
    }
}
