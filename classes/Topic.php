<?php

class Topic {
	private $_db,
			$_data,
			$_totalPages = 0;

	public function __construct($topic_id = null) {
		$this->_db = DB::getInstance();
		if($topic_id != null) {
			$this->_data = $this->_db->get('topics', array('topic_id', '=', $topic_id), 'topic_id')->first();
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('topics', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowego tematu.');
		}
	}

	public function update($id, $fields = array()) {
		if(!$this->_db->update('topics', 'topic_id', $id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function delete($id) {
		if(!$this->_db->delete('topics', array('topic_id', '=', $id))) {
			throw new Exception('Usunięcie tematu nie powiodło się.');
		}
	}

	public function show($id) {
		$data = $this->_db->get('topics', array('topic_section', '=', $id), 'topic_id');
		if($data->count()) {
			return $data->first();
		}
		return false;
	}

	public function data() {
		return $this->_data;
	}

	public function lastPost($topic_id) {
		$data = $this->_db->get('posts', array('post_topic', '=', $topic_id), 'post_id', 'DESC');
		if($data->count()) {
			return $data->first();
		}
		return false;
	}

	public function lastTopic($section_id) {
		$topic_data = $this->_db->get('topics', array('topic_section', '=', $section_id), 'topic_id', 'DESC')->first();
		if(!empty($topic_data)) {
			$first_post = $this->_db->get('posts', array('post_topic', '=', $topic_data->topic_id), 'post_id', 'ASC')->first();
			$last_topic_data = array(
				'topic_id' => $topic_data->topic_id,
				'topic_name' => $topic_data->topic_name,
				'topic_by' => $topic_data->topic_by,
				'post_date' => $first_post->post_date,
			);

			return $last_topic_data;
		} else {
			return false;
		}
	}

	public function newsSlider() {
		$ads_data = $this->_db->get('topics', array('topic_section', '=', 1), 'topic_id', 'DESC', 2)->results();
		$updates_data = $this->_db->get('topics', array('topic_section', '=', 6), 'topic_id', 'DESC', 2)->results();
		$news_data = [];

		foreach ($ads_data as $ad) {
			$first_post = $this->_db->get('posts', array('post_topic', '=', $ad->topic_id), 'post_id', 'ASC')->first();
			$news_data[] = array(
				'topic_id' => $ad->topic_id,
				'topic_name' => $ad->topic_name,
				'topic_by' => $ad->topic_by,
				'post_date' => $first_post->post_date,
			);
		}

		foreach ($updates_data as $update) {
			$first_post = $this->_db->get('posts', array('post_topic', '=', $update->topic_id), 'post_id', 'ASC')->first();
			$news_data[] = array(
				'topic_id' => $update->topic_id,
				'topic_name' => $update->topic_name,
				'topic_by' => $update->topic_by,
				'post_date' => $first_post->post_date,
			);
		}

		return (!empty($news_data)) ? $news_data : false;
	}

	public function postCounter($topic_id) {
		$data = $this->_db->get('posts', array('post_topic', '=', $topic_id));
		return $data->count() -1;
	}

	public function exists() {
		return(!empty($this->_data)) ? true : false;
	}

	public function showFromSection($section_id, $page, $user_sets) {
		$sql = $this->_db->get('topics', array('topic_section', '=', $section_id), 'topic_id');
		if($sql) {
			$total_records = $sql->count();
			$total_pages = ceil($total_records / $user_sets);
			$start_from = ($page-1) * $user_sets;
			if($this->_data = $this->_db->get('topics', array('topic_section', '=', $section_id), array('topic_sticky', 'topic_id'), 'DESC', $start_from, $user_sets)->results()) {
				$this->_totalPages = $total_pages;
				return true;
			}
		}
		return false;
	}

	public function totalPages() {
		return $this->_totalPages;
	}
}
