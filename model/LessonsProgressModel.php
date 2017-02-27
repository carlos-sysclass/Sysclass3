<?php
class UnitsProgressmodel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_lessons_progress";
        $this->id_field = "id";
        $this->mainTablePrefix = "lp";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT
            lp.id,
            lp.user_id,
            lp.lesson_id,
            lp.factor,
            l.class_id as 'class#class_id'
        FROM `mod_lessons_progress` lp
        LEFT JOIN mod_lessons l ON (lp.lesson_id = l.id)";

//        $this->order = array("-lc.`position` DESC");
        parent::init();
    }


    public function recalculateProgress($lesson_id) {
        $progressAwareTypes = array('file');

        $contents = $this->model("lessons/content")
            ->setUserFilter($this->getUserFilter())
            ->addFilter(array(
                'lesson_id'  => $lesson_id,
                'content_type'  => $progressAwareTypes
            ))->getItems();

        $progressItens = array_column($contents, 'progress');

        $factorSum = 0;
        foreach($progressItens as $item) {
            $factorSum += $item['factor'];
        }

        $factor = $factorSum / count($progressItens);

        $factor = ($factor > 1) ? 1 : $factor;

        //if (array_sum($progressItens) == count($progressItens)) {
            $this->addOrSetItem(array(
                'factor'        => $factor,
                'lesson_id'     => $lesson_id,
                'user_id'       => $this->getUserFilter()
            ), array(
                'lesson_id'     => $lesson_id,
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
            'lesson_id'    => $data['lesson_id']
        ))->getItems();

        if (count($items) > 0) {
            $result = parent::setItem($data, array(
                'user_id'       => $data['user_id'],
                'lesson_id'    => $data['lesson_id']
            ));

            $progress_id = $items[0]['id'];

        } else {
            $result = parent::addItem($data);

            $progress_id = $result;
        }

        $progress = $this->clear()->getItem($progress_id);

        if (floatval($data['factor']) >= 1) {
            $this->model("classes/progress")
                ->setUserFilter($this->getUserFilter())
                ->recalculateProgress($progress['class']['class_id']);
        }

        return $progress;
    }

}
