<?php 

require_once('core/init.php');

$user = new User();
$user->logout();

Session::flash('forum', 'Wylogowano!');
Redirect::to('forum.php');