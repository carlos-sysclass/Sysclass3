<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * Manage and control the entities address data, geolocation, city and zip search
 * @package Sysclass\Modules
 */
class AddressModule extends SysclassModule implements IBlockProvider
{
	// IBlockProvider
	/**
	 * [registerBlocks description]
	 * @return [type]
	 */
	public function registerBlocks() {
		return array(
			'address.add' => function($data, $self) {
        		$self->putSectionTemplate("address", "blocks/add");

        		return true;
			}
		);
	}
}