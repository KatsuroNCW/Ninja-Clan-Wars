<?php

class Decoration {
	private $_db,
			$_data;

	public function __construct($decoration_id = null) {
		$this->_db = DB::getInstance();
		if($decoration_id == null) {
			$this->_data = $this->_db->get('users_decorations', array('dec_id', '>', 0), 'dec_id')->results();
		} else {
			$this->_data = $this->_db->get('users_decorations', array('dec_id', '=', $decoration_id))->first();
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('users_decorations', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowego odznaczenia.');
		}
	}

	public function update($id, $fields = array()) {
		if(!$this->_db->update('users_decorations', 'dec_id', $id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function delete($id) {
		if(!$this->_db->delete('users_decorations', array('dec_id', '=', $id))) {
			throw new Exception('Usunięcie odznaczenia nie powiodło się.');
		}
	}

	public function data() {
		return $this->_data;
	}

	public function lastDecoration() {
		return $this->_db->get('users_decorations', array('dec_id', '>', 0), 'dec_id', 'DESC')->first();
	}
}