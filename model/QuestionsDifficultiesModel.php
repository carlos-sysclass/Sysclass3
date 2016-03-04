<?php
/**
 * @deprecated 3.2.0
 */
class QuestionsDifficultiesModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_questions_difficulties";
		$this->id_field = "id";

		$this->selectSql = "
            SELECT
                qd.`id`,
                qd.`name`
            FROM `mod_questions_difficulties` qd";

 		parent::init();

	}
}
