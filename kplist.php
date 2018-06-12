<?php
require_once('core/init.php');

$user = new User();
$user_data = $user->data();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Lista kart postaci</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<section class="wrapper">
<?php
	$kp = new KP();
	$kp_list = $kp->getKpList();

	$kp_active_players = [];
	$kp_inactive_players = [];
	$kp_active_npcs = [];
	$kp_new_list = [];

	foreach ($kp_list as $single_kp) {
		if($single_kp->kp_status === 'active') {
			if($single_kp->kp_type === 'gracz') {
				$kp_active_players[] = $single_kp;
			} elseif (strtolower($single_kp->kp_type) === 'npc') {
				$kp_active_npcs[] = $single_kp;
			}
		} elseif ($single_kp->kp_status === 'inactive') {
			$kp_inactive_players[] = $single_kp;
		} elseif ($single_kp->kp_status === 'new') {
			$kp_new_list[] = $single_kp;
		}
	}

	if($user->isLoggedIn() && $user->hasPermission('root')) {
		echo '<h2 class="big-heading">Lista kart postaci do zaakceptowania</h2>';
		if (empty($kp_new_list)) {
			echo '<div class="info-box__item info-box__item--error">';
				echo '<i class="info-box__icon icon-attention"></i>';
				echo '<p class="info-box__description">W tej chwili nie ma kart postaci do zaakceptowania.</p>';
			echo '</div>';
		} else {
			foreach ($kp_new_list as $kp_new) {
				echo '<br>Karta postaci <a href="kp.php?kp_id='.$kp_new->kp_id.'">'.$kp_new->kp_name.'</a>';
			}
		}
	}

	echo '<h2 class="big-heading">Lista aktywnych kart postaci</h2>';
	if (empty($kp_active_players)) {
		echo '<div class="info-box__item info-box__item--error">';
			echo '<i class="info-box__icon icon-attention"></i>';
			echo '<p class="info-box__description">W tej chwili nie ma aktywnych kart postaci graczy.</p>';
		echo '</div>';
	} else {
		foreach ($kp_active_players as $active_kp) {
			echo '<br>Karta postaci <a href="kp.php?kp_id='.$active_kp->kp_id.'">'.$active_kp->kp_name.'</a>';
		}
	}

	echo '<h2 class="big-heading">Lista aktywnych NPC</h2>';
	if (empty($kp_active_npcs)) {
		echo '<div class="info-box__item info-box__item--error">';
			echo '<i class="info-box__icon icon-attention"></i>';
			echo '<p class="info-box__description">W tej chwili nie ma aktynych kart postaci NPC.</p>';
		echo '</div>';
	} else {
		foreach ($kp_active_npcs as $npc_kp) {
			echo '<br>Karta postaci <a href="kp.php?kp_id='.$npc_kp->kp_id.'">'.$npc_kp->kp_name.'</a>';
		}
	}

	if($user->isLoggedIn() && $user->hasPermission('root')) {
		echo '<h2 class="big-heading">Lista nieaktywnych kart postaci</h2>';
		if (empty($kp_inactive_players)) {
			echo '<div class="info-box__item info-box__item--error">';
				echo '<i class="info-box__icon icon-attention"></i>';
				echo '<p class="info-box__description">W tej chwili nie ma niekaktywnych KP.</p>';
			echo '</div>';
		} else {
			foreach ($kp_inactive_players as $inactive_kp) {
				echo '<br>Karta postaci <a href="kp.php?kp_id='.$inactive_kp->kp_id.'">'.$inactive_kp->kp_name.'</a>';
			}
		}
	}
?>
</section>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
