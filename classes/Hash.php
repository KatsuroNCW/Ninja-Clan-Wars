<?php 

class Hash {
	public static function make($string) {
		return password_hash($string, PASSWORD_DEFAULT);
	}

	public static function check($form_password, $db_password) {
		if(password_verify($form_password, $db_password)) {
			return true;
		}
		return false;
	}

	public static function unique() {
		return self::make(uniqid());
	}
}