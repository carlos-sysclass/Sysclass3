<?php 
/**
 * @package PlicoLib\Managers
 */
abstract class ModelChildrenManager extends ModelManager {

	protected $child_table_name = null;
	protected $child_parent_link = "cod_parent";
	protected $children_FOUND = FALSE;
	protected $parent_field = null;

	public function setParentField($cod_parent)
	{
		$this->parent_field = $cod_parent;

		return $this;
	}

	public function getChildrenById($id)
	{
		if ($this->isValid()) {
			return $this->getList(array("where" => sprintf("%s = '%s'",$this->child_parent_link, $id)), $this->child_table_name);
		}

	}

	public function insertChild($postData)
	{
		if ($this->isValid()) {
			$postData[$this->child_parent_link] = $this->parent_field;
			return $this->db->AutoExecute($this->child_table_name, $postData, 'INSERT');
		}

	}

	private function isValid() {
		if (!is_null($this->child_table_name)) {
			return TRUE;
		}

		throw new Exception('$child_table_name não pode ser NULL.');
		exit;

	}

}
