<?php

class Section {
	private $_db,
			$_data = null;

	public function __construct($section_id = null) {
		$this->_db = DB::getInstance();
		if($section_id != null) {
			$this->_data = $this->_db->get('sections', array('section_id', '=', $section_id), 'section_id')->first();
		} else {
			$this->listOfSections();
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('sections', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowego działu.');
		}
	}

	public function update($id, $fields = array()) {
		if(!$this->_db->update('sections', 'section_id', $id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

	public function delete($section_id) {
		if(!$this->_db->delete('sections', array('section_id', '=', $section_id))) {
			throw new Exception('Usunięcie działu nie powiodło się.');
		}
	}

	public function showTopics($section_id) {
		return $this->_db->get('topics', array('topic_section', '=', $section_id), 'topic_id')->results();
	}

	public function lastTopic($section_id) {
		return $this->_db->get('topics', array('topic_section', '=', $section_id), 'topic_id', 'DESC')->first();
	}

	public function lastPost($section_id) {
		$data = $this->_db->get('posts', array('post_section', '=', $section_id), 'post_id', 'DESC');
		if($data->count()) {
			return $data->first();
		}
		return false;
	}

	public function data() {
		return $this->_data;
	}

	public function showFromCategory($category_id) {
		if(!$this->_data = $this->_db->get('sections', array('section_cat', '=', $category_id), 'section_position')->results()) {
			throw new Exception('Wystąpił problem z odczytam informacji');
		}
	}

	public function showSubsections($section_id) {
		return $this->_db->get('sections', array('section_subsection', '=', $section_id), 'section_position')->results();
	}

	public function exists($section_id) {
		$data = $this->_db->get('sections', array('section_id', '=', $section_id));
		return ($data->count()) ? true : false;
	}

	public function listOfSections() {
		return $this->_db->get('sections', array('section_id', '>', 0), 'section_position')->results();
	}
}