<?php
namespace Sysclass\Services\Utils;

use Phalcon\Mvc\User\Component;

class YmlParser extends Component
{
    protected static $cfg;
    public function __construct() {
    }

    public function parseFile($filename) {
        $ndocs = 0;
        $result = yaml_parse_file($filename, 0, $ndocs, [
                '!user' => [self, 'parseUserCallback']
        ]);

        return $result;
    }

    protected static function parseUserCallback($value, $tag, $flags) {
        $user = \Phalcon\DI::getDefault()->get('user');
        return $user->{$value};
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
