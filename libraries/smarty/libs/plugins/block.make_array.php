<?php

/**
* Smarty block plugin
* -------------------------------------------------------------
* Type:     block
* Name:     dsrlz
* Version:  1.1
* Author:   boots
* Purpose:  make assignnents from within a template with a simple syntax
*           supporting multiple assignments and allowing for simple
*           assignments as well as arrays and keyed arrays.
* See:      http://www.phpinsider.com/smarty-forum/viewtopic.php?t=64
*
* Example:
* {dsrlz}
* test: "test"
* test2: 10
* test3: "this is a test"
*
* test4: ["test1", "test2", "test3"]
* test5: [
*       key1: "value1"
*       key2: "value2"
* ]
* test6: [
*       key1: "value1"
*       key2: [
*          subkey1: "subvalue1"
*          subkey2: "subvalue2"
*       ]
* ]
* {/dsrlz}
*
* creates the following smarty assignments:
* $test  [= "test"]
* $test2  [= 10]
* $test3  [= "this is a test"]
* $test4  [= array("test1", "test2", "test3")]
* $test5  [= array('key1'=>"value1", 'key2'=>"value2")]
* $test6  [= array('key1'=>"value1", 'key2'=>array('subkey1'=>"subvalue1", 'subkey2'=>"subvalue2"))]
*/
function smarty_block_make_array($params, $content, &$smarty)
{
    if (!empty($content)) {
        $src = $content;

        // pre-tokenize strings
        $src_c  = preg_match_all(   '/(".*")/',       $src,         $token_str);
        $src    = preg_replace(      '/".*"/',         ' STRING! ',   $src);
        // "fix" array delimiters
        $src    = str_replace(      '[',             ' [ ',          $src);
        $src    = str_replace(      ']',             ' ] ',          $src);
        // split on whitespace
        $src    = preg_split (      '/\s+/',          $src);
       
        $msg    = '';
        $stack  = array();
   
        // take each token in turn...
        $level  = 0;
        $items  = 0;
        foreach ($src as $_token) {
            $token      = trim($_token);
            $last_char  = substr($token, strlen($token) - 1, 1);
            $stack[]    = $items;
            switch ($last_char) {
                case '[':
                    // array start
                    $msg .= "array(";
                    ++$level;
                    $stack[] = $items;
                    $items = 0;
                    break;
                case ']':
                    // array end
                    $msg .= ")";
                    $items = array_pop($stack);
                    ++$items;
                    --$level;
                    break;
                case ':':
                    if ($level == 0) {
                        if ($items > 0) {
                            $msg .= ';';
                        }
                        $msg .= "$" . substr($token, 0, strlen($token) - 1) . "=";
                    } else {
                        if ($items > 0) {
                            $msg .= ',';
                        }
                        $msg .= '"' . substr($token, 0, strlen($token) - 1) . '"=>';
                    }
                    // assignment
                    ++$items;
                    break;
                case '!':
                    // pre tokenized type
                    switch ($token) {
                        case 'STRING!':
                            $msg .= array_shift($token_str[1]);
                            break;
                    }
                    break;
                default:
                    $msg .= $token;
                    break;
            }
        }
        $msg .= ';';
        $cnt = preg_match_all('/(\$(\w+)\s*=\s*(.*?;))/', $msg, $list);
        $cnt = count($list[1]);
        if ($cnt > 0) {
            for ($i = 0; $i < $cnt; $i++) {
                $var = $list[2][$i];
                if (!empty($var)) {
                    eval ($list[1][$i]);
                    $smarty->assign($var, $$var);
                }
            }
        }

    }
}
?> 