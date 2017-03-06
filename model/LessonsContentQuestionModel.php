<?php
class UnitsContentQuestionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_lessons_content_questions";
        $this->id_field = null;
        $this->mainTablePrefix = "lcq";
        //$this->fieldsMap = array();

        $this->selectSql = "
            SELECT
                `content_id`,
                `question_id`,
                `position`,
                `active`
            FROM `mod_lessons_content_questions` lcq
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
            $innerModel = $this->model("lessons/content/" . $type);
            $innerData = array(
                'content_id'    => $id,
                'file_id'       => $data[$type]['id']
            );
            $innerModel->addItem($innerData);
        }

        // TODO: SAVE EXERCISES SENT!
        return $id;
    }

    protected function resetContentOrder($lesson_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'lesson_id' => $lesson_id
        ));
    }
    */
    public function setContentOrder($lesson_id, array $order_ids) {
        $this->resetContentOrder($lesson_id);

        foreach($order_ids as $index => $content_id) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $content_id,
                'lesson_id' => $lesson_id
            ));
        }

        return true;

    }
}
