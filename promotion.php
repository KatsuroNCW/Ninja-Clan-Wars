<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Materiały promocyjne</title>
	<?php require_once('head.inc'); ?>
</head>
<body>
<div id="fb-root"></div>
<?php
require_once('core/init.php');

$user = new User();
$user_data = $user->data();
?>

<?php require_once('header.inc.php'); ?>

<nav class="mobile-nav">
	<ul class="mobile-nav__menu">
		<li id="mobile-nav__land-switcher" class="mobile-nav__item"><a href="javascript: void(0)" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-switch"></i>Switcher</a></li>
		<li id="mobile-nav__land-switcher" class="mobile-nav__item"><a href="maps.php" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-globe"></i>Mapy</a></li>
		<li id="mobile-nav__land-switcher" class="mobile-nav__item"><a href="forum.php" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-home"></i>Główna</a></li>
		<li id="mobile-nav__menu" class="mobile-nav__item"><a href="javascript: void(0)" class="mobile-nav__menu--link"><i class="mobile-nav__menu--icon icon-menu"></i>Menu</a></li>
	</ul>
</nav>

<div class="page-up" title="Przewiń do góry"><i class="icon-up-open"></i></div>

<main class="promotion wrapper">
	<h2 class="big-heading">Materiały promocyjne</h2>
	<div class="promotion__description">Chcesz zwiększyć popularność Ninja Clan Wars? Bardzo dobrze trafiłeś. Znajdziesz tu wszystkie formy graficznej reklamy jakimi dysponujemy. Wystarczy wybrać interesująca Cię grafikę, skopiować kod (do wyboru HTML oraz BBcode) i wkleić na forum, bloga. Gorąco zachęcamy do rozpowszechniania linków, grafik i filmików znajdujących się tutaj. Jesteś administratorem forum, PBF'a lub innego typu witryny? Chcesz wymienić się bannerami, buttonami lub nawiązać bliższą współpracę? <a href="contact.php">Skontaktuj się z nami</a> i omów szczegóły. Zastrzegamy sobie prawo do usunięcia reklamy w przypadku upadku danej strony lub zniknięcia naszej grafiki.</div>

	<div class="promotion__box">
		<h2 class="left-border-heading">Bannery</h2>
		<div class="promotion-item">
			<img src="style/img/promotion/banners/1.png">
			<p class="promotion-item__info">390x50px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/banners/1.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/banners/1.png[/img][/url]</p>
		</div>

		<div class="promotion-item">
			<img src="style/img/promotion/banners/2.png">
			<p class="promotion-item__info">500x100px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/banners/2.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/banners/2.png[/img][/url]</p>
		</div>

		<div class="promotion-item">
			<img src="style/img/promotion/banners/3.png">
			<p class="promotion-item__info">500x100px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/banners/3.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/banners/3.png[/img][/url]</p>
		</div>

		<div class="promotion-item">
			<img src="style/img/promotion/banners/4.png">
			<p class="promotion-item__info">500x100px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/banners/4.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/banners/4.png[/img][/url]</p>
		</div>
	</div>

	<div class="promotion__box">
		<h2 class="left-border-heading">Buttony</h2>
		<div class="promotion-item">
			<img src="style/img/promotion/buttons/1.png">
			<p class="promotion-item__info">88x31px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/buttons/1.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/buttons/1.png[/img][/url]</p>
		</div>

		<div class="promotion-item">
			<img src="style/img/promotion/buttons/2.png">
			<p class="promotion-item__info">88x31px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/buttons/2.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/buttons/2.png[/img][/url]</p>
		</div>

		<div class="promotion-item">
			<img src="style/img/promotion/buttons/3.png">
			<p class="promotion-item__info">88x31px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/buttons/3.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/buttons/3.png[/img][/url]</p>
		</div>

		<div class="promotion-item">
			<img src="style/img/promotion/buttons/4.png">
			<p class="promotion-item__info">88x31px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/buttons/4.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/buttons/4.png[/img][/url]</p>
		</div>

		<div class="promotion-item">
			<img src="style/img/promotion/buttons/5.png">
			<p class="promotion-item__info">88x31px</p>
			<p class="promotion-item__code">&lt;a href="http://www.ninjaclanwars.pl"&gt;&lt;img src="http://ninjaclanwars.pl/style/img/promotion/buttons/5.png"&gt;&lt;/a&gt;</p>
			<p class="promotion-item__code">[url=http://www.ninjaclanwars.pl][img]http://ninjaclanwars.pl/style/img/promotion/buttons/5.png[/img][/url]</p>
		</div>
	</div>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
