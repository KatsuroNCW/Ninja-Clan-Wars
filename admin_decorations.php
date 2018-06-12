<?php
require_once('core/init.php');

$user = new User();

if(!$user->isLoggedIn() || !$user->hasPermission('root')) {
	Redirect::to('index.php');
} else {
	$user_data = $user->data();
}

$decoration = new Decoration();

if(Input::exists('post', 'submit_add_decoration')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'dec_name' => array(
			'name' => 'nazwa odznaczenia',
			'required' => true
		),
		'dec_description' => array(
			'name' => 'opis odznaczenia',
			'required' => true
		)
	));

	$validation2 = $validate->check($_FILES, array(
		'decoration_img' => array(
			'name' => 'grafika odznaczenia',
			'fileRequired' => true,
			'img_verify' => true,
			'img_types' => true,
			'img_width' => 185,
			'img_height' => 55
		)
	));

	if($validation->passed() && $validation2->passed()) {
		try {
			$decoration->create(array(
				'dec_name' => Input::get('dec_name'),
				'dec_description' => Input::get('dec_description')
			));

			move_uploaded_file($_FILES['decoration_img']['tmp_name'], 'style/img/decorations/'.$decoration->lastDecoration()->dec_id.'.'.substr($_FILES['decoration_img']['type'], 6));

			Session::flash('admin_decorations', 'Pomyślnie dodano nowe odznaczenie!');
			Redirect::to('admin_decorations.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}
if(Input::exists('post', 'submit_update_decoration')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'dec_name' => array(
			'name' => 'nazwa odznaczenia',
			'required' => true
		),
		'dec_description' => array(
			'name' => 'opis odznaczenia',
			'required' => true
		)
	));

	$validation2 = $validate->check($_FILES, array(
		'decoration_img' => array(
			'name' => 'grafika odznaczenia',
			'img_verify' => true,
			'img_types' => true,
			'img_width' => 185,
			'img_height' => 55
		)
	));

	if($validation->passed() && $validation2->passed()) {
		try {
			$decoration->update(Input::get('dec_edit'), array(
				'dec_name' => Input::get('dec_name'),
				'dec_description' => Input::get('dec_description')
			));

			move_uploaded_file($_FILES['decoration_img']['tmp_name'], 'style/img/decorations/'.Input::get('dec_edit').'.'.substr($_FILES['decoration_img']['type'], 6));

			Session::flash('admin_decorations', 'Pomyślnie edytowano nowe odznaczenie!');
			Redirect::to('admin_decorations.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}
if(Input::exists('get', 'submit_delete_decoration')) {
	try {
		$decoration->delete(Input::get('submit_delete_decoration'));

		Session::flash('admin_decorations', 'Pomyślnie usunięto wybrane odznaczenie!');
		Redirect::to('admin_decorations.php');
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Odznaczenia</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<main class="admin-panel wrapper">
	<h2 class="big-heading">Bany</h2>
<?php

include_once('admin_panel_menu.inc');

echo '<div class="info-box">';
if(Session::exists('admin_decorations')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('admin_decorations').'</div>';
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

echo '<div class="admin-decorations">';
if(Input::exists('get', 'add_decoration')) {
	if(Input::exists('post', 'submit_add_decoration')) {
		if(!$validation->passed()) {
			echo '<div class="info-box">';
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
		if(!$validation2->passed()) {
			echo '<div class="info-box">';
			foreach($validation2->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
	}
	echo '<h2 class="left-border-heading">Tworzenie nowego odznaczenia</h2>';
	echo '<form method="post" enctype="multipart/form-data" class="form">';
		echo '<h2 class="form__heading">Nazwa:</h2>';
		echo '<input type="text" name="dec_name" placeholder="Podaj nazwę odznaczenia" value="'.Input::get('dec_name').'" class="form__input">';

		echo '<h2 class="form__heading">Opis:</h2>';
		echo '<textarea name="dec_description" class="form__input form__input--textarea" placeholder="Krótki opis odznaczenia">'.Input::get('dec_description').'</textarea>';

		echo '<h2 class="form__heading">Grafika:</h2>';
		echo '<input type="file" name="decoration_img" class="form__input">';

		echo '<input type="submit" name="submit_add_decoration" value="Dodaj odznaczenie" class="form__button form__button--first">';
		echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
	echo '</form>';
} else if(Input::exists('get', 'dec_edit')) {
	$update_decoration = new Decoration(Input::get('dec_edit'));
	$update_data = $update_decoration->data();

	if(Input::exists('post', 'submit_update_decoration')) {
		if(!$validation->passed()) {
			echo '<div class="info-box">';
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
		if(!$validation2->passed()) {
			echo '<div class="info-box">';
			foreach($validation2->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
	}
	echo '<h2 class="left-border-heading">Tworzenie nowego odznaczenia</h2>';
	echo '<form method="post" enctype="multipart/form-data" class="form">';
		echo '<h2 class="form__heading">Nazwa:</h2>';
		echo '<input type="text" name="dec_name" placeholder="Podaj nazwę odznaczenia" value="'.$update_data->dec_name.'" class="form__input">';

		echo '<h2 class="form__heading">Opis:</h2>';
		echo '<textarea name="dec_description" class="form__input form__input--textarea" placeholder="Krótki opis odznaczenia">'.$update_data->dec_description.'</textarea>';

		echo '<h2 class="form__heading">Grafika:</h2>';
		echo '<input type="file" name="decoration_img" class="form__input">';

		echo '<input type="submit" name="submit_update_decoration" value="Edytuj odznaczenie" class="form__button form__button--first">';
		echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
	echo '</form>';
} else if(Input::exists('get', 'dec_delete')) {
	$preview = new Decoration(Input::get('dec_delete'));
	echo '<h2 class="left-border-heading">Usuwanie odzznaczenia</h2>';
	echo '<div class="information">Czy jesteś pewny, że chcesz usunąć odznaczenie o nazwie: "'.$preview->data()->dec_name.'"?</div>';
	echo '<a href="?submit_delete_decoration='.$preview->data()->dec_id.'" class="button">usuń</a>';
	echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
} else {
	echo '<h2 class="left-border-heading">Odznaczenia</h2>';

	echo '<form method="get" class="form">';
		echo '<input type="submit" name="add_decoration" value="Dodaj odznaczenie" class="form__button form__button--center">';
	echo '</form>';

	echo '<div class="admin-decorations__item">';
		echo '<div class="admin-decorations__item-img admin-decorations__item--heading">Grafika</div>';
		echo '<div class="admin-decorations__item-name admin-decorations__item--heading">Nazwa</div>';
		echo '<div class="admin-decorations__item-description admin-decorations__item--heading">Opis</div>';
		echo '<div class="admin-decorations__item-options admin-decorations__item--heading">Opcje</div>';
	echo '</div>';

	foreach ($decoration->data() as $dec) {
		echo '<div class="admin-decorations__item">';
			echo '<div class="admin-decorations__item-img admin-decorations__item--box"><img src="'.imageType('style/img/decorations/'.$dec->dec_id).'"></div>';
			echo '<div class="admin-decorations__item-name admin-decorations__item--box">'.$dec->dec_name.'</div>';
			echo '<div class="admin-decorations__item-description admin-decorations__item--box">'.$dec->dec_description.'</div>';
			echo '<div class="admin-decorations__item-options admin-decorations__item--box">
				<a class="button button--center" href="?dec_edit='.$dec->dec_id.'">edycja</a>
				<a class="button button--center" href="?dec_delete='.$dec->dec_id.'">usuń</a>
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
