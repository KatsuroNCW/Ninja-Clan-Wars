<?php

require_once('core/init.php');

$user = new User();
$user_data = $user->data();
$jutsu = new Jutsu();

if(Input::get('page')) {
	$page = Input::get('page');
} else {
	$page = 1;
}

if(Input::exists('get', 'submit_search')) {
	$jutsulist = $jutsu->getList($page, Input::get('jutsu_name'), Input::get('jutsu_classification'), Input::get('jutsu_nature'), Input::get('jutsu_rank'), Input::get('jutsu_fight_style'));
} elseif(Input::exists('get', 'reset_search')) {
	$jutsulist = $jutsu->getList($page);
	Redirect::to('jutsulist.php');
} else {
	$jutsulist = $jutsu->getList($page, Input::get('jutsu_name'), Input::get('jutsu_classification'), Input::get('jutsu_nature'), Input::get('jutsu_rank'), Input::get('jutsu_fight_style'));
}
$total_pages = $jutsu->getTotalPages();

$settings = json_decode(file_get_contents("json/globalSettings.json"), true);
$fight_styles = json_decode(file_get_contents("json/fightStyles.json"));
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Spis jutsu</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
require_once('header.inc.php');
require_once('mobile_menu.inc');
?>

<section class="jutsulist wrapper">
	<h2 class="big-heading">Spis jutsu</h2>
	<?php
	echo '<form method="get" class="form jutsulist__form">';
		echo '<div class="jutsulist__box">';
			echo '<h2 class="form__heading">Nazwa</h2>';
			echo '<input type="text" name="jutsu_name" class="form__input" value="'.Input::get('jutsu_name').'" placeholder="Nazwa techniki">';
		echo '</div>';

		echo '<div class="jutsulist__box">';
			echo '<h2 class="form__heading">Specjalizacja</h2>';
			echo '<select name="jutsu_classification" class="form__input form__input--select">';
				echo '<option value="%">Wszystkie</option>';
				foreach ($settings['jutsu_classification'] as $classification) {
					if(Input::get('jutsu_classification') === $classification) {
						echo '<option value="'.$classification.'" selected>'.$classification.'</option>';
					} else {
						echo '<option value="'.$classification.'">'.$classification.'</option>';
					}
				}
			echo '</select>';
		echo '</div>';

		echo '<div class="jutsulist__box">';
			echo '<h2 class="form__heading">Żywioł</h2>';
			echo '<select name="jutsu_nature" class="form__input form__input--select">';
				echo '<option value="%">Wszystkie</option>';
				foreach ($settings['jutsu_nature'] as $nature) {
					if($nature !== 'none') {
						if(Input::get('jutsu_nature') === $nature) {
							echo '<option value="'.$nature.'" selected>'.$nature.'</option>';
						} else {
							echo '<option value="'.$nature.'">'.$nature.'</option>';
						}
					}
				}
			echo '</select>';
		echo '</div>';

		echo '<div class="jutsulist__box">';
			echo '<h2 class="form__heading">Ranga</h2>';
			echo '<select name="jutsu_rank" class="form__input form__input--select">';
				echo '<option value="%">Wszystkie</option>';
				foreach ($settings['jutsu_rank'] as $rank) {
					if(Input::get('jutsu_rank') === $rank) {
						echo '<option value="'.$rank.'" selected>'.$rank.'</option>';
					} else {
						echo '<option value="'.$rank.'">'.$rank.'</option>';
					}
				}
			echo '</select>';
		echo '</div>';

		echo '<div class="jutsulist__box">';
			echo '<h2 class="form__heading">Styl walki</h2>';
			echo '<select name="jutsu_fight_style" class="form__input form__input--select">';
				echo '<option value="%">Wszystkie</option>';
				foreach ($fight_styles as $fight_style) {
					if(Input::get('jutsu_fight_style') === $fight_style->name) {
						echo '<option value="'.$fight_style->name.'" selected>'.$fight_style->name.'</option>';
					} else {
						echo '<option value="'.$fight_style->name.'">'.$fight_style->name.'</option>';
					}
				}
			echo '</select>';
		echo '</div>';

		echo '<div class="jutsulist__buttons">';
			echo '<input type="submit" value="Resetuj" name="reset_search" class="form__button form__button--bigger">';
			echo '<input type="submit" value="Szukaj" name="submit_search" class="form__button form__button--bigger">';
		echo '</div>';
	echo '</form>';

	echo '<div class="pagination">';
	if($total_pages != 0) {
		if($page != 1) {
			echo '<a class="pagination__link" href="?page=1&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'" title="Przejdź do pierwszej strony"><<</a>';
		}
		if($total_pages <= 5) {
			for($i = 1; $i <= $total_pages; $i++) {
				if($page == $i) {
					echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
				} else {
					echo '<a class="pagination__link" href="?page='.$i.'&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'"> '.$i.' </a>';
				}
			}
		} else {
			for($i = $page-2; $i < $page; $i++) {
				if($i > 0) {
					echo '<a class="pagination__link" href="?page='.$i.'&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'"> '.$i.' </a>';
				}
			}
			echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
			for($i = $page+1; $i < $page+3; $i++) {
				if($i <= $total_pages) {
					echo '<a class="pagination__link" href="?page='.$i.'&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'"> '.$i.' </a>';
				}
			}
		}
		if($page != $total_pages) {
			echo '<a class="pagination__link" href="?page='.$total_pages.'&name='.Input::get('jutsu_name').'&class='.Input::get('jutsu_classification').'&nature='.Input::get('jutsu_nature').'&rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'" title="Przejdź do ostatniej strony">>></a>';
		}
	}
	echo '</div>';

	echo '<div class="jutsulist__container">';
		if(!empty($jutsulist)) {
			foreach ($jutsulist as $single_jutsu) {
				echo '<a href="jutsu.php?id='.$single_jutsu->jutsu_id.'" class="kp-jutsu__item">';
					echo '<img src="'.$jutsu->getJutsuImgUrl($single_jutsu->jutsu_name_romaji, 'main').'">';
					echo '<h2 class="kp-jutsu__title">['.$single_jutsu->jutsu_rank.'] '.$single_jutsu->jutsu_name_romaji.'</h2>';
				echo '</a>';
			}
		} else {
			echo '<div class="info-box__item info-box__item--error">';
				echo '<i class="info-box__icon icon-check"></i>';
				echo '<p class="info-box__description">Brak jutsu odpowiadających wybranym parametrom.</p>';
			echo '</div>';
		}
	echo '</div>';

	echo '<div class="pagination">';
	if($total_pages != 0) {
		if($page != 1) {
			echo '<a class="pagination__link" href="?page=1&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'" title="Przejdź do pierwszej strony"><<</a>';
		}
		if($total_pages <= 5) {
			for($i = 1; $i <= $total_pages; $i++) {
				if($page == $i) {
					echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
				} else {
					echo '<a class="pagination__link" href="?page='.$i.'&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'"> '.$i.' </a>';
				}
			}
		} else {
			for($i = $page-2; $i < $page; $i++) {
				if($i > 0) {
					echo '<a class="pagination__link" href="?page='.$i.'&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'"> '.$i.' </a>';
				}
			}
			echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
			for($i = $page+1; $i < $page+3; $i++) {
				if($i <= $total_pages) {
					echo '<a class="pagination__link" href="?page='.$i.'&jutsu_name='.Input::get('jutsu_name').'&jutsu_classification='.Input::get('jutsu_classification').'&jutsu_nature='.Input::get('jutsu_nature').'&jutsu_rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'"> '.$i.' </a>';
				}
			}
		}
		if($page != $total_pages) {
			echo '<a class="pagination__link" href="?page='.$total_pages.'&name='.Input::get('jutsu_name').'&class='.Input::get('jutsu_classification').'&nature='.Input::get('jutsu_nature').'&rank='.Input::get('jutsu_rank').'&jutsu_fight_style='.Input::get('jutsu_fight_style').'" title="Przejdź do ostatniej strony">>></a>';
		}
	}
	echo '</div>';
	?>
</section>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
