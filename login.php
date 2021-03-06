<?php

require_once('core/init.php');

if(Input::exists('post', 'submit_login')) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'login' => array(
			'name' => 'login',
			'required' => true,
			'min_char' => 2,
			'max_char' => 30,
			'letter_and_japanise_only' => true
		),
		'password' => array(
			'name' => 'hasło',
			'required' => true
		)
	));

	if($validation->passed()) {
		$ban = new Ban();
		if(!$ban->checkLoginForm(Input::get('login'))) {
			$user = new User();
			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('login'), Input::get('password'), $remember);

			if($login->passed()) {
				$user->updateIp();
				Session::flash('forum', 'Zalogowano pomyślnie!');
				Redirect::to('forum.php');
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Logowanie</title>
	<?php require_once('head.inc'); ?>
</head>
<body>
	<?php
	if(Input::exists('post', 'submit_login')) {
		echo '<div class="info-box">';
		if(!$validation->passed()) {
			foreach($validation->errors() as $error) {
				echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
			}
		} else {
			if($ban->checkLoginForm(Input::get('login'))) {
				echo '<div class="info-box__ban">';
				foreach($ban->banInfo() as $ban_info) {
					echo '<p class="info-box__ban-info">'.$ban_info.'</p>';
				}
				if($ban->banMessage() != false) {
					echo '<p class="info-box__ban-message">Wiadomość od banujcego: '.$ban->banMessage().'</p>';
				}
				echo '</div>';
			}
		}
		echo '</div>';
	}
	?>
	<section class="login">
		<div class="login__logotype"><a href="forum.php"><img src="style/img/logotype.png"></a></div>
		<form method="post" class="form login__form">
			<h2 class="left-border-heading">Logowanie</h2>
			<label class="form__prompt-label">
				<input type="text" name="login" required="required" data-validateForm="login" placeholder="Podaj nazwę użytkownika..." value="<?php echo escape(Input::get('login')); ?>" class="form__input form__input--vertical">
			</label>
			<label class="form__prompt-label">
				<input type="password" name="password" required="required" data-validateForm="password" placeholder="Podaj hasło..." class="form__input form__input--vertical">
			</label>
			<label class="form__prompt-label">
				<div class="checkbox-panel">
					<div class="checkbox-panel__checkbox">
						<input type="checkbox" name="remember" id="rememberCheckbox" class="form__input--checkbox">
						<label for="rememberCheckbox"></label>
					</div>
					<div class="checkbox-panel__description">Zapamiętaj mnie</div>
				</div>
			</label>

			<input type="submit" value="Zaloguj się" name="submit_login" class="form__button form__button--center">

			<div class="login__info">Nie masz konta? <a class="login__link" href="register.php">Zarejestruj się!</a></div>
		</form>
		<script src="js/formValidate.js"></script>
		</div>
	</section>
</body>
</html>
