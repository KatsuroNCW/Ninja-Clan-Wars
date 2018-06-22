<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Ninja Clan Wars</title>
	<?php require_once('head.inc'); ?>
</head>
<body>
<?php
require_once('core/init.php');

if(Session::exists('forum')) {
	echo '<div class="info-box">';
		echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('forum').'</div>';
	echo '</div>';
}

$user = new User();
$user_data = $user->data();
?>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>


<div class="news-panel wrapper">
	<?php
	$news = new Topic();
	$news_data = $news->newsSlider();
	if($news_data) {
		foreach ($news_data as $value) {
			echo '<a href="viewtopic.php?id='.$value['topic_id'].'" class="news" style="background: url('.imageType('style/img/topic/'.$value['topic_id']).') center;">';
				echo '<div class="news__container">';
					echo '<h2 class="news__title">'.$value['topic_name'].'</h2>';
					echo '<div class="news__info">'.$value['topic_by'].': '.dateFormat($value['post_date']).'</div>';
				echo '</div>';
			echo '</a>';
		}
	}
	?>
</div>
<script src="js/slider.js"></script>

<div class="land-switcher">
	<h2 class="land-switcher__heading">Przemieszczaj się pomiędzy krajami</h2>
	<div class="land-switcher__main">
		<div class="land-switcher__land" data-land_button_id="2"><img src="http://i.imgur.com/6GrnQDi.png"></div>
		<div class="land-switcher__land" data-land_button_id="3"><img src="http://i.imgur.com/4IWI7ol.png"></div>
		<div class="land-switcher__land" data-land_button_id="4"><img src="http://i.imgur.com/Eq12HKK.png"></div>
		<div class="land-switcher__land" data-land_button_id="5"><img src="http://i.imgur.com/RTWBNUA.png"></div>
		<div class="land-switcher__land" data-land_button_id="6"><img src="http://i.imgur.com/56SWkyN.png"></div>
		<div class="land-switcher__land" data-land_button_id="7"><img src="http://i.imgur.com/DXRTAu2.png"></div>
		<div class="land-switcher__land" data-land_button_id="8"><img src="http://i.imgur.com/YcajsSQ.png"></div>
		<div class="land-switcher__land" data-land_button_id="9"><img src="http://i.imgur.com/MkYW32w.png"></div>
		<div class="land-switcher__land" data-land_button_id="10"><img src="http://i.imgur.com/RzdeLny.png"></div>
		<div class="land-switcher__land" data-land_button_id="11"><img src="http://i.imgur.com/IyCjexO.png"></div>
		<div class="land-switcher__land" data-land_button_id="12"><img src="http://i.imgur.com/2OVaIru.png"></div>
		<div class="land-switcher__land" data-land_button_id="13"><img src="http://i.imgur.com/lyGKoru.png"></div>
		<div class="land-switcher__land" data-land_button_id="14"><img src="http://i.imgur.com/l7DWvgF.png"></div>
		<div class="land-switcher__land" data-land_button_id="15"><img src="http://i.imgur.com/iWIJSUx.png"></div>
	</div>
</div>

<main class="forum">
<?php
	$cat = new Category();
	$cat->showAll();
	foreach ($cat->data() as $category) {
		echo '<section data-land_id="'.$category->cat_id.'" class="category">';
		if(file_exists('style/img/land_icon/'.$category->cat_id.'.png')) {
			echo '<div class="category__name">'.$category->cat_name.' <img src="'.imageType('style/img/land_icon/'.$category->cat_id).'"></div>';
		} else {
			echo '<div class="category__name">'.$category->cat_name.'</div>';
		}

		$sections = new Section();
		$sections->showFromCategory($category->cat_id);
		foreach ($sections->data() as $section) {
			if($sections->lastPost($section->section_id)) {
				$last_post = $sections->lastPost($section->section_id);
				$posts = new Post();
				if($posts->showTopicInfo($last_post->post_topic)) {
					$last_post_topic = $posts->showTopicInfo($last_post->post_topic);
				}
			} else {
				$last_post = false;
			}

			if($section->section_type === 'forum' && $section->section_subsection == 0) {
				echo '<div class="section section-forum">';
					echo '<a href="viewsection.php?id='.$section->section_id.'" class="section-forum__link" style="background: url('.imageType('style/img/section_img/'.$section->section_id).')">'.$section->section_name.'</a>';
					if($last_post != false) {
						echo '<a href="viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id.'" class="section-forum__last-post"><span class="last-post-link__text">'.$last_post_topic->topic_name.'<br>'.dateFormat($last_post->post_date).' przez '.$last_post->post_by.'</span></a>';
					} else {
						echo '<div class="section-forum__last-post--none">brak postów w tym dziale</div>';
					}
				echo '</div>';
			} else if($section->section_type === 'land' && $section->section_subsection == 0) {
				echo '<div class="section section-land">';
					echo '<div class="section-land__left" style="background: url('.imageType('style/img/land_img/'.$section->section_id).');"><a href="viewsection.php?id='.$section->section_id.'" class="section-land__name"><span>'.$section->section_name.'</span></a></div>';
					echo '<div class="section-land__center"><div class="section-land__description">'.$section->section_description.'</div><a href="viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id.'" class="section-land__last-post">'.$last_post_topic->topic_name.'<br>'.dateFormat($last_post->post_date).' przez '.$last_post->post_by.'</a></div>';
					echo '<div class="section-land__right">';
						$sub_sections = new Section();
						$sub_sections->showFromCategory($category->cat_id);
						foreach ($sub_sections->data() as $sub_section) {
							if($sub_section->section_subsection == $section->section_id) {
								echo '<a href="viewsection.php?id='.$sub_section->section_id.'" class="section-land__links section-land__links--subsection">'.$sub_section->section_name.'</a>';
							}
						}
						foreach ($sections->showTopics($section->section_id) as $topic) {
							echo '<a href="viewtopic.php?id='.$topic->topic_id.'" class="section-land__links">'.$topic->topic_name.'</a>';
						}
					echo '</div>';
				echo '</div>';
			}
		}
		echo '</section>';
	}
