<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Ninja Clan Wars</title>
	<?php require_once('head.inc'); ?>
</head>
<body>
<?php
require_once('core/init.php');

echo '<div class="info-box">';
if(Session::exists('index')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('index').'</div>';
}
echo '</div>';

$user = new User();
$user_data = $user->data();
?>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<main class="portal wrapper">
	<h2 class="portal__heading">Witaj na <span>Ninja Clan Wars</span> - Naruto PBF!</h2>
	<p class="portal__description">Ninja Clan Wars to PBF <a href="http://pl.wikipedia.org/wiki/Play_by_forum">(play by forum)</a> osadzony w klimatach dzieła autorstwa <a href="http://pl.wikipedia.org/wiki/Masashi_Kishimoto">Masashiego Kishimoto</a>: <a href="https://pl.wikipedia.org/wiki/Naruto_(manga_i_anime)">Naruto</a>. Gracz wciela się w wojownika należącego do jednego z 8 Klanów Głównych, organizacji Samotników, zrzeszającej pomniejsze klany lub Wyrzutków nieposiadających unikatowych więzów krwi. Każdy ninja ma swój cel - każdy dąży do niego wyznaczoną przez siebie ścieżką, a czy się powiedzie? Wszystko zależy od podjętych decyzji, wytrwałości i zangażowaniu.</p>
</main>

<section class="portal-section wrapper history">
	<h2 class="portal-section__heading">Historia</h2>
	<div class="history__box">
		<div class="history__img"><img src="style/img/portal/portal-1.png"></div>
		<div class="history__text">Świat nie raz pokazał nam ninja - żeby przetrwać nie wystarczy jedynie silna wola. Ani mocarne mięśnie, pod którymi giną nasi wrogowie. Ani wszechstronny umysł, dzięki któremu rozwiązujemy napotkane problemy. Wśród czternastu państw nie ma miłości i współczucia. Ludzie odrzucili pokój, nie wierząc w nastanie dnia jedności. Nadzieje na to głęboko pogrzebała czarna stal. Każdego, plugawego dnia przelewem krwi. Bowiem nienawiść to część, której nie może pozbyć się żaden ninja. Strach przez nieznanym owocuje paniką wśród mieszkańców. W czasach, gdy wojna stoi u progu drzwi, ludzie drżą. Przed wrogami, przed bestiami, a nawet przez samymi sobą.</div>
	</div>
	<div class="history__box">
		<div class="history__img"><img src="style/img/portal/portal-2.png"></div>
		<div class="history__text">Koalicja Kraju Wody, Kraju Ziemi i Kraju Ognia rozpoczęła ekspansywną politykę, podbijając ziemie wzdłuż kontynentu. Przebiegły lord z Airando chwycił swymi pazurami tereny niezależnych państw. Chciwość, jaką wykazywał wraz z organizacją Samotników, doprowadziła do podzielenia świata na dwie niemalże równorzędne strony. I choć tyraństwo prze naprzód, połączone siły Kraju Błyskawicy i Kraju Ziemi wychodzą im naprzeciw. Niezliczone bitwy, prowadzone przez przywódców wielu klanów, zabrały ze sobą tysiące ludzkich istnień. Napięcie na arenie międzynarodowej doprowadziło także do rozłamu Kraju Ziemi. Skutki obecnej wojny domowej na tychże ziemiach odłączyły Sashikan oraz Baigai. Od tamtego czasu Kiyoshi borykają się z brakiem odpowiedniej ilości jedzenia i surowców.</div>
	</div>
	<div class="history__box">
		<div class="history__img"><img src="style/img/portal/portal-3.png"></div>
		<div class="history__text">Po śmierci grubego lorda Kraju Ognia, władzę przejęła jego córka. Poparła klan Senju, zwierzchnika rodu Ayatatsuri. Wspomogła swymi siłami Kraj Wody i razem z nimi, zapragnęła władzy. Stała się pierwszym daimyō, który brał udział w bitwie i samodzielnie kierował oddziałami. Została zarówno pierwszym feudałem kobietą, a także lordem ninja. Strach na kontynencie podsycają także wieści o przebudzeniu demona. O dwu ogoniastej bestii, która swym parzącym oddechem niszczy wioski w kilka sekund. I choć plotki pochodzą z wielu sprzecznych stron jednocześnie, ludzie kierując się legendami tego świata - nie wykluczają żadnej z opcji. Wiedząc jednocześnie, że tak potężna siła nie przyniesie nic innego, jak zagładę.</div>
	</div>
</section>

<section class="portal-section wrapper movies">
	<h2 class="portal-section__heading">Filmy promocyjne</h2>
	<iframe src="https://www.youtube.com/embed/kCF93_LzGVc" frameborder="0" allowfullscreen=""></iframe>
	<iframe src="https://www.youtube.com/embed/K4lGcPT98Nc" frameborder="0" allowfullscreen=""></iframe>
	<p class="movies__caption">( by MeHow aka Miyaguchi )</p>
</section>

<section class="portal-section wrapper ad">
	<h2 class="portal-section__heading">Dołącz do nas!</h2>
	<div class="ad__img"><img src="style/img/portal/portal-4.png"></div>
	<div class="ad__text">Dzięki silnikowi forum, który został napisany specjalnie na potrzeby NCW sprawiamy, że gracz nie musi przejmować się skomplikowanymi wyliczeniami i aktualizacjami karty postaci. Zdobywanie umiejętności, których postać używa w fabule, odbywa się za pomocą jednego przycisku. System sam sprawdza wymagania, sam dokonuje niezbędnych modyfikacji KP, dzięki czemu wszyscy, zarówno gracze jak i mistrzowie gry, mogą skupić się na fabule. Tym samym mechanika gry staje się dużo bardziej przyjazna.</div>
	<div class="ad__buttons">
		<a href="login.php" class="button">Logowanie</a>
		<a href="register.php" class="button">Rejestracja</a>
	</div>
</section>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
