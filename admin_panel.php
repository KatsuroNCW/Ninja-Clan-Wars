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

<?php require_once('header.inc.php'); ?>

<div class="page-up" title="Przewiń do góry"><i class="icon-up-open"></i></div>

<nav class="mobile-nav">
	<ul class="mobile-nav__menu">
		<li id="mobile-nav__land-switcher" class="mobile-nav__item"><a href="maps.php" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-globe"></i>Mapy</a></li>
		<li id="mobile-nav__land-switcher" class="mobile-nav__item"><a href="forum.php" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-home"></i>Główna</a></li>
		<li id="mobile-nav__menu" class="mobile-nav__item"><a href="javascript: void(0)" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-menu"></i>Menu</a></li>
	</ul>
</nav>

<main class="admin-panel wrapper">
	<h2 class="big-heading">Panel administratora</h2>
	<?php include_once('admin_panel_menu.inc'); ?>
</main>
