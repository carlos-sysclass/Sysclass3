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
	public function registerBlocks() {
		return array(
			'address.add' => function($params) {
        		return $this->template("blocks/add");
        		
			}
		);
	}
}

