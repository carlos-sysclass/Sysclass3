<?php
/**
  * @todo Merge this class WITH AbstractSysclassModel and AbstractCepetiModel, TO MAKE A UNIQUE MODEL PARENT CLASS
 */
abstract class ModelManager extends DatabaseManager {

	protected $where = array();
	protected $order = array();
	public $mainTablePrefix = null;
	protected $selectSql = null;
	protected $joins;
	protected $fieldsMap = array();
	protected $_FOUND = FALSE;
	protected $dbcolumns = null;

	protected static $sanitizeFilters = array(
		'int'		=> FILTER_VALIDATE_INT,
		'varchar'	=> FILTER_SANITIZE_STRING,
		'text'		=> FILTER_SANITIZE_STRING,
		'email'		=> FILTER_SANITIZE_EMAIL,
		'timestamp'	=> array(self, "sanitizeTimestamp")
	);
	protected static $sanitizeOptions = array(
		'int'		=> array(),
		'varchar'	=> array(),
		'text'		=> array(),
		'email'		=> array(),
		'timestamp'	=> array(
			'formats' => array("d/m/Y", "Y-m-d", "U")
		)
	);
	protected static $transformationFilters = array(
		'timestamp'	=> array(
			'callback'	=> array(self, 'transformTimestamp'),
			'options'	=> array(
				'format' 	=> "Y-m-d"
			)
		)
	);

	public static function sanitizeTimestamp($value, $opt, $return_value = false) {
		$formats = $opt['formats'];

		foreach ($formats as $format) {
			if ($new_value = date_create_from_format($format, $value)) {
				if ($return_value) {
					return $new_value;
				}
				return true;
			}
		}
		return false;
	}
	public function validate($item, $returnStats = false, $ignore = array())
	{
		// SANITIZE DATA, BASED ON METACOLUMNS
		$stats = array();
		$result = true;
		$this->dbcolumns = is_null($this->dbcolumns) ? $this->db->MetaColumns($this->table_name) : $this->dbcolumns;
		if ($this->dbcolumns) {
			foreach($this->dbcolumns as $column) {
				if (array_key_exists($column->type, self::$sanitizeFilters)) {
					$filter = self::$sanitizeFilters[$column->type];
					$options = self::$sanitizeOptions[$column->type];
					if (!array_key_exists($column->name, $item)) {
						if (!in_array($column->name, $ignore) && $column->not_null) {
							$result = false;
						}
					} else {
						$value = $item[$column->name];
						if (is_callable($filter) && !($stats[$column->name] = (bool)call_user_func($filter, $value, $options))) {
							if ($column->not_null) {
								$result = false;
							}
						} elseif (is_numeric($filter) && !($stats[$column->name] = (bool)filter_var($value, $filter, $options))) {
							if ($column->not_null) {
								$result = false;
							}
						}
					}
				}
			}
		}
		if ($returnStats) {
			$stats['*RESULT*'] = $result;
			return $stats;
		} else {
			return $result;
		}

	}

	public static function transformTimestamp($value, $opt) {
		$sanitizeOptions = self::$sanitizeOptions['timestamp'];
		if ($new_value = self::sanitizeTimestamp($value, $sanitizeOptions, true)){
			return $new_value->format($opt['format']);
		}
		return $value;

	}

	public function transform($item) {
		// SANITIZE DATA, BASED ON METACOLUMNS
		$stats = array();
		$result = true;
		$this->dbcolumns = is_null($this->dbcolumns) ? $this->db->MetaColumns($this->table_name) : $this->dbcolumns;

		if ($this->dbcolumns) {
			foreach($this->dbcolumns as $column) {
				if (array_key_exists($column->type, self::$transformationFilters)) {
					$filter = self::$transformationFilters[$column->type];

					if (array_key_exists($column->name, $item)) {
						if (is_callable($filter['callback'])) {
							$item[$column->name] = call_user_func($filter['callback'], $item[$column->name], $filter['options']);
						}
					}
				}
			}
		}
		return $item;
	}

