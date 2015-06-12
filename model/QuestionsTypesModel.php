<?php
class QuestionsTypesModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_questions_types";
		$this->id_field = "id";

		$this->selectSql = "
            SELECT
                qt.`id`,
                qt.`name`
            FROM `mod_questions_types` qt";

 		parent::init();

	}
}
