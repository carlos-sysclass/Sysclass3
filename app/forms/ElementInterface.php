<?php
namespace Sysclass\Forms;

interface ElementInterface extends \Phalcon\Forms\ElementInterface
{
	public function getHTMLType();
	public function toArray();
}