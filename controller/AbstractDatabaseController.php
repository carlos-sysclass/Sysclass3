<?php
abstract class AbstractDatabaseController extends AbstractToolsController
{

	function prepareGetTableData($table, $fields = "*", $where = "", $order = "", $group = "", $limit = "")
	{
	    $tables = explode(",", $table);
	    foreach ($tables as $key => $value) {
	        //Prepend prefix to the table
	        $tables[$key] = trim($value);
	    }
	    $table = implode(",", $tables);
	    $table = str_ireplace(" join ", " join ", $table);
	    $sql = "SELECT ".$fields." FROM ".$table;
	    if ($where != "") {
	        $sql .= " WHERE ".$where;
	    }
	    if ($group != "") {
	        $sql .= " GROUP BY ".$group;
	    }
	    if ($order != "") {
	        $sql .= " ORDER BY ".$order;
	    }
	    if ($limit != "") {
	        $sql .= " limit ".$limit;
	    }
	    return $sql;
	}



	/**
	 * Retrieve table contents Flat
	 *
	 * This function, much similar to the sC_getTableData(), retrieves data from the designated
	 * database table. The main difference lies at the result array format: This time, each
	 * field in the result set corresponds to an array in the result array.
	 * <br/>Example:
	 * <code>
	 * $result = sC_getTableDataFlat("users", "name, surname");
	 * print_r($result);
	 * </code>
	 * Returns:
	 * <code>
	 * Array
	 * (
	 *     [name]     => Array
	 *                   (
	 *                     [0] => 'john',
	 *                     [1] => 'joe',
	 *                     [2] => 'mary'
	 *                   )
	 *     [surname]  => Array
	 *                   (
	 *                     [0] => 'white',
	 *                     [1] => 'black',
	 *                     [2] => 'green'
	 *                   )
	 * )
	 * </code>
	 *
	 * @param string $table The database table name.
	 * @param string $fields Comma separated list of the fields to retrieve, defaults to *.
	 * @param string $where The where clause of the SQL query.
	 * @return array The query result table.
	 * @version 2.0
	 * @see sC_getTableData()
	 * Changes from 1.0 to 2.0:
	 * - Rewritten function in order to accelerate execution. It now uses sC_getTableData()
	 */
	function _getTableDataFlat($table, $fields="*", $where="", $order="", $group="")
	{
	    $thisQuery = microtime(true);
	    $sql = "SELECT ".$fields." FROM ".$table;
	    if ($where != "") {
	        $sql .= " WHERE ".$where;
	    }
	    if ($order != "") {
	        $sql .= " ORDER BY ".$order;
	    }
	    if ($group != "") {
	        $sql .= " GROUP BY ".$group;
	    }
	    $result = $this->_getTableData($table, $fields, $where, $order, $group);
	    $temp = array();
	    for ($i = 0; $i < sizeof($result); $i++) {
	        foreach ($result[$i] as $key => $value) {
	            $temp[$key][] = $value;
	        }
	    }
	    $this->logProcess($thisQuery, $sql);
	    return $temp;
	}

	/**
	 * Retrieve database data.
	 *
	 * This function is used to perform a SELECT query. Multiple parameters may be used, to
	 * specify the ordering, length and groups of the data set. It returns an array of associative arrays,
	 * where each of these arrays holds the column name and the corresponding value for the result row
	 * <br>Example:
	 * <code>
	 * //Retrieve all data from table users:
	 * $result = sC_getTableData("users");
	 * //Retrieve all rows from table users, but only columns "name" and "surname"
	 * $result = sC_getTableData("users", 'name, surname');
	 * //Get the "name" and "surname" for user with login "jdoe"
	 * $result = sC_getTableData("users", 'name, surname', 'login=jdoe');
	 * //Get the same information, but this time ordered by "name"
	 * $result = sC_getTableData("users", 'name, surname', 'login=jdoe', 'name');
	 * //Get the same information, but this time grouped by "surname"
	 * $result = sC_getTableData("users", 'name, surname', 'login=jdoe', '', 'surname');
	 * </code>
	 * @param string $table The table to retrieve data from
	 * @param string $fields The fields to retrive, comma-separated string, defaults to *.
	 * @param string $where The where clause of the SQL Select.
	 * @param string $order The order by clause of the SQL Select.
	 * @param string $group The group by clause of the SQL Select.
	 * @param string $limit The limit clause of the SQL Select.
	 * @return mixed an array holding the query result.
	 * @version 1.0
	 */
	function _getTableData($table, $fields = "*", $where = "", $order = "", $group = "", $limit = "")
	{
	    $thisQuery = microtime(true);
	    $sql = self::prepareGetTableData($table, $fields, $where, $order, $group, $limit);
	    //$result = $GLOBALS['db']->GetAll($sql);

	    $result = $GLOBALS['db']->_Execute($sql);

	    if ($result) {
	        $tempResult = array();

	        while(!$result->EOF) {
	            $tempResult[] = $result->fields;
	            $result->MoveNext();
	        }

	        $result = $tempResult;
	    }


	    $this->logProcess($thisQuery, $sql);
	    if ($result == false) {
	        return array();
	    } else {
	        return $result;
	    }
	}

