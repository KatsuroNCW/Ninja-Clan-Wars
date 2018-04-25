<?php

class Ban {
	private $_isBanned = false, 
			$_db,
			$_banInfo = array(),
			$_ban_message,
			$_data;

	public function __construct($ban_id = null) {
		$this->_db = DB::getInstance();
		if($ban_id == null) {
			$this->_data = $this->_db->get('bans', array('ban_id', '>', 0), 'ban_date')->results();
		} else {
			$this->_data = $this->_db->get('bans', array('ban_id', '=', $ban_id))->first();
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('bans', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowego bana.');
		}
	}

	public function update($id, $fields = array()) {
		if(!$this->_db->update('bans', 'ban_id', $id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function delete($id) {
		if(!$this->_db->delete('bans', array('ban_id', '=', $id))) {
			throw new Exception('Usunięcie bana nie powiodło się.');
		}
	}

	public function isBanned() {
		return $this->_isBanned;
	}

	public function addInfo($info) {
		$this->_banInfo[] = $info;
	}

	public function banInfo() {
		return $this->_banInfo;
	}

	public function banMessage() {
		if(!empty($this->_ban_message)) {
			return $this->_ban_message;
		}
		return false;
	}

	public function checkLoginForm($username) {
		if($this->checkUsername($username)) {
			$this->addInfo('Konto "'.$username.'" zostało zbanowane!');
		}

		if($this->checkIp()) {
			$this->addInfo('Twój adres IP znajduje się na naszej czarnej liście - logowanie niemożliwe!');
		}

		return ($this->isBanned()) ? true : false;
	}

	public function checkRegisterForm($email) {
		if($this->checkIp()) {
			$this->addInfo('Twój adres IP znajduje się na naszej czarnej liście - rejestracja niemożliwa!');
		}

		if($this->checkEmail($email)) {
			$this->addInfo('Twój e-mail znajduje się na naszej czarnej liście - rejestracja niemożliwa!');
		}

		return ($this->isBanned()) ? true : false;
	}

	public function checkUsername($username) {
		if($this->_db->get('bans', array('ban_username', '=', $username))->count() != 0) {
			$this->_isBanned = true;
			$this->_ban_message = $this->_db->get('bans', array('ban_username', '=', $username))->first()->ban_message;
			return true;
		}
		return false;
	}

	public function checkEmail($email) {
		if($this->_db->get('bans', array('ban_email', '=', $email))->count() != 0) {
			$this->_isBanned = true;
			return true;
		}
		return false;
	}

	public function checkIp() {
		if($this->_db->get('bans', array('ban_ip', '=', getIp()))->count() != 0) {
			$this->_isBanned = true;
			return true;
		}
		return false;
	}

	public function data() {
		return $this->_data;
	}
}