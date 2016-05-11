<?php 
class FilterVariablesModel /* extends ModelManager */ {
/*
	public function init()
	{
		$this->table_name = "users";
		$this->id_field = "id";

		parent::init();

	}
*/
	public function is_timestamp($timestamp) {
		return (is_numeric($timestamp)) 
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX);
	}

}
