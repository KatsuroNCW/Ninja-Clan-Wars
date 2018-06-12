<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Kontakt</title>
	<?php require_once('head.inc'); ?>
	<script>
	(function() {
	    var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.id = 'gg-widget-script';
	    s.src = '//widget.gg.pl/resources/js/widget.js';
	    var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss); })();
	</script>

	<script>
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = 'https://connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.12';
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
</head>
<body>
<div id="fb-root"></div>
<?php
require_once('core/init.php');

$user = new User();
$user_data = $user->data();
?>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<main class="contact wrapper">
	<h2 class="big-heading">Kontakt z administracją</h2>
	<div class="contact__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nunc metus, dapibus vel mi a, venenatis accumsan ligula. Phasellus sit amet arcu consectetur, convallis orci non, faucibus tortor. Suspendisse accumsan sapien et dui lacinia maximus. Integer consequat lectus ac nulla pharetra porttitor. Nulla facilisi. Morbi ac rhoncus ipsum. Phasellus nunc ex, suscipit nec dapibus nec, malesuada dapibus sapien. In eleifend eget quam a maximus. Integer sit amet elit nibh. Ut id faucibus magna.</div>

	<h2 class="big-heading">Nasz skład</h2>
	<div class="contact__box">
		<a href="profile.php?id=1" class="contact-item">
			<h2 class="contact-item__name">Katsuro</h2>
			<div class="contact-item__avatar"><img src="style/img/avatars/1.png"></div>
			<div class="contact-item__description">Sed interdum leo nibh, non efficitur odio consectetur at. In elementum sed nunc sit amet imperdiet. Duis nec sem at neque ultrices elementum. Duis vel efficitur odio. Donec pellentesque finibus risus varius porttitor. Sed ac diam elementum, posuere ipsum nec, cursus arcu.</div>
		</a>

		<a href="profile.php?id=1" class="contact-item">
			<h2 class="contact-item__name">Ichuza</h2>
			<div class="contact-item__avatar"><img src="style/img/avatars/0.png"></div>
			<div class="contact-item__description">Sed interdum leo nibh, non efficitur odio consectetur at. In elementum sed nunc sit amet imperdiet. Duis nec sem at neque ultrices elementum. Duis vel efficitur odio. Donec pellentesque finibus risus varius porttitor. Sed ac diam elementum, posuere ipsum nec, cursus arcu.</div>
		</a>

		<a href="profile.php?id=1" class="contact-item">
			<h2 class="contact-item__name">Yocharu</h2>
			<div class="contact-item__avatar"><img src="style/img/avatars/0.png"></div>
			<div class="contact-item__description">Sed interdum leo nibh, non efficitur odio consectetur at. In elementum sed nunc sit amet imperdiet. Duis nec sem at neque ultrices elementum. Duis vel efficitur odio. Donec pellentesque finibus risus varius porttitor. Sed ac diam elementum, posuere ipsum nec, cursus arcu.</div>
		</a>
	</div>

	<h2 class="big-heading">Formy kontaktu</h2>
	<div class ="contact__description">Dla osób chcących się z nami skontaktować mamy kilka opcji. Pierwsza to kanał discord, na którym przesiadują zarówno gracze jak i administracja - wystarczy założyć darmowe konto. Ponadto oferujemy numer GG (5259823), pod którym co jakiś czas można znaleźć Katsuro. Jeśli nie posiadasz własnego konta możesz skorzystać z widgetu znajdującego się poniżej. Jeżeli dla kogoś dwie pierwsze opcje nie są wystarczające zachęcamy do zadawania pytań poprzez facebooka. Ostatnia opcja, najbardziej uniwersalna: e-mail <a href="mailto:ninjaclanwars@gmail.com">ninjaclanwars@gmail.com</a>.</div>
	<div class="contact__box">
		<div class="contact-discord">
			<iframe src="https://discordapp.com/widget?id=353520842002661388&theme=dark" height="350" width="300" allowtransparency="true" frameborder="0"></iframe>
		</div>
		<div class="contact-gg">
			<a href="//widget.gg.pl/widget/7a1a943ef855c9dd8077d0c0bb9deb775106f02970368ecad83876781df86a04#uin%3D5259823%7Cmsg_online%3DCze%C5%9B%C4%87%2C%20w%20czym%20mog%C4%99%20pom%C3%B3c%3F%7Cmsg_offline%3DZostaw%20wiadomo%C5%9B%C4%87%20i%20informacje%20kontaktowe%2C%20a%20odpowiemy%20na%20Twoje%20pytanie.%7Chash%3D7a1a943ef855c9dd8077d0c0bb9deb775106f02970368ecad83876781df86a04" rel="nofollow" data-gg-widget="normal-bottom" target="_blank">
			<span class="contact-gg__first-span">
			<img alt="" src="https://status.gadu-gadu.pl/users/status.asp?id=5259823&amp;styl=1&amp;source=widget"/>Zadaj pytanie on-line</span>
			<span class="contact-gg__second-span">Napisz do nas...
			<span class="contact-gg__third-span">Wy&sacute;lij</span>
			</span>
			</a>
		</div>

		<div class="contact-fb fb-page" data-href="https://www.facebook.com/NinjaClanWars/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/NinjaClanWars/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/NinjaClanWars/">Ninja Clan Wars</a></blockquote></div>
	</div>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
