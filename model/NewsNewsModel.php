<?php 
class NewsNewsModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "news";
		$this->id_field = "id";
		$this->fieldsMap = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN'
		);

		$this->selectSql = "SELECT `id`, `title`, `data`, `timestamp`, `expire`, `users_LOGIN`, `permission_access_mode` FROM `news`";
		//`lessons_ID`, `classe_id`, 

		parent::init();

	}
}
