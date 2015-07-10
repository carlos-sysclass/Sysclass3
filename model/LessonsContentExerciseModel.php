<?php
class LessonsContentExerciseModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_lessons_content_questions";
        $this->id_field = null;
        $this->mainTablePrefix = "lcq";
        //$this->fieldsMap = array();

        $this->selectSql = "
            SELECT
            q.`id`,
            lcq.content_id,
            lcq.question_id,
            lcq.position,
            lcq.active,
            q.`title`,
            q.`question`,
            q.`area_id`,
            a.`name` as area,
            q.`difficulty_id`,
            qd.`name` as difficulty,
            q.`type_id`,
            qt.`name` as type,
            q.`options`,
            q.`answer`,
            q.`explanation`,
            q.`answers_explanation`,
            q.`estimate`,
            q.`settings`
        FROM `mod_lessons_content_questions` lcq
        LEFT JOIN `mod_questions` q ON (lcq.question_id = q.id)
        LEFT JOIN `mod_areas` a ON (q.area_id = a.id)
        LEFT JOIN `mod_questions_difficulties` qd ON (q.difficulty_id = qd.id)
        LEFT JOIN `mod_questions_types` qt ON (q.type_id = qt.id)
		";

        $this->order = array("-lcq.`position` DESC");

        parent::init();

    }

    protected function parseItem($item)
    {
        $item['options'] = json_decode($item['options'], true);
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


    public function addOrSetItem($data) {
        $items = $this->clear()->addFilter(array(
            'question_id'   => $data['question_id'],
            'content_id'    => $data['content_id']
        ))->getItems();

        if (count($items) > 0) {
            $this->setItem($data, array(
                'question_id'   => $data['question_id'],
                'content_id'    => $data['content_id']
            ));
        } else {
            $this->addItem($data);
        }
        return true;
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
