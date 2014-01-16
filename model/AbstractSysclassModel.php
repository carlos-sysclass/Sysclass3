<?php
abstract class AbstractSysclassModel extends ModelManager implements ISyncronizableCollection  {
	
	protected $mapfields = array();
	protected $where = array();
	protected $mainTablePrefix = null;
	protected $selectSql = null;

	// ISyncronizableModel Methods
	// PUT THESE ISyncronizableCollection AND ISyncronizableModel METHODS INTO ModelManager Class
	public function getItem($id) {
		// !!!THIS METHODS DOES NOT VALIDATE IF THE USER HAS PERMISSION TO DO THAT, VALIDATE BEFORE!!! //
		$sql = $this->selectSql;

		if (count($this->joins) > 0) {
			$sql .= " " . $this->wrapJoins();
		}

		$this->addFilter(array(
			$this->id_field => $id
		));

		if (count($this->where) > 0) {
	        $sql .= " WHERE " . implode(" AND ", $this->where);
		}

        $itemRow = $this->db->GetRow($sql);

   		$itemRow = $this->transfields($itemRow);

        return $itemRow;
	}
	public function addItem($item) {
		throw new Exception('Class' . get_class($this) . 'must implement "addItem($data)" method');
		exit;
	}
	public function setItem($item, $id) {
		throw new Exception('Class' . get_class($this) . 'must implement "setItem($data, $id)" method');
		exit;
	}	
	public function deleteItem($id) {
		throw new Exception('Class' . get_class($this) . 'must implement "deleteItem($id)" method');
		exit;
	}

	// ISyncronizableCollection Methods
	public function getItems($id) {
		throw new Exception('Class' . get_class($this) . 'must implement "getItems($id)" method');
		exit;
	}
	public function addItems($data) {
		throw new Exception('Class' . get_class($this) . 'must implement "addItems($data)" method');
		exit;
	}
	public function setItems($data, $id) {
		throw new Exception('Class' . get_class($this) . 'must implement "setItems($data, $id)" method');
		exit;
	}	
	public function deleteItems($id) {
		throw new Exception('Class' . get_class($this) . 'must implement "deleteItems($id)" method');
		exit;
	}

	protected function transfields($rowData) {
		foreach($this->mapFields as $key => $dbkey) {
			if ($dbkey !== FALSE) {
				//var_dump(array_key_exists($dbkey, $rowData));
				if (array_key_exists($dbkey, $rowData)) {
					$rowData[$key] = $rowData[$dbkey];
					unset($rowData[$dbkey]);
				}
				//unset($rowData[$key]);
			}
		}
		return $rowData;
	}



	protected function mapfields($postData) {
		foreach($this->mapFields as $key => $dbkey) {
			if (array_key_exists($key, $postData)) {
				if ($dbkey !== FALSE) {
					//$dbkey = end(explode(".", $dbkey));
					$postData[$dbkey] = $postData[$key];
				}
				unset($postData[$key]);
			}
		}
		return $postData;
	}

	public function createJoin($type = 'LEFT', $table, $filter) {
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
		if (isset($options['quote']) && $options['quote'] === false) {
			$quote = false;
		} else {
			$quote = true;
		}
		if (isset($options['operator'])) {
			$operator = $options['operator'];
		} else {
			$operator = " IN ";
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

		$filterString = sprintf("%s %s (%s)", $field, $operator, $value);
		return $filterString;

	}
	public function addFilter(array $filter, array $options = null) {
		$filter = $this->mapFields($filter, $this->mainTablePrefix);
		foreach ($filter as $field => $value) {
			if (strpos($field, ".") === FALSE) {
				$field = (!is_null($this->mainTablePrefix) ? $this->mainTablePrefix . "." : "")  . $field;
			}

			$this->where[] = $this->createFilter($field, $value, $options);

		}

		return $this;
	}
}