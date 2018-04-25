<?php

class Input {
	public static function exists($type, $value) {
		switch ($type) {
			case 'post':
				return (!empty($_POST[$value])) ? true : false;
				break;

			case 'get':
				return (!empty($_GET[$value])) ? true : false;
				break;
			
			default:
				return false;
				break;
		}
	}

	public static function get($item) {
		if(isset($_POST[$item])) {
			return $_POST[$item];
		}
		else if(isset($_GET[$item])) {
			return $_GET[$item];
		}
		return '';
	}
}