	public function clear()
	{
		$this->where = array();
		return $this;
	}
	// ISyncronizableModel Methods
	// PUT THESE ISyncronizableCollection AND ISyncronizableModel METHODS INTO ModelManager Class
	public function getItem($identifier)
	{
		// !!!THIS METHODS DOES NOT VALIDATE IF THE USER HAS PERMISSION TO DO THAT, VALIDATE BEFORE!!! //
		$sql = $this->selectSql;
		if (empty($sql)) {
			$sql = sprintf("SELECT * FROM %s", $this->table_name);
		}

		if (count($this->joins) > 0) {
			$sql .= " " . $this->wrapJoins();
		}

		if (!is_null($identifier)) {
			$this->addFilter(array(
				$this->id_field => $identifier
			));
		}
		if (count($this->where) > 0) {
	        $sql .= " WHERE " . implode(" AND ", $this->where);
		}

        $itemRow = $this->db->GetRow($sql);

   		$itemRow = $this->transfields($itemRow);

   		$this->_FOUND = (count($itemRow) > 0);

        return $itemRow;
	}
/*
	public function getItem($data, $override_table_name = null)
	{
		$result = $this->getList($data, $override_table_name);

		$this->_FOUND = (count($result) > 0);

		if ($this->_FOUND) {
			return reset($result);
		}
		return false;

	}
*/
	public function addItem($item) {
		$item = $this->mapFields($item);
		$item = $this->transform($item);
		return $this->insert($item);
	}

	public function setItem($item, $id, $quote = true)
	{
		$item = $this->mapFields($item);
		return $this->update($item, $id, null, $quote);
	}

	public function deleteItem($id)
	{
		return $this->delete($id);
	}


	// ISyncronizableCollection Methods
	public function getItems() {
		// !!!THIS METHODS DOES NOT VALIDATE IF THE USER HAS PERMISSION TO DO THAT, VALIDATE BEFORE!!! //
		$sql = $this->selectSql;
		if (empty($sql)) {
			$sql = sprintf("SELECT * FROM %s", $this->table_name);
		}


		if (count($this->joins) > 0) {
			$sql .= " " . $this->wrapJoins();
		}
		/*
				$this->addFilter(array(
					$this->id_field => $id
				));
		*/
		if (count($this->where) > 0) {
	        $sql .= " WHERE " . implode(" AND ", $this->where);
		}

		if (count($this->group_by) > 0) {
			$sql .= " GROUP BY " . implode(", ", $this->group_by);
		}

		if (count($this->order) > 0) {
			$sql .= " ORDER BY " . implode(", ", $this->order);
		}

        $items = $this->db->GetArray($sql);
        foreach($items as $index => $item) {
			$items[$index] = $this->transfields($item);
        }
        //debug_print_backtrace();

        return $items;
	}
	public function addItems($data)
	{
		throw new Exception('Class' . get_class($this) . 'must implement "addItems($data)" method');
		exit;
	}
	public function setItems($data, $identifier)
	{
		throw new Exception('Class' . get_class($this) . 'must implement "setItems($data, $identifier)" method');
		exit;
	}
	public function deleteItems($identifier)
	{
		throw new Exception('Class' . get_class($this) . 'must implement "deleteItems($identifier)" method');
		exit;
	}

	protected function transfields($rowData)
	{
		foreach($this->fieldsMap as $key => $dbkey) {
			$dbkey = end(explode(".", $dbkey));
			if ($dbkey !== FALSE) {
				//var_dump(array_key_exists($dbkey, $rowData));
				if (array_key_exists($dbkey, $rowData)) {
					$rowData[$key] = $rowData[$dbkey];
					unset($rowData[$dbkey]);
				}
				//unset($rowData[$key]);
			}
		}
		return $this->transliterateToTree($rowData);
	}

