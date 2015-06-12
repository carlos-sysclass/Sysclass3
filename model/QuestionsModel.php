<?php
class QuestionsModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_questions";
		$this->id_field = "id";
        $this->mainTablePrefix = "q";

		$this->selectSql = "
            SELECT
                q.`id`,
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
                q.`settings`,
                q.`active`
            FROM `mod_questions` q
            LEFT JOIN `mod_areas` a ON (q.area_id = a.id)
            LEFT JOIN `mod_questions_difficulties` qd ON (q.difficulty_id = qd.id)
            LEFT JOIN `mod_questions_types` qt ON (q.type_id = qt.id)";

 		parent::init();

	}

    public function getItem($identifier) {
        $data = parent::getItem($identifier);
        $data['options'] = json_decode($data['options'], true);
        return $data;
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
}
