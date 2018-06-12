<?php

class User {
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn,
			$_passed = false,
			$_errors = array(),
			$_userlist_data,
			$_totalPages = 0;

	private static $_user_gender = array('brak', 'kobieta', 'mężczyzna'),
					$_user_style = array('style_dark', 'style_red'),
					$_user_sort_by = array('Login' => 'user_login', 'Liczba postów' => 'user_total_posts', 'Data rejestracji' => 'user_date'),
					$_user_order_by = array('Rosnąco' => 'ASC', 'Malejąco' => 'DESC');

	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/logged_in_user_id');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);

				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					$this->logout();
				}
			}
		} else {
			$this->find($user);
		}
	}

	public function update($user_id, $fields = array()) {
		if(!$this->_db->update('users', 'user_id', $user_id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowego konta.');
		}
	}

	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'user_id' : 'user_login';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($username = null, $password = null, $remember = false) {

		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->user_id);
		} else {
			$user = $this->find($username);
			if($user) {
				if(Hash::check($password, $this->data()->user_password)) {
					Session::put($this->_sessionName, $this->data()->user_id);

					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('us_user_id', '=', $this->data()->user_id));

						if(!$hashCheck->count()) {
							$this->_db->insert('users_session', array(
								'us_user_id' => $this->data()->user_id,
								'us_hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->us_hash;
						}
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
				} else {
					$this->addError("Nieprawidłowe hasło.");
				}
			} else {
				$this->addError("Podana nazwa użytkownika nie istnieje w naszej bazie.");
			}
		}

		if(empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	public function updateIp() {
		$user_ip_list = explode(', ', $this->data()->user_ip);
		$current_ip = getIp();
		$new_ip_list = array();
		if(!empty($user_ip_list)) {
			if(in_array($current_ip, $user_ip_list)) {
				foreach ($user_ip_list as $ip) {
					if($current_ip != $ip) {
						array_push($new_ip_list, $ip);
					}
				}
			}
			array_unshift($new_ip_list, $current_ip);
		} else {
			array_push($new_ip_list, $current_ip);
		}
		$user_ip_list = implode(', ', $new_ip_list);
		$this->update($this->data()->user_id, array('user_ip' => $user_ip_list));
	}

	public function getCurrentIp() {
		$user_ip_list = explode(', ', $this->data()->user_ip);
		return $user_ip_list[0];
	}

	public function hasPermission($key) {
		if($this->isLoggedIn()) {
			$list_of_groups = explode(', ', $this->data()->user_group);
			foreach ($list_of_groups as $group_id) {
				$group = $this->_db->get('users_groups', array('group_id', '=', $group_id));
				if($group->count()) {
					$permissions = json_decode($group->first()->group_permissions, true);
					if(intval($permissions[$key]) === 1) {
						return true;
					}
				}
			}
			return false;
		}
		return false;
	}

	public function showGroups($user_id) {
		if(is_numeric($user_id)) {
			$list_of_groups = $this->_db->get('users', array('user_id', '=', $user_id))->first()->user_group;
		} else {
			$list_of_groups = $this->_db->get('users', array('user_login', '=', $user_id))->first()->user_group;
		}

		$list_of_groups = explode(', ', $list_of_groups);
		foreach ($list_of_groups as $group_id) {
			$user_groups[$this->_db->get('users_groups', array('group_id', '=', $group_id))->first()->group_name] = $this->_db->get('users_groups', array('group_id', '=', $group_id))->first()->group_color;
		}
		return $user_groups;
	}

	public function showDecorations($user_id) {
		if(is_numeric($user_id)) {
			$list_of_decorations = $this->_db->get('users', array('user_id', '=', $user_id))->first()->user_decorations;
		} else {
			$list_of_decorations = $this->_db->get('users', array('user_login', '=', $user_id))->first()->user_decorations;
		}

		$list_of_decorations = explode(', ', $list_of_decorations);
		foreach ($list_of_decorations as $dec_id) {
			$user_decorations[] = $this->_db->get('users_decorations', array('dec_id', '=', $dec_id))->first();
		}
		return $user_decorations;
	}

	public function exists() {
		return(!empty($this->_data)) ? true : false;
	}

	public function logout() {
		$this->_db->delete('users_session', array('us_user_id', '=', $this->data()->user_id));
		$this->_db->delete('users_online', array('login', '=', $this->data()->user_login));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function data() {
		return $this->_data;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function passed() {
		return $this->_passed;
	}

	public function addError($error) {
		return $this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public static function userGender() {
		return self::$_user_gender;
	}

	public static function userStyle() {
		return self::$_user_style;
	}

	public static function userSortBy() {
		return self::$_user_sort_by;
	}

	public static function userOrderBy() {
		return self::$_user_order_by;
	}

	public function getUserlist($page, $login = '%', $group = '%', $order_by = 'user_login', $sort_by = 'ASC') {
		$sql = $this->_db->get('users', array('user_login', 'LIKE', $login));

		$user_sets = 20;
		$userlist = array();
		if($login === '') {
			$login = '%';
		} else {
			$login = '%'.$login.'%';
		}

		if($sql) {
			$total_records = $sql->count();
			$total_pages = ceil($total_records / $user_sets);
			$start_from = ($page-1) * $user_sets;
			if($userlist_data = $this->_db->get('users', array('user_login', 'LIKE', $login), $order_by, $sort_by, $start_from, $user_sets)->results()) {
				$this->_totalPages = $total_pages;
				foreach ($userlist_data as $user) {
					$user_groups = $this->showGroups($user->user_id);
					foreach ($user_groups as $group_name => $group_color) {
						if($group_name == $group || $group === '%') {
							$userlist[] = array($user->user_id, $user->user_login, $user->user_date, $user->user_total_posts, $user_groups);
							break;
						}
					}
				}
				return $userlist;
			}
		}
		return false;
	}

	public function userlistData() {
		return $this->_userlist_data;
	}

	public function totalPages() {
		return $this->_totalPages;
	}

	public function isOnline($user_login) {
		return ($this->_db->get('users_online', array('login', '=', $user_login))->count() != 0) ? true : false;
	}

	public function hasKp() {
        return (empty($this->_db->get('kp', array('kp_user_id', '=', $this->_data->user_id))->results())) ? false : true;
	}

	public function getKpId() {
		$sql = $this->_db->get('kp', array('kp_user_id', '=', $this->_data->user_id))->results();
		$kp_list = array();
		foreach ($sql as $kp) {
			foreach ($kp as $kp_key => $kp_value) {
				if($kp_key === 'kp_id') {
					array_push($kp_list, $kp_value);
				}
			}
		}
		return $kp_list;
	}
}
