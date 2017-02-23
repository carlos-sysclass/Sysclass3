<?php
class LessonsProgressmodel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_units_progress";
        $this->id_field = "id";
        $this->mainTablePrefix = "lp";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT
            lp.id,
            lp.user_id,
            lp.unit_id,
            lp.factor,
            l.class_id as 'class#class_id'
        FROM `mod_units_progress` lp
        LEFT JOIN mod_units l ON (lp.unit_id = l.id)";

//        $this->order = array("-lc.`position` DESC");
        parent::init();
    }


    public function recalculateProgress($unit_id) {
        $progressAwareTypes = array('file');

        $contents = $this->model("units/content")
            ->setUserFilter($this->getUserFilter())
            ->addFilter(array(
                'unit_id'  => $unit_id,
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
                'unit_id'     => $unit_id,
                'user_id'       => $this->getUserFilter()
            ), array(
                'unit_id'     => $unit_id,
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
            'unit_id'    => $data['unit_id']
        ))->getItems();

        if (count($items) > 0) {
            $result = parent::setItem($data, array(
                'user_id'       => $data['user_id'],
                'unit_id'    => $data['unit_id']
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
