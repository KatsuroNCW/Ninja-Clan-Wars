<?php
require_once('core/init.php');

$user = new User();

if(!$user->isLoggedIn() || !$user->hasPermission('root')) {
	Redirect::to('index.php');
} else {
	$user_data = $user->data();
}

$categories = new Category();
$sections = new Section();

if(Input::exists('post', 'submit_add_section')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'section_name' => array(
			'name' => 'nazwa działu',
			'required' => true,
			'min_char' => 2,
			'max_char' => 30,
			'unique' => array('sections', 'section_name')
		)
	));

	if($validation->passed()) {
		try {
			$sections->create(array(
				'section_name' => Input::get('section_name'),
				'section_cat' => Input::get('section_cat'),
				'section_description' => Input::get('section_description'),
				'section_type' => Input::get('section_type'),
				'section_subsection' => Input::get('section_subsection')
			));

			Session::flash('admin_sections', 'Pomyślnie dodano nowy dział!');
			Redirect::to('admin_sections.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', 'submit_edit_section')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'section_name' => array(
			'name' => 'nazwa działu',
			'required' => true,
			'min_char' => 2,
			'max_char' => 30,
			'unique' => array('sections', 'section_name')
		)
	));

	if($validation->passed()) {
		try {
			$sections->update(Input::get('edit_section'), array(
				'section_name' => Input::get('section_name'),
				'section_cat' => Input::get('section_cat'),
				'section_description' => Input::get('section_description'),
				'section_type' => Input::get('section_type'),
				'section_subsection' => Input::get('section_subsection')
			));

			Session::flash('admin_sections', 'Pomyślnie edytowano wybrany dział!');
			Redirect::to('admin_sections.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('get', 'submit_delete_section')) {
	try {
		$sections->delete(Input::get('submit_delete_section'));

		Session::flash('admin_sections', 'Pomyślnie usunięto wybrany dział!');
		Redirect::to('admin_sections.php');
	} catch(Exception $e) {
		die($e->getMessage());
	}
}

if(Input::exists('post', 'update_positions')) {
	try {
		foreach ($sections->listOfSections() as $section_item) {
			$sections->update($section_item->section_id, array(
				'section_position' => Input::get('section_position'.$section_item->section_id)
			));
		}

		Session::flash('admin_sections', 'Pomyślnie zaktualizowano pozycje działów!');
		Redirect::to('admin_sections.php');
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Działy</title>
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
	<h2 class="big-heading">Działy</h2>
<?php

include_once('admin_panel_menu.inc');

echo '<div class="info-box">';
if(Session::exists('admin_sections')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('admin_sections').'</div>';
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

echo '<div class="admin-sections">';
if(Input::exists('get', 'add_section')) {
	if(Input::exists('post', 'submit_add_section')) {
		if(!$validation->passed()) {
			echo '<div class="info-box">';
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
	}
	echo '<h2 class="left-border-heading">Tworzenie nowego działu</h2>';
	echo '<form method="post" class="form">';
		echo '<h2 class="form__heading">Podaj nazwę działu:</h2>';
		echo '<input type="text" name="section_name" placeholder="Podaj nazwę działu" value="'.Input::get('section_name').'" class="form__input">';

		echo '<h2 class="form__heading">Kategoria:</h2>';
		echo '<select name="section_cat" class="form__input">';
			foreach ($categories->data() as $category) {
				echo '<option value="'.$category->cat_id.'">'.$category->cat_name.'</option>';
			}
		echo '</select>';

		echo '<h2 class="form__heading">Subforum:</h2>';
		echo '<select name="section_subsection" class="form__input">';
			echo '<option value="0">brak</option>';
			foreach ($sections->listOfSections() as $section) {
				echo '<option value="'.$section->section_id.'">'.$section->section_name.'</option>';
			}
		echo '</select>';

		echo '<h2 class="form__heading">Rodzaj:</h2>';
		echo '<div class="form__label-box"><span>Rodzaj działu: </span>';
			echo '<label class="form__label-box-label">kraj<input type="radio" name="section_type" value="land" class="form__label-box-radio" checked></label>';
			echo '<label class="form__label-box-label">forum<input type="radio" name="section_type" value="forum" class="form__label-box-radio"></label>';
		echo '</div>';

		echo '<h2 class="form__heading">Opis:</h2>';
		echo '<textarea name="section_description" class="form__input form__input--textarea" placeholder="Opis działu">'.Input::get('section_description').'</textarea>';

		echo '<input type="submit" name="submit_add_section" value="Dodaj nowy dział" class="form__button form__button--center">';
		echo '<a href="javascript:history.go(-1)" class="form__button form__button--center">Powrót</a>';
	echo '</form>';
} else if(Input::exists('get', 'edit_section')) {
	$seciton_edit = new Section(Input::get('edit_section'));
	$section_data = $seciton_edit->data();
	echo '<h2 class="left-border-heading">Edycja działu</h2>';
	echo '<form method="post">';
		echo '<h2 class="form__heading">Podaj nazwę działu:</h2>';
		echo '<input type="text" name="section_name" placeholder="Podaj nazwę działu" value="'.$section_data->section_name.'" class="form__input">';

		echo '<h2 class="form__heading">Kategoria:</h2>';
		echo '<select name="section_cat" class="form__input">';
			foreach ($categories->data() as $category) {
				if($section_data->section_cat == $category_id) {
					echo '<option value="'.$category->cat_id.'" selected>'.$category->cat_name.'</option>';
				} else {
					echo '<option value="'.$category->cat_id.'">'.$category->cat_name.'</option>';
				}
			}
		echo '</select>';

		echo '<h2 class="form__heading">Subforum:</h2>';
		echo '<select name="section_subsection" class="form__input">';
			if($section_data->section_subsection == 0) {
				echo '<option value="0" selected>brak</option>';
			} else {
				echo '<option value="0">brak</option>';
			}
			foreach ($sections->listOfSections() as $section) {
				if ($section_data->section_subsection == $section->section_id) {
					echo '<option value="'.$section->section_id.'" selected>'.$section->section_name.'</option>';
				} else {
					echo '<option value="'.$section->section_id.'">'.$section->section_name.'</option>';
				}
			}
		echo '</select>';

		echo '<h2 class="form__heading">Rodzaj:</h2>';
		echo '<div class="form__label-box"><span>Rodzaj działu: </span>';
			if($section_data->section_type == 'land') {
				echo '<label class="form__label-box-label">kraj<input type="radio" name="section_type" value="land" class="form__label-box-radio" checked></label>';
				echo '<label class="form__label-box-label">forum<input type="radio" name="section_type" value="forum" class="form__label-box-radio"></label>';
			} else {
				echo '<label class="form__label-box-label">kraj<input type="radio" name="section_type" value="land" class="form__label-box-radio"></label>';
				echo '<label class="form__label-box-label">forum<input type="radio" name="section_type" value="forum" class="form__label-box-radio" checked></label>';
			}

		echo '</div>';

		echo '<h2 class="form__heading">Opis:</h2>';
		echo '<textarea name="section_description" class="form__input form__input--textarea" placeholder="Opis działu">'.Input::get('section_description').'</textarea>';

		echo '<input type="submit" name="submit_edit_section" value="Potwierdź edycję" class="form__button form__button--center">';
		echo '<a href="javascript:history.go(-1)" class="form__button form__button--center">Powrót</a>';
	echo '</form>';
} else if(Input::exists('get', 'delete_section')) {
	$seciton_delete = new Section(Input::get('delete_section'));
	echo '<h2 class="left-border-heading">Usuwanie działu</h2>';
	echo '<div class="information">Czy jesteś pewny, że chcesz usunąć dział o nazwie "'.$seciton_delete->data()->section_name.'"?</div>';
	echo '<a href="?submit_delete_section='.$seciton_delete->data()->section_id.'" class="button">usuń bezpowrotnie</a>';
} else {
	echo '<h2 class="left-border-heading">Działy</h2>';

	echo '<form method="get" class="form">';
		echo '<input type="submit" name="add_section" value="Dodaj nowy dział" class="form__button form__button--center">';
	echo '</form>';

	echo '<form method="post" class="form">';
		echo '<h2 class="form__heading">Zarządzaj działami:</h2>';
		echo '<div class="admin-sections__item">';
			echo '<div class="admin-sections__item-name admin-sections__item--heading">Nazwa działu</div>';
			echo '<div class="admin-sections__item-position admin-sections__item--heading">Pozycja</div>';
			echo '<div class="admin-sections__item-options admin-sections__item--heading">Opcje</div>';
		echo '</div>';

		foreach ($categories->data() as $category) {
			echo '<div class="admin-sections__category">';
				echo '<h2 class="left-border-heading">'.$category->cat_name.'</h2>';
				$sections->showFromCategory($category->cat_id);
				foreach ($sections->data() as $section) {
					if($section->section_cat == $category->cat_id && $section->section_subsection == 0) {
						echo '<div class="admin-sections__item">';
							echo '<div class="admin-sections__item-name">'.$section->section_name.'</div>';
							echo '<div class="admin-sections__item-position"><input type="number" name="section_position'.$section->section_id.'" value="'.$section->section_position.'" class="form__input"></div>';
							echo '<div class="admin-sections__item-options">
								<a href="?edit_section='.$section->section_id.'" class="button">edytuj</a>
								<a href="?delete_section='.$section->section_id.'" class="button">usuń</a>
							</div>';
						echo '</div>';

						if($sections->showSubsections($section->section_id)) {
							foreach ($sections->showSubsections($section->section_id) as $subsection) {
								echo '<div class="admin-sections__item admin-sections__item--subforum">';
									echo '<div class="admin-sections__item-name">'.$subsection->section_name.'</div>';
									echo '<div class="admin-sections__item-position"><input type="number" name="section_position'.$subsection->section_id.'" value="'.$subsection->section_position.'" class="form__input"></div>';
									echo '<div class="admin-sections__item-options">
										<a href="?edit_section='.$subsection->section_id.'" class="button">edytuj</a>
										<a href="?delete_section='.$subsection->section_id.'" class="button">usuń</a>
									</div>';
								echo '</div>';
							}
						}
					}
				}
			echo '</div>';
		}
		echo '<input type="submit" name="update_positions" value="Zaktualizuj pozycje działów" class="form__button form__button--center">';
	echo '</form>';
}
echo '</div>';

?>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
