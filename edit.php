<?php

require_once('core/init.php');

$user = new User();
if(!$user->isLoggedIn()) {
	Redirect::to(404);
} else {
	$user_data = $user->data();

}

if(($topic_id = Input::get('tid')) && ($post_id = Input::get('id'))) {
	$post = new Post($post_id);
	if(!$post->exists()) {
		Redirect::to(404);
	}
	$topic = new Topic($topic_id);
	$post_data = $post->data();
	$topic_data = $topic->data();
} else if(($topic_id = Input::get('tid')) && ($action = Input::get('sticky'))) {
	$topic = new Topic();
	try {
		if($action === 'yes') {
			$topic->update($topic_id, array(
				'topic_sticky' => 1
			));
			Session::flash('viewtopic', 'Pomyślnie przyklejono wybrany temat!');
		} else if($action === 'no') {
			$topic->update($topic_id, array(
				'topic_sticky' => 0
			));
			Session::flash('viewtopic', 'Pomyślnie odklejono wybrany temat!');
		}
		Redirect::to('viewtopic.php?id='.$topic_id);
	} catch(Exception $e) {
		die($e->getMessage());
	}
} else if(($topic_id = Input::get('tid')) && ($action = Input::get('close'))) {
	$topic = new Topic();
	try {
		if($action === 'yes') {
			$topic->update($topic_id, array(
				'topic_closed' => 1
			));
			Session::flash('viewtopic', 'Pomyślnie zamknięto wybrany temat!');
		} else if($action === 'no') {
			$topic->update($topic_id, array(
				'topic_closed' => 0
			));
			Session::flash('viewtopic', 'Pomyślnie otwarto wybrany temat!');
		}
		Redirect::to('viewtopic.php?id='.$topic_id);
	} catch(Exception $e) {
		die($e->getMessage());
	}
} else if(($topic_id = Input::get('tid')) && ($action = Input::get('move'))) {
	$topic = new Topic($topic_id);
	$topic_data = $topic->data();
} else if(($topic_id = Input::get('tid')) && ($action = Input::get('edit_topic'))) {
	$topic = new Topic($topic_id);
	$topic_data = $topic->data();
} else {
	Redirect::to(404);
}

