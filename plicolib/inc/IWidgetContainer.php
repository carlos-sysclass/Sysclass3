<?php
/**
 * @package PlicoLib\Interfaces
 */
interface IWidgetContainer {
	public function getWidgets($widgetsIndexes = array(), $caller = null);
}