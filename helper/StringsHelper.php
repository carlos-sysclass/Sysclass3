<?php
/**
 * File Wrapper Helper File
 * @filesource
 */
/**
 * Provides utility functions to manipulate strings
 * @package Sysclass\Helpers
 */

class StringsHelper {
    /**
     * Use as Interface Method
     */

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

}
