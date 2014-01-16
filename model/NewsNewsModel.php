<?php 
class NewsNewsModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "news";
		$this->id_field = "id";
		$this->mapFields = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN',
			'lesson_id'				=> 'lessons_ID'
		);

		$this->selectSql = "SELECT `id`, `title`, `data`, `timestamp`, `expire`, `lessons_ID`, `classe_id`, `users_LOGIN` FROM `news`";

		parent::init();

	}

	public function setItem($data, $id) {
		$data = $this->mapFields($data);
		return $this->update($data, $id);
	}
}
