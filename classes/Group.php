<?php

class Group {
	private $_db,
			$_user,
			$_groups = array(),
			$_permissions = array(
				'root' => array(
					'Administrator forum' => 0
				),
				'moderator' => array(
					'Moderator forum' => 0
				),
				'player' => array(
					'Gracz' => 0
				),
				'read' => array(
					'Czytanie postów' => 0
				),
				'write' => array(
					'Tworzenie postów i tematów' => 0
				),
				'edit' => array(
					'Edycja postów i tematów' => 0
				),
				'delete' => array(
					'Kasowanie postów i tematów' => 0
				),
				'chat' => array(
					'Używanie chatu' => 0
				),
				'test' => array(
					'Testowa opcja' => 0
				)
			);

	public function __construct() {
		$this->_db = DB::getInstance();
		$this->_user = new User();
		$this->_groups = $this->getList();
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('users_groups', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowej grupy.');
		}
	}

	public function update($group_id, $fields = array()) {
		if(!$this->_db->update('users_groups', 'group_id', $group_id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function getList() {
		$users_groups = $this->_db->get('users_groups', array('group_id', '>', '0'))->results();
		foreach ($users_groups as $group) {
			$this->_groups[$group->group_id] = $group->group_name;
		}
		return $this->_groups;
	}

	public function getPermissionsList() {
		return $this->_permissions;
	}

	public function hasPermission($permission_name) {
		$list_of_groups = explode(', ', $this->_user->data()->user_group);

		foreach ($list_of_groups as $group_id) {
			$group = $this->_db->get('users_groups', array('group_id', '=', $group_id));
			if($group->count()) {
				$permissions = json_decode($group->first()->group_permissions, true);
				if($permissions[$permission_name]) {
					return true;
				}
			}
		}
		return false;
	}

	public function getGroup($name) {
		return $this->_db->get('users_groups', array('group_name', '=', $name))->first();
	}

	public function getPermissions($name) {
		return json_decode($this->getGroup($name)->group_permissions, true);
	}

	public function getPermissionDescription($name) {
		foreach ($this->_permissions as $per_name => $per_array) {
			if($per_name === $name) {
				foreach ($per_array as $per_description => $per_value) {
					return $per_description;
					break;
				}
			}
		}
		return false;
	}

	public function updatePermission($group_id, $group_permissions) {
		$this->update($group_id, array('group_permissions' => $group_permissions));
	}
}