<?php
/**
 * @deprecated 3.2.0
 */
class NewsModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_news";
		$this->id_field = "id";
		$this->fieldsMap = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN'
		);

		$this->selectSql = "SELECT `id`, `title`, `data`, `timestamp`, user_id, `expire` FROM `mod_news`";
		//`units_ID`, `classe_id`,

		parent::init();

	}
}
