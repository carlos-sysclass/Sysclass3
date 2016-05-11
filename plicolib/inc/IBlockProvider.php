<?php
/**
 * @package PlicoLib\Interfaces
 */
interface IBlockProvider {
	/**
	 * The method to be called when the system needs to get all module's blocks
	 * @return closure[] Return a array with keys as block names and a closure as the module definition
	 */
	public function registerBlocks();
}