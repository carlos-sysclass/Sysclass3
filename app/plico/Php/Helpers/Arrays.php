<?php
namespace Plico\Php\Helpers;
/**
 * File Wrapper Helper File
 * @filesource
 */
/**
 * Provides utility functions to manipulate strings
 * @package Plico\Php\Helpers
 */

class Arrays {

    public function multiUnique(array $array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
       
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array; 
    }
}
