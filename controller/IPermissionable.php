<?php
/**
 * @package PlicoLib\Interfaces
 */
interface IPermissionable {
	/**
	 * @return array Contains a id (prefixed with mpodule name) and translated term 
	 */
	public function getResources();
}


?>