	protected function transliterateToTree($rowData, $separator = "#")
	{
		$row = array();
		$append = array();
		$keys = array_keys($rowData);
		foreach($rowData as $key => $value) {
			$part = explode($separator, $key, 2);
			if (count($part) == 2) {
				if (!array_key_exists($part[0], $append)) {
					$append[$part[0]] = array();
				}
				$append[$part[0]][$part[1]] = $value;
			} else {
				$row[$key] = $value;
			}
		}
		foreach($append as $key => $fields) {
			$filtered = array_filter($fields);
			if (count($filtered) > 0) {
				$row[$key] = $fields;
			}
		}


		return $row;
	}



	protected function mapfields($postData)
	{
		foreach($this->fieldsMap as $key => $dbkey) {
			if (array_key_exists($key, $postData)) {
				if ($dbkey !== FALSE) {
					//$dbkey = end(explode(".", $dbkey));
					$postData[$dbkey] = $postData[$key];
					//APPLY TRANSFORMATION FILTERS

				}
				unset($postData[$key]);
			}
		}
		return $postData;
	}

	public function createJoin($type = 'LEFT', $table, $filter)
	{
		$this->joins[] = array(
			'type' 		=> $type,
			'table' 		=> $table,
			'filter'	=> $filter,
		);

		return $this;
	}
	public function wrapJoins() {
		$joins = array();
		foreach($this->joins as $join) {
			$joins[] = sprintf("%s JOIN %s ON (%s)", $join['type'], $join['table'], $join['filter']);
		}
		return implode(" ", $joins);
	}

	public function createFilter($field, $value, $options = null) {
		if (isset($options['operator'])) {
			$operator = $options['operator'];
			if ($options['operator'] == "IS") {
				$options['quote'] = false;
			} else if ($options['operator'] == "IN") {
				$options['parentesis'] = true;
			}
		// SETTING DEFAULT OPTIONS FOR VALUES
		} elseif (is_null($value)) {
			$operator = " IS ";
			$options['parentesis'] = FALSE;
		} else {
			$operator = " IN ";
			$options['parentesis'] = true;
		}

		if (isset($options['quote']) && $options['quote'] === false) {
			$quote = false;
		} else {
			$quote = is_null($field) ? false : true;
		}


		if (is_array($value)) {
			foreach($value as &$item) {
				if ($quote) {
					$item = $this->db->Quote($item);
				}
			}
			$value = implode(",", $value);
		} else {
			if ($quote) {
				$value = $this->db->Quote($value);
			}
		}

		if (is_null($field)) {
			$filterString = $value;
		} else if (isset($options['parentesis']) && $options['parentesis'] == true) {
			$filterString = sprintf("%s %s (%s)", $field, $operator, $value);
		} else {
			$filterString = sprintf("%s %s %s", $field, $operator, $value);
		}
		return $filterString;

	}

	public function addFilter(array $filter, array $options = null) {
		$filter = $this->mapFields($filter, $this->mainTablePrefix);
		foreach ($filter as $field => $value) {
			if (is_numeric($field)) {
				$field = null;
			} elseif (strpos($field, ".") === FALSE && !empty($this->mainTablePrefix)) {
				$field = $this->mainTablePrefix . "." . $field;
			}

			$this->where[] = $this->createFilter($field, $value, $options);

		}

		return $this;
	}


	public function injectFilter($filter, $joinOperator = "AND") {
		if (is_array($filter) && count($filter) > 0) {
			$whereClause = "(" . implode(" " . $joinOperator . " ", $filter) . ")";
			$this->where[] = $whereClause;
		}
		return $this;
	}




	const NOT_FOUND = 'NOT_FOUND';

	protected $table_name = null;
	protected $id_field = "codigo";

	private $_cacheable = true;

	protected function isValid() {
		if (!is_null($this->table_name)) {
			return TRUE;
		}

		throw new Exception('$table_name não pode ser NULL!');
		exit;

	}

