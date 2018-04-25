<?php

class DB {
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;

	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db').';charset='.Config::get('mysql/charset'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch(PDOEXception $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function query($sql, $params = array()) {
		$this->_error = false;

		if($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if(count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}

		return $this;
	}

	public function action($action, $table, $where = array(), $order_by = '', $sort_order = 'ASC', $start_from = null, $records_per_page = null) {
		if(count($where)%3 === 0) {
			$counter = count($where);
			$operators = array('=', '>', '<', '>=', '<=', 'LIKE');
			$sql = "{$action} FROM {$table} WHERE ";
			$tmp = 0;
			$values = array();
			for($i=0; $i < $counter; $i+=3) { 
				$field 		= $where[$i];
				$operator 	= $where[$i+1];
				$value 		= $where[$i+2];

				if(in_array($operator, $operators)) {
					if($tmp > 0) {
						$sql .= " AND ";
					}
					$sql .= "{$field} {$operator} ?";
					$tmp++;
					$values[] = $value;
				}
			}

			if($order_by !== '') {
				if(!is_array($order_by)) {
					$sql .= " ORDER BY {$order_by} {$sort_order}";
				} else {
					$tmp = 0;
					$sql .= " ORDER BY ";
					foreach ($order_by as $value) {
						if($tmp > 0) {
							$sql .= ', ';
						}
						$sql .= $value." {$sort_order}";
						$tmp++;
					}
				}
			}

			if($start_from !== null) {
				if($records_per_page !== null) {
					$sql .= " LIMIT {$start_from}, {$records_per_page}";
				} else {
					$sql .= " LIMIT {$start_from}";
				}
			}

			if(!$this->query($sql, $values)->error()) {
	 			return $this;
	 		}
		}
		return false;
	}

	public function get($table, $where, $order_by = '', $sort_order = 'ASC', $start_from = null, $records_per_page = null) {
		return $this->action('SELECT *', $table, $where, $order_by, $sort_order, $start_from, $records_per_page);
	}

	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	public function results() {
		return $this->_results;
	}

	public function first() {
		return $this->results()[0];
	}

	public function insert($table, $fields = array()) {
		if(count($fields)) {
			$keys = array_keys($fields);
			$values = '';
			$x = 1;

			foreach($fields as $key => $field) {
				$values .= '?';
				if($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}

			$sql = "INSERT INTO ".$table." (`".implode("`, `", $keys)."`) VALUES (".$values.")";

			if(!$this->query($sql, $fields)->error()) {
				return true;
			}
		}
		return false;
	}

	public function update($table, $update_key, $update_value, $fields = array()) {
		$set = '';
		$x = 1;

		foreach($fields as $name => $value) {
			$set .= $name." = ?";
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}

		$sql = "UPDATE ".$table." SET ".$set." WHERE ".$update_key." = ".$update_value;

		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}

	public function backup() {
		
	}

	public function error() {
		return $this->_error;
	}

	public function count() {
		return $this->_count;
	}
}