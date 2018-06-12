<header class="header">
	<div class="header-main">
		<div class="header-main__logotype"><a href="forum.php"><img src="style/img/logotype.png"></a></div>
		<div class="header-user-info">
			<?php
			if(!$user->isLoggedIn()) {
				echo '<a href="login.php" class="header-user-info__link"><i class="header-user-info__icon icon-login"></i> Zaloguj</a>';
				echo '<a href="register.php" class="header-user-info__link"><i class="header-user-info__icon icon-user-add"></i> Zarejestruj</a>';
			} else {
				echo '<a href="kp.php" class="header-user-info__login"><img src="'.imageType('style/img/clan_symbols/senju').'"> '.$user_data->user_login.'</a>';
			}
			?>
		</div>
	</div>

	<nav class="main-navigation">
		<div class="mobile-user-info">
		<?php
			if($user->isLoggedIn()) {
				echo '<a href="profile.php?id=<?php echo $user_data->user_login ?>" class="mobile-user-info__block"><img src="'.imageType('style/img/clan_symbols/senju').'">'.$user_data->user_login.'</a>';
			} else {
				echo '<a href="login.php" class="mobile-user-info__link"><i class="icon-login mobile-user-info__icon"></i> Zaloguj</a>';
				echo '<a href="register.php" class="mobile-user-info__link"><i class="icon-user-add mobile-user-info__icon"></i> Zarejestruj</a>';
			}
		?>
		</div>

		<ul class="menu">
			<li class="menu__item"><a href="index.php">Portal</a></li>
			<li class="menu__item"><a href="forum.php">Forum</a></li>
			<li class="menu__item has-submenu"><a href="#">Baza opisów <span class="menu__item-arrow"></span></a>
				<ul class="submenu">
					<li class="submenu__item"><a href="maps.php">Mapy świata</a></li>
					<li class="submenu__item"><a href="#">Profesje</a></li>
					<li class="submenu__item"><a href="#">Style walki</a></li>
					<li class="submenu__item"><a href="#">Żywioły chakry</a></li>
					<li class="submenu__item"><a href="#">Specjalizacje</a></li>
					<li class="submenu__item"><a href="#">Jutsu</a></li>
					<li class="submenu__item"><a href="#">Przedmioty</a></li>
					<li class="submenu__item"><a href="#">Umiejętności</a></li>
					<li class="submenu__item"><a href="#">Baza</a></li>
					<li class="submenu__item"><a href="#">Summony</a></li>
				</ul>
			</li>
			<li class="menu__item"><a href="kplist.php">Karty postaci</a></li>
			<li class="menu__item has-submenu"><a href="#">Opcje forum <span class="menu__item-arrow"></span></a>
				<ul class="submenu">
					<li class="submenu__item"><a href="userlist.php">Lista użytkowników</a></li>
					<li class="submenu__item"><a href="promotion.php">Materiały promocyjne</a></li>
				</ul>
			</li>
			<li class="menu__item"><a href="contact.php">Kontakt</a></li>
			<?php
			if($user->isLoggedIn()) {
				echo '<li class="menu__item has-submenu"><a href="javascript: void(0)">Profil <span class="menu__item-arrow"></span></a>';
					echo '<ul class="submenu">';
						echo '<li class="submenu__item"><a href="profile.php?id='.$user_data->user_login.'">Zobacz profil</a></li>';
						if($user->hasPermission('root')) {
							echo '<li class="submenu__item"><a href="admin_panel.php">Admin panel</a></li>';
					    }
					echo '</ul>';
				echo '</li>';
				echo '<li class="menu__item"><a href="logout.php">Wyloguj</a></li>';
			}
			?>
		</ul>
	</nav>
</header>

<div class="page-up" title="Przewiń do góry">
	<i class="page-up__icon icon-up-open"></i>
</div>
