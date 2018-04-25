<?php

require_once('core/init.php');

if($topic_id = Input::get('id')) {
	$topic = new Topic($topic_id);
	if(!$topic->exists(Input::get('id'))) {
		Redirect::to(404);
	}
	$topic_data = $topic->data();
} else if($post_id = Input::get('pid')) {
	$post_chosen = new Post();
	if(!$topic_data = $post_chosen->showTopicInfo($post_id, 'post')) {
		Redirect::to(404);
	}
} else {
	Redirect::to(404);
}

$user = new User();
$user_data = $user->data();
if($user->isLoggedIn()) {
	$disp_posts = $user_data->disp_posts;
} else {
	$disp_posts = 10;
}

$posts = new Post();
if(Input::get('page')) {
	$page = Input::get('page');
} else if(Input::get('pid')) {
	$page = $posts->findPage(Input::get('pid'), $disp_posts);
} else {
	$page = 1;
}

if($posts->showFromTopic($topic_data->topic_id, $page, $disp_posts)) {
	$pagination = true;
}
$total_pages = $posts->totalPages();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title><?php echo $topic_data->topic_name ?></title>
	<?php require_once('head.inc'); ?>

	<link rel="stylesheet" type="text/css" href="style/forum.css">
	<link rel="stylesheet" type="text/css" href="style/viewtopic.css">
</head>
<body>

<?php require_once('header.inc.php'); ?>

<nav class="mobile-nav">
	<ul class="mobile-nav__menu">
		<li id="mobile-nav__land-switcher" class="mobile-nav__item"><a href="maps.php" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-globe"></i>Mapy</a></li>
		<li id="mobile-nav__land-switcher" class="mobile-nav__item"><a href="forum.php" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-home"></i>Główna</a></li>
		<li id="mobile-nav__menu" class="mobile-nav__item"><a href="javascript: void(0)" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-menu"></i>Menu</a></li>
	</ul>
</nav>

<div class="page-up" title="Przewiń do góry"><i class="icon-up-open"></i></div>

<?php
echo '<div class="info-box">';
if(Session::exists('viewtopic')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('viewtopic').'</div>';
}
echo '</div>';
?>

