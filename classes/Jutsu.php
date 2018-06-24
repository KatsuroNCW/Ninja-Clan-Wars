<?php
class Jutsu {
    private $_db,
            $_data = array(),
            $_settings;

    public function __construct($jutsu_id) {
        $this->_db = DB::getInstance();
        $this->_settings = json_decode(file_get_contents("json/globalSettings.json"), true);

        if($jutsu_id !== null) {
            if($this->_db->get('jutsu', array('jutsu_id', '=', $jutsu_id))->first() !== null) {
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
}
?>
