<?php

require_once('core/init.php');

if(Input::exists('post', "submit_register")) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'login' => array(
			'name' => 'nazwa użytkownika',
			'required' => true,
			'unique' => array('users', 'user_login'),
			'min_char' => 2,
			'max_char' => 30,
			'letter_and_japanise_only' => true,
			'no_anime_names' => true,
			'no_clan_names' => true
		),
		'email' => array(
			'name' => 'e-mail',
			'required' => true,
			'email_validate' => true
		),
		'password' => array(
			'name' => 'hasło',
			'required' => true,
			'min_char' => 6,
			'matches' => 'password_again'
		),
		'password_again' => array(
			'name' => 'powtórz hasło',
			'required' => true
		),
		'rules' => array(
			'name' => 'regulamin',
			'checked' => true
		),
		'g-recaptcha-response' => array(
			'requiredCaptcha' => true,
			'recaptcha' => true
		)
	));

	if($validation->passed()) {
		$ban = new Ban();
		if(!$ban->checkRegisterForm(Input::get('email'))) {
			$user = new User();
			try {
				$user->create(array(
					'user_login' => Input::get('login'),
					'user_password' => Hash::make(Input::get('password')),
					'user_email' => Input::get('email'),
					'user_date' => date('Y-m-d H:i:s'),
					'user_ip' => getIp()
				));

				Session::flash('login', 'Rejestracja zakończona pomyślnie!');
				Redirect::to('login.php');
			} catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			echo '<div class="register__ban">';
			foreach($ban->banInfo() as $ban_info) {
				echo '<p class="ban__info">'.$ban_info.'</p>';
			}
			if($ban->banMessage() != false) {
				echo '<p class="ban__message">Wiadomość od banujcego: '.$ban->banMessage().'</p>';
			}
			echo '</div>';
		}
	}
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Rejestracja</title>
	<?php require_once('head.inc'); ?>

	<link rel="stylesheet" type="text/css" href="style/forum.css">
	<link rel="stylesheet" type="text/css" href="style/login.css">

	<script src='https://www.google.com/recaptcha/api.js'></script>
	<link rel="stylesheet" type="text/css" href="style/tooltipster/tooltipster.bundle.min.css" />
	<link rel="stylesheet" type="text/css" href="style/tooltipster/tooltipster-sideTip-borderless.min.css" />
	<script type="text/javascript" src="js/tooltipster/tooltipster.bundle.min.js"></script>
	<script>
        $(document).ready(function() {
            $('.tooltip').tooltipster({
            	animation: 'grow',
            	theme: 'tooltipster-borderless',
            	maxWidth: 300
            });
        });
    </script>
</head>
<body>
	<section class="register">
		<div class="register__logotype"><a href="forum.php"><img src="style/img/logotype.png"></a></div>
		<form method="post" class="form register__form" action="register.php">
			<h2 class="left-border-heading">Rejestracja</h2>
			<?php
			if(Input::exists('post', "submit_register")) {
				echo '<div class="info-box">';
				if(!$validation->passed()) {
					foreach($validation->errors() as $error) {
						echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
					}
				}
				echo '</div>';
			}
			?>
			<label class="form__prompt-label">
				<input type="text" name="login" required="required" data-validateForm="login" placeholder="Podaj nazwę użytkownika..." value="<?php echo escape(Input::get('login')); ?>" class="form__input form__input--vertical">
				<span class="tooltip" title="Nazwa użytkownika może składać się jedynie z liter (bez polskich znaków diakrytycznych, dozwolone jedynie japońskie. Zabronione jest używanie imion z Naruto oraz nazw klanów.">?</span>
			</label>
			<label class="form__prompt-label">
				<input type="text" name="email" required="required" data-validateForm="email" placeholder="Podaj działający e-mail..." value="<?php echo escape(Input::get('email')); ?>" class="form__input form__input--vertical">
				<span class="tooltip" title="Podanie poprawnego adresu e-mail umożliwi przesłanie specjalnych informacji użytkownikom.">?</span>
			</label>
			<label class="form__prompt-label">
				<input type="password" name="password" required="required" data-validateForm="password" placeholder="Podaj hasło..." class="form__input form__input--vertical">
				<span class="tooltip" title="Hasło musi składać się przynajmniej z 6 znaków.">?</span>
			</label>
			<label class="form__prompt-label">
				<input type="password" name="password_again" required="required" data-validateForm="password_again" placeholder="Powtórz hasło..." class="form__input form__input--vertical">
			</label>
			<label class="form__prompt-label">
				<div class="checkbox-panel">
					<div class="checkbox-panel__checkbox">
						<input type="checkbox" name="rules" required="required" id="rulesCheckbox" class="form__input--checkbox">
						<label for="rulesCheckbox" required="required" data-validateForm="rules"></label>
					</div>
					<div class="checkbox-panel__description">Akceptuję <a href="rules.php" class="form__label-box-link">regulamin</a></div>
				</div>
			</label>
			<div class="g-recaptcha" data-sitekey="6Ldy3QsUAAAAAKC64J7D_QIxZmURRiz61Aemcf5_" data-theme="dark"></div>
			<!-- <div class="g-recaptcha" data-sitekey="6LcGFikTAAAAAAEn0m2DILLa-d5A7sQ9iZclwbbh" data-theme="dark"></div> -->
			<input type="submit" value="Zarejestruj się" name="submit_register" class="form__button form__button--center">
			<div class="register__info">Masz już konto? <a class="register__link" href="login.php">Zaloguj się!</a></div>
		</form>
		<script src="js/formValidate.js"></script>
	</section>
</body>
</html>