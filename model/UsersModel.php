<?php
class UsersModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "users";
		$this->id_field = "id";

		$this->selectSql = "SELECT
			id,
		    login,
		    password,
		    email,
		    dashboard_id,
		    language_id,
		    languages_NAME,
		    timezone,
		    name,
		    surname,
		    active,
		    comments,
		    user_type,
		    group_id,
		    timestamp,
		    avatar,
		    pending,
		    user_types_ID,
		    additional_accounts,
		    viewed_license,
		    status,
		    short_description,
		    balance,
		    archive,
		    dashboard_positions,
		    need_mod_init,
		    autologin,
		    last_login,
		    can_be_instructor,
		    can_be_coordinator
		FROM users";
		parent::init();
	}
	/*
	public function getItem($id) {
		return MagesterUserFactory::factory($id);
	}
	*/
}
