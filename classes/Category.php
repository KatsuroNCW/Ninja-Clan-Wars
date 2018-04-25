<?php

class Category {
	private $_db,
			$_data;

	public function __construct($category_id = null) {
		$this->_db = DB::getInstance();
		if($category_id != null) {
			$this->_data = $this->_db->get('categories', array('cat_id', '=', $category_id), 'cat_id')->first();
		} else {
			$this->showAll();
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('categories', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowej kategorii.');
		}
	}

	public function update($id, $fields = array()) {
		if(!$this->_db->update('categories', 'cat_id', $id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function delete($id) {
		if(!$this->_db->delete('categories', array('cat_id', '=', $id))) {
			throw new Exception('Usunięcie kategorii nie powiodło się.');
		}
	}

	public function show($id) {
		$data = $this->_db->get('categories', array('cat_id', '=', $id));

		if($data->count()) {
			return $data->first();
		}
		return false;
	}

	public function showAll() {
		$this->_data = $this->_db->get('categories', array('cat_id', '>', 0), 'cat_position', 'ASC')->results();
	}

	public function data() {
		return $this->_data;
	}
}