	public function cacheable() {
		return $this->_cacheable;
	}
	public function nocache() {
		return $this->cache(false);
	}
	public function cache($switch = true) {
		$this->_cacheable = (bool)$switch;

		return $this;
	}

	public function debug() {
		$this->db->debug = TRUE;

		return $this;
	}
	public function found() {
		return $this->_FOUND;
	}
	public function StartTrans() {
		$this->db->StartTrans();
		return $this;
	}
	public function CompleteTrans() {
		$this->db->CompleteTrans();
		return $this;
	}

	public function insert($postData)
	{
		$result = false;

		if ($this->isValid()) {

			if (is_string($this->id_field) && !array_key_exists($this->id_field, $postData)) {
				// GERAR NOVO ID
				$postData[$this->id_field] = $this->db->GetOne(sprintf("SELECT MAX(%s)+1 FROM %s", $this->id_field, $this->table_name));
				if (is_null($postData[$this->id_field])) {
					$postData[$this->id_field] = 1;
				}
			}
			if (is_string($this->id_field) && $this->db->AutoExecute($this->table_name, $postData, "INSERT")) {
				$result = $this->db->GetOne(sprintf("SELECT MAX(%s) FROM %s", $this->id_field, $this->table_name));
			} else {
				$result = $this->db->AutoExecute($this->table_name, $postData, "INSERT");
			}
		}
		return $result;
	}

	public function update($postData, $id, $id_field=NULL, $quote = true)
	{
		$id_field = is_null($id_field) ? $this->id_field : $id_field;

		$where = array();
		if (is_string($id)) {
			$where[] = sprintf("%s = %s", $id_field, ($quote ? $this->db->Quote($id) : $id));
		} else if (is_array($id)) {
			foreach($id as $field => $value) {
				if (is_numeric($field)) {
					$where[] = $value;
				} else {
					$where[] = sprintf("%s IN (%s)", $field, ($quote ? $this->db->Quote($value) : $value));
				}
			}
		}

		if ($this->isValid()) {
			$ret = $this->db->AutoExecute($this->table_name, $postData, 'UPDATE', implode(" AND ", $where), false);

			if (!$ret) {
				// NOTHING TO UPDATE.
				return -1;
			}
			return $this->db->Affected_Rows();
		}
		return false;

	}

	public function delete($identifier, $id_field=NULL)
	{
		$id_field = is_null($id_field) ? $this->id_field : $id_field;

		if ($this->isValid()) {
			$sql = sprintf("DELETE FROM %s", $this->table_name);

			$where = array();
			if (is_string($identifier)) {
				$where[] = sprintf("%s = %s", $id_field, $this->db->Quote($identifier));
			} else if (is_array($identifier)) {
				foreach($identifier as $field => $value) {
					if (is_numeric($field)) {
						$where[] = $value;
					} else {
						$where[] = sprintf("%s = %s", $field, $this->db->Quote($value));
					}
				}
			}

			if (count($where) > 0) {
				$sql .= " WHERE " . implode(" AND ", $where);

				return $this->db->Execute($sql);
			}
			return false;
		}
		return false;

	}

	public function save($postData, $mode="INSERT", $id) {
		if ($mode == "INSERT") {
			return $this->insert($postData);
		} else if ($mode == "UPDATE") {
			return $this->update($postData, $id);
		}
	}

	/**
	  * @deprecated
	 */
	public function getTotal($data=array())
	{
		if ($this->isValid()) {
			$sql = sprintf("SELECT COUNT(*) as total FROM %s", $this->table_name);

			list($where, $order, $group, $limit) = $this->processFilter($data);
			if (count($where) > 0) {
				$sql .= " WHERE " . implode(" AND ", $where);
			}
			return $this->db->GetOne($sql);
		}

	}

