<?php

class Post {
	private $_db,
			$_data,
			$_totalPages = 0;

	public function __construct($post_id = null) {
		$this->_db = DB::getInstance();
		if($post_id != null) {
			$this->_data = $this->_db->get('posts', array('post_id', '=', $post_id), 'post_id')->first();
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('posts', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowego posta.');
		}
	}

	public function update($id, $fields = array()) {
		if(!$this->_db->update('posts', 'post_id', $id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function delete($id) {
		if(!$this->_db->delete('posts', array('post_id', '=', $id))) {
			throw new Exception('Usunięcie posta nie powiodło się.');
		}
	}

	public function deleteAllFromTopic($topic_id) {
		if(!$this->_db->delete('posts', array('post_topic', '=', $topic_id))) {
			throw new Exception('Usunięcie posta nie powiodło się.');
		}
	}

	public function showFromTopic($topic_id, $page, $user_sets) {
		$sql = $this->_db->get('posts', array('post_topic', '=', $topic_id), 'post_id');
		if($sql) {
			$total_records = $sql->count();
			$total_pages = ceil($total_records / $user_sets);
			$start_from = ($page-1) * $user_sets;
			if($this->_data = $this->_db->get('posts', array('post_topic', '=', $topic_id), 'post_id', 'ASC', $start_from, $user_sets)->results()) {
				$this->_totalPages = $total_pages;
				return true;
			}
		}
		return false;
	}

	public function showUserPosts($user_id, $page) {
		$sql = $this->_db->get('posts', array('post_by_id', '=', $user_id));
		if($sql) {
			$total_records = $sql->count();
			$total_pages = ceil($total_records / 20);
			$start_from = ($page-1) * 20;
			if($this->_data = $this->_db->get('posts', array('post_by_id', '=', $user_id), 'post_id', 'DESC', $start_from, 20)->results()) {
				$this->_totalPages = $total_pages;
				return true;
			}
		}
		return false;
	}

	public function findPage($post_id, $user_sets) {
		$topic_id = $this->_db->get('posts', array('post_id', '=', $post_id))->first()->post_topic;
		$data = $this->_db->get('posts', array('post_topic', '=', $topic_id))->results();
		$counter = 0;
		foreach ($data as $post) {
			foreach ($post as $key => $value) {
				if($key == 'post_id') {
					if($value != $post_id) {
						$counter += 1;
					} else {
						$counter += 1;
						break;
					}
				}
			}
		}
		return ceil($counter / $user_sets);
	}

	public function showAllFromTopic($topic_id) {
		return $this->_db->get('posts', array('post_topic', '=', $topic_id), 'post_id');
	}

	public function showTopicInfo($id, $id_name = null) {
		if($id_name === 'post') {
			$id = $this->_db->get('posts', array('post_id', '=', $id))->first()->post_topic;
		}
		$data = $this->_db->get('topics', array('topic_id', '=', $id));
		
		if($data->count()) {
			return $data->first();
		}
		return false;
	}

	public function showLastPosts() {
		return $this->_db->get('posts', array('post_id', '>', '0'), 'post_id', 'DESC', 0, 5)->results();
	}

	public function data() {
		return $this->_data;
	}

	public function totalPages() {
		return $this->_totalPages;
	}

	public function exists() {
		return(!empty($this->_data)) ? true : false;
	}
}