	function logProcess($thisQuery, $sql)
	{
	    if ($GLOBALS['db']->debug == true) {
	        echo '<span style = "color:red">Time spent on this query: '.(microtime(true) - $thisQuery).'</span>';
	    }
	    $GLOBALS['db']->databaseTime = $GLOBALS['db']->databaseTime + microtime(true) - $thisQuery;
	    $GLOBALS['db']->databaseQueries++;
	    if (G_DEBUG) {
	        $GLOBALS['db']->queries[$GLOBALS['db']->databaseQueries]['times'] = microtime(true) - $thisQuery;
	        $GLOBALS['db']->queries[$GLOBALS['db']->databaseQueries]['sql'] = $sql;
	        foreach (debug_backtrace(false) as $value) {
	            $backtrace[] = basename(dirname($value['file'])).'/'.basename($value['file']).':'.$value['line'];
	        }
	        $GLOBALS['db']->queries[$GLOBALS['db']->databaseQueries]['trace'] = print_r($backtrace, true);
	    }
	    // Comment the next line in a production environment
	    //storeLog($thisQuery, $sql);

	}
	/**
	 * Insert data to a database table
	 *
	 * This function is used to insert data to a database table. The data is formed as an associative
	 * array, where the keys are column names and the values are the column data. The function returns
	 * the auto_increment value of the insertion id, if one exists
	 * <br>Example:
	 * <code>
	 * $fields = array('name' => 'john', 'surname' => 'doe');
	 * $result = sC_insertTableData('users', $fields);
	 * </code>
	 * @param string $table The table to insert data into
	 * @param array $fields An associative array with the table cell data
	 * @return mixed The id of the insertion, if an AUTO_INCREMENT id field is set. Otherwise, true in success and false on failure
	 * @version 1.0
	 */
	public function _insertTableData($table, $fields)
	{
	    $thisQuery = microtime(true);
	    //Prepend prefix to the table
	    $table = G_DBPREFIX.$table;
	    if (sizeof($fields) < 1) {
	        trigger_error(_EMPTYFIELDSLIST, E_USER_WARNING);
	        return false;
	    }
	    isset($fields['id']) ? $customId = $fields['id'] : $customId = 0;
	    $fields = sC_addSlashes($fields);
	    array_walk($fields, create_function('&$v, $k', 'if (is_string($v)) $v = "\'".$v."\'"; else if (is_null($v)) $v = "null"; else if ($v === false) $v = 0; else if ($v === true) $v = 1;'));
	    $sql = "insert into $table (".implode(",", array_map("escapemaDBFieldsArray", array_keys($fields))).") values (".implode(",", ($fields)).")";
	    $result = $GLOBALS['db']->Execute($sql);
	    logProcess($thisQuery, $sql);
	    if ($result) {
	        if (!$customId) {
	            $id = $GLOBALS['db']->Insert_ID();
	        } else {
	            $id = $customId;
	        }
	        if ($id == 0) {
	            return true;
	        } else {
	            return $id;
	        }
	    } else {
	        return false;
	    }
	}
	/**
	 * Update table data
	 *
	 * This function is used to update data to a database table. The data is formed as an associative
	 * array, where the keys are column names and the values are the column data.
	 * <br>Example:
	 * <code>
	 * $fields = array('name' => 'john', 'surname' => 'doe');
	 * $result = sC_updateTableData('users', $fields, 'login=jdoe');
	 * </code>
	 * @param string $table The table to update data to
	 * @param array $fields An associative array with the table cell data
	 * @param string $where The where clause of the SQL Update.
	 * @return mixed The query result, usually true or false.
	 * @version 1.0
	 */
	public function _updateTableData($table, $fields, $where)
	{
	    $thisQuery = microtime(true);
	    //Prepend prefix to the table
	    $table = G_DBPREFIX.$table;
	    if (sizeof($fields) < 1) {
	        trigger_error(_EMPTYFIELDSLIST, E_USER_WARNING);
	        return false;
	    }
	    $fields = sC_addSlashes($fields);
	    //array_walk($fields, create_function('&$v, $k', 'if (is_string($v)) $v = "\'".$v."\'"; else if (is_null($v)) $v = "null"; $v=$k."=".$v;'));
	    array_walk($fields, create_function('&$v, $k', 'if (is_string($v)) $v = "\'".$v."\'"; else if (is_null($v)) $v = "null"; else if ($v === false) $v = 0; else if ($v === true) $v = 1; $v= escapemaDBFieldsArray($k)."=".$v;'));
	    $sql = "update $table set ".implode(",", $fields)." where ".$where;
	    $result = $GLOBALS['db']->Execute($sql);

	    logProcess($thisQuery, $sql);
	    return $result;
	}
	public function _countTableData($table, $fields = "*", $where = "", $order = "", $group = "", $limit = "")
	{
	    $thisQuery = microtime(true);
	    $sql = prepareGetTableData($table, $fields, $where, $order, $group, $limit);
	    $result = $GLOBALS['db']->GetAll("select count(*) as count from ($sql) count_query");
	    logProcess($thisQuery, $sql);
	    if ($result == false) {
	        return array();
	    } else {
	        return $result;
	    }
	}
}