	/**
	  * @deprecated
	 */
	public function getList($data, $override_table_name = null)
	{
		$this->_FOUND = FALSE;

		if ($this->isValid()) {
			if (!is_null($override_table_name)) {
				$table_name = $override_table_name;
			} else {
				$table_name = $this->table_name;
			}
			$sql = sprintf("SELECT * FROM %s", $table_name);

			list($where, $order, $group, $limit) = $this->processFilter($data);
			if (count($where) > 0) {
				$sql .= " WHERE " . implode(" AND ", $where);
			}
			if (count($order) > 0) {
				$sql .= " ORDER BY " . implode(" AND ", $order);
			}
			if (count($limit) > 0) {
				$sql .= " " . implode(" ", $limit);
			}
			$result = $this->db->GetArray($sql);

			$this->_FOUND = (count($result) > 0);

			return $result;
		}

	}



	/**
	  * @deprecated
	 */
	public function exists($id, $id_field=NULL)
	{
		$result = $this->getItemById($id, $id_field);
		return $result && count($result) > 0;
	}

	/**
	  * @deprecated
	 */
	public function getItemById($id, $id_field=NULL)
	{
		$id_field = is_null($id_field) ? $this->id_field : $id_field;

		$this->_FOUND = FALSE;

		if ($this->isValid()) {
			$sql = sprintf("SELECT * FROM %s", $this->table_name);

			$where = array();
			if (is_string($id) || is_numeric($id)) {
				$where[] = sprintf("%s = '%s'", $id_field, $id);
			} else if (is_array($id)) {
				foreach($id as $field => $value) {
					$where[] = sprintf("%s = %s", $field, $this->db->Quote($value));
				}
			}

			if (count($where) > 0) {
				$sql .= " WHERE " . implode(" AND ", $where);
			}
			$result = $this->db->GetRow($sql);

			$this->_FOUND = (count($result) > 0);

			return $result;
		}
		return false;
	}

	/**
	  * @deprecated
	 */
	public function deleteItemById($id, $id_field=NULL)
	{
		$id_field = is_null($id_field) ? $this->id_field : $id_field;

		if ($this->isValid()) {
			$sql = sprintf("DELETE FROM %s", $this->table_name);

			$where = array();
			if (is_string($id)) {
				$where[] = sprintf("%s = '%s'", $id_field, $id);
			} else if (is_array($id)) {
				foreach($id as $field => $value) {
					$where[] = sprintf("%s = '%s'", $field, $value);
				}
			}

			if (count($where) > 0) {
				$sql .= " WHERE " . implode(" AND ", $where);

				return $this->db->Execute($sql);
			}

			return false;
		}

		return false;

	}

	/**
	  * @deprecated
	 */
	public function getItensById($id, $id_field=NULL)
	{
		$id_field = is_null($id_field) ? $this->id_field : $id_field;

		$this->_FOUND = FALSE;

		if ($this->isValid()) {
			$sql = sprintf("SELECT * FROM %s", $this->table_name);

			$data['where'] = sprintf("%s = '%s'", $id_field, $id);

			list($where, $order, $group, $limit) = $this->processFilter($data);
			if (count($where) > 0) {
				$sql .= " WHERE " . implode(" AND ", $where);
			}

			$result = $this->db->GetArray($sql);

			$this->_FOUND = (count($result) > 0);

			return $result;
		}
		return false;
	}

	/**
	  * @deprecated
	 */
	public function getParentFlow($contact_id, $parent_field, $index_field) {
		$flow = array();
		while(($object = $this->getItemById($contact_id, $index_field))) {
			array_unshift($flow, $object);
			if ($object[$parent_field] != null) {
				$contact_id = $object[$parent_field];
			} else {
				break;
			}
		}
		array_pop($flow);

		return $flow;
	}

	/**
	  * @deprecated
	 */
	public function getFirstParent($id, $parent_field, $index_field) {
		$currentObject = FALSE;
		while(($object = $this->getItemById($id, $index_field))) {
			$currentObject = $object;
			if ($object[$parent_field] != null) {
				$id = $object[$parent_field];
			} else {
				break;
			}
		}
		return $currentObject;
	}

/*
	public function getChildFlow($contact_id, $parent_field, $index_field) {
		$flow = array();
		while(($object = $this->getItemById($contact_id, $parent_field))) {
			array_push($flow, $object);
			if ($object[$index_field] != null) {
				$contact_id = $object[$index_field];
			} else {
				break;
			}
		}

		return $flow;
	}
*/

