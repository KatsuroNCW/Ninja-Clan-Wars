<?php

session_start();
date_default_timezone_set('Europe/Warsaw');
setlocale(LC_ALL, 'pl_PL', 'pl', 'Polish_Poland.28592');

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8mb4',
		'db' => 'ncw_dbV3'
	),
	'remember' => array(
		'cookie_name' => 'remember_me',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'token_name' => 'token',
		'logged_in_user_id' => 'user_id'
	)
);

spl_autoload_register(function($class) {
	require_once('classes/'.$class.'.php');
});

require_once('functions/sanitize.php');
require_once('functions/imageType.php');
require_once('functions/getIp.php');
require_once('functions/dateFormat.php');

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/logged_in_user_id'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_session', array('us_hash', '=', $hash));

	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->us_user_id);
		$user->login();
	}
}