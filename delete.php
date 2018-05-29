<?php

require_once('core/init.php');

$user = new User();
if(!$user->isLoggedIn() || !$user->hasPermission('root')) {
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
} else if(($topic_id = Input::get('tid')) && !($post_id = Input::get('id'))) {
	$topic = new Topic($topic_id);
	$topic_data = $topic->data();
} else {
	Redirect::to(404);
}

if(Input::exists('post', "submit_delete_post")) {
	try {
		$post->delete($post_data->post_id);

		$last_post = $topic->lastPost($topic_id);
		Session::flash('viewtopic', 'Pomyślnie usunięto posta!');
		Redirect::to('viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id);
	} catch(Exception $e) {
		die($e->getMessage());
	}
} else if(Input::exists('post', "submit_delete_topic")) {
	try {
		$post = new Post();
		$post->deleteAllFromTopic($topic_id);
		$topic->delete($topic_id);

		Session::flash('viewsection', 'Pomyślnie usunięto temat i wszystkie jego posty!');
		Redirect::to('viewsection.php?id='.$topic_data->topic_section);
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<?php
	if(($topic_id = Input::get('tid')) && ($post_id = Input::get('id'))) {
		echo '<title>Usuwanie posta</title>';
	} else if(($topic_id = Input::get('tid')) && !($post_id = Input::get('id'))) {
		echo '<title>Usuwanie tematu</title>';
	}
	require_once('head.inc');
	?>
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
if(Input::exists('post', "submit_edit")) {
	if(!$validation->passed()) {
		echo '<div class="info-box">';
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
		echo '</div>';
	}
}

if(($topic_id = Input::get('tid')) && ($post_id = Input::get('id'))) {
	echo '<main class="post-create wrapper">';
		echo '<form method="post" class="form">';
			echo '<h2 class="left-border-heading">Usuwanie posta z tematu: '.$topic_data->topic_name.'</h2>';
			echo '<div class="information">Kliknięcie przycisku poniżej spowoduje trwałe i nieodwracalne usunięcie wybranego posta. Czy na pewno chcesz go skasować?</div>';
			echo '<input type="submit" value="Usuń posta" name="submit_delete_post" class="form__button form__button--first">';
			echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
		echo '</form>';
	echo '</main>';

	echo '<section class="post-preview wrapper">';
		echo '<h2 class="left-border-heading">Podgląd</h2>';
		echo '<div class="post">';
			if($post_data->post_type != 1) {
				echo '<div class="post-left">';
					$author = new User($post_data->post_by);
					$author_data = $author->data();
					echo '<a class="post-left__author" href="profile.php?id='.$author_data->user_login.'">'.$author_data->user_login.'</a>';
					echo '<div class="post-left__avatar"><img src="'.imageType('style/img/avatars/'.$author_data->avatar).'"></div>';
					foreach ($user->showGroups($author_data->user_id) as $group_name => $group_color) {
						echo '<div class="post-left__rang" style="color: #'.$group_color.'">'.$group_name.'</div>';
					}
				echo '</div>';
			}
			if($post_data->post_type != 1) {
				echo '<div class="post-right">';
			} else {
				echo '<div class="post-center">';
			}

			echo '<div class="post-right__contents">'.$post_data->post_contents.'</div>';
			if($post_data->post_hide != '') {
				echo '<div class="post-right__contents post-right__contents--hide"><h2 class="post-right__contents--hide-header">Ukryta wiadomość:</h2>'.$post_data->post_hide.'</div>';
			}
			echo '</div>';
		echo '</div>';
	echo '</section>';
} else if(($topic_id = Input::get('tid')) && !($post_id = Input::get('id'))) {
	echo '<main class="post-create wrapper">';
		echo '<form method="post">';
			echo '<h2 class="left-border-heading">Usuwanie tematu: '.$topic_data->topic_name.'</h2>';
			echo '<div class="information">Kliknięcie przycisku poniżej spowoduje trwałe i nieodwracalne usunięcie wybranego tematu oraz postów w nim zawartych! Czy na pewno chcesz go skasować?</div>';
			echo '<input type="submit" value="Usuń temat" name="submit_delete_topic" class="post-create-button">';
			echo '<a href="javascript:history.go(-1)" class="post-create-button">Powrót</a>';
		echo '</form>';
	echo '</main>';
}
?>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
