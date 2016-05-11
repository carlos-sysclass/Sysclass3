<?php
/**
 * @package PlicoLib\Interfaces
 */
interface ISyncronizableCollection extends ISyncronizableModel {
	public function getItems();
	public function addItems($data);
	public function setItems($data, $id);
	public function deleteItems($id);
}