if(Input::exists('post', "submit_edit")) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'post_contents' => array(
			'name' => 'treść posta',
			'required' => true
		)
	));

	if($validation->passed()) {
		$post = new Post();
		if(Input::get('post_type') == 1) {
			$post_type = 1;
		} else {
			$post_type = 0;
		}

		try {
			if(Input::get('hide_edit') == 1) {
				$post->update($post_data->post_id, array(
					'post_contents' => BbcodeParser::get(Input::get('post_contents')),
					'post_hide' => BbcodeParser::get(Input::get('post_hide')),
					'post_type' => $post_type
				));
			} else {
				$post->update($post_data->post_id, array(
					'post_contents' => BbcodeParser::get(Input::get('post_contents')),
					'post_hide' => BbcodeParser::get(Input::get('post_hide')),
					'post_edited' => date('Y-m-d H:i:s'),
					'post_edited_by' => $user_data->user_login,
					'post_type' => $post_type
				));
			}

			Session::flash('viewtopic', 'Pomyślnie zaktualizowano wybranego posta!');
			Redirect::to('viewtopic.php?pid='.$post_data->post_id.'#p'.$post_data->post_id);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', "submit_edit_topic")) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'topic_name' => array(
			'name' => 'nazwa tematu',
			'required' => true,
			'min_char' => '5'
		)
	));

	$validate2 = new Validate();
	$validation2 = $validate2->check($_FILES, array(
		'topic_img' => array(
			'name' => 'grafika tematu',
			'img_verify' => true,
			'img_types' => true,
			'img_width' => 1000,
			'img_height' => 300
		)
	));

	if($validation->passed() && $validation2->passed()) {
		try {
			$topic->update($topic_id, array(
				'topic_name' => Input::get('topic_name')
			));

			if($validation2->passed()) {
				move_uploaded_file($_FILES['topic_img']['tmp_name'], 'style/img/topic/'.$topic_id.'.'.substr($_FILES['topic_img']['type'], 6));
			}

			Session::flash('viewtopic', 'Pomyślnie uaktualniono nazwę tematu!');
			Redirect::to('viewtopic.php?id='.$topic_id);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', "submit_move_topic")) {
	try {
		$posts = new Post();
		foreach ($posts->showAllFromTopic($topic_id) as $post) {
			$posts->update($post->post_id, array(
				'post_section' => Input::get('new_topic_section')
			));
		}

		$topic->update($topic_id, array(
			'topic_section' => Input::get('new_topic_section')
		));

		Session::flash('viewtopic', 'Pomyślnie przeniesiono temat!');
		Redirect::to('viewtopic.php?id='.$topic_id);
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Edycja posta</title>
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

<?php
if(Input::exists('post', "submit_preview")) {
	echo '<section class="post-preview wrapper">';
		echo '<h2 class="left-border-heading">Podgląd posta</h2>';
		echo '<div class="post">';
			if(Input::get('post_type') != 1) {
				echo '<div class="post__left">';
					$author = new User($post_data->post_by);
					$author_data = $author->data();
					echo '<a class="post__author" href="profile.php?id='.$author_data->user_login.'">'.$author_data->user_login.'</a>';
					echo '<div class="post__avatar"><img src="'.imageType('style/img/avatars/'.$author_data->avatar).'"></div>';
					foreach ($user->showGroups($author_data->user_id) as $group_name => $group_color) {
						echo '<div class="post__rang" style="color: #'.$group_color.'">'.$group_name.'</div>';
					}
				echo '</div>';
			}
			if(Input::get('post_type') != 1) {
				echo '<div class="post__right">';
			} else {
				echo '<div class="post__center">';
			}

				echo '<div class="post__contents">'.BbcodeParser::get(Input::get('post_contents')).'</div>';
				if(Input::get('post_hide') != '') {
					echo '<div class="post__contents post__contents--hide"><h2 class="hide-header">Ukryta wiadomość:</h2>'.BbcodeParser::get(Input::get('post_hide')).'</div>';
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
}

if(($topic_id = Input::get('tid')) && ($post_id = Input::get('id'))) {
	if(Input::exists('post', "submit_edit")) {
		if(!$validation->passed()) {
			echo '<div class="info-box">';
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
	}
	echo '<main class="post-create wrapper">';
		echo '<form method="post" class="form">';
			echo '<h2 class="left-border-heading">Edycja posta z tematu: '.escape($topic_data->topic_name).'</h2>';
			echo '<h3 class="form__heading">Treść posta</h3>';
			include_once('bbcode-panel.php');
			echo '<textarea id="bbcode-menu" class="form__input form__input--textarea" name="post_contents" placeholder="Treść posta">'.HtmlParser::get($post_data->post_contents).'</textarea>';

			echo '<h3 class="form__heading">Ukryta wiadomość (opcjonalna)</h3>';
			echo '<textarea class="form__input form__input--textarea" name="post_hide" placeholder="Ukryta wiadomość">'.HtmlParser::get($post_data->post_hide).'</textarea>';
			if($user->hasPermission('root')) {
				echo '<label class="form__button form__button--first"><input type="checkbox" name="hide_edit" class="form__button-checkbox" value="1" checked> Ukryta edycja</label>';
				echo '<label class="form__button"><input type="checkbox" name="post_type" class="form__button-checkbox" value="1"';
				if($post_data->post_type == 1) {
					echo ' checked';
				}
				echo '> Włącz tryb mechaniki</label>';
			}
			echo '<input type="submit" value="Wyślij posta" name="submit_edit" class="form__button">';
			echo '<input type="submit" value="Podgląd" name="submit_preview" class="form__button">';
			echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
		echo '</form>';
	echo '</main>';
} else if(($topic_id = Input::get('tid')) && ($action = Input::get('move'))) {
	echo '<main class="post-create wrapper">';
		echo '<form method="post">';
			echo '<h2 class="left-border-heading">Zmiana przenoszenie tematu: '.escape($topic_data->topic_name).'</h2>';
			echo '<div class="information">Kliknięcie przycisku poniżej spowoduje przeniesienie tematu oraz postów w nim zawartych do wybranego miejsca.</div>';
			echo '<h3 class="form__heading">Dział docelowy</h3>';
				$sections = new Section();
				echo '<select name="new_topic_section" class="form__input form__input--select">';
				foreach ($sections->listOfSections() as $section) {
					echo '<option value="'.$section->section_id.'">'.$section->section_name.'</option>';
				}
				echo '</select>';
			echo '<input type="submit" value="Przenieś temat" name="submit_move_topic" class="form__button">';
			echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
		echo '</form>';
	echo '</main>';
} else if(($topic_id = Input::get('tid')) && ($action = Input::get('edit_topic'))) {
	if(Input::exists('post', "submit_edit_topic")) {
		if(!$validation->passed()) {
			echo '<div class="info-box">';
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			foreach($validation2->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
			echo '</div>';
		}
	}

	echo '<main class="post-create wrapper">';
		echo '<form method="post" enctype="multipart/form-data" class="form">';
			echo '<h2 class="left-border-heading">Edycja tematu</h2>';
			echo '<h3 class="form__heading">Nazwa tematu</h3>';
			echo '<input type="text" class="form__input" name="topic_name" placeholder="Nazwa tematu" value="'.escape($topic_data->topic_name).'">';
			echo '<h3 class="form__heading">Grafika tematu</h3>';
			echo '<input type="file" name="topic_img" class="form__input">';
			echo '<input type="submit" value="Zmień nazwę" name="submit_edit_topic" class="form__button">';
			echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
		echo '</form>';
	echo '</main>';
}
?>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
