<?php
class KP
{
    private $_db,
            $_data = array(),
            $_isCreated = false;

    private static $_db2;

    public function __construct($kp_id = null) {
        $this->_db = DB::getInstance();
        if($kp_id !== null) {
            if($this->_db->get('kp', array('kp_id', '=', $kp_id))->first() !== null) {
                $this->_data = $this->_db->get('kp', array('kp_id', '=', $kp_id))->first();
                $this->_isCreated = true;
            }
        }
    }

    public function isCreated() {
        return $this->_isCreated;
    }

    public function create($fields = array()) {
        if(!$this->_db->insert('kp', $fields)) {
			throw new Exception('Wystąpił problem z utworzeniem nowej karty postaci.');
		}

    }

    public function update($kp_id, $fields = array()) {
		if(!$this->_db->update('kp', 'kp_id', $kp_id, $fields)) {
			throw new Exception('Aktualizacja danych nie powiodła się.');
		}
	}

    // public static function isActive($kp_id) {
    //     return ($this->_db->get('kp', array('kp_id', '=', $kp_id))->first()->kp_is_active == 1) ? true : false;
    // }

    // public static function userHasKp($user_id) {
    //     $sql = DB::getInstance()->get('kp', array('kp_user_id', '=', $user_id));
    //     return (empty($sql->results())) ? false : true;
    // }

    public function getValue($key) {
        $data = $this->_data->$key;
        if(Json::isJson($data)) {
            $data = json_decode($data);
        }
        return $data;
    }

    public function hasNatureType($nature_type) {
        foreach ($this->getValue('kp_nature_types') as $player_nature_type) {
            if (strtoupper($player_nature_type) === strtoupper($nature_type)) {
                return true;
            }
        }
        return false;
    }

    public function hasSpecialization($specialization) {
        foreach ($this->getValue('kp_specializations') as $player_specialization) {
            if (strtoupper($player_specialization) === strtoupper($specialization)) {
                return true;
            }
        }
        return false;
    }

    public function hasProfesion($profesion) {
        foreach ($this->getValue('kp_profesions') as $player_profesion) {
            if (strtoupper($player_profesion) === strtoupper($profesion)) {
                return true;
            }
        }
        return false;
    }

    public function getStatisticValue($stat_name) {
        $statistic_value = 0;
        switch (strtolower($stat_name)) {
            case "strength":
                $statistic_data = $this->getValue('kp_strength');
                break;
            case "concentration":
                $statistic_data = $this->getValue('kp_concentration');
                break;
            case "endurance":
                $statistic_data = $this->getValue('kp_endurance');
                break;
            case "speed":
                $statistic_data = $this->getValue('kp_speed');
                break;
            case "reactions":
                $statistic_data = $this->getValue('kp_reactions');
                break;
            case "mind":
                $statistic_data = $this->getValue('kp_mind');
                break;
            case "will":
                $statistic_data = $this->getValue('kp_will');
                break;
            case "chakra_control":
                $statistic_data = $this->getValue('kp_chakra_control');
                break;
        }

        foreach ($statistic_data as $data) {
            if($data->type === "basic" || $data->type === "constant") {
                $statistic_value += $data->amount;
            }
        }
        return $statistic_value;
    }

    public function hasJutsu($jutsu_id) {
        foreach ($this->getValue('kp_list_of_jutsus') as $player_jutsu_id) {
            if (intval($player_jutsu_id) === intval($jutsu_id)) {
                return true;
            }
        }
        return false;
    }

    public function hasSkill($skill_id, $skill_type) {
        switch ($skill_type) {
            case "normal":
                $player_skills = $this->getValue('kp_list_of_normal_skills');
                break;
            case "special":
                $player_skills = $this->getValue('kp_list_of_special_skills');
                break;
            case "extra":
                $player_skills = $this->getValue('kp_list_of_extra_skills');
                break;
        }
        foreach ($player_skills as $player_skill) {
            if (intval($player_skill->skill_id) === intval($skill_id)) {
                return true;
            }
        }
        return false;
    }

    public function hasItem($item_id) {
        foreach ($this->getValue('kp_list_of_items') as $player_item) {
            if (intval($player_item->item_id) === intval($item_id)) {
                return true;
            }
        }
        return false;
    }

    public function getKpList() {
        return $this->_db->get('kp', array('kp_id', '>', 0))->results();
    }
}
?>
