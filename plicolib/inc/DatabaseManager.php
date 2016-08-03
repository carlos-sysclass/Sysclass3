<?php
require_once __DIR__ . "/../lib/adodb5/adodb.inc.php";
/**
 * @package PlicoLib\Managers
 */
abstract class DatabaseManager extends CacheManager {
	protected static $db;

	public static function db() {

		if (is_null(self::$db)) {
			$plico = PLicoLib::instance();
			$DB_DSN = $plico->get('db_dsn');
			$charset = $plico->get("db/charset");

			$GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
			self::$db = &ADONewConnection($DB_DSN);
			if (!is_null($charset)) {
				switch(self::$db->databaseType) {
					case "mysql" :{
						mysql_set_charset($charset, self::$db->_connectionID);
						break;
					}
					case "postgres7" :{
						pg_set_client_encoding(self::$db->_connectionID, $charset);
					}
				}
			}
		}
		return self::$db;

	}

	public function init()
	{
		$this->db = self::db();
	}

	protected function debug($switch=TRUE)
	{
		$this->db->debug = $switch;

		return $this;

	}

}
