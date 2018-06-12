<?php

require_once('core/init.php');

$user = new User();

if(!$user->isLoggedIn() || !$user->hasPermission('root')) {
	Redirect::to('index.php');
} else {
	$user_data = $user->data();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Panel administratora</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<main class="admin-panel wrapper">
	<h2 class="big-heading">Panel administratora</h2>
	<?php include_once('admin_panel_menu.inc'); ?>
</main>
