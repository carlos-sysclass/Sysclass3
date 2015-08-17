<?php
namespace Sysclass\Services;

use Phalcon\Mvc\Model\Resultset,
    Phalcon\Mvc\User\Component,
    Sysclass\Models\Settings;

class Configuration extends Component
{
    protected static $cfg;
    public function __construct() {
        if (is_null(self::$cfg)) {
            // LOAD CONFIGURATION
            $configRS = Settings::find(/*array(
                'hydration' => Resultset::HYDRATE_ARRAYS
            )*/);

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
