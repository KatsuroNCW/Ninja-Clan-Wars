<?php

require_once('core/init.php');

if(!$section_id = Input::get('id')) {
	Redirect::to(404);
} else {
	$section = new Section($section_id);
	if(!$section->exists(Input::get('id'))) {
		Redirect::to(404);
	}
	$section_data = $section->data();
}

$user = new User();
$user_data = $user->data();

if(Input::get('page')) {
	$page = Input::get('page');
} else {
	$page = 1;
}
if($user->isLoggedIn()) {
	$disp_topics = $user_data->disp_topics;
} else {
	$disp_topics = 10;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title><?php echo $section_data->section_name ?></title>
	<?php require_once('head.inc'); ?>
	
	<link rel="stylesheet" type="text/css" href="style/forum.css">
	<link rel="stylesheet" type="text/css" href="style/viewsection.css">
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
	$topics = new Topic();
	if($topics->showFromSection($section_data->section_id, $page, $disp_topics)) {
		$pagination = true;
	}
	$total_pages = $topics->totalPages();

	echo '<div class="info-box">';
	if(Session::exists('viewsection')) {
		echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('viewsection').'</div>';
	}
	echo '</div>';
?>

<main class="viewsection wrapper">
	<div class="viewsection-header">
		<?php
		echo '<div class="big-heading">'.$section_data->section_name.'</div>';
		?>
	</div>

<?php
	$sections = new Section();
	$sections->showFromCategory($section_data->section_cat);
	foreach ($sections->data() as $section) {
		if($section->section_subsection == $section_id) {
			if($sections->lastPost($section->section_id)) {
				$last_post = $sections->lastPost($section->section_id);
				$posts = new Post();
				if($posts->showTopicInfo($last_post->post_topic)) {
					$last_post_topic = $posts->showTopicInfo($last_post->post_topic);
				}
			} else {
				$last_post = false;
			}

			if($section->section_type === 'forum') {
				echo '<div class="section section-forum">';
					echo '<a href="viewsection.php?id='.$section->section_id.'" class="section-forum__link" style="background: url('.imageType('style/img/section_img/'.$section->section_id).')">'.$section->section_name.'</a>';
					if($last_post != false) {
						echo '<a href="viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id.'" class="section-forum__last-post"><span class="last-post-link__text">'.$last_post_topic->topic_name.'<br>'.dateFormat($last_post->post_date).' przez '.$last_post->post_by.'</span></a>';
					} else {
						echo '<div class="section-forum__last-post--none">brak postów w tym dziale</div>';
					}
				echo '</div>';
			} else if($section->section_type === 'land') {
				echo '<div class="section section-land">';
					echo '<div class="section-land__left" style="background: url('.imageType('style/img/land_img/'.$section->section_id).');"><a href="viewsection.php?id='.$section->section_id.'" class="section-land__name"><span>'.$section->section_name.'</span></a></div>';
					echo '<div class="section-land__center"><div class="section-land__description">'.$section->section_description.'</div><a href="viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id.'" class="section-land__last-post">'.$last_post_topic->topic_name.'<br>'.dateFormat($last_post->post_date).' przez '.$last_post->post_by.'</a></div>';
					echo '<div class="section-land__right">';
						foreach ($sections->showTopics($section->section_id) as $topic) {
							echo '<a href="viewtopic.php?id='.$topic->topic_id.'" class="section-land__links">'.$topic->topic_name.'</a>';
						}
					echo '</div>';
				echo '</div>';
			}
		}
	}
?>

	<div class="viewsection-info wrapper">
		<div class="pagination">
			<?php
				if($total_pages != 0) {
					if($page != 1) {
						echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page=1" title="Przejdź do pierwszej strony"><<</a>';
					}
					if($total_pages <= 5) {
						for($i = 1; $i <= $total_pages; $i++) {
							if($page == $i) {
								echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
							} else {
								echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
					} else {
						for($i = $page-2; $i < $page; $i++) {
							if($i > 0) {
								echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page='.$i.'"> '.$i.' </a>';
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
						echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page='.$total_pages.'" title="Przejdź do ostatniej strony">>></a>';
					}
				}
			?>
		</div>

		<div class="viewsection-info__buttons">
			<?php
			if($user->isLoggedIn()) {
				echo '<a class="viewsection-link" href="topic.php?id='.$section_data->section_id.'">Nowy temat</a>';
			}
			if($section_data->section_subsection == 0) {
				echo '<a class="viewsection-link" href="forum.php">Powrót do forum</a>';
			} else {
				$subsection_info = new Section($section_data->section_subsection);
				echo '<a class="viewsection-link" href="viewsection.php?id='.$section_data->section_subsection.'">Powrót do '.$subsection_info->data()->section_name.'</a>';
			}
			?>
		</div>
	</div>
	
	<div class="viewsection-main">
	<?php
		if(isset($pagination)) {
			echo '<div class="topic">';
				echo '<div class="topic__block topic__block--title topic__name">Temat</div>';
				echo '<div class="topic__block topic__block--title topic__last-post">Ostatni post</div>';
				echo '<div class="topic__block topic__block--title topic__replies">Odpowiedzi</div>';
				if($user->hasPermission('root')) {
					echo '<div class="topic__block topic__block--title topic__options"></div>';
				}
			echo '</div>';
			foreach ($topics->data() as $topic) {
				$last_post = $topics->lastPost($topic->topic_id);
				echo '<div class="topic">';
					if($topic->topic_sticky == 1) {
						echo '<div class="topic__block topic__block--sticky topic__name">
						<a href="viewtopic.php?id='.$topic->topic_id.'" class="topic__link">
						<div class="topic__icon topic__icon--sticky"></div>
						<div class="topic-name">Przyklejony: '.$topic->topic_name.'<span class="link-author">przez '.$topic->topic_by.'</span>
						</div>
						</a>
						</div>';
						echo '<div class="topic__block topic__block--sticky topic__last-post">
						<a href="viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id.'" class="topic__link">
						<div>'.dateFormat($last_post->post_date).'<span class="link-author">przez '.$last_post->post_by.'</span></div>
						</a>
						</div>';
						echo '<div class="topic__block topic__block--sticky topic__replies">'.$topics->postCounter($topic->topic_id).'</div>';
						if($user->hasPermission('root')) {
							echo '<div class="topic__block topic__block--sticky topic__options"><li class="topic-nav"><i class="mobile-nav__menu--icon icon-menu"></i>';
								echo '<ul class="topic-menu">';
									if($topic->topic_closed == 0) {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&close=yes">Zamknij</a></li>';
									} else {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&close=no">Otwórz</a></li>';
									}
									if($topic->topic_sticky == 0) {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&sticky=yes">Przypnij</a></li>';
									} else {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&sticky=no">Odepnij</a></li>';
									}
									echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="">Zmień nazwę</a></li>';
									echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="delete.php?tid='.$topic->topic_id.'">Usuń</a></li>';
									echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="">Przenieś</a></li>';
								echo '</ul></li>';
							echo '</div>';
						}
					} else {
						echo '<div class="topic__block topic__name">
						<a href="viewtopic.php?id='.$topic->topic_id.'" class="topic__link">
						<div class="topic__icon"></div>
						<div class="topic-name">'.$topic->topic_name.'<span class="link-author">przez '.$topic->topic_by.'</span></div>
						</a>
						</div>';
						echo '<div class="topic__block topic__last-post">
						<a href="viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id.'" class="topic__link">
						<div>'.dateFormat($last_post->post_date).'<span class="link-author">przez '.$last_post->post_by.'</span></div>
						</a>
						</div>';
						echo '<div class="topic__block topic__replies">'.$topics->postCounter($topic->topic_id).'</div>';
						if($user->hasPermission('root')) {
							echo '<ul class="topic__block topic__options"><li class="topic-nav"><i class="mobile-nav__menu--icon icon-menu"></i>';
								echo '<ul class="topic-menu">';
									if($topic->topic_closed == 0) {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&close=yes">Zamknij</a></li>';
									} else {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&close=no">Otwórz</a></li>';
									}
									if($topic->topic_sticky == 0) {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&sticky=yes">Przypnij</a></li>';
									} else {
										echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&sticky=no">Odepnij</a></li>';
									}
									echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&edit_topic=yes">Edytuj temat</a></li>';
									echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="delete.php?tid='.$topic->topic_id.'">Usuń</a></li>';
									echo '<li class="topic-menu__item"><a class="topic-menu__item--link" href="edit.php?tid='.$topic->topic_id.'&move=yes">Przenieś</a></li>';
								echo '</ul></li>';
							echo '</ul>';
						}
					}
				echo '</div>';
			}
		} else {
			echo '<div class="topic--empty-section">Ten dział jest pusty!</div>';
		}
	?>
	</div>

	<div class="viewsection-info">
		<div class="pagination">
			<?php
				if($total_pages != 0) {
					if($page != 1) {
						echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page=1" title="Przejdź do pierwszej strony"><<</a>';
					}
					if($total_pages <= 5) {
						for($i = 1; $i <= $total_pages; $i++) {
							if($page == $i) {
								echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
							} else {
								echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page='.$i.'"> '.$i.' </a>';
							}
						}
					} else {
						for($i = $page-2; $i < $page; $i++) {
							if($i > 0) {
								echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page='.$i.'"> '.$i.' </a>';
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
						echo '<a class="pagination__link" href="?id='.$section_data->section_id.'&page='.$total_pages.'" title="Przejdź do ostatniej strony">>></a>';
					}
				}
			?>
		</div>

		<div class="viewsection-info__buttons">
			<?php
			if($user->isLoggedIn()) {
				echo '<a class="viewsection-link" href="topic.php?id='.$section_data->section_id.'">Nowy temat</a>';
			}
			?>
			<a class="viewsection-link" href="forum.php">Powrót do forum</a>
		</div>
	</div>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>