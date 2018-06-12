<?php

require_once('core/init.php');

if(!$username = Input::get('id')) {
	Redirect::to('index.php');
} else {
	$user_profile = new User($username);
	$user = new User();
	$user_data = $user->data();

	if(!$user_profile->exists()) {
		Redirect::to(404);
	} else {
		$data_profile = $user_profile->data();
	}
}

if(Input::exists('post', 'update_password')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'old_password' => array(
			'name' => 'stare hasło',
			'required' => true
		),
		'new_password' => array(
			'name' => 'nowe hasło',
			'required' => true,
			'min_char' => 2,
			'max_char' => 30
		),
		'repeat_new_password' => array(
			'name' => 'powtórz nowe hasło',
			'required' => true,
			'matches' => 'new_password',
			'min_char' => 2,
			'max_char' => 30
		)
	));

	if($validation->passed()) {
		if(Hash::check(Input::get('old_password'), $data_profile->user_password)) {
			try {
				$user_profile->update($data_profile->user_id, array(
					'user_password' => Hash::make(Input::get('new_password'))
				));

				Session::flash('profile', 'Pomyślnie zmieniono hasło!');
				Redirect::to('profile.php?id='.$data_profile->user_login);
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
	}
}

if(Input::exists('post', 'update_settings')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'disp_posts' => array(
			'name' => 'liczba postów na stronę',
			'min_size' => 5,
			'max_size' => 50
		),
		'disp_topics' => array(
			'name' => 'liczba tematów na stronę',
			'min_size' => 5,
			'max_size' => 50
		),
		'style' => array(
			'name' => 'styl forum',
		)
	));

	if($validation->passed()) {
		try {
			$user->update($data_profile->user_id, array(
				'disp_posts' => Input::get('disp_posts'),
				'disp_topics' => Input::get('disp_topics'),
				'style' => Input::get('style')
			));

			Session::flash('profile', 'Pomyślnie zmieniono ustawienia wyświetlania!');
			Redirect::to('profile.php?id='.$data_profile->user_login);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', 'update_avatar')) {
	$validate = new Validate();
	$validation = $validate->check($_FILES, array(
		'user_avatar' => array(
			'required' => true,
			'avatar_file' => true
		)
	));

	if($validation->passed()) {
		try {
			$user_profile->update($data_profile->user_id, array(
				'avatar' => $data_profile->user_id
			));
			move_uploaded_file($_FILES['user_avatar']['tmp_name'], 'style/img/avatars/'.$data_profile->user_id.'.'.substr($_FILES['user_avatar']['type'], 6));

			Session::flash('profile', 'Pomyślnie zmieniono avatar!');
			Redirect::to('profile.php?id='.$data_profile->user_login);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	} else {
		foreach($validation->errors() as $error) {
			echo '<div>'.$error.'</div>';
		}
	}
}
if(Input::exists('post', 'delete_avatar')) {
	try {
		$user_profile->update($data_profile->user_id, array(
			'avatar' => 0
		));

		Session::flash('profile', 'Pomyślnie usunięto aktualny avatar!');
		Redirect::to('profile.php?id='.$data_profile->user_login);
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
if(Input::exists('post', 'update_profile')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'user_real_name' => array(
			'name' => 'prawdziwe imię',
			'min_char' => 2,
			'max_char' => 50
		),
		'user_gender' => array(
			'name' => 'płeć',
			'required' => true,
			'min_char' => 2,
			'max_char' => 50
		),
		'user_location' => array(
			'name' => 'lokalizacja',
			'min_char' => 2,
			'max_char' => 50
		)
	));

	if($validation->passed()) {
		try {
			$user_profile->update($data_profile->user_id, array(
				'user_real_name' => Input::get('user_real_name'),
				'user_gender' => Input::get('user_gender'),
				'user_location' => Input::get('user_location')
			));

			Session::flash('profile', 'Edycja danych profilowych powiodła się!');
			Redirect::to('profile.php?id='.$data_profile->user_login);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}
if(Input::exists('post', 'submit_change_nickname')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'user_login' => array(
			'name' => 'nazwa użytkownika',
			'required' => true,
			'unique' => array('users', 'user_login'),
			'min_char' => 2,
			'max_char' => 30,
			'letter_only' => true,
			'no_anime_names' => true,
			'no_clan_names' => true
		)
	));

	if($validation->passed()) {
		try {
			$user->update($data_profile->user_id, array(
				'user_login' => Input::get('user_login')
			));

			Session::flash('profile', 'Pomyślnie zmieniono nick użytkownika!');
			Redirect::to('profile.php?id='.Input::get('user_login'));
		} catch(Exception $e) {
			die($e->getMessage());
		}
	} else {
		foreach($validation->errors() as $error) {
			echo '<div>'.$error.'</div>';
		}
	}
}
if(Input::exists('post', 'submit_change_groups')) {
	$temp = 1;
	$user_group = '';
	foreach (Input::get('user_group') as $group_id) {
		$user_group .= $group_id;
		if($temp < count(Input::get('user_group'))) {
			$user_group .= ', ';
			$temp++;
		}
	}

	try {
		$user_profile->update($data_profile->user_id, array(
			'user_group' => $user_group
		));

		Session::flash('profile', 'Pomyślnie zaktualizowano rangi użytkownika!');
		Redirect::to('profile.php?id='.$data_profile->user_id);
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
if(Input::exists('post', 'submit_change_decorations')) {
	$temp = 1;
	$user_decorations = '';
	foreach (Input::get('user_decorations') as $dec_id) {
		$user_decorations .= $dec_id;
		if($temp < count(Input::get('user_decorations'))) {
			$user_decorations .= ', ';
			$temp++;
		}
	}

	try {
		$user_profile->update($data_profile->user_id, array(
			'user_decorations' => $user_decorations
		));

		Session::flash('profile', 'Pomyślnie zaktualizowano odznaczenia użytkownika!');
		Redirect::to('profile.php?id='.$data_profile->user_id);
	} catch(Exception $e) {
		die($e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Ninja Clan Wars</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<main class="profile wrapper">
	<?php
	if(Session::exists('profile')) {
		echo '<div class="info-box">';
			echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('profile').'</div>';
		echo '</div>';
	}
	?>
	<div class="big-heading">Profil użytkownika</div>
	<div class="profile-left">
		<div class="profile-left__login"><?php echo $data_profile->user_login ?></div>
		<div class="profile-left__avatar"><img src="<?php echo imageType('style/img/avatars/'.$data_profile->avatar) ?>"></div>
		<ul class="profile-nav">
			<?php
			echo '<li class="profile-nav__item"><a class="profile-nav__link" href="profile.php?id='.$data_profile->user_login.'">Informacje o profilu</a></li>';
			if($data_profile->user_id === $user_data->user_id || $user->hasPermission('root')) {
				echo '<li class="profile-nav__item"><a class="profile-nav__link" href="profile.php?id='.$data_profile->user_login.'&action=update_profile">Edytuj dane profilowe</a></li>';
				echo '<li class="profile-nav__item"><a class="profile-nav__link" href="profile.php?id='.$data_profile->user_login.'&action=display_settings">Wyświetlanie</a></li>';
				echo '<li class="profile-nav__item"><a class="profile-nav__link" href="profile.php?id='.$data_profile->user_login.'&action=password">Zmiana hasła</a></li>';
				echo '<li class="profile-nav__item"><a class="profile-nav__link" href="profile.php?id='.$data_profile->user_login.'&action=avatar">Ustawienia avatara</a></li>';
			}
			if($user->hasPermission('root')) {
				echo '<li class="profile-nav__item"><a class="profile-nav__link" href="profile.php?id='.$data_profile->user_login.'&action=admin">Opcje administratora</a></li>';
			}
			?>
		</ul>
	</div>

	<div class="profile-right">
			<?php
			if(!Input::get('action')) {
				echo '<h2 class="profile__heading">Informacje o profilu</h2>';
				echo '<div class="profile-informations">';
					echo '<div class="profile-informations__item">';
						echo '<p class="profile-informations__name">płeć</p>';
						echo '<p class="profile-informations__value">'.$data_profile->user_gender.'</p>';
					echo '</div>';

					if($data_profile->user_real_name !== '' && $data_profile->user_real_name !== NULL) {
						echo '<div class="profile-informations__item">';
							echo '<p class="profile-informations__name">prawdziwe imię</p>';
							echo '<p class="profile-informations__value">'.$data_profile->user_real_name.'</p>';
						echo '</div>';
					}

					echo '<div class="profile-informations__item">';
						echo '<p class="profile-informations__name">e-mail</p>';
						echo '<p class="profile-informations__value"><a class="profile-information__mail" href="mailto:'.$data_profile->user_email.'">'.$data_profile->user_email.'</a></p>';
					echo '</div>';

					echo '<div class="profile-informations__item">';
						echo '<p class="profile-informations__name">data rejestracji</p>';
						echo '<p class="profile-informations__value">'.dateFormat($data_profile->user_date).'</p>';
					echo '</div>';

					if($data_profile->user_location !== '' && $data_profile->user_location !== NULL) {
						echo '<div class="profile-informations__item">';
							echo '<p class="profile-informations__name">miejsce zamieszkania</p>';
							echo '<p class="profile-informations__value">'.$data_profile->user_location.'</p>';
						echo '</div>';
					}

					if($user->hasPermission('root')) {
						echo '<div class="profile-informations__item">';
							echo '<p class="profile-informations__name">adres IP</p>';
							echo '<p class="profile-informations__value">'.$user_profile->getCurrentIp().'</p>';
						echo '</div>';
					}
				echo '</div>';

				echo '<h2 class="profile__heading">Statystyki</h2>';
				echo '<div class="profile-informations">';
					$register_date = strtotime(substr($data_profile->user_date, 0, 10));
					$now = time();
					$datediff = $now - $register_date;

					echo '<div class="profile-informations__item">';
						echo '<p class="profile-informations__name">dni na forum</p>';
						echo '<p class="profile-informations__value">'.round($datediff / (60 * 60 * 24)).'</p>';
					echo '</div>';

					echo '<div class="profile-informations__item">';
						echo '<p class="profile-informations__name">liczba postów</p>';
						echo '<p class="profile-informations__value">'.$data_profile->user_total_posts.'</p>';
					echo '</div>';
				echo '</div>';

				echo '<a href="profile.php?id='.$data_profile->user_id.'&action=show_posts" class="button button--first-in-row">Wyświetl wszystkie posty</a>';

				echo '<h2 class="profile__heading">Odznaczenia</h2>';
				echo '<div class="profile-decorations">';
				if(!empty($user->showDecorations($data_profile->user_id))) {
					foreach ($user->showDecorations($data_profile->user_id) as $decoration) {
						echo '<div class="profile-decorations__item">
							<img src="'.imageType('style/img/decorations/'.$decoration->dec_id).'" title="'.$decoration->dec_name.'">
							<p class="profile-decorations__description">'.$decoration->dec_description.'</p>
						</div>';
					}
				} else {
					echo '<div class="profile-decorations__no-data">'.$data_profile->user_login.' nie ma żadnych odznaczeń :(</div>';
				}
				echo '</div>';
			} else if($action = Input::get('action') && Input::get('action') == 'password' && ($data_profile->user_id === $user_data->user_id || $user->hasPermission('root'))) {
				if(Input::exists('post', "update_password")) {
					if(!$validation->passed()) {
						echo '<div class="info-box">';
						foreach($validation->errors() as $error) {
							echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
						}
						echo '</div>';
					}
					if($validation->passed()) {
						echo '<div class="info-box">';
						if(!Hash::check(Input::get('old_password'), $data_profile->user_password)) {
							echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> Zostało podane błędne stare hasło!</p>';
						}
						echo '</div>';
					}
				}
				echo '<h2 class="profile__heading">Zmiana hasła</h2>';
				echo '<form method="post" class="form">';
					echo '<h2 class="form__heading">Stare hasło</h2>';
					echo '<input type="password" name="old_password" placeholder="Stare hasło" class="form__input">';
					echo '<h2 class="form__heading">Nowe hasło</h2>';
					echo '<input type="password" name="new_password" placeholder="Nowe hasło" class="form__input">';
					echo '<h2 class="form__heading">Powtórz nowe hasło</h2>';
					echo '<input type="password" name="repeat_new_password" placeholder="Powtórz nowe hasło" class="form__input">';

					echo '<input type="submit" name="update_password" value="Potwierdź zmianę" class="form__button form__button--first">';
					echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
				echo '</form>';
			} else if($action = Input::get('action') && Input::get('action') == 'display_settings' && ($data_profile->user_id === $user_data->user_id || $user->hasPermission('root'))) {
				if(Input::exists('post', "update_settings")) {
					if(!$validation->passed()) {
						echo '<div class="info-box">';
						foreach($validation->errors() as $error) {
							echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
						}
						echo '</div>';
					}
				}
				echo '<h2 class="profile__heading">Ustawienia wyświetlania</h2>';
				echo '<form method="post" class="form">';
					echo '<h2 class="form__heading">Liczba postów na stronę</h2>';
					echo '<input type="number" name="disp_posts" placeholder="Postów na stronę" class="form__input" value="'.$data_profile->disp_posts.'">';
					echo '<h2 class="form__heading">Liczba tematów na stronę</h2>';
					echo '<input type="number" name="disp_topics" placeholder="Tematów na stronę" class="form__input" value="'.$data_profile->disp_topics.'">';
					echo '<h2 class="form__heading">Styl forum</h2>';
					echo '<select name="style" class="form__input form__input--select">';
					foreach (User::userStyle() as $value) {
						if($value === $data_profile->style) {
							echo '<option value="'.$value.'" selected>'.$value.'</option>';
						} else {
							echo '<option value="'.$value.'">'.$value.'</option>';
						}
					}
					echo '</select>';

					echo '<input type="submit" name="update_settings" value="Potwierdź zmianę" class="form__button form__button--first">';
					echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
				echo '</form>';
			} else if($action = Input::get('action') && Input::get('action') == 'avatar' && ($data_profile->user_id === $user_data->user_id || $user->hasPermission('root'))) {
				if(Input::exists('post', "update_avatar")) {
					if(!$validation->passed()) {
						echo '<div class="info-box">';
						foreach($validation->errors() as $error) {
							echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
						}
						echo '</div>';
					}
				}
				echo '<h2 class="profile__heading">Ustawienia avatara</h2>';
				echo '<form method="post" enctype="multipart/form-data" class="form">';
					echo '<h2 class="form__heading">Wybieranie avatara</h2>';
					echo '<div class="information">Dozwolone jest używanie avatara w formacie PNG o rozmiarach 185x260px. Grafika nie może przekraczać 977 KB.</div>';
					echo '<input type="file" name="user_avatar" class="form__input">';
					echo '<input type="submit" value="Wybierz avatar" name="update_avatar" class="form__button form__button--first">';
				echo '</form>';

				echo '<form method="post" class="form">';
					echo '<h2 class="form__heading">Usuwanie avatara</h2>';
					echo '<div class="information">Usunięcie aktualnego avatara spowoduje przywrócenie domyślnego avatara forum.</div>';
					echo '<input type="submit" value="Usuń avatar" name="delete_avatar" class="form__button form__button--first">';
					echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
				echo '</form>';
			} else if($action = Input::get('action') && Input::get('action') == 'update_profile' && ($data_profile->user_id === $user_data->user_id || $user->hasPermission('root'))) {
				if(Input::exists('post', "update_profile")) {
					if(!$validation->passed()) {
						echo '<div class="info-box">';
						foreach($validation->errors() as $error) {
							echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
						}
						echo '</div>';
					}
				}
				echo '<h2 class="profile__heading">Zmiana informacji o profilu</h2>';
				echo '<form method="post" class="form">';
					echo '<h2 class="form__heading">Prawdziwe imię</h2>';
					echo '<input type="text" name="user_real_name" value="'.$data_profile->user_real_name.'" placeholder="Prawdziwe imię" class="form__input">';
					echo '<h2 class="form__heading">Płeć</h2>';
					echo '<select name="user_gender" class="form__input form__input--select">';
					foreach (User::userGender() as $value) {
						if($value === $data_profile->user_gender) {
							echo '<option value="'.$value.'" selected>'.$value.'</option>';
						} else {
							echo '<option value="'.$value.'">'.$value.'</option>';
						}
					}
					echo '</select>';
					echo '<h2 class="form__heading">Miejsce zamieszkania</h2>';
					echo '<input type="text" name="user_location" value="'.$data_profile->user_location.'" placeholder="Lokalizacja" class="form__input">';

					echo '<input type="submit" name="update_profile" value="Aktualizuj" class="form__button form__button--first"> ';
					echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
				echo '</form>';
			} else if($action = Input::get('action') && Input::get('action') == 'admin' && ($data_profile->user_id === $user_data->user_id || $user->hasPermission('root'))) {
				if(Input::exists('post', "submit_change_nickname")) {
					if(!$validation->passed()) {
						echo '<div class="info-box">';
						foreach($validation->errors() as $error) {
							echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
						}
						echo '</div>';
					}
				}
				echo '<h2 class="profile__heading">Opcje administratora</h2>';
				echo '<form method="post" class="form">';
					echo '<h2 class="form__heading">Nazwa użytkownika</h2>';
					echo '<input type="text" name="user_login" value="'.$data_profile->user_login.'" class="form__input">';
					echo '<input type="submit" name="submit_change_nickname" value="Zmień nick" class="form__button form__button--first">';
				echo '</form>';

				echo '<form method="post" class="form">';
					$group = new Group();
					echo '<h2 class="form__heading">Przydzielone grupy</h2>';
					echo '<select name="user_group[]" class="form__input form__input--select form__input--multiple" multiple>';
					foreach ($group->getList() as $group_id => $group_name) {
						$counter = 0;
						$user_group_size = count($user_profile->showGroups($data_profile->user_id));
						foreach ($user_profile->showGroups($data_profile->user_id) as $user_group_name => $user_group_color) {
							if($user_group_name == $group_name) {
								echo '<option value="'.$group_id.'" selected>'.$group_name.'</option>';
							} elseif($user_group_name != $group_name && $counter < $user_group_size-1) {
								$counter++;
							} else {
								echo '<option value="'.$group_id.'">'.$group_name.'</option>';
							}
						}
					}
					echo '</select>';
					echo '<input type="submit" name="submit_change_groups" value="Aktualizuj grupy" class="form__button form__button--first">';
					echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
				echo '</form>';

				echo '<form method="post" class="form">';
					$decorations = new Decoration();
					echo '<h2 class="form__heading">Odznaczenia</h2>';
					echo '<select name="user_decorations[]" class="form__input form__input--select form__input--multiple" multiple>';
					foreach ($decorations->data() as $decoration) {
						$counter = 0;
						$user_decorations_size = count($user_profile->showDecorations($data_profile->user_id));
						foreach ($user_profile->showDecorations($data_profile->user_id) as $user_decoration) {
							if($decoration->dec_name == $user_decoration->dec_name) {
								echo '<option value="'.$decoration->dec_id.'" selected>'.$decoration->dec_name.'</option>';
							} elseif($decoration->dec_name != $user_decoration->dec_name && $counter < $user_decorations_size-1) {
								$counter++;
							} else {
								echo '<option value="'.$decoration->dec_id.'">'.$decoration->dec_name.'</option>';
							}
						}
					}
					echo '</select>';
					echo '<input type="submit" name="submit_change_decorations" value="Aktualizuj odznaczenia" class="form__button form__button--first">';
					echo '<a href="javascript:history.go(-1)" class="form__button">Powrót</a>';
				echo '</form>';

				echo '<form method="post" action="admin_bans?ban_user='.$data_profile->user_id.'" class="form">';
					echo '<h2 class="form__heading">Banowanie</h2>';
					echo '<input type="submit" value="Zbanuj użytkownika '.$data_profile->user_login.'" class="form__button form__button--center">';
				echo '</form>';
			} else if($action = Input::get('action') && Input::get('action') == 'show_posts') {
				echo '<h2 class="profile__heading">Wszystkie posty użytkownika</h2>';

				$user_posts = new Post();
				if(Input::get('page')) {
					$page = Input::get('page');
				} else {
					$page = 1;
				}

				if($user_posts->showUserPosts($data_profile->user_id, $page)) {
					$pagination = true;
				}
				$total_pages = $user_posts->totalPages();
				echo '<div class="pagination">';
					if($total_pages != 0) {
						if($page != 1) {
							echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page=1&action=show_posts" title="Przejdź do pierwszej strony"><<</a>';
						}
						if($total_pages <= 5) {
							for($i = 1; $i <= $total_pages; $i++) {
								if($page == $i) {
									echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
								} else {
									echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$i.'&action=show_posts"> '.$i.' </a>';
								}
							}
						} else {
							for($i = $page-2; $i < $page; $i++) {
								if($i > 0) {
									echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$i.'&action=show_posts"> '.$i.' </a>';
								}
							}
							echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
							for($i = $page+1; $i < $page+3; $i++) {
								if($i <= $total_pages) {
									echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$i.'&action=show_posts"> '.$i.' </a>';
								}
							}
						}
						if($page != $total_pages) {
							echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$total_pages.'&action=show_posts" title="Przejdź do ostatniej strony">>></a>';
						}
					}
				echo '</div>';

				if($user_posts->data()) {
					foreach ($user_posts->data() as $post) {
						$topic_info = new Topic($post->post_topic);
						echo '<div class="post">';
							echo '<div class="post-center">';
								echo '<div class="post-right__info"><a class="post-right__info-date" href="viewtopic.php?pid='.$post->post_id.'#p'.$post->post_id.'">dodano: '.dateFormat($post->post_date).' w temacie: '.$topic_info->data()->topic_name.'</a></div>';
								echo '<div class="post-right__contents">'.$post->post_contents.'</div>';
							echo '</div>';
						echo '</div>';
					}
				} else {
					echo 'ten użytkownik nie napisał żadnego posta lub zostały one usunięte!';
				}


				echo '<div class="pagination">';
					if($total_pages != 0) {
						if($page != 1) {
							echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page=1&action=show_posts" title="Przejdź do pierwszej strony"><<</a>';
						}
						if($total_pages <= 5) {
							for($i = 1; $i <= $total_pages; $i++) {
								if($page == $i) {
									echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
								} else {
									echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$i.'&action=show_posts"> '.$i.' </a>';
								}
							}
						} else {
							for($i = $page-2; $i < $page; $i++) {
								if($i > 0) {
									echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$i.'&action=show_posts"> '.$i.' </a>';
								}
							}
							echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
							for($i = $page+1; $i < $page+3; $i++) {
								if($i <= $total_pages) {
									echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$i.'&action=show_posts"> '.$i.' </a>';
								}
							}
						}
						if($page != $total_pages) {
							echo '<a class="pagination__link" href="?id='.$data_profile->user_id.'&page='.$total_pages.'&action=show_posts" title="Przejdź do ostatniej strony">>></a>';
						}
					}
				echo '</div>';
			}

			?>
	</div>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
