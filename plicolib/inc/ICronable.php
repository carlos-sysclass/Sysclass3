<?php
/**
 * @package PlicoLib\Interfaces
 */
interface ICronable {
	public function getInterval();
	public function cronExecute($params = null);
}