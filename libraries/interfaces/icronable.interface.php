<?php
/** @todo CREATE FUNCTIONS TO REGISTER CRON EVENTS */
interface ICronable {
	public function onCronEvent(array $contraints);
}
?>