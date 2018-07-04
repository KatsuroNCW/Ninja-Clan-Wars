<?php
class Jutsu {
  private $_db,
          $_data = array(),
          $_settings,
          $_total_pages = 0;

  public function __construct($jutsu_id = null) {
    $this->_db = DB::getInstance();
    $this->_settings = json_decode(file_get_contents("json/globalSettings.json"), true);

    if($jutsu_id !== null) {
      if($this->_db->get('jutsu', array('jutsu_id', '=', $jutsu_id))->count() !== 0) {
        $this->_data = $this->_db->get('jutsu', array('jutsu_id', '=', $jutsu_id))->first();
      }
    }
  }

  public function create($fields = array()) {
    if(!$this->_db->insert('jutsu', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowego jutsu.');
		}

  }

  public function update($jutsu_id, $fields = array()) {
		if(!$this->_db->update('jutsu', 'jutsu_id', $jutsu_id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

  public function exists() {
    return (!empty($this->_data)) ? true : false;
  }

  public function getValue($key) {
    return $this->_data->$key;
  }

  public function getFolderName($jutsu_name) {
    $format_string = iconv(mb_detect_encoding($jutsu_name, mb_detect_order(), true), "us-ascii//TRANSLIT//IGNORE", $jutsu_name);
    $folder_bad_chars = array('#', '%', '&', '*', ':', '?', '/', '\\', '|', ' ');
    $folder_name = strtolower(str_replace($folder_bad_chars, "_", $format_string));

    return $folder_name;
  }

  public function getJutsuImgUrl($jutsu_name, $img_name) {
    $folder_name = $this->getFolderName($jutsu_name);
    return imageType("style/img/jutsu/".$folder_name.'/'.$img_name);
  }

  public function getList($page = 1, $name = '%', $class = '%', $nature = '%', $rank = '%', $fight_style = '%', $order_by = array('FIELD(jutsu_rank, "D","C","B","A","S")', 'jutsu_name_romaji'), $sort_by = 'ASC') {
    if(empty($name)) {
      $name = '%';
    }
    if(empty($class)) {
      $class = '%';
    }
    if(empty($nature)) {
      $nature = '%';
    }
    if(empty($rank)) {
      $rank = '%';
    }
    if(empty($fight_style)) {
      $fight_style = '%';
    }
    $sql = $this->_db->get('jutsu', array(
      'jutsu_name_romaji', 'LIKE', $name,
      'jutsu_classification', 'LIKE', $class,
      'jutsu_nature', 'LIKE', $nature,
      'jutsu_rank', 'LIKE', $rank,
      'jutsu_fight_style', 'LIKE', $fight_style
    ));

    $user_sets = 20;
		$jutsu_list = array();
		if($name === '') {
			$name = '%';
		} else {
			$name = '%'.$name.'%';
		}

    if($sql) {
			$total_records = $sql->count();
			$this->_total_pages = ceil($total_records / $user_sets);
			$start_from = ($page-1) * $user_sets;

      $jutsu_list_data = $this->_db->get('jutsu', array(
        'jutsu_name_romaji', 'LIKE', $name,
        'jutsu_classification', 'LIKE', $class,
        'jutsu_nature', 'LIKE', $nature,
        'jutsu_rank', 'LIKE', $rank,
        'jutsu_fight_style', 'LIKE', $fight_style
      ), $order_by, $sort_by, $start_from, $user_sets);
			if($jutsu_list_data) {
				return $jutsu_list_data->results();
			}
		}
    return false;
  }

  public function getTotalPages() {
    return $this->_total_pages;
  }
}
?>
