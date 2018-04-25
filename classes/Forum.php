<?php

class Forum {
	private $_db,
			$_all_posts,
			$_all_topics,
			$_all_users,
			$_users_online,
			$_guests_online,
			$_active_players,
			$_latest_user;

	public function __construct($user_login = NULL) {
		$this->_db = DB::getInstance();
		$session    = session_id();
		$time       = time();
		$time_check = $time-300;

		if($user_login) {
			$this->_db->delete('guests_online', array('session', '=', $session));
			$sql = $this->_db->get('users_online', array('session', '=', $session));
			if($sql->count() === 0) {
				$this->_db->insert('users_online', array(
					'session' => $session,
					'login' => $user_login,
					'time' => $time
				));
			} else {
				$this->_db->update('users_online', 'session', $session, array('time' => $time));
			}
		} else {
			$sql = $this->_db->get('guests_online', array('session', '=', $session));
			if($sql->count() === 0) {
				$this->_db->insert('guests_online', array(
					'session' => $session,
					'time' => $time
				));
			} else {
				$this->_db->update('guests_online', 'session', $session, array('time' => $time));
			}
		}

		$this->_db->delete('users_online', array('time', '<', $time_check));
		$this->_db->delete('guests_online', array('time', '<', $time_check));
		$this->_all_posts = $this->_db->get('posts', array('post_id', '>', '0'))->count();
		$this->_all_topics = $this->_db->get('topics', array('topic_id', '>', '0'))->count();
		$this->_all_users = $this->_db->get('users', array('user_id', '>', '0'))->count();
		$this->_users_online = $this->_db->get('users_online', array('time', '>', '0'))->count();
		$this->_guests_online = $this->_db->get('guests_online', array('time', '>', '0'))->count();
		$this->_latest_user = $this->_db->get('users', array('user_id', '>', '0'), 'user_id', 'DESC')->first()->user_login;
	}

	public function allPosts() {
		return $this->_all_posts;
	}

	public function allTopics() {
		return $this->_all_topics;
	}

	public function allUsers() {
		return $this->_all_users;
	}

	public function usersOnline() {
		return $this->_users_online;
	}

	public function guestsOnline() {
		return $this->_guests_online;
	}

	public function getOnlineList() {
		$data = $this->_db->get('users_online', array('time', '>', '0'))->results();
		$user_online = new User();
		$users_online = array();
		foreach ($data as $user) {
			$user_groups = array();
			foreach ($user_online->showGroups($user->login) as $group_name => $group_color) {
				$user_groups[] = $group_color;
			}
			$users_online[$user->login] = $user_groups[0];
		}
		return $users_online;
	}

	public function latestUser() {
		return $this->_latest_user;
	}
}