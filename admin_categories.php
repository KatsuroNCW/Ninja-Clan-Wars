<?php
require_once('core/init.php');

$user = new User();

if(!$user->isLoggedIn() || !$user->hasPermission('root')) {
	Redirect::to('index.php');
} else {
	$user_data = $user->data();
}

$category = new Category();

if(Input::exists('post', 'submit_add_category')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'cat_name' => array(
			'name' => 'nazwa kategorii',
			'required' => true,
			'min_char' => 2,
			'max_char' => 30,
			'unique' => array('categories', 'cat_name')
		),
		'cat_position' => array(
			'name' => 'pozycja kategorii',
			'required' => true
		)
	));

	if($validation->passed()) {
		try {
			$category->create(array(
				'cat_name' => Input::get('cat_name'),
				'cat_position' => Input::get('cat_position')
			));

			Session::flash('admin_categories', 'Pomyślnie dodano nową kategorię!');
			Redirect::to('admin_categories.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('get', 'delete_cat')) {
	try {
		$category->delete(Input::get('delete_cat'));

		Session::flash('admin_categories', 'Pomyślnie usunięto wybraną kategorię!');
		Redirect::to('admin_categories.php');
	} catch(Exception $e) {
		die($e->getMessage());
	}
}

if(Input::exists('post', 'update_categories')) {
	try {
		foreach ($category->data() as $category_item) {
			$category->update($category_item->cat_id, array(
				'cat_name' => Input::get('cat_name'.$category_item->cat_id),
				'cat_position' => Input::get('cat_position'.$category_item->cat_id)
			));
		}

		Session::flash('admin_categories', 'Pomyślnie zaktualizowano kategorie!');
		Redirect::to('admin_categories.php');
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Kategorie</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<main class="admin-panel wrapper">
	<h2 class="big-heading">Kategorie</h2>
<?php

include_once('admin_panel_menu.inc');

echo '<div class="info-box">';
if(Session::exists('admin_categories')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('admin_categories').'</div>';
}
echo '</div>';

if(Input::exists('post', 'submit_add_category')) {
	if(!$validation->passed()) {
		echo '<div class="info-box">';
		foreach($validation->errors() as $error) {
			echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
		}
		echo '</div>';
	}
}

echo '<div class="admin-categories">';
if(Input::exists('get', 'add_category')) {
	echo '<h2 class="left-border-heading">Tworzenie nowej kategorii</h2>';
	echo '<form method="post">';
		echo '<h2 class="form__heading">Podaj nazwę kategorii:</h2>';
		echo '<input type="text" name="cat_name" placeholder="Podaj nazwę kategorii" value="'.Input::get('cat_name').'" class="form__input">';

		echo '<h2 class="form__heading">Podaj pozycję na forum:</h2>';
		echo '<input type="number" name="cat_position" value="'.Input::get('cat_position').'" class="form__input">';

		echo '<input type="submit" name="submit_add_category" value="Dodaj nowa kategorię" class="form__button form__button--center">';
		echo '<a href="javascript:history.go(-1)" class="form__button form__button--center">Powrót</a>';
	echo '</form>';
}
else {
	echo '<h2 class="left-border-heading">Kategorie</h2>';

	echo '<form method="get" class="form">';
		echo '<input type="submit" name="add_category" value="Dodaj nową kategorię" class="form__button form__button--center">';
	echo '</form>';

	echo '<form method="post" class="form">';
		echo '<h2 class="form__heading">Zarzadzaj kategoriami:</h2>';
		echo '<div class="admin-categories__item">';
			echo '<div class="admin-categories__item-name admin-categories__item--heading">Nazwa kategorii</div>';
			echo '<div class="admin-categories__item-position admin-categories__item--heading">Pozycja</div>';
			echo '<div class="admin-categories__item-options admin-categories__item--heading">Opcje</div>';
		echo '</div>';

		foreach ($category->data() as $category) {
			echo '<div class="admin-categories__item">';
				echo '<div class="admin-categories__item-name"><input type="text" name="cat_name'.$category->cat_id.'" placeholder="Podaj nazwę grupy" value="'.$category->cat_name.'" class="form__input"></div>';
				echo '<div class="admin-categories__item-position"><input type="number" name="cat_position'.$category->cat_id.'" placeholder="Podaj nazwę grupy" value="'.$category->cat_position.'" class="form__input"></div>';
				echo '<div class="admin-categories__item-options"><a href="?delete_cat='.$category->cat_id.'" class="button">usuń</a></div>';
			echo '</div>';
		}
		echo '<input type="submit" name="update_categories" value="Potwierdź zmiany" class="form__button form__button--center">';
	echo '</form>';
}
echo '</div>';

?>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
