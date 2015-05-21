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
			'address.book' => function($data, $self) {
		        $country_codes = $self->model("i18n/country")->getItems();
		        $self->putItem("country_codes", $country_codes);

        		$self->putSectionTemplate("address", "blocks/book");

        		return true;
			}
		);
	}
}
