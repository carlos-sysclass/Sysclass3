<?php
class ProfileModel extends ModelManager {

	const PASSWORD_DOES_NOT_MATCH = 'PASSWORD_DOES_NOT_MATCH';
	const PASSWORD_CONFIRMATION_FAIL = 'PASSWORD_CONFIRMATION_FAIL';

	public function init()
	{
		$this->table_name = "users";
		$this->id_field = "id";

		parent::init();

	}
	public function updatePassword($id, $passwdData) {
		$userData = $this->getItemById($id);

		if ($this->found()) {
			if ($passwdData['old'] != $userData['passwd']) {
				return self::PASSWORD_DOES_NOT_MATCH;
			}
			if ($passwdData['new'] != $passwdData['confirm']) {
				return self::PASSWORD_CONFIRMATION_FAIL;
			}
			$this->update(array('passwd' => $passwdData['new']), $id);
			return true;
		}
		return self::NOT_FOUND;

	}

}
