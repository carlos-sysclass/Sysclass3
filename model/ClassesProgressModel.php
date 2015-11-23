<?php
class ClassesProgressmodel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_classes_progress";
        $this->id_field = "id";
        $this->mainTablePrefix = "cp";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT
            cp.id,
            cp.user_id,
            cp.class_id,
            cp.factor,
            cl.course_id as 'course#course_id'
        FROM `mod_classes_progress` cp
        LEFT JOIN mod_classes cl ON (cp.class_id = cl.id)";

//        $this->order = array("-lc.`position` DESC");
        parent::init();
    }

    public function recalculateProgress($class_id) {
        //$progressAwareTypes = array('file');

        $contents = $this->model("lessons")
            ->setUserFilter($this->getUserFilter())
            ->addFilter(array(
                'class_id'  => $class_id
            ))->getItems();

        $progressItens = \array_column($contents, 'progress');

        $factorSum = 0;
        foreach($progressItens as $item) {
            $factorSum += $item['factor'];
        }

        $factor = $factorSum / count($progressItens);

        $factor = ($factor > 1) ? 1 : $factor;

        //if (array_sum($progressItens) == count($progressItens)) {
            $this->addOrSetItem(array(
                'factor'        => $factor,
                'class_id'      => $class_id,
                'user_id'       => $this->getUserFilter()
            ), array(
                'class_id'      => $class_id,
                'user_id'       => $this->getUserFilter()
            ));
            return true;
        //}

        return false;
    }

    public function addOrSetItem($data) {
        $data['user_id'] = $this->getUserFilter();

        $items = $this->clear()->addFilter(array(
            'user_id'       => $data['user_id'],
            'class_id'    => $data['class_id']
        ))->getItems();

        if (count($items) > 0) {
            $result = parent::setItem($data, array(
                'user_id'     => $data['user_id'],
                'class_id'    => $data['class_id']
            ));

            $progress_id = $items[0]['id'];

        } else {
            $result = parent::addItem($data);

            $progress_id = $result;
        }

        $progress = $this->clear()->getItem($progress_id);

        if (floatval($data['factor']) >= 1) {
            $this->model("courses/progress")
                ->setUserFilter($this->getUserFilter())
                ->recalculateProgress($progress['course']['course_id']);
        }
        return $progress;
    }

}
