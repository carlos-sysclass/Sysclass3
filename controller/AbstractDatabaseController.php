<?php 
abstract class AbstractDatabaseController extends AbstractToolsController
{
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
	    $result = sC_getTableData($table, $fields, $where, $order, $group);
	    $temp = array();
	    for ($i = 0; $i < sizeof($result); $i++) {
	        foreach ($result[$i] as $key => $value) {
	            $temp[$key][] = $value;
	        }
	    }
	    logProcess($thisQuery, $sql);
	    return $temp;
	}

	/**
	 * Retrieve database data.
	 *
	 * This function is used to perform a SELECT query. Multiple parameters may be used, to
	 * specify the ordering, length and grouping of the data set. It returns an array of associative arrays,
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
	    $sql = prepareGetTableData($table, $fields, $where, $order, $group, $limit);
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


	    logProcess($thisQuery, $sql);
	    if ($result == false) {
	        return array();
	    } else {
	        return $result;
	    }
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
