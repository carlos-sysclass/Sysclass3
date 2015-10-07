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

class Strings {

    public function vksprintf($str, $args)
    {
        if (is_object($args)) {
            $args = get_object_vars($args);
        }
        $map = array_flip(array_keys($args));
        $new_str = preg_replace_callback(
            '/(^|[^%])%([a-zA-Z0-9_-]+)\$/',
            function($match) use ($map) {
                //var_dump($map[$match[2]]);
                //if (is_array($map[$match[2]])) {
                //    return $match[1].'%'.(json_encode($map[$match[2]]) + 1).'$';
                //} else {
                    return $match[1].'%'.($map[$match[2]] + 1).'$';
                //}
            },
            $str
        );
        foreach ($args as $key => $value) {
            if (is_array($value)) {
                $args[$key] = json_encode($value);
            }
        }
        return vsprintf($new_str, $args);
    }

    public function camelCasefying($string, $sep = "_", $firstLower = true)
    {
        $strings = explode($sep, $string);

        foreach ($strings as &$data) {
            $data = ucfirst($data);
        }
        if (count($strings) > 0 && $firstLower) {
            $strings[0] = strtolower($strings[0]);
        }

        return implode('', $strings);
    }

    public function camelDiscasefying($string, $sep = "_")
    {
        $matches = array();
        preg_match_all('/[A-Z]/', $string, $matches, PREG_OFFSET_CAPTURE);

        $matches = array_reverse($matches[0]);

        foreach($matches as $match) {
            $string = substr_replace(
                $string,
                ($match[1] == 0) ? strtolower($match[0]) : ($sep . strtolower($match[0])),
                $match[1],
                1
            );
        }
        return $string;
    }

    /**
     * [sanitizeFilter description]
     * @return [type] [description]
     * @todo Include This function inside a Phalcon\Filter
     */
    public function stripChars($string) {
        return strtolower(str_replace(" ", "-", ucwords($string)));
    }

}
