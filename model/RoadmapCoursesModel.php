<?php
class RoadmapCoursesModel extends CoursesModel implements ISyncronizableCollection {

    public function getItem($identifier) {

        $data = parent::getItem($identifier);
        if (count($data) == 0) {
            return $data;
        }

        // GET CLASSES
        $data['classes'] = $this->model("roadmap/classes")
            ->setUserFilter($this->getUserFilter())
            ->addFilter(array(
                'course_id' => $identifier
            ))->getItems();
        return $data;
    }

}


