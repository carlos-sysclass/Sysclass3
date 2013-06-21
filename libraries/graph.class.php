<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

class MagesterGraph
{
 public $type;
 public $data = array();
 public $title;
 public $xLabels = array();
 public $xTitle;
 public $yLabels = array();
 public $yTitle;
 public $label;

}
