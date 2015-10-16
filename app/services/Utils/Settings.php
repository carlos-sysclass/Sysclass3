<?php
namespace Sysclass\Services\Utils;

use Phalcon\Mvc\Model\Resultset,
    Phalcon\Mvc\User\Component,
    Sysclass\Models\Settings as SettingsModel;

class Settings extends Component
{
    protected static $cfg;
    public function __construct() {
        if (is_null(self::$cfg)) {
            // LOAD CONFIGURATION
            /*
            $configRS = SettingsModel::find(array(
                "cache" => array(
                    "key"      => "settings-configuration",
                    "lifetime" => 600
                )
            ));
            */
            $configRS = SettingsModel::find();

            $configRS->setHydrateMode(Resultset::HYDRATE_ARRAYS);
            self::$cfg = array();
            foreach($configRS as $item) {
                self::$cfg[$item['name']] = $this->castValue($item['value'], $item['datatype']);
            }
        }
    }


    protected function castValue($value, $datatype) {
        switch($datatype) {
            case "bool" : {
                return (bool) $value;
                break;
            }
            case "int" : {
                return !is_numeric($value) ? null : (int) $value;
                break;
            }            
            default : {
                return $value;
            }
        }
    }

    public function asArray() {
        return self::$cfg;
    }
    public function get($key) {
        return self::$cfg[$key];
    }

}
