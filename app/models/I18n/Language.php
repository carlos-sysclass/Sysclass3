<?php
namespace Sysclass\Models\I18n;

use Phalcon\Mvc\Model;

class Language extends Model
{
    public function initialize()
    {
        $this->setSource("mod_translate");

    }

    public function toArray() {
    	$depinj = \Phalcon\DI::getDefault();
    	
    	$response = parent::toArray();
       	$response['country_image'] = $depinj->get("resourceUrl")->get(sprintf("/images/flags/%s.png", strtolower($this->country_code)));
		return $response;
    }
}