?>
</main>
<script src="js/landSwitcher.js"></script>

<?php
if($user->isLoggedIn()) {
	echo '<section class="chatbox">';
		echo '<div class="chatbox__container">';
			echo '<h2 class="left-border-heading">Chatbox</h2>';
			echo '<embed class="chatbox__main" src="https://widgetbot.io/embed/353520842002661388/353520930238103553/0002/"/>';
			echo '<iframe class="chatbox__online" src="https://discordapp.com/widget?id=353520842002661388&theme=dark" allowtransparency="true" frameborder="0"></iframe>';
		echo '</div>';
	echo '</section>';
}
?>

<section class="last-posts">
	<div class="last-posts__box wrapper">
		<h2 class="left-border-heading">Ostatnie posty</h2>
		<?php
		$last_posts = new Post();
		foreach ($last_posts->showLastPosts() as $last_post) {
			$topic_info = $last_posts->showTopicInfo($last_post->post_topic);
			echo '<div class="last-posts__item"><p class="last-posts__date">'.dateFormat($last_post->post_date).'</p>
			<a href="viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id.'" class="last-posts__link">'.$topic_info->topic_name.' przez '.$last_post->post_by.'</a>
			</div>';
		}
		?>
	</div>
</section>

<footer class="footer footer--ads">
	<div class="footer__wrapper">
		<div class="footer__ad">
			<h2 class="left-border-heading">Toplisty</h2>
			<div class="footer__description">Pomóż w zwiększeniu popularności NCW! Klikając raz dziennie w bannery znajdujące się poniżej zwiększysz pozycje naszego forum w toplistach, dzięki którym, być może, zyskamy nowych graczy. Poświęć kilka minut i przyczyń się do zwiększenia popularności strony.</div>
			<div class="toplists">
				<div class="toplists__big">
					<a class="toplists__link toplists__link--big" href="http://top50.com.pl/"><img src="http://top50.com.pl/button.php?u=Katsuro" alt="TOP50 Gry" border="0"></a>
					<a class="toplists__link toplists__link--big" href="http://www.play4now.pl/in/685"><img src="http://www.play4now.pl/img/685/0" alt="Gry w przeglądarce"></a>
				</div>
				<div class="toplists__small">
					<a class="toplists__link" href="http://pbf-wars.topka.pl/?we=Katsuro"><img src="http://i.imgur.com/YGY5QxX.png"></a>
					<a class="toplists__link" href="http://anime-super-top.topka.pl/?we=Katsuro"><img src="http://i.imgur.com/etvMyIg.png"></a>
					<a class="toplists__link" href="http://anime-strefa.toplista.info/?we=Katsuro"><img src="http://i.imgur.com/or11yp1.png"></a>
					<a class="toplists__link" href="http://anime.toplista.pl/?we=Katsuro"><img src="http://sklep.boo.pl/banner.jpg"></a>
					<a class="toplists__link" href="http://animetop.topka.pl/?we=Katsuro"><img src="http://i.imgur.com/aKf9ATw.jpg"></a>
					<a class="toplists__link" href="http://naruto-zone.topka.pl/?we=Katsuro"><img src="http://ws2ws.info/n-z/button.jpg"></a>
					<a class="toplists__link" href="http://anime-manga.top-100.pl/?we=Katsuro"><img src="http://ws2ws.info/top/topbut.gif"></a>
					<a class="toplists__link" href="http://pon.topka.pl/?we=Katsuro"><img src="http://i.imgur.com/QI5OGsK.png"></a>
					<a class="toplists__link" href="http://naruto-world-cup.top-100.pl/?we=Katsuro"><img src="http://i44.servimg.com/u/f44/11/94/32/26/utto10.png"></a>
				</div>
			</div>
		</div>

		<div class="footer__ad">
			<h2 class="left-border-heading">Zaprzyjaźnione serwisy</h2>
			<div class ="footer__description">Chcesz umieścić swoją reklamę graficzną na naszej stronie? Jesteś zainteresowany innymi formami współpracy? Jesteśmy otwarci na wszelkie propozycje! :) Zajrzyj <a href="">tutaj</a> i dowiedz się szczegółów.</div>
			<div class="exchange">
				<a class="exchange__link" href="http://ero-senin.pl/index.php"><img src="http://ero-senin.pl/uploads/wymiana/88x31/Ero-Senin-3.png" alt="Ero Senin" title="Ero-Senin - Skanlacje mang hentai"></a>
				<a class="exchange__link" href="http://www.pokemoncrystal.jun.pl" target="_blank"><img src="http://i49.tinypic.com/2939ht3.png" border="0"></a>
				<a class="exchange__link" href="http://neomelodramatic.cba.pl/"><img src="http://i261.photobucket.com/albums/ii57/wisnia-szatana/neo/do/nmdt-baner_02.png"></a>
				<a class="exchange__link" href="http://opadventure.cba.pl/" target="_blank"><img src="http://i1281.photobucket.com/albums/a515/qulob2/Obrazeczki%20do%20klikania/Button.png" alt="One Piece Adventure PBF/RPG"/></a>
				<a class="exchange__link" href="http://hogwartdream.cba.pl/"><img src="http://i.imgur.com/C26CtJb.png"></a>
				<a class="exchange__link" href="http://www.sekretmiasta.czo.pl/"><img src="http://i.imgur.com/MyFHIOk.pngEL.png"></a>
				<a class="exchange__link" href="http://virus.forumpl.net/"><img src="http://i.imgur.com/ByLsD2g.png"></a>
				<a class="exchange__link" href="http://beyond-undertale.forumpolish.com/"><img src="http://i.imgur.com/KJIvUwb.png"></a>
				<a class="exchange__link" href="http://egodraconis.cba.pl/index.php"><img src="http://i.imgur.com/tRDCjB6.png"></a>
			</div>
		</div>
	</div>
