<?php
/**
 * @deprecated 3.0.0.18
 */
class InstitutionCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_institution";
		$this->id_field = "id";
		//$this->fieldsMap = array();

		$this->selectSql = "SELECT `id`, `name`, `formal_name`, `contact`, `observations`, `zip`, `address`, `number`, `address2`, `city`, `state`, `country_code`, `phone`, `active`, `website`, `facebook` FROM `mod_institution`";
		//`lessons_ID`, `classe_id`,
 		parent::init();

	}

	public function getInstitution($id)
    {
    	if($id == 0)
    	{
    		$sql = sprintf(
	           "SELECT
				  inst.address,
				  inst.active,
				  inst.website,
				  inst.facebook
				FROM
				  mod_institution inst
				WHERE
				  inst.id = 1"
       		);
    	}
    	else
    	{
			$sql = sprintf(
				"SELECT
					inst.address,
					inst.active,
					inst.website,
					inst.facebook
				FROM
					mod_institution inst
				LEFT JOIN
					mod_classes clas
				ON
					(inst.id = clas.ies_id)
				LEFT JOIN
					users_to_classes ucla
				ON
					(ucla.classes_ID = clas.id) AND (ucla.users_ID = " . $id .")"
			);
   		}

       return $this->db->GetArray($sql);
    }
}