<section class="viewtopic wrapper">
	<h2 class="big-heading"><?php echo $topic_data->topic_name; ?></h2>

	<div class="viewtopic-info">
		<div class="pagination">
			<?php
				if($total_pages != 0) {
					if($page != 1) {
						echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page=1" title="Przejdź do pierwszej strony"><<</a>';
					}
					if($total_pages <= 5) {
						for($i = 1; $i <= $total_pages; $i++) {
							if($page == $i) {
								echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
							} else {
								echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
					} else {
						for($i = $page-2; $i < $page; $i++) {
							if($i > 0) {
								echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
						echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
						for($i = $page+1; $i < $page+3; $i++) {
							if($i <= $total_pages) {
								echo '<a class="pagination__link" href="?id='.$id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
					}
					if($page != $total_pages) {
						echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page='.$total_pages.'" title="Przejdź do ostatniej strony">>></a>';
					}
				}
			?>
		</div>

		<div class="viewtopic-info__buttons">
			<?php
			if($topic_data->topic_closed == 1) {
				echo '<a class="button button--closed" href="#">Zamknięty</a>';
			}
			if($user->isLoggedIn() || $user->hasPermission('root')) {
				echo '<a class="button" href="post.php?id='.$topic_data->topic_id.'">Odpowiedz</a>';
			}
			
			$section = new Section($topic_data->topic_section);
			?>
			<a class="button button--last-in-row" href="viewsection.php?id=<?php echo $topic_data->topic_section ?>">Powrót do <?php echo $section->data()->section_name ?></a>
		</div>
	</div>

	<div class="viewtopic__main">
	<?php
		if(isset($pagination)) {
			$first_post = true;
			foreach($posts->data() as $post) {
				echo '<div id="p'.$post->post_id.'" class="post">';
					if($post->post_type == 0) {
						echo '<div class="post-left">';
							echo '<a class="post-left__author" href="profile.php?id='.$post->post_by.'">';
								if($user->isOnline($post->post_by)) {
									echo '<span class="user-status user-status--online"></span>';
								} else {
									echo '<span class="user-status user-status--offline"></span>';
								}
							echo $post->post_by.'</a>';
							$post_author = new User($post->post_by_id);
							echo '<div class="post-left__avatar"><img src="'.imageType('style/img/avatars/'.$post_author->data()->avatar).'"></div>';
							foreach ($user->showGroups($post->post_by_id) as $group_name => $group_color) {
								echo '<div class="post-left__rang" style="color: #'.$group_color.'"><span></span>'.$group_name.'</div>';
							}
						echo '</div>';
					}

					if($post->post_type == 0) {
						echo '<div class="post-right">';
					} else {
						echo '<div class="post-center">';
					}
						echo '<a class="post-right__info" href="viewtopic.php?pid='.$post->post_id.'#p'.$post->post_id.'">dodano: '.dateFormat($post->post_date).'</a>';
						if($first_post && file_exists(imageType('style/img/topic/'.$topic_data->topic_id))) {
							echo '<div class="post-right__img"><img src="'.imageType('style/img/topic/'.$topic_data->topic_id).'"></div>';
							$first_post = false;
						}
						echo '<div class="post-right__contents">'.$post->post_contents;
						if($post->post_edited != NULL) {
							echo '<p class="post-right__contents-edited"><i class="icon-pencil post-right__contents-edited-icon"></i>Ostatnio edytowano przez '.$post->post_edited_by.' ('.dateFormat($post->post_edited).')</p>';
						}
						echo '</div>';
						if($user->isLoggedIn()) {
							if($post->post_hide != '' && ($user->hasPermission('root') || $post->post_by_id === $user_data->user_id)) {
								echo '<div class="post-right__contents post-right__contents--hide"><h2 class="post-right__contents--hide-header">Ukryta wiadomość:</h2>'.$post->post_hide.'</div>';
							}
						}
						echo '<div class="post-right__options">';
						if($user->isLoggedIn()) {
							if($user->hasPermission('root')) {
								echo '<a class="post-right__options-link" href="delete.php?tid='.$topic_data->topic_id.'&id='.$post->post_id.'"><i class="icon-trash-empty"></i> Usuń</a>';
							}
							if($user->hasPermission('root') || $post->post_by_id === $user_data->user_id) {
								echo '<a class="post-right__options-link" href="edit.php?tid='.$topic_data->topic_id.'&id='.$post->post_id.'"><i class="icon-pencil"></i> Edytuj</a>';
							}
							echo '<a class="post-right__options-link" href="post.php?id='.$topic_data->topic_id.'&qid='.$post->post_id.'"><i class="icon-quote"></i> Cytuj</a>';
						}
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		}
	?>
	</div>

	<div class="viewtopic-info">
		<div class="pagination">
			<?php
				if($total_pages != 0) {
					if($page != 1) {
						echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page=1" title="Przejdź do pierwszej strony"><<</a>';
					}
					if($total_pages <= 5) {
						for($i = 1; $i <= $total_pages; $i++) {
							if($page == $i) {
								echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
							} else {
								echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
					} else {
						for($i = $page-2; $i < $page; $i++) {
							if($i > 0) {
								echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
						echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
						for($i = $page+1; $i < $page+3; $i++) {
							if($i <= $total_pages) {
								echo '<a class="pagination__link" href="?id='.$id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
					}
					if($page != $total_pages) {
						echo '<a class="pagination__link" href="?id='.$topic_data->topic_id.'&page='.$total_pages.'" title="Przejdź do ostatniej strony">>></a>';
					}
				}
			?>
		</div>

		<div class="viewtopic-info__buttons">
			<?php
			if($topic_data->topic_closed == 1) {
				echo '<a class="button button--closed" href="#">Zamknięty</a>';
			}
			if($user->isLoggedIn() || $user->hasPermission('root')) {
				echo '<a class="button" href="post.php?id='.$topic_data->topic_id.'">Odpowiedz</a>';
			}
			$section = new Section($topic_data->topic_section);
			?>
			<a class="button button--last-in-row" href="viewsection.php?id=<?php echo $topic_data->topic_section ?>">Powrót do <?php echo $section->data()->section_name ?></a>
		</div>
	</div>
</section>

<?php
if($user->isLoggedIn()) {
	echo '<section class="quick-reply wrapper">';
		echo '<h2 class="big-heading">Szybka odpowiedź</h2>';
		echo '<form method="post" action="post.php?id='.$topic_data->topic_id.'">';
			include_once('bbcode-panel.php');
			echo '<textarea id="bbcode-menu" class="form__input form__input--textarea" name="post_contents" placeholder="Treść posta"></textarea>';
			echo '<input type="submit" value="Wyślij posta" name="submit_post" class="form__button form__button--first">';
			echo '<input type="submit" value="Podgląd" name="submit_preview" class="form__button">';
			echo '<input type="submit" value="Zapisz szkic" name="submit_save_draft" class="form__button">';
			if($user_data->user_draft != null || $user_data->user_draft != '') {
				echo '<input type="submit" value="Wczytaj szkic" name="submit_load_draft" class="form__button">';
				echo '<input type="submit" value="Usuń zapisany szkic" name="submit_delete_draft" class="form__button">';
			}
			echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
		echo '</form>';
	echo '</section>';
}
?>

<?php @require_once('footer_main.inc') ?>

</body>
</html>