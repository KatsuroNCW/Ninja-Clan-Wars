<?php
require_once('core/init.php');

// TODO: dopisac, żeby stworzyc kp mozna było tylko z uprawnieniami gracza

// 1. użytkownik niezalogowany i podane id kp
	// jeśli kp istnieje - wyświetla podstawowe informacje o kp
	// jeśli kp nie istnieje - 404
// 2. użytkownik niezalogowany i nie podane id kp
	// 404
// 3. użytkownik zalogowany i podane id kp
	// kp istnieje - wyświetla kp
		// jeśli to jego kp - wyświetla wszystko
		// jeśli to nie jego kp - wyświetla podstawowe informacje o kp
	// kp nie istnieje - 404
// 4. użytkownik zalogowany i nie podane id kp
	// użytkownik nie ma własnej kp - wyświetla form do stworzenia
	// użytkownik ma własną kp
		// jeśli ma jedną kp - przekierowanie do istnieje
		// jeśli ma ich więcej - wyświetla listę

$user = new User();
$kp_owner = false;
$user_has_kp = false;
$create_kp = false;
if(!$user->isLoggedIn()) {
	if($kp_id = Input::get('kp_id')) {
		$kp = new KP($kp_id);
		if(!$kp->isCreated()) {
			Redirect::to(404);
		}
	} else {
		Redirect::to(404);
	}
} else {
	$user_data = $user->data();
	if($kp_id = Input::get('kp_id')) {
		$kp = new KP($kp_id);
		if(!$kp->isCreated()) {
			Redirect::to(404);
		} else {
			if(intval($kp->getValue('kp_user_id')) === intval($user_data->user_id)) {
				$kp_owner = true;
				$user_has_kp = true;
			}
		}
	} else {
		var_dump($user->hasPermission('player'));
		if($user->HasKp($user_data->user_id) && $user->hasPermission('player')) {
			if(sizeof($user->getKpId()) === 1) {
				Redirect::to('kp.php?kp_id='.$user->getKpId()[0]);
			}
		}
	}
	if($user->hasPermission('root')) {
		$kp_owner = true;
	}
}

// wartości nagrody za stworzenie karty postaci
$reward_pu = 100;
$reward_pw = 50;
$reward_ryo = 10000;

// przekierowanie użytkowników, których wybrana KP nie jest własnością
if(!$kp_owner && !(Input::get('section') === 'informacje_ogolne' || Input::get('section') === '')) {
	Redirect::to('kp.php?kp_id='.$kp->getValue('kp_id'));
}

// edycja informacji ogólnych
if(Input::exists('post', 'submit_edit_info')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'kp_gender' => array(
			'name' => 'płeć',
			'required' => true
		),
		'kp_birth_year' => array(
			'name' => 'rok urodzenia',
			'required' => true,
			'numbers_only' => true
		),
		'kp_height' => array(
			'name' => 'wzrost',
			'required' => true,
			'numbers_only' => true
		),
		'kp_weight' => array(
			'name' => 'waga',
			'required' => true,
			'numbers_only' => true
		),
		'kp_look' => array(
			'name' => 'wygląd',
			'required' => true
		),
		'kp_outfit' => array(
			'name' => 'ubranie',
			'required' => true
		),
		'kp_nindo' => array(
			'name' => 'płeć',
			'required' => true
		),
		'kp_goals' => array(
			'name' => 'cele',
			'required' => true
		),
		'kp_prolog' => array(
			'name' => 'prolog',
			'required' => true
		)
	));

	if($validation->passed()) {
		try {
			$kp->update($kp->getValue('kp_id'), array(
				'kp_gender' => Input::get('kp_gender'),
				'kp_birth_year' => Input::get('kp_birth_year'),
				'kp_height' => Input::get('kp_height'),
				'kp_weight' => Input::get('kp_weight'),
				'kp_look' => BbcodeParser::get(Input::get('kp_look')),
				'kp_outfit' => BbcodeParser::get(Input::get('kp_outfit')),
				'kp_nindo' => BbcodeParser::get(Input::get('kp_nindo')),
				'kp_goals' => BbcodeParser::get(Input::get('kp_goals')),
				'kp_prolog' => BbcodeParser::get(Input::get('kp_prolog'))
			));

			Session::flash('kp', 'Pomyślnie zaktualizowano podstawowe informacje w karcie postaci!');
			Redirect::to('kp.php?kp_id='.$kp->getValue('kp_id'));
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

// tworzenie kp
if(Input::exists('post', 'submit_kp')) {
	$date = date('Y-m-d h:i:s');
	$organizations = '["'.Input::get('kp_organizations').'"]';
	$statistic = '[{"type": "basic","description": "podstawowa wartość","amount": 1}]';
	$kp_summary_pu = '[{"operation": "+","amount": '.$reward_pu.',"description": "stworzenie karty postaci","author": "System","date": "'.$date.'"}]';
	$kp_summary_pw = '[{"operation": "+","amount": '.$reward_pw.',"description": "stworzenie karty postaci","author": "System","date": "'.$date.'"}]';
	$kp_summary_ryo = '[{"operation": "+","amount": '.$reward_ryo.',"description": "stworzenie karty postaci","author": "System","date": "'.$date.'"}]';

	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'kp_gender' => array(
			'name' => 'płeć',
			'required' => true
		),
		'kp_birth_year' => array(
			'name' => 'rok urodzenia',
			'required' => true,
			'numbers_only' => true
		),
		'kp_height' => array(
			'name' => 'wzrost',
			'required' => true,
			'numbers_only' => true
		),
		'kp_weight' => array(
			'name' => 'waga',
			'required' => true,
			'numbers_only' => true
		),
		'kp_look' => array(
			'name' => 'wygląd',
			'required' => true
		),
		'kp_outfit' => array(
			'name' => 'ubranie',
			'required' => true
		),
		'kp_nindo' => array(
			'name' => 'płeć',
			'required' => true
		),
		'kp_goals' => array(
			'name' => 'cele',
			'required' => true
		),
		'kp_prolog' => array(
			'name' => 'prolog',
			'required' => true
		)
	));

	if($validation->passed()) {
		try {
			$new_kp = new KP();
			$new_kp->create(array(
				'kp_user_id' => $user_data->user_id,
				'kp_status' => 'new',
				'kp_gender' => Input::get('kp_gender'),
				'kp_name' => Input::get('kp_name'),
				'kp_surname' => Input::get('kp_surname'),
				'kp_clan' => Input::get('kp_clan'),
				'kp_organizations' => Input::get('kp_organizations'),
				'kp_birth_year' => Input::get('kp_birth_year'),
				'kp_height' => Input::get('kp_height'),
				'kp_weight' => Input::get('kp_weight'),
				'kp_look' => BbcodeParser::get(Input::get('kp_look')),
				'kp_outfit' => BbcodeParser::get(Input::get('kp_outfit')),
				'kp_nindo' => BbcodeParser::get(Input::get('kp_nindo')),
				'kp_goals' => BbcodeParser::get(Input::get('kp_goals')),
				'kp_prolog' => BbcodeParser::get(Input::get('kp_prolog')),
				'kp_pu' => $reward_pu,
				'kp_pw' => $reward_pw,
				'kp_ryo' => $reward_ryo,
				'kp_strength' => $statistic,
				'kp_concentration' => $statistic,
				'kp_endurance' => $statistic,
				'kp_speed' => $statistic,
				'kp_reactions' => $statistic,
				'kp_mind' => $statistic,
				'kp_will' => $statistic,
				'kp_chakra_control' => $statistic,
				'kp_summary_pu' => $kp_summary_pu,
				'kp_summary_pw' => $kp_summary_pw,
				'kp_summary_ryo' => $kp_summary_ryo
			));

			Session::flash('kp', 'Karta postaci została stworzona pomyślnie! Nie jest jednak aktywna, przez co nie możesz nabywać nowych zdolności. Poczekaj na akceptację Twojej KP przez administrację.');
			Redirect::to('kp.php');
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

// zmiana statusu karty postaci
if(Input::exists('post', 'submit_kp_status')) {
	try {
		$kp->update($kp->getValue('kp_id'), array(
			'kp_status' => Input::get('kp_status'),
			'kp_admin_message' => Input::get('kp_admin_message')
		));

		Session::flash('kp', 'Pomyślnie zmieniono status karty postaci!');
		Redirect::to('kp.php?kp_id='.$kp->getValue('kp_id'));
	} catch(Exception $e) {
		die($e->getMessage());
	}
}

// ustawienie tytułu sekcji kp
$section = Input::get('section');
switch ($section) {
	case 'informacje_ogolne':
		$section_title = 'Informacje ogólne';
		break;

	case 'edit_info':
		$section_title = 'Edycja informacji ogólnych';
		break;

	case 'statistics':
		$section_title = 'Statystyki';
		break;

	case 'jutsus':
		$section_title = 'Jutsu';
		break;

	case 'skills':
		$section_title = 'Umiejętności';
		break;

	case 'items':
		$section_title = 'Ekwipunek';
		break;

	case 'summary':
		$section_title = 'Podsumowanie';
		break;

	case 'notes':
		$section_title = 'Notatnik';
		break;

	case 'admin':
		$section_title = 'Panel administratora';
		break;

	default:
		$section_title = 'Informacje ogólne';
		break;
}

// ustawienie tytułu strony
if($user->isLoggedIn()) {
	if(!$user_has_kp) {
		$title = 'Tworzenie nowej karty postaci';
	} else {
		$title = $kp->getValue('kp_name').' - karta postaci';
	}
} else {
	$title = $kp->getValue('kp_name').' - karta postaci';
}

$settings = json_decode(file_get_contents("json/globalSettings.json"), true);

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

if(Session::exists('kp')) {
	echo '<div class="info-box">';
		echo '<div class="info-box__item info-box__item--confirmation">';
			echo '<i class="info-box__icon icon-check"></i>';
			echo '<p class="info-box__description">'.Session::flash('kp').'</p>';
		echo '</div>';
	echo '</div>';
}
?>

<section class="wrapper">
<?php
	if($kp_id = Input::get('kp_id')) {
		$user_kp = new User($kp->getValue('kp_user_id'));
		$user_kp_data = $user_kp->data();
		echo '<h2 class="big-heading">Karta postaci</h2>';
		if($user->isLoggedIn() && ($kp_owner || $user->hasPermission('root'))) {
			echo '<ul class="kp-nav">';
				echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=informacje_ogolne">Podstawowe informacje</a></li>';
				echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=statistics">Statystyki</a></li>';
				echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=jutsus">Jutsu</a></li>';
				echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=skills">Umiejętności</a></li>';
				echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=items">Ekwipunek</a></li>';
				echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=summary">Podsumowanie</a></li>';
				echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=notes">Notatnik</a></li>';
				if($user->hasPermission('root')) {
					echo '<li class="kp-nav__item"><a href="?kp_id='.$kp->getValue('kp_id').'&section=admin">Admin</a></li>';
				}
			echo '</ul>';
		}

		echo '<div class="kp">';
			echo '<div class="kp__left">';
				echo '<div class="kp__avatar">';
				if ($user->isOnline($kp->getValue('kp_name'))) {
					echo '<div class="kp__user-status kp__user-status--online"></div>';
				} else {
					echo '<div class="kp__user-status kp__user-status--offline"></div>';
				}
				echo '<img src="'.imageType('style/img/avatars/'.$user_kp_data->avatar).'">';
				echo '</div>';
				if(!empty($kp->getValue('kp_clan'))) {
					echo '<div class="kp__symbol"><img src="'.imageType('style/img/clan_symbols/'.strtolower($kp->getValue('kp_clan'))).'"></div>';
				}
				if(!empty($kp->getValue('kp_organizations'))) {
					foreach ($kp->getValue('kp_organizations') as $organization) {
						echo '<div class="kp__symbol"><img src="'.imageType('style/img/clan_symbols/'.$organization).'"></div>';
					}
				}
				if($kp_owner) {
					echo '<div class="kp__info"><p>PU</p><p>'.$kp->getValue('kp_pu').'</p></div>';
					echo '<div class="kp__info"><p>PW</p><p>'.$kp->getValue('kp_pw').'</p></div>';
					echo '<div class="kp__info"><p>RYO</p><p>'.$kp->getValue('kp_ryo').'</p></div>';
				}
			echo '</div>';
			echo '<div class="kp__right">';
				echo '<div class="kp__heading">';
					if(!empty($kp->getValue('kp_surname'))) {
						echo '<div class="kp__name">'.$kp->getValue('kp_name').' <span>'.$kp->getValue('kp_surname').'</span></div>';
					} elseif(!empty($kp->getValue('kp_clan'))) {
						echo '<div class="kp__name">'.$kp->getValue('kp_name').' <span>'.$kp->getValue('kp_clan').'</span></div>';
					} else {
						echo '<div class="kp__name">'.$kp->getValue('kp_name').'</div>';
					}
					echo '<div class="kp__title">'.$section_title.'</div>';
				echo '</div>';
				if($user->isLoggedIn()) {
					if($kp->getValue('kp_status') === 'inactive' && $kp_owner) {
						echo '<div class="kp__container">';
							echo '<div class="info-box__item info-box__item--error">';
								echo '<i class="info-box__icon icon-attention"></i>';
								echo '<p class="info-box__description">KP jest nieaktywna. Twoja postać fabularnie została uznana za zaginioną, a Ty, właścicielu, musisz zgłosić się <a href="#">tutaj</a>. Do czasu ponownej akceptacji nie ma możliwości rozwijania postaci.</p>';
							echo '</div>';
						echo '</div>';
					}

					if($kp->getValue('kp_status') === 'new' && $kp_owner) {
						echo '<div class="kp__container">';
							echo '<div class="info-box__item info-box__item--error">';
								echo '<i class="info-box__icon icon-attention"></i>';
								echo '<p class="info-box__description">Twoja KP jest nieaktywna, ponieważ dopiero została założona. Poczekaj na akceptację administracji lub ewentualne uwagi, które wyświetlą się tuż pod tym tekstem i zastosuj się do nich. Do czasu akceptacji nie możesz rozwijać swojej postaci.</p>';
							echo '</div>';
						echo '</div>';
					}

					if(!empty($kp->getValue('kp_admin_message')) && $kp_owner) {
						echo '<div class="kp__container">';
							echo '<div class="info-box__item info-box__item--error">';
								echo '<i class="info-box__icon icon-attention"></i>';
								echo '<p class="info-box__description">'.$kp->getValue('kp_admin_message').'</p>';
							echo '</div>';
						echo '</div>';
					}
				}
				if (Input::get('section') === 'informacje_ogolne' || Input::get('section') === '') {
					echo '<div class="kp__container">';
						if(!empty($kp->getValue('kp_organizations'))) {
							echo '<div class="kp__item"><p>Organizacja</p><p>';
								$temp = 1;
								foreach ($kp->getValue('kp_organizations') as $organization) {
									echo($temp > 1) ? ', ' : '';
									echo $organization;
									$temp++;
								}
							echo '</p></div>';
						}
						echo '<div class="kp__item"><p>Ranga</p><p>'.$kp->getValue('kp_rank').'</p></div>';
						echo '<div class="kp__item"><p>Płeć</p><p>'.$kp->getValue('kp_gender').'</p></div>';
						echo '<div class="kp__item"><p>Rok urodzenia</p><p>'.$kp->getValue('kp_birth_year').'</p></div>';
						echo '<div class="kp__item"><p>Wzrost</p><p>'.$kp->getValue('kp_height').'cm</p></div>';
						echo '<div class="kp__item"><p>Waga</p><p>'.$kp->getValue('kp_weight').'kg</p></div>';
						if ($kp->getValue('kp_status') === 'active') {
							echo '<div class="kp__item"><p>Status</p><p>aktywna</p></div>';
						} elseif ($kp->getValue('kp_status') === 'inactive' || $kp->getValue('kp_status') === 'new') {
							echo '<div class="kp__item"><p>Status</p><p>nieaktywna</p></div>';
						}
					echo '</div>';

					echo '<div class="kp__container kp-descriptions switcher">';
						echo '<ul class="kp-descriptions__nav">';
							echo '<li class="kp-descriptions__nav-item switcher-nav" data-item="1">Wygląd</li>';
							echo '<li class="kp-descriptions__nav-item switcher-nav" data-item="2">Nindo</li>';
							echo '<li class="kp-descriptions__nav-item switcher-nav" data-item="3">Cele</li>';
							echo '<li class="kp-descriptions__nav-item switcher-nav" data-item="4">Ubranie</li>';
						echo '</ul>';
						echo '<div class="kp-descriptions__box">';
							echo '<div class="kp-descriptions__item switcher-item" data-item="1">'.$kp->getValue('kp_look').'</div>';
							echo '<div class="kp-descriptions__item switcher-item" data-item="2">'.$kp->getValue('kp_outfit').'</div>';
							echo '<div class="kp-descriptions__item switcher-item" data-item="3">'.$kp->getValue('kp_nindo').'</div>';
							echo '<div class="kp-descriptions__item switcher-item" data-item="4">'.$kp->getValue('kp_goals').'</div>';
						echo '</div>';
					echo '</div>';

					echo '<div class="kp__container">';
						echo '<h2 class="kp__mini-heading">Historia postaci</h2>';
						echo '<div class="kp__prolog">'.$kp->getValue('kp_prolog').'</div>';
					echo '</div>';

					if($kp_owner) {
						echo '<div class="kp__container">';
							echo '<a href="?kp_id='.$kp->getValue('kp_id').'&section=edit_info" class="button">Edytuj informacje profilowe</a>';
						echo '</div>';
					}

					echo '<script defer src="js/infoSwitcher.js"></script>';
				} elseif (Input::get('section') === 'edit_info' && $kp_owner) {
					if(Input::exists('post', 'submit_edit_info')) {
						if(!$validation->passed()) {
							echo '<div class="info-box">';
							foreach($validation->errors() as $error) {
								echo '<div class="info-box__item info-box__item--error">';
									echo '<i class="info-box__icon icon-attention"></i>';
									echo '<p class="info-box__description">'.$error .'</p>';
								echo '</div>';
							}
							echo '</div>';
						}
					}
					echo '<div class="kp__container">';
						echo '<form method="post" class="form">';
							echo '<h2 class="form__heading">Płeć</h2>';
							echo '<select name="kp_gender" class="form__input form__input--select">';
							foreach ($settings['gender'] as $gender) {
								if($gender === $kp->getValue('kp_gender')) {
									echo '<option value="'.$gender.'" selected>'.$gender.'</option>';
								} else {
									echo '<option value="'.$gender.'">'.$gender.'</option>';
								}
							}
							echo '</select>';

							echo '<h2 class="form__heading">Rok urodzenia</h2>';
							echo '<input type="text" name="kp_birth_year" value="'.$kp->getValue('kp_birth_year').'" class="form__input">';

							echo '<h2 class="form__heading">Wzrost</h2>';
							echo '<input type="text" name="kp_height" value="'.$kp->getValue('kp_height').'" class="form__input">';

							echo '<h2 class="form__heading">Waga</h2>';
							echo '<input type="text" name="kp_weight" value="'.$kp->getValue('kp_weight').'" class="form__input">';

							echo '<h2 class="form__heading">Wygląd</h2>';
							echo '<div class="textarea-panel">';
								include('bbcode-panel.php');
								echo '<textarea name="kp_look" class="form__input form__input--textarea" placeholder="Wygląd">'.HtmlParser::get($kp->getValue('kp_look')).'</textarea>';
							echo '</div>';

							echo '<h2 class="form__heading">Ubranie</h2>';
							echo '<div class="textarea-panel">';
								include('bbcode-panel.php');
								echo '<textarea name="kp_outfit" class="form__input form__input--textarea">'.HtmlParser::get($kp->getValue('kp_outfit')).'</textarea>';
							echo '</div>';

							echo '<h2 class="form__heading">Nindo</h2>';
							echo '<div class="textarea-panel">';
								include('bbcode-panel.php');
								echo '<textarea name="kp_nindo" class="form__input form__input--textarea">'.HtmlParser::get($kp->getValue('kp_nindo')).'</textarea>';
							echo '</div>';

							echo '<h2 class="form__heading">Cele</h2>';
							echo '<div class="textarea-panel">';
								include('bbcode-panel.php');
								echo '<textarea name="kp_goals" class="form__input form__input--textarea">'.HtmlParser::get($kp->getValue('kp_goals')).'</textarea>';
							echo '</div>';

							echo '<h2 class="form__heading">Prolog</h2>';
							echo '<div class="textarea-panel">';
								include('bbcode-panel.php');
								echo '<textarea name="kp_prolog" placeholder="Jaka jest historia Twojej postaci? (wklej w tym miejscu prolog napisany i zatwierdzony przez administrację)" class="form__input form__input--textarea">'.HtmlParser::get($kp->getValue('kp_prolog')).'</textarea>';
							echo '</div>';

							echo '<input type="submit" name="submit_edit_info" value="Potwierdź edycję informacji o postaci" class="form__button form__button--first">';
							echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
						echo '</form>';
					echo '</div>';
				} elseif (Input::get('section') === 'statistics' && $kp_owner) {
					// TODO: statystyki gracza i panel zakupów (musi uwzględniac kupowanie pojedynczego skilla i kilku na raz)
				} elseif (Input::get('section') === 'jutsus' && $kp_owner) {
					// TODO: techniki
				} elseif (Input::get('section') === 'skills' && $kp_owner) {
					// TODO: umiejętności
				} elseif (Input::get('section') === 'items' && $kp_owner) {
					// TODO: przedmioty
				} elseif (Input::get('section') === 'summary' && $kp_owner) {
					// TODO: podsumowanie pu, pw i ryo
					$summary_pu = $kp->getValue('kp_summary_pu');
					$summary_pw = $kp->getValue('kp_summary_pw');
					$summary_ryo = $kp->getValue('kp_summary_ryo');

					echo '<div class="summary switcher">';
						echo '<ul class="summary__nav">';
							echo '<li class="summary__nav-item switcher-nav" data-item="1">PU</li>';
							echo '<li class="summary__nav-item switcher-nav" data-item="2">PW</li>';
							echo '<li class="summary__nav-item switcher-nav" data-item="3">RYO</li>';
						echo '</ul>';

						echo '<div class="summary__box switcher-item" data-item="1">';
							echo '<p class="summary__total">Aktualna wartość: <span>'.$kp->getValue('kp_pu').'</span></p>';
							foreach ($summary_pu as $pu_bilans) {
								if($pu_bilans->operation === '+') {
									echo '<p class="summary__item summary__item--plus">';
								} elseif ($pu_bilans->operation === '-') {
									echo '<p class="summary__item summary__item--minus">';
								} else {
									throw new Exception("Wystąpił problem z odczytam podsumowania!");
									echo '<p class="summary">';
								}
								echo '['.$pu_bilans->operation.''.$pu_bilans->amount.'] - '.$pu_bilans->description.' ['.$pu_bilans->author.': '.dateFormat($pu_bilans->date).']';
								echo '</p>';
							}
						echo '</div>';

						echo '<div class="summary__box switcher-item" data-item="2">';
							echo '<p class="summary__total">Aktualna wartość: <span>'.$kp->getValue('kp_pw').'</span></p>';
							foreach ($summary_pw as $pw_bilans) {
								if($pw_bilans->operation === '+') {
									echo '<p class="summary__item summary__item--plus">';
								} elseif ($pw_bilans->operation === '-') {
									echo '<p class="summary__item summary__item--minus">';
								} else {
									throw new Exception("Wystąpił problem z odczytam podsumowania!");
									echo '<p class="summary">';
								}
								echo '['.$pw_bilans->operation.''.$pw_bilans->amount.'] - '.$pw_bilans->description.' ['.$pw_bilans->author.': '.dateFormat($pu_bilans->date).']';
								echo '</p>';
							}
						echo '</div>';

						echo '<div class="summary__box switcher-item" data-item="3">';
							echo '<p class="summary__total">Aktualna wartość: <span>'.$kp->getValue('kp_ryo').'</span></p>';
							foreach ($summary_ryo as $ryo_bilans) {
								if($pw_bilans->operation === '+') {
									echo '<p class="summary__item summary__item--plus">';
								} elseif ($ryo_bilans->operation === '-') {
									echo '<p class="summary__item summary__item--minus">';
								} else {
									throw new Exception("Wystąpił problem z odczytam podsumowania!");
									echo '<p class="summary">';
								}
								echo '['.$ryo_bilans->operation.''.$ryo_bilans->amount.'] - '.$ryo_bilans->description.' ['.$pw_bilans->author.': '.dateFormat($pu_bilans->date).']';
								echo '</p>';
							}
						echo '</div>';
					echo '</div>';
					echo '<script defer src="js/infoSwitcher.js"></script>';
				} elseif (Input::get('section') === 'notes' && $kp_owner) {
					// TODO: notatki admina i gracza
				} elseif (Input::get('section') === 'admin' && $user->hasPermission('root')) {
					// TODO: opcje administratora
					echo '<div class="kp__container">';
						echo '<form method="post" class="form">';
							echo '<h2 class="form__heading">Status karty postaci</h2>';
							echo '<select name="kp_status" class="form__input form__input--select">';
							foreach ($settings['kp_status'] as $status) {
								if($status === $kp->getValue('kp_status')) {
									echo '<option value="'.$status.'" selected>'.$status.'</option>';
								} else {
									echo '<option value="'.$status.'">'.$status.'</option>';
								}
							}
							echo '</select>';

							echo '<h2 class="form__heading">Informacja dla gracza</h2>';
							echo '<div class="textarea-panel">';
								include('bbcode-panel.php');
								echo '<textarea name="kp_admin_message" placeholder="Informacja dla gracza" class="form__input form__input--textarea">'.$kp->getValue('kp_admin_message').'</textarea>';
							echo '</div>';

							echo '<input type="submit" value="Zmień status karty" name="submit_kp_status" class="form__button form__button--first">';
							echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
						echo '</form>';
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
	} else {
		if($user->HasKp($user_data->user_id) && $user->hasPermission('player')) {
			if(sizeof($user->getKpId()) > 1) {
				echo 'tutaj powinna byc lista kart postaci danego gracza<br>';
				foreach ($user->getKpId() as $kp_id) {
					echo '<br><a href="kp.php?kp_id='.$kp_id.'">Karta postaci o ID '.$kp_id.'</a>';
				}
			}
		} else {
			if(Input::exists('post', 'submit_kp')) {
				if(!$validation->passed()) {
					echo '<div class="info-box">';
					foreach($validation->errors() as $error) {
						echo '<div class="info-box__item info-box__item--error">';
							echo '<i class="info-box__icon icon-attention"></i>';
							echo '<p class="info-box__description">'.$error .'</p>';
						echo '</div>';
					}
					echo '</div>';
				}
			}
			echo '<h2 class="big-heading">tworzenie nowej karty postaci</h2>';
			echo '<div class="information">Witaj, '.$user_data->user_login.'! Znajdujesz się na stronie, dzięki której stworzysz swoją kartę postaci - niezbędny element konieczny do dalszej gry na forum. Uzupełnij poniższe pola, które określają podstawowe informacje o prowadzonym przez Ciebie wojowniku. Opisz jego wygląd oraz sposób bycia. Jeżeli wszystko przebiegnie prawidłowo otrzymasz '.$reward_pu.' punktów umiejętności (PU), '.$reward_pw.' punktów wymagań (PW) oraz '.$reward_ryo.' ryo. PU mozna przeznaczyć na opanowanie <a href="skills.php">umiejętności</a> lub rozwinięcie <a href="">statystyk</a>, natomiast za ryo, forumową walutę, możesz kupić <a href="items.php">przedmioty</a>. Informacje, które tutaj podasz będziesz mógł/mogła zmienić po utowrzeniu KP. Pamiętaj o przemyślanym wyborze przynależności, masz mozliwość zapisania się do jednego z ośmiu klanów głównych oraz dwóch organizacji, zrzeszających ninja. O wszystkich przeczytasz <a href="">tutaj</a>.</div>';
			echo '<form method="post" class="form">';
				echo '<h2 class="form__heading">Imię</h2>';
				echo '<input type="text" name="kp_name" placeholder="Imię postaci" class="form__input" value="'.Input::get('kp_name').'">';

				echo '<h2 class="form__heading">Nazwisko</h2>';
				echo '<input type="text" name="kp_surname" placeholder="Nazwisko (tylko w przypadku wyrzutków)" class="form__input" value="'.Input::get('kp_surname').'">';

				echo '<h2 class="form__heading">Klan</h2>';
				echo '<select name="kp_clan" class="form__input form__input--select">';
					echo '<option value="">brak</option>';
					foreach ($settings['clans'] as $clan) {
						if($clan === Input::get('kp_clan')) {
							echo '<option value="'.$clan.'" selected>'.$clan.'</option>';
						} else {
							echo '<option value="'.$clan.'">'.$clan.'</option>';
						}
					}
				echo '</select>';

				echo '<h2 class="form__heading">Organizacja</h2>';
				echo '<select name="kp_organizations" class="form__input form__input--select">';
					echo '<option value="">brak</option>';
					foreach ($settings['organizations'] as $organization) {
						if($organization === Input::get('kp_organizations')) {
							echo '<option value="'.$organization.'" selected>'.$organization.'</option>';
						} else {
							echo '<option value="'.$organization.'">'.$organization.'</option>';
						}
					}
				echo '</select>';

				echo '<h2 class="form__heading">Płeć</h2>';
				echo '<select name="kp_gender" class="form__input form__input--select">';
					foreach ($settings['gender'] as $gender) {
						if($gender === Input::get('kp_gender')) {
							echo '<option value="'.$gender.'" selected>'.$gender.'</option>';
						} else {
							echo '<option value="'.$gender.'">'.$gender.'</option>';
						}
					}
				echo '</select>';

				echo '<h2 class="form__heading">Rok urodzenia</h2>';
				echo '<input type="number" name="kp_birth_year" min="1" class="form__input" value="'.Input::get('kp_birth_year').'">';

				echo '<h2 class="form__heading">Wzrost</h2>';
				echo '<input type="number" name="kp_height" min="1" class="form__input" value="'.Input::get('kp_height').'">';

				echo '<h2 class="form__heading">Waga</h2>';
				echo '<input type="number" name="kp_weight" min="1" class="form__input" value="'.Input::get('kp_weight').'">';

				echo '<h2 class="form__heading">Wygląd</h2>';
				echo '<div class="textarea-panel">';
					include('bbcode-panel.php');
					echo '<textarea name="kp_look" placeholder="Opis wyglądu postaci" class="form__input form__input--textarea">'.Input::get('kp_look').'</textarea>';
				echo '</div>';

				echo '<h2 class="form__heading">Ubranie</h2>';
				echo '<div class="textarea-panel">';
					include('bbcode-panel.php');
					echo '<textarea name="kp_outfit" placeholder="Opis noszonego ubrania" class="form__input form__input--textarea">'.Input::get('kp_outfit').'</textarea>';
				echo '</div>';

				echo '<h2 class="form__heading">Nindo</h2>';
				echo '<div class="textarea-panel">';
					include('bbcode-panel.php');
					echo '<textarea name="kp_nindo" placeholder="Jakie jest nindo Twojej postaci?" class="form__input form__input--textarea">'.Input::get('kp_nindo').'</textarea>';
				echo '</div>';

				echo '<h2 class="form__heading">Cele</h2>';
				echo '<div class="textarea-panel">';
					include('bbcode-panel.php');
					echo '<textarea name="kp_goals" placeholder="Jakie cele ma Twoja postać?" class="form__input form__input--textarea">'.Input::get('kp_goals').'</textarea>';
				echo '</div>';

				echo '<h2 class="form__heading">Prolog</h2>';
				echo '<div class="textarea-panel">';
					include('bbcode-panel.php');
					echo '<textarea name="kp_prolog" placeholder="Jaka jest historia Twojej postaci? (wklej w tym miejscu prolog napisany i zatwierdzony przez administrację)" class="form__input form__input--textarea">'.Input::get('kp_prolog').'</textarea>';
				echo '</div>';

				echo '<input type="submit" value="Stwórz karte postaci" name="submit_kp" class="form__button form__button--first">';
				echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
			echo '</form>';
		}
	}
?>
</section>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
