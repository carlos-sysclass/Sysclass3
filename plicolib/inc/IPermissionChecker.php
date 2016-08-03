<?php
/**
 * @package PlicoLib\Interfaces
 * @deprecated 3.2.0.100 Use the role acl resource to permission check
 */
interface IPermissionChecker {
	/**
	 * Return the module humanized name
	 * @return string
	 */
	public function getName();
	/**
	 * Return the permissions provided by the implementation class, a
	 * @param  string $index If sent, the module must try to send only the especified permission
	 * @return array[]|array
	 */
	public function getPermissions($index = null);
	/**
	 * Returns a humanized string for parsed condition
	 * @param  string $condition_id
	 * @param  array $data
	 * @return string
	 */
	public function getConditionText($condition_id, $data);
	/**
	 * Check a condition based on sent data, the module must provide a default entity
	 * @param  string $condition_id
	 * @param  array $data
	 * @return bool
	 */
	public function checkCondition($condition_id, $data);
	/**
	 * Check a condition based on a entify ID and sent data
	 * @param  ID $entity_id
	 * @param  string $condition_id
	 * @param  array $data
	 * @return bool
	 */
	public function checkConditionByEntityId($entity_id, $condition_id, $data);
	/**
	 * Return the HTML portion for add/edit permission entries
	 * @param  string $condition_id
	 * @param  array  $data
	 * @return html
	 */
	public function getPermissionForm($condition_id, $data = array());
	/**
	 * Parse the sent form data to a resonable DB struct
	 * @param  string $condition_id
	 * @param  array $data
	 * @return array
	 */
	public function parseFormData($condition_id, $data);
}
