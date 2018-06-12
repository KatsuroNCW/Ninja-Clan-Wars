<?php

require_once('core/init.php');

$user = new User();
$user_data = $user->data();
$group = new Group();

if(Input::get('page')) {
	$page = Input::get('page');
} else {
	$page = 1;
}

if(Input::exists('post', 'submit_search')) {
	$userlists = $user->getUserlist($page, Input::get('search_login'), Input::get('search_group'), Input::get('sort_by'), Input::get('order_by'));
} elseif(Input::exists('post', 'reset_search')) {
	$userlists = $user->getUserlist($page);
} else {
	$userlists = $user->getUserlist($page);
}
$total_pages = $user->totalPages();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Lista użytkowników</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
?>

<div class="userlists">
	<h2 class="big-heading">Lista użytkowników</h2>
	<form method="post" class="form">
		<div class="userlists__options">
			<div class="userlists__box">
				<h2 class="form__heading">Login</h2>
				<input type="text" name="search_login" placeholder="Podaj login" value="<?php echo Input::get('search_login') ?>" class="form__input">
			</div>

			<div class="userlists__box">
				<h2 class="form__heading">Grupa</h2>
				<select name="search_group" class="form__input form__input--select">
					<option value="%">Wszystkie grupy</option>
					<?php
					foreach ($group->getList() as $group_name) {
						if(Input::get('search_group') === $group_name) {
							echo '<option value="'.$group_name.'" selected>'.$group_name.'</option>';
						} else {
							echo '<option value="'.$group_name.'">'.$group_name.'</option>';
						}
					}
					?>
				</select>
			</div>

			<div class="userlists__box">
				<h2 class="form__heading">Sortuj przez</h2>
				<select name="sort_by" class="form__input form__input--select">
					<?php
					foreach (User::userSortBy() as $key => $value) {
						if(Input::get('sort_by') === $value) {
							echo '<option value="'.$value.'" selected>'.$key.'</option>';
						} else {
							echo '<option value="'.$value.'">'.$key.'</option>';
						}
					}
					?>
				</select>
			</div>

			<div class="userlists__box">
				<h2 class="form__heading">Kolejność</h2>
				<select name="order_by" class="form__input form__input--select">
					<?php
					foreach (User::userOrderBy() as $key => $value) {
						if(Input::get('order_by') === $value) {
							echo '<option value="'.$value.'" selected>'.$key.'</option>';
						} else {
							echo '<option value="'.$value.'">'.$key.'</option>';
						}
					}
					?>
				</select>
			</div>
		</div>

		<input type="submit" value="Szukaj" name="submit_search" class="form__button form__button--bigger form__button--first">
		<input type="submit" value="Resetuj" name="reset_search" class="form__button form__button--bigger">
	</form>

	<div class="pagination">
	<?php
		if($total_pages != 0) {
			if($page != 1) {
				echo '<a class="pagination__link" href="?page=1" title="Przejdź do pierwszej strony"><<</a>';
			}
			if($total_pages <= 5) {
				for($i = 1; $i <= $total_pages; $i++) {
					if($page == $i) {
						echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
					} else {
						echo '<a class="pagination__link" href="?page='.$i.'"> '.$i.' </a>';
					}
				}
			} else {
				for($i = $page-2; $i < $page; $i++) {
					if($i > 0) {
						echo '<a class="pagination__link" href="?page='.$i.'"> '.$i.' </a>';
					}
				}
				echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
				for($i = $page+1; $i < $page+3; $i++) {
					if($i <= $total_pages) {
						echo '<a class="pagination__link" href="?id='.$id.'&page='.$i.'"> '.$i.' </a>';
					}
				}
			}
			if($page != $total_pages) {
				echo '<a class="pagination__link" href="?page='.$total_pages.'" title="Przejdź do ostatniej strony">>></a>';
			}
		}
	?>
	</div>

	<div class="userlists__list">
		<div class="user-item user-item--heading">
			<div class="user-item__block user-item__block--heading user-item__block--login">Login</div>
			<div class="user-item__block user-item__block--heading user-item__block--groups">Grupy</div>
			<div class="user-item__block user-item__block--heading user-item__block--posts">Liczba postów</div>
			<div class="user-item__block user-item__block--heading user-item__block--register-date">Data rejestracji</div>
		</div>
	<?php
	if(empty($userlists)) {
		echo 'Nie znaleziono żadnego pasującego wyniku!';
	} else {
		foreach ($userlists as $value) {
			$user_groups = $user->showGroups($value[0]);
			echo '<div class="user-item">';
				echo '<div class="user-item__block user-item__block--login"><a href="profile.php?id='.$value[0].'" class="user-item__link">';
					if($user->isOnline($value[1])) {
						echo '<span class="user-item__status user-item__status--online" title="online"></span>';
					} else {
						echo '<span class="user-item__status user-item__status--offline" title="offline"></span>';
					}
				echo $value[1].'</a></div>';
				echo '<div class="user-item__block user-item__block--groups">';
					$tmp = count($user_groups);
					$x = 1;
					foreach ($user_groups as $group_name => $group_color) {
						echo '<span style="color: #'.$group_color.'">'.$group_name.'</span>';
						if($x < $tmp) {
							echo ', ';
							$x++;
						}
					}
				echo '</div>';
				echo '<div class="user-item__block user-item__block--posts">'.$value[3].'</div>';
				echo '<div class="user-item__block user-item__block--register-date">'.dateFormat($value[2]).'</div>';
			echo '</div>';
		}
	}
	?>
	</div>

	<div class="pagination">
	<?php
		if($total_pages != 0) {
			if($page != 1) {
				echo '<a class="pagination__link" href="?page=1" title="Przejdź do pierwszej strony"><<</a>';
			}
			if($total_pages <= 5) {
				for($i = 1; $i <= $total_pages; $i++) {
					if($page == $i) {
						echo '<a class="pagination__link  pagination__link--active" href="javascript: void(0)">'.$i.'</a>';
					} else {
						echo '<a class="pagination__link" href="?page='.$i.'"> '.$i.' </a>';
					}
				}
			} else {
				for($i = $page-2; $i < $page; $i++) {
					if($i > 0) {
						echo '<a class="pagination__link" href="?page='.$i.'"> '.$i.' </a>';
					}
				}
				echo '<a class="pagination__link pagination__link--active" href="javascript: void(0)">'.$page.'</a>';
				for($i = $page+1; $i < $page+3; $i++) {
					if($i <= $total_pages) {
						echo '<a class="pagination__link" href="?id='.$id.'&page='.$i.'"> '.$i.' </a>';
					}
				}
			}
			if($page != $total_pages) {
				echo '<a class="pagination__link" href="?page='.$total_pages.'" title="Przejdź do ostatniej strony">>></a>';
			}
		}
	?>
	</div>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