</footer>

<footer class="footer footer--stats">
	<div class="footer__wrapper footer__wrapper--stats">
		<?php
			if($user->isLoggedIn()) {
				$forum = new Forum($user_data->user_login);
			} else {
				$forum = new Forum();
			}
		?>
		<div class="users-online">
			<h2 class="left-border-heading">Użytkownicy online</h2>
			<div class="users-online__box">
				<?php
				$online_list = $forum->getOnlineList();
				if(count($online_list)) {
					foreach ($online_list as $online_login => $online_group_color) {
						echo '<p class="users-online__item" style="color: #'.$online_group_color.';"><a href="profile.php?id='.$online_login.'">'.$online_login.'</a></p>';
					}
				} else {
					echo '<p class="users-online__item users-online__item--nouser">Żaden użytkownik nie przegląda forum w tej chwili.</p>';
				}

				?>
			</div>
		</div>

		<div class="statistics">
			<h2 class="left-border-heading">Statystyki forum</h2>
			<div class="statistics__item">
				<p class="statistics__item-box statistics__item-box--name">Wszystkie posty</p>
				<p class="statistics__item-box statistics__item-box--value"><?php echo $forum->allPosts() ?></p>
			</div>

			<div class="statistics__item">
				<p class="statistics__item-box statistics__item-box--name">Wszystkie tematy</p>
				<p class="statistics__item-box statistics__item-box--value"><?php echo $forum->allTopics() ?></p>
			</div>

			<div class="statistics__item">
				<p class="statistics__item-box statistics__item-box--name">Wszyscy użytkownicy</p>
				<p class="statistics__item-box statistics__item-box--value"><?php echo $forum->allUsers() ?></p>
			</div>

			<div class="statistics__item">
				<p class="statistics__item-box statistics__item-box--name">Użytkownicy online</p>
				<p class="statistics__item-box statistics__item-box--value"><?php echo $forum->usersOnline() ?></p>
			</div>

			<div class="statistics__item">
				<p class="statistics__item-box statistics__item-box--name">Aktywni gracze</p>
				<p class="statistics__item-box statistics__item-box--value">20</p>
			</div>

			<div class="statistics__item">
				<p class="statistics__item-box statistics__item-box--name">Goście online</p>
				<p class="statistics__item-box statistics__item-box--value"><?php echo $forum->guestsOnline() ?></p>
			</div>

			<div class="statistics__item statistics__item--latest-user">
				<p>Najnowszy użytkownik</p>
				<a href="profile.php?id=<?php echo $forum->latestUser() ?>"><?php echo $forum->latestUser() ?></a>
				<p>Witamy na Ninja Clan Wars!</p>
			</div>
		</div>
	</div>
</footer>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
