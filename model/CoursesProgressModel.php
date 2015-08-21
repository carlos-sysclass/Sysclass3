<?php
class CoursesProgressModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_courses_progress";
        $this->id_field = "id";
        $this->mainTablePrefix = "cp";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT
            cp.id,
            cp.user_id,
            cp.course_id,
            cp.factor
        FROM `mod_courses_progress` cp";

//        $this->order = array("-lc.`position` DESC");
        parent::init();
    }

    public function recalculateProgress($course_id) {
        //$progressAwareTypes = array('file');

        $contents = $this->model("classes")
            ->setUserFilter($this->getUserFilter())
            ->addFilter(array(
                'course_id'  => $course_id
            ))->getItems();

        $progressItens = array_column($contents, 'progress');

        $factorSum = 0;
        foreach($progressItens as $item) {
            $factorSum += $item['factor'];
        }

        $factor = $factorSum / count($progressItens);

        $factor = ($factor > 1) ? 1 : $factor;

            $this->addOrSetItem(array(
                'factor'        => $factor,
                'course_id'      => $course_id,
                'user_id'       => $this->getUserFilter()
            ), array(
                'course_id'      => $course_id,
                'user_id'       => $this->getUserFilter()
            ));
            return true;

        return false;
    }

    public function addOrSetItem($data) {
        $data['user_id'] = $this->getUserFilter();

        $items = $this->clear()->addFilter(array(
            'user_id'       => $data['user_id'],
            'course_id'    => $data['course_id']
        ))->getItems();

        if (count($items) > 0) {
            $result = parent::setItem($data, array(
                'user_id'     => $data['user_id'],
                'course_id'    => $data['course_id']
            ));

            $progress_id = $items[0]['id'];

        } else {
            $result = parent::addItem($data);

            $progress_id = $result;
        }

        $progress = $this->clear()->getItem($progress_id);

        return $progress;
    }

}
