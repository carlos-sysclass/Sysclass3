<?php
/**
 * @package PlicoLib\Interfaces
 */
interface ISyncronizableModel {
	public function getItem($id);
	public function addItem($item);
	public function setItem($item, $id);
	public function deleteItem($id);
}