<?php
class TestsQuestionModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_tests_to_questions";
		$this->id_field = "id";
        $this->mainTablePrefix = "t2q";

		$this->selectSql = "
            SELECT
                t2q.`id`,
                t2q.`lesson_id`,
                t2q.`question_id`,
                t2q.`position`,
                t2q.`points`,
                t2q.`weight`,
                t2q.`active`,
                q.`id` as 'question#id',
                q.`title` as 'question#title',
                q.`question` as 'question#question',
                q.`area_id` as 'question#area_id',
                a.`name` as 'question#area',
                q.`difficulty_id` as 'question#difficulty_id',
                qd.`name` as 'question#difficulty',
                q.`type_id` as 'question#type_id',
                qt.`name` as 'question#type',
                q.`options` as 'question#options',
                q.`answer` as 'question#answer',
                q.`explanation` as 'question#explanation',
                q.`answers_explanation` as 'question#answers_explanation',
                q.`estimate` as 'question#estimate',
                q.`settings` as 'question#settings',
                q.`active` as 'question#active'
            FROM mod_tests_to_questions t2q
            LEFT JOIN mod_lessons l ON (t2q.lesson_id = l.id)
            LEFT JOIN `mod_questions` q ON (t2q.question_id = q.id)
            LEFT JOIN `mod_areas` a ON (q.area_id = a.id)
            LEFT JOIN `mod_questions_difficulties` qd ON (q.difficulty_id = qd.id)
            LEFT JOIN `mod_questions_types` qt ON (q.type_id = qt.id)";


        $this->order = array("-t2q.`position` DESC");

 		parent::init();

	}

    protected function parseItem($item) {
        // var_dump($item['question']['options']);
        // exit;

        $item['question']['options'] = json_decode($item['question']['options'], true);

        return $item;
    }

    public function getItems()
    {
        $data = parent::getItems();

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
        $data = $this->mergeDetails($data);
        return parent::addItem($data);
    }

    public function setItem($data, $identifier)
    {
        $data = $this->mergeDetails($data);
        //$this->debug();
        return parent::setItem($data, $identifier);
    }
    protected function mergeDetails($data) {
        if (array_key_exists($data['type_id'], $data) && is_array($data[$data['type_id']])) {
            $data = array_merge($data, $data[$data['type_id']]);
        }
        // ENCODE 'JSONED' FIELDS
        $data['options'] = json_encode($data['options']);
        return $data;
    }

    protected function resetOrder($lesson_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'lesson_id' => $lesson_id
        ));
    }

    public function setOrder($lesson_id, array $order_ids) {
        $this->resetOrder($lesson_id);
        foreach($order_ids as $index => $item_id) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $item_id,
                'lesson_id' => $lesson_id
            ));
        }

        return true;

    }
}
