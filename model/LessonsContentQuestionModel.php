<?php
class LessonsContentQuestionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_units_content_questions";
        $this->id_field = null;
        $this->mainTablePrefix = "lcq";
        //$this->fieldsMap = array();

        $this->selectSql = "
            SELECT
                `content_id`,
                `question_id`,
                `position`,
                `active`
            FROM `mod_units_content_questions` lcq
		";

        $this->order = array("-lcq.`position` DESC");

        parent::init();

    }
    /*
    public function addItem($data) {
        $id = parent::addItem($data);

        $type = $data['content_type'];
        if ($type == "subtitle" || $type == "subtitle-translation") {
            $type = "file";
        }
        if (in_array($type, array('file', 'text', 'exercise')) && array_key_exists($type, $data)) {
            $innerModel = $this->model("units/content/" . $type);
            $innerData = array(
                'content_id'    => $id,
                'file_id'       => $data[$type]['id']
            );
            $innerModel->addItem($innerData);
        }

        // TODO: SAVE EXERCISES SENT!
        return $id;
    }

    protected function resetContentOrder($unit_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'unit_id' => $unit_id
        ));
    }
    */
    public function setContentOrder($unit_id, array $order_ids) {
        $this->resetContentOrder($unit_id);

        foreach($order_ids as $index => $content_id) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $content_id,
                'unit_id' => $unit_id
            ));
        }

        return true;

    }
}
