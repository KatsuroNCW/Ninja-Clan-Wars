<?php
require_once('core/init.php');

$user = new User();

if(!$user->isLoggedIn() || !$user->hasPermission('root')) {
	Redirect::to('index.php');
} else {
	$user_data = $user->data();
}

$ban = new Ban();

if(Input::exists('post', 'submit_add_ban')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'ban_username' => array(
			'name' => 'login użytkownika',
			'must_exists' => array('users', 'user_login')
		),
		'ban_email' => array(
			'name' => 'e-mail użytkownika',
			'must_exists' => array('users', 'user_email'),
			'email_validate' => true
		),
		'ban_ip' => array(
			'name' => 'ip użytkownika',
			'ip_validate' => true
		)
	));

	if($validation->passed()) {
		try {
			$ban->create(array(
				'ban_date' => date('Y-m-d H:i:s'),
				'ban_username' => Input::get('ban_username'),
				'ban_email' => Input::get('ban_email'),
				'ban_ip' => Input::get('ban_ip'),
				'ban_message' => Input::get('ban_message'),
				'ban_creator' => $user_data->user_id,
				'ban_expire' => Input::get('ban_expire')
			));

			Session::flash('admin_bans', 'Pomyślnie dodano nowego bana!');
			Redirect::to('admin_bans.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', 'submit_edit_ban')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'ban_username' => array(
			'name' => 'login użytkownika',
			'must_exists' => array('users', 'user_login')
		),
		'ban_email' => array(
			'name' => 'e-mail użytkownika',
			'must_exists' => array('users', 'user_email'),
			'email_validate' => true
		),
		'ban_ip' => array(
			'name' => 'ip użytkownika',
			'ip_validate' => true
		)
	));

	if($validation->passed()) {
		try {
			$ban->update(Input::get('ban_edit'), array(
				'ban_username' => Input::get('ban_username'),
				'ban_email' => Input::get('ban_email'),
				'ban_ip' => Input::get('ban_ip'),
				'ban_message' => Input::get('ban_message'),
				'ban_expire' => Input::get('ban_expire')
			));

			Session::flash('admin_bans', 'Pomyślnie edytowano wybranego bana!');
			Redirect::to('admin_bans.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('get', 'submit_delete_ban')) {
	try {
		$ban->delete(Input::get('submit_delete_ban'));

		Session::flash('admin_bans', 'Pomyślnie usunięto wybranego bana!');
		Redirect::to('admin_bans.php');
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Bany</title>
	<?php require_once('head.inc'); ?>
	
	<link rel="stylesheet" type="text/css" href="style/forum.css">
	<link rel="stylesheet" type="text/css" href="style/admin_panel.css">
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
	<h2 class="big-heading">Bany</h2>
<?php

include_once('admin_panel_menu.inc');

echo '<div class="info-box">';
if(Session::exists('admin_bans')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('admin_bans').'</div>';
}
echo '</div>';

if(Input::exists('post', 'submit_add_ban')) {
	if(!$validation->passed()) {
		echo '<div class="info-box">';
		foreach($validation->errors() as $error) {
			echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
		}
		echo '</div>';
	}
}

echo '<div class="admin-bans">';
if(Input::exists('get', 'add_ban')) {
	if(Input::exists('post', 'submit_add_ban')) {
		if(!$validation->passed()) {
			echo '<div class="info-box">';
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
	}
	echo '<h2 class="left-border-heading">Tworzenie nowego bana</h2>';
	echo '<form method="post" class="form">';
		echo '<h2 class="form__heading">Login użytkownika:</h2>';
		echo '<input type="text" name="ban_username" placeholder="Podaj login użytkownika" value="'.Input::get('ban_username').'" class="form__input">';

		echo '<h2 class="form__heading">E-mail:</h2>';
		echo '<input type="email" name="ban_email" placeholder="Podaj e-mail użytkownika" value="'.Input::get('ban_email').'" class="form__input">';

		echo '<h2 class="form__heading">IP:</h2>';
		echo '<input type="text" name="ban_ip" placeholder="Podaj IP użytkownika" value="'.Input::get('ban_ip').'" class="form__input">';

		echo '<h2 class="form__heading">Wiadomość:</h2>';
		echo '<textarea name="ban_message" class="form__input form__input--textarea" placeholder="Wiadomość od banujacego">'.Input::get('ban_message').'</textarea>';

		echo '<h2 class="form__heading">Data wygaśnięcia:</h2>';
		echo '<input type="date" name="ban_expire" value="'.Input::get('ban_expire').'" class="form__input form__input--date">';

		echo '<input type="submit" name="submit_add_ban" value="Zbanuj" class="form__button form__button--first">';
		echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
	echo '</form>';
} else if(Input::exists('get', 'ban_user')) {
	$user_ban = new User(Input::get('ban_user'));
	$user_ban_data = $user_ban->data();
	echo '<h2 class="left-border-heading">Banowanie użytkownika</h2>';
	echo '<form method="post" class="form">';
		echo '<h2 class="form__heading">Login użytkownika:</h2>';
		echo '<input type="text" name="ban_username" placeholder="Podaj login użytkownika" value="'.$user_ban_data->user_login.'" class="form__input">';

		echo '<h2 class="form__heading">E-mail:</h2>';
		echo '<input type="email" name="ban_email" placeholder="Podaj e-mail użytkownika" value="'.$user_ban_data->user_email.'" class="form__input">';

		echo '<h2 class="form__heading">IP:</h2>';
		echo '<input type="text" name="ban_ip" placeholder="Podaj IP użytkownika" value="'.$user_ban_data->user_ip.'" class="form__input">';

		echo '<h2 class="form__heading">Wiadomość:</h2>';
		echo '<textarea name="ban_message" class="form__input form__input--textarea" placeholder="Wiadomość od banujacego"></textarea>';

		echo '<h2 class="form__heading">Data wygaśnięcia:</h2>';
		echo '<input type="date" name="ban_expire" class="form__input form__input--date">';

		echo '<input type="submit" name="submit_add_ban" value="Zbanuj" class="form__button form__button--first">';
		echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
	echo '</form>';
} else if(Input::exists('get', 'ban_preview')) {
	$preview = new Ban(Input::get('ban_preview'));
	$preview_data = $preview->data();
	echo '<h2 class="left-border-heading">Podglad bana</h2>';
	echo '<div class="ban-preview">';
		echo '<h2 class="ban-preview__heading">Nazwa użytkownika:</h2>';
		echo '<div class="ban-preview__box">'.$preview_data->ban_username.'</div>';

		echo '<h2 class="ban-preview__heading">Adres e-mail:</h2>';
		echo '<div class="ban-preview__box">'.$preview_data->ban_email.'</div>';

		echo '<h2 class="ban-preview__heading">IP:</h2>';
		echo '<div class="ban-preview__box">'.$preview_data->ban_ip.'</div>';

		echo '<h2 class="ban-preview__heading">Wiadomość od banujacego:</h2>';
		echo '<div class="ban-preview__box">'.$preview_data->ban_message.'</div>';

		echo '<h2 class="ban-preview__heading">Nazwa banujacego:</h2>';
		$ban_author = new User($preview_data->ban_creator);
		echo '<div class="ban-preview__box">'.$ban_author->data()->user_login.'</div>';

		echo '<h2 class="ban-preview__heading">Data wygaśnięcia:</h2>';
		if($preview_data->ban_expire != '') {
			echo '<div class="ban-preview__box">'.dateFormat($preview_data->ban_expire).'</div>';
		} else {
			echo '<div class="ban-preview__box">bezterminowy</div>';
		}

		echo '<a class="button button--first-in-row" href="?ban_edit='.$preview_data->ban_id.'">Edytuj</a>';
		echo '<a class="button" href="?ban_delete='.$preview_data->ban_id.'">Usuń</a>';
		echo '<a href="javascript:history.go(-1)" class="button">Powrót</a>';
	echo '</div>';
} else if(Input::exists('get', 'ban_edit')) {
	$edit = new Ban(Input::get('ban_edit'));
	$edit_data = $edit->data();
	echo '<h2 class="left-border-heading">Tworzenie nowego bana</h2>';
	echo '<form method="post" class="form">';
		echo '<h2 class="form__heading">Login użytkownika:</h2>';
		echo '<input type="text" name="ban_username" placeholder="Podaj login użytkownika" value="'.$edit_data->ban_username.'" class="form__input">';

		echo '<h2 class="form__heading">E-mail:</h2>';
		echo '<input type="email" name="ban_email" placeholder="Podaj e-mail użytkownika" value="'.$edit_data->ban_email.'" class="form__input">';

		echo '<h2 class="form__heading">IP:</h2>';
		echo '<input type="text" name="ban_ip" placeholder="Podaj IP użytkownika" value="'.$edit_data->ban_ip.'" class="form__input">';

		echo '<h2 class="form__heading">Wiadomość:</h2>';
		echo '<textarea name="ban_message" class="form__input form__input--textarea" placeholder="Wiadomość od banujacego">'.$edit_data->ban_message.'</textarea>';

		echo '<h2 class="form__heading">Data wygaśnięcia:</h2>';
		echo '<input type="date" name="ban_expire" value="'.$edit_data->ban_expire.'" class="form__input form__input--date">';

		echo '<input type="submit" name="submit_edit_ban" value="Potwierdz edycję" class="form__button form__button--first">';
		echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
	echo '</form>';
} else if(Input::exists('get', 'ban_delete')) {
	$preview = new Ban(Input::get('ban_delete'));
	echo '<h2 class="left-border-heading">Usuwanie bana</h2>';
	echo '<div class="information">Czy jesteś pewny, że chcesz usunąć bana z "'.dateFormat($preview->data()->ban_date).'"?</div>';
	echo '<a href="?submit_delete_ban='.$preview->data()->ban_id.'" class="button">usuń</a>';
} else {
	echo '<h2 class="left-border-heading">Bany</h2>';

	echo '<form method="get" class="form">';
		echo '<input type="submit" name="add_ban" value="Dodaj bana" class="form__button form__button--center">';
	echo '</form>';

	echo '<div class="admin-bans__item">';
		echo '<div class="admin-bans__item-date admin-bans__item--heading">Data wystawienia</div>';
		echo '<div class="admin-bans__item-login admin-bans__item--heading">Login</div>';
		echo '<div class="admin-bans__item-email admin-bans__item--heading">E-mail</div>';
		echo '<div class="admin-bans__item-ip admin-bans__item--heading">IP</div>';
		echo '<div class="admin-bans__item-options admin-bans__item--heading">Opcje</div>';
	echo '</div>';

	foreach ($ban->data() as $ban) {
		echo '<div class="admin-bans__item">';
			echo '<div class="admin-bans__item-date admin-bans__item--box">'.dateFormat($ban->ban_date).'</div>';
			echo '<div class="admin-bans__item-login admin-bans__item--box">'.$ban->ban_username.'</div>';
			echo '<div class="admin-bans__item-email admin-bans__item--box">'.$ban->ban_email.'</div>';
			echo '<div class="admin-bans__item-ip admin-bans__item--box">'.$ban->ban_ip.'</div>';
			echo '<div class="admin-bans__item-options admin-bans__item--box">
				<a class="button button--center" href="?ban_preview='.$ban->ban_id.'">podgląd</a>
				<a class="button button--center" href="?ban_edit='.$ban->ban_id.'">edycja</a>
				<a class="button button--center" href="?ban_delete='.$ban->ban_id.'">usuń</a>
			</div>';
		echo '</div>';
	}
}
echo '</div>';

?>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>