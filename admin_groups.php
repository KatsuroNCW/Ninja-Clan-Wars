<?php
require_once('core/init.php');

$user = new User();

if(!$user->isLoggedIn() || !$user->hasPermission('root')) {
	Redirect::to('index.php');
} else {
	$user_data = $user->data();
}

$group = new Group();

if(Input::exists('post', 'submit_add_group')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'group_name' => array(
			'name' => 'nazwa grupy',
			'required' => true,
			'min_char' => 2,
			'max_char' => 30,
			'unique' => array('users_groups', 'group_name')
		),
		'group_color' => array(
			'name' => 'kolor grupy',
			'required' => true,
			'unique' => array('users_groups', 'group_color')
		)
	));

	if($validation->passed()) {
		try {
			$group_permissions = array();
			foreach ($group->getPermissionsList() as $per_name => $per_array) {
				$group_permissions[$per_name] = Input::get($per_name);
			}
			$group->create(array(
				'group_name' => Input::get('group_name'),
				'group_color' => Input::get('group_color'),
				'group_permissions' => json_encode($group_permissions)
			));

			Session::flash('admin_groups', 'Pomyślnie dodano nowa grupę!');
			Redirect::to('admin_groups.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', 'submit_update_group')) {
	$group_data = $group->getGroup(Input::get('user_group'));
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'group_name' => array(
			'name' => 'nazwa grupy',
			'required' => true,
			'min_char' => 2,
			'max_char' => 30
		),
		'group_color' => array(
			'name' => 'kolor grupy',
			'required' => true
		)
	));

	if($validation->passed()) {
		try {
			$group_permissions = array();
			foreach ($group->getPermissionsList() as $per_name => $per_array) {
				$group_permissions[$per_name] = Input::get($per_name);
			}
			$group->update($group_data->group_id, array(
				'group_name' => Input::get('group_name'),
				'group_color' => substr(Input::get('group_color'), 1),
				'group_permissions' => json_encode($group_permissions)
			));

			Session::flash('admin_groups', 'Pomyślnie edytowano grupę!');
			Redirect::to('admin_groups.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', 'submit_add_permission')) {
	foreach ($group->getList() as $group_id => $group_name) {
		$group_data = $group->getGroup($group_name);
		$group_permissions = json_decode($group_data->group_permissions, true);
		$group_permissions[Input::get('per_name')] = intval(Input::get('per_value'));
		try {
			$group->updatePermission($group_id, json_encode($group_permissions));
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
	Session::flash('admin_groups', 'Pomyślnie dodano uprawnienie do wszystkich grup!');
	Redirect::to('admin_groups.php');
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Grupy i uprawnienia</title>
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
	<h2 class="big-heading">Grupy i uprawnienia</h2>
<?php

include_once('admin_panel_menu.inc');

echo '<div class="info-box">';
if(Session::exists('admin_groups')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('admin_groups').'</div>';
}
echo '</div>';

if(Input::exists('post', 'submit_update_group') || Input::exists('post', 'submit_add_group')) {
	if(!$validation->passed()) {
		echo '<div class="info-box">';
		foreach($validation->errors() as $error) {
			echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
		}
		echo '</div>';
	}
}

echo '<div class="admin-groups">';
if(Input::exists('get', 'add_group')) {
	echo '<h2 class="left-border-heading">Tworzenie nowej grupy</h2>';
	echo '<form method="post">';
		echo '<h2 class="form__heading">Podaj nazwę grupy:</h2>';
		echo '<input type="text" name="group_name" placeholder="Podaj nazwę grupy" value="'.Input::get('group_name').'" class="form__input">';
		echo '<h2 class="form__heading">Podaj kolor grupy:</h2>';
		echo '<input type="color" name="group_color" value="'.Input::get('group_color').'" class="form__input--colorpicker">';
		echo '<h2 class="form__heading">Uprawnienia:</h2>';
		foreach ($group->getPermissionsList() as $per_name => $per_array) {
			foreach ($per_array as $per_description => $per_value) {
				echo '<div class="form__label-box"><span>'.$per_description.': </span>';
				if($per_value) {
					echo '<label class="form__label-box-label">tak<input type="radio" name="'.$per_name.'" value="1" class="form__label-box-radio" checked></label>';
					echo '<label class="form__label-box-label">nie<input type="radio" name="'.$per_name.'" value="0" class="form__label-box-radio"></label>';
				} else {
					echo '<label class="form__label-box-label">tak<input type="radio" name="'.$per_name.'" value="1" class="form__label-box-radio"></label>';
					echo '<label class="form__label-box-label">nie<input type="radio" name="'.$per_name.'" value="0" class="form__label-box-radio" checked></label>';
				}
				echo '</div>';
			}
		}
		echo '<input type="submit" name="submit_add_group" value="Dodaj nowa grupe" class="form__button form__button--center">';
		echo '<a href="javascript:history.go(-1)" class="form__button form__button--center">Powrót</a>';
	echo '</form>';
}
elseif (Input::exists('get', 'update_group')) {
	$group_data = $group->getGroup(Input::get('user_group'));
	echo '<h2 class="left-border-heading">Aktualizacja grupy</h2>';
	echo '<form method="post">';
		echo '<h2 class="form__heading">Podaj nazwę grupy:</h2>';
		echo '<input type="text" name="group_name" placeholder="Podaj nazwę grupy" value="'.$group_data->group_name.'" class="form__input">';
		echo '<h2 class="form__heading">Podaj kolor grupy:</h2>';
		echo '<div class="admin-groups__colorpicker-box"><span>aktualny kolor: #'.$group_data->group_color.'</span><input type="color" name="group_color" value="#'.$group_data->group_color.'" class="form__input--colorpicker"></div>';
		echo '<h2 class="form__heading">Uprawnienia:</h2>';
		foreach ($group->getPermissions(Input::get('user_group')) as $per_name => $per_value) {
			echo '<div class="form__label-box"><span>'.$group->getPermissionDescription($per_name).': </span>';
				if($per_value) {
					echo '<label class="form__label-box-label">tak<input type="radio" name="'.$per_name.'" value="1" class="form__label-box-radio" checked></label>';
					echo '<label class="form__label-box-label">nie<input type="radio" name="'.$per_name.'" value="0" class="form__label-box-radio"></label>';
				} else {
					echo '<label class="form__label-box-label">tak<input type="radio" name="'.$per_name.'" value="1" class="form__label-box-radio"></label>';
					echo '<label class="form__label-box-label">nie<input type="radio" name="'.$per_name.'" value="0" class="form__label-box-radio" checked></label>';
				}
			echo '</div>';
		}
		echo '<input type="submit" name="submit_update_group" value="Aktualizuj grupę" class="form__button form__button--center">';
		echo '<a href="javascript:history.go(-1)" class="form__button form__button--center">Powrót</a>';
	echo '</form>';
}
elseif (Input::exists('get', 'add_new_permission')) {
	echo '<h2 class="left-border-heading">Dodaj nowe uprawnienie</h2>';
	echo '<form method="post" class="form">';
		echo '<h2 class="form__heading">Nazwa nowego uprawnienia:</h2>';
		echo '<input type="text" name="per_name" placeholder="Podaj nazwę uprawnienia" value="'.Input::get('per_name').'" class="form__input">';
		echo '<h2 class="form__heading">Domyślne ustawienie:</h2>';
		echo '<select name="per_value" class="form__input form__input--select">';
			echo '<option value="1">Tak</option>';
			echo '<option value="0">Nie</option>';
		echo '</select>';
		echo '<input type="submit" name="submit_add_permission" value="Dodaj nowe uprawnienie" class="form__button form__button--center">';
		echo '<a href="javascript:history.go(-1)" class="form__button form__button--center">Powrót</a>';
	echo '</form>';
}
else {
	echo '<h2 class="left-border-heading">Grupy i uprawnienia</h2>';
	echo '<form method="get" class="form">';
		echo '<h2 class="form__heading">Wybierz grupę:</h2>';
		echo '<select name="user_group" class="form__input form__input--select">';
		foreach ($group->getList() as $group_name) {
			echo '<option value="'.$group_name.'">'.$group_name.'</option>';
		}
		echo '</select>';
		echo '<input type="submit" name="update_group" value="Przejdź do ustawień" class="form__button form__button--center">';
	echo '</form>';

	echo '<form method="get" class="form">';
		echo '<input type="submit" name="add_group" value="Dodaj nowa grupę" class="form__button form__button--center">';
	echo '</form>';

	echo '<form method="get" class="form">';
		echo '<input type="submit" name="add_new_permission" value="Dodaj nowe uprawnienie" class="form__button form__button--center">';
		echo '<a href="javascript:history.go(-1)" class="form__button form__button--center">Powrót</a>';
	echo '</form>';
}
echo '</div>';

?>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
