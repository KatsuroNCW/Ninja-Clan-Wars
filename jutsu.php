<?php

require_once('core/init.php');

$user = new User();
$user_data = $user->data();

// zostało podane id:
//    jutsu o podanym id istnieje:
//      wyświetlam jutsu
//
//    jutsu o podanym id nie istnieje:
//      404

// nie zostało podane id:
//    404

if($jutsu_id = Input::get('id')) {
  $jutsu = new Jutsu($jutsu_id);
  if(!$jutsu->exists()) {
    Redirect::to(404);
  } else {
    $title = '['.$jutsu->getValue('jutsu_rank').'] '.$jutsu->getValue('jutsu_name_romaji');
    if(!empty($jutsu->getValue('jutsu_nature'))) {
      $title .= ' ['.$jutsu->getValue('jutsu_nature').'] ';
    }
  }
} else {
  Redirect::to(404);
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<title><?php echo $title ?></title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
require_once('header.inc.php');
require_once('mobile_menu.inc');

if(Session::exists('jutsu')) {
	echo '<div class="info-box">';
		echo '<div class="info-box__item info-box__item--confirmation">';
			echo '<i class="info-box__icon icon-check"></i>';
			echo '<p class="info-box__description">'.Session::flash('jutsu').'</p>';
		echo '</div>';
	echo '</div>';
}
?>

<section class="jutsu wrapper">
  <?php
  echo '<h2 class="big-heading">'.$jutsu->getValue('jutsu_name_romaji').' ('.$jutsu->getValue('jutsu_name_kanji').')</h2>';
  echo '<div class="jutsu__container">';
    echo '<div class="jutsu__left">';
      echo '<div class="jutsu__img-box">';
        echo '<img src="'.$jutsu->getJutsuImgUrl($jutsu->getValue('jutsu_name_romaji'), '1').'">';
        echo '<img src="'.$jutsu->getJutsuImgUrl($jutsu->getValue('jutsu_name_romaji'), '2').'">';
        echo '<img src="'.$jutsu->getJutsuImgUrl($jutsu->getValue('jutsu_name_romaji'), '3').'">';
        echo '<img src="'.$jutsu->getJutsuImgUrl($jutsu->getValue('jutsu_name_romaji'), '4').'">';
      echo '</div>';

      echo '<h3 class="jutsu__heading">Opis</h3>';
      echo '<div class="jutsu__description">'.$jutsu->getValue('jutsu_description').'</div>';

      echo '<h3 class="jutsu__heading">Dodatkowe informacje</h3>';
      echo '<div class="jutsu__more">'.$jutsu->getValue('jutsu_more').'</div>';

      echo '<div class="jutsu__date">';
        echo 'Technikę dodano '.dateFormat($jutsu->getValue('jutsu_date')).'.';
        if(!empty($jutsu->getValue('jutsu_update'))) {
          echo ' Ostatnia aktualizacja '.dateFormat($jutsu->getValue('jutsu_update'));
        }
      echo '</div>';
    echo '</div>';

    echo '<div class="jutsu__right">';
      echo '<h3 class="jutsu__heading jutsu__heading--title">'.$jutsu->getValue('jutsu_name_romaji').'</h3>';
      echo '<div class="jutsu__img-big"><img src="'.$jutsu->getJutsuImgUrl($jutsu->getValue('jutsu_name_romaji'), 'main').'"></div>';

      echo '<div class="jutsu__info">';
        echo '<p>Klasyfikacja</p>';
        echo '<p>'.$jutsu->getValue('jutsu_classification').'</p>';
      echo '</div>';

      if(!empty($jutsu->getValue('jutsu_nature'))) {
        echo '<div class="jutsu__info">';
          echo '<p>Żywioł</p>';
          echo '<p><img src="style/img/nature_types/'.$jutsu->getValue('jutsu_nature').'.png">'.$jutsu->getValue('jutsu_nature').'</p>';
        echo '</div>';
      }

      echo '<div class="jutsu__info">';
        echo '<p>Ranga</p>';
        echo '<p>'.$jutsu->getValue('jutsu_rank').'</p>';
      echo '</div>';

      echo '<div class="jutsu__info">';
        echo '<p>Typ</p>';
        echo '<p>'.$jutsu->getValue('jutsu_class').'</p>';
      echo '</div>';

      if(!empty($jutsu->getValue('jutsu_fight_style'))) {
        echo '<div class="jutsu__info">';
          echo '<p>Styl walki</p>';
          echo '<p>'.$jutsu->getValue('jutsu_fight_style').'</p>';
        echo '</div>';
      }

      echo '<div class="jutsu__info">';
        echo '<p>Zasięg</p>';
        echo '<p>'.$jutsu->getValue('jutsu_range').'</p>';
      echo '</div>';

      echo '<div class="jutsu__info">';
        echo '<p>Koszt</p>';
        echo '<p>'.$jutsu->getValue('jutsu_chakra').'</p>';
      echo '</div>';

      echo '<div class="jutsu__info">';
        echo '<p>Pieczęcie</p>';
        echo '<p>'.$jutsu->getValue('jutsu_seals').'</p>';
      echo '</div>';

    echo '</div>';
  echo '</div>';
  echo '<a href="jutsulist.php" class="button button--first-in-row">Lista jutsu</a>';
  echo '<a href="javascript:history.go(-1)" class="button button--first-in-row">Powrót</a>';
  ?>
</section>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
