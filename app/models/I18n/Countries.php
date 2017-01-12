<?php
namespace Sysclass\Models\I18n;

use Plico\Mvc\Model;

class Countries extends Model
{
      protected $countryList = null;

      public function initialize() {
            $this->setSource("mod_translate_countries");
      }

      public static function getFlagUrl($code) {
            $depinj = \Phalcon\DI::getDefault();

            return $depinj->get("resourceUrl")->get(sprintf("/images/flags/%s.png", strtolower($code)));
      }
}
