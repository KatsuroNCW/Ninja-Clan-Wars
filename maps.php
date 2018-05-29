<?php
require_once('core/init.php');

$user = new User();
$user_data = $user->data();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Mapy świata</title>
	<?php require_once('head.inc'); ?>
	<link rel="stylesheet" type="text/css" href="style/tooltipster/tooltipster.bundle.min.css" />
	<link rel="stylesheet" type="text/css" href="style/tooltipster/tooltipster-sideTip-borderless.min.css" />
	<script type="text/javascript" src="js/maps.js"></script>
	<script type="text/javascript" src="js/tooltipster/tooltipster.bundle.min.js"></script>
	<script>
        $(document).ready(function() {
            $('.tooltip').tooltipster({
            	animation: 'grow',
            	theme: 'tooltipster-borderless'
            });
        });
    </script>
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

	<main class="map">
		<h2 class="big-heading">Mapy świata</h2>
		<div class="map__disable">Twój wyświetlacz jest za mały, żeby poprawnie korzystać z map świata.</div>
		<div class="map__container">

			<div id="shiso" class="map__box map__box--land">
				<h2 class="map-box__header">SHISŌ</h2>
				<img src="style/img/maps/N0OxL0v.png" usemap="#mapShiso">
				<map name="mapShiso">
				<area shape="circle" coords="426,274,23" href="viewsection.php?id=39" onmouseover="" target="_blank" class="tooltip" title="Siedziba klanu Ayatsuri"/>
				<area shape="circle" coords="495,215,14" href="viewtopic.php?id=57" target="_blank" class="tooltip" title="Tereny starego Kanketsu"/>
				<area shape="circle" coords="382,203,14" href="viewtopic.php?id=55" target="_blank" class="tooltip" title="Ufortyfikowana osada"/>
				<area shape="circle" coords="353,228,14" href="viewtopic.php?id=58" target="_blank" class="tooltip" title="Tereny starego Debiruiyaa"/>
				<area shape="circle" coords="383,280,14" href="viewtopic.php?id=56" target="_blank" class="tooltip" title="Niezbadane jaskinie"/>
				<area shape="circle" coords="461,301,15" href="viewtopic.php?id=59" target="_blank" class="tooltip" title="Budynki mieszkalne"/>
				<area shape="circle" coords="609,209,14" href="viewtopic.php?id=60" target="_blank" class="tooltip" title="Miasto handlowe"/>
				<area shape="circle" coords="635,230,14" href="viewtopic.php?id=53" target="_blank" class="tooltip" title="Koszary miejskie"/>
				<area shape="circle" coords="603,240,14" href="viewtopic.php?id=54" target="_blank" class="tooltip" title="Wielki pałac"/>
				<area shape="circle" coords="589,354,14" href="viewtopic.php?id=61" target="_blank" class="tooltip" title="Rzeka"/>
				</map>
			</div>

			<div id="main_map" class="map__box map__box--main">
				<img src="style/img/maps/land.png" usemap="#mapMain">
				<map name="mapMain">
				<area shape="poly" coords="426,275,438,287,456,280,473,281,485,288,489,289,492,287,503,287,508,289,518,289,527,299,529,318,531,319,539,312,545,317,557,317,558,325,554,327,549,328,548,331,525,332,516,338,514,350,507,350,502,361,515,381,526,381,529,389,534,393,535,397,531,397,529,402,525,403,521,411,517,421,493,420,477,426,470,431,465,430,462,425,437,410,431,409,423,409,406,398,405,392,403,388,398,389,397,394,389,396,385,400,386,415,383,415,382,418,377,418,375,421,373,424,349,423,347,416,343,417,338,413,327,413,320,407,321,400,319,397,319,391,314,387,315,381,317,380,319,361,321,359,321,351,317,348,316,344,312,344,307,336,311,327,313,311,306,305,305,302,320,292,327,278,336,279,344,275,347,277,350,283,355,283,367,289,373,290,375,288,382,288,391,289,396,284,408,281,424,275" href="javascript: void(0)" id="shiso_button" class="tooltip" title="Shisō (klan Ayatsuri)"/>
				</map>
			</div>
		</div>
	</main>

	<?php @require_once('footer_main.inc') ?>

</body>
</html>