	/**
	  * @deprecated
	 */
	public function getChildTreeById($id, $parent_field, $index_field)
	{
		$tree = $this->getItensById($id, $parent_field);

		if (count($tree) > 0) {
			foreach($tree as &$subobject) {
				$subobject['tree'] = $this->getChildTreeById($subobject[$index_field], $parent_field, $index_field);
			}
		}
		return $tree;
	}

	/**
	  * @deprecated
	 */
	protected function processFilter($data)
	{
		$where = array();
		if (isset($data['where'])) {
			$where[] = $data['where'];
		}
		$order = array();
		if (isset($data['order'])) {
			$order[] = $data['order'];
		}
		$group = array();

		$limit = array();
		if (isset($data['limit']) && is_numeric($data['limit'])) {
			$limit[] = "LIMIT " . $data['limit'];
		}

		if (isset($data['start']) && is_numeric($data['start'])) {
			$limit[] = "OFFSET " . $data['start'];
		}

		return array(
			$where, $order, $group, $limit
		);
	}

	/**
	  * @deprecated
	 */
	public function getBlank() {
		if ($this->isValid()) {
			// GETTING FIELDS.
			$tableFields = $this->db->MetaColumnNames($this->table_name, true);

			$fields = array_flip($tableFields);

			foreach($fields as &$item) {
				$item = "";
			}

			return $fields;

		}

	}

	/**
	  * @deprecated
	 */
	public function processDataTableFilter() {
		if ($this->isValid()) {
			// PROCESS DATATABLE PARAMS.
			$totalColumns = $_GET['iColumns'];

			// GETTING FIELDS.
			$tableFields = $this->db->MetaColumnNames($this->table_name, true);

			$htmlFields = array();
			for($i = 0; $i < $totalColumns; $i++) {
				$htmlFields[] = $_GET["mDataProp_" . $i];
			}

			$fields = array_intersect($tableFields, $htmlFields);

			$where = array();
			if (!empty($_GET['sSearch'])) {
				$search = $_GET['sSearch'];
				$whereSearch = array();
				foreach($fields as $field) {
					$whereSearch[] = sprintf("%s LIKE '%%%s%%'", $field, $search);
				}
				$where[] = "(" . implode(" OR ", $whereSearch) . ")";
			}


			// PROCESSING WHERES.
			if (count($where) > 0) {
				$data['where'] = implode(" AND ", $where);
			}

			// ORDER FIELDS.
	  		$sortIndex = $_GET['iSortCol_0'];

	  		if ($_GET["bSortable_" . $sortIndex] == "true") {
	  			$totalSort = $_GET['iSortingCols'];
	  			$sortName = sprintf("mDataProp_%d", $sortIndex);
	  			$sortDirc = sprintf("sSortDir_%d", ($totalSort - 1));

	  			$sort = $_GET[$sortName];
	  			$order = $_GET[$sortDirc];
	  			$data['order'] = $sort . " " . $order;
	  		}

			// LIMIT VARS
			$data['limit'] = $_GET['iDisplayLength'];
			$data['start'] = $_GET['iDisplayStart'];

			return $data;
		}
		return array();
	}

	/**
	  * @deprecated
	 */
	public function arrayPostgres2Php($postgresArray, $cast = null){
		$postgresStr = trim($postgresArray,'{}');
		$elmts = explode(',',$postgresStr);

		/*
		if ($cast == "int") {
			$elmts = array_filter_key($elmts, function ($value, $index) use ($params) {
				if (filter_var($value, FILTER_VALIDATE_FLOAT) === FALSE) {
					return false;
				}
				return true;
			});
		}
		*/
		return $elmts;
	}

}
