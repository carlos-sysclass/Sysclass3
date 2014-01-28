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
				// TODO PUT A CONTRY SELECT AND OPEN FIELDS BASED ON CONTRY CONFIGURATION
				/*
 				contact
 				phone
 				zip
 				address
 				number
 				address2
 				city
 				state
 				country_code
 				*/
 				
        		return $this->template("blocks/add", true);
        		
			}
		);
	}
}

