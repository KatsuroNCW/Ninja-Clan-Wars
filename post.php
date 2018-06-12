<?php

require_once('core/init.php');

if(!$topic_id = Input::get('id')) {
	Redirect::to(404);
} else {
	$topic = new Topic($topic_id);
	$user = new User();
	if(!$user->isLoggedIn()) {
		Redirect::to(404);
	} else {
		$user_data = $user->data();
		$topic_data = $topic->data();
	}
}

$quote_id = Input::get('qid');
$quote_post = new Post($quote_id);
if($quote_post->exists()) {
	$quote_data = $quote_post->data();
	$post_quote = '[quote='.$quote_data->post_by.']'.$quote_data->post_contents.'[/quote]';
}

if(Input::exists('post', "submit_post")) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'post_contents' => array(
			'name' => 'treść posta',
			'required' => true
		)
	));

	if($validation->passed()) {
		$post = new Post();
		if(Input::get('post_type') == 1) {
			$post_type = 1;
		} else {
			$post_type = 0;
		}
		$user_total_posts = intval($user_data->user_total_posts) + 1;

		try {
			$post->create(array(
				'post_topic' => $topic_id,
				'post_section' => $topic_data->topic_section,
				'post_by' => $user_data->user_login,
				'post_by_id' => $user_data->user_id,
				'post_contents' => BbcodeParser::get(Input::get('post_contents')),
				'post_hide' => BbcodeParser::get(Input::get('post_hide')),
				'post_date' => date('Y-m-d H:i:s'),
				'post_type' => $post_type
			));

			$user->update($user_data->user_id, array(
				'user_total_posts' => $user_total_posts
			));

			$last_post = $topic->lastPost($topic_id);
			Session::flash('viewtopic', 'Pomyślnie dodano nowego posta!');
			Redirect::to('viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', "submit_save_draft")) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'post_contents' => array(
			'name' => 'treść posta',
			'required' => true
		)
	));

	if($validation->passed()) {
		try {
			$user->update($user_data->user_id, array(
				'user_draft' => BbcodeParser::get(Input::get('post_contents'))
			));

			$last_post = $topic->lastPost($topic_id);
			Session::flash('viewtopic', 'Pomyślnie zapisano szkic posta!');
			Redirect::to('viewtopic.php?pid='.$last_post->post_id.'#p'.$last_post->post_id);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}

if(Input::exists('post', "submit_load_draft")) {
	Session::flash('post', 'Pomyślnie wczytano zapisany szkic!');
}

if(Input::exists('post', "submit_delete_draft")) {
	try {
		$user->update($user_data->user_id, array(
			'user_draft' => ''
		));
	} catch(Exception $e) {
		die($e->getMessage());
	}

	Session::flash('post', 'Pomyślnie skasowano zapisany szkic!');
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Tworzenie posta</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');

echo '<div class="info-box">';
if(Session::exists('post')) {
	echo '<div class="info-box__item info-box__item--confirmation"><i class="info-box__icon icon-check"></i> '.Session::flash('post').'</div>';
}
echo '</div>';

if(Input::exists('post', "submit_preview")) {
	echo '<section class="post-preview wrapper">';
		echo '<h2 class="left-border-heading">Podgląd posta</h2>';
		echo '<div class="post">';
			if(Input::get('post_type') != 1) {
				echo '<div class="post-left">';
					echo '<a class="post-left__author" href="profile.php?id='.$user_data->user_login.'">'.$user_data->user_login.'</a>';
					echo '<div class="post-left__avatar"><img src="'.imageType('style/img/avatars/'.$user_data->avatar).'"></div>';
					foreach ($user->showGroups($user_data->user_id) as $group_name => $group_color) {
						echo '<div class="post-left__rang" style="color: #'.$group_color.'">'.$group_name.'</div>';
					}
				echo '</div>';
			}
			if(Input::get('post_type') != 1) {
				echo '<div class="post-right">';
			} else {
				echo '<div class="post-center">';
			}

				echo '<div class="post-right__contents">'.BbcodeParser::get(Input::get('post_contents')).'</div>';
				if(Input::get('post_hide') != '') {
					echo '<div class="post-right__contents post-right__contents--hide"><h2 class="post-right__contents--hide-header">Ukryta wiadomość:</h2>'.BbcodeParser::get(Input::get('post_hide')).'</div>';
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
}
?>

<main class="wrapper post-create">
	<form method="post" class="form">
		<h2 class="left-border-heading"><?php echo $topic_data->topic_name ?>: napisz odpowiedź</h2>
		<?php
		if(Input::exists('post', "submit_post") || Input::exists('post', "submit_save_draft")) {
			if(!$validation->passed()) {
				echo '<div class="info-box">';
				foreach($validation->errors() as $error) {
					echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
				}
				echo '</div>';
			}
		}
		?>
		<h3 class="form__heading">Treść posta</h3>
		<div class="textarea-panel">
			<?php include('bbcode-panel.php'); ?>
			<textarea class="form__input form__input--textarea" name="post_contents" placeholder="Treść posta"><?php if($quote_post->exists()) { echo HtmlParser::get($post_quote); } if(Input::exists('post', "submit_load_draft")) { echo HtmlParser::get($user_data->user_draft); } else { echo HtmlParser::get(Input::get('post_contents')); } ?></textarea>
		</div>


		<h3 class="form__heading">Ukryta wiadomość (opcjonalna)</h3>
		<div class="textarea-panel">
			<?php include('bbcode-panel.php'); ?>
			<textarea class="form__input form__input--textarea" name="post_hide" placeholder="Ukryta wiadomość"><?php echo HtmlParser::get(Input::get('post_hide')); ?></textarea>
		</div>
		<?php
			if($user->hasPermission('root')) {
				echo '<label class="form__button form__button--first"><input type="checkbox" name="post_type" class="form__button-checkbox" value="1"> Włącz tryb mechaniki</label>';
			}
		?>
		<input type="submit" value="Wyślij posta" name="submit_post" class="form__button">
		<input type="submit" value="Podgląd" name="submit_preview" class="form__button">
		<input type="submit" value="Zapisz szkic" name="submit_save_draft" class="form__button">
		<?php
		if($user_data->user_draft != null || $user_data->user_draft != '') {
			echo '<input type="submit" value="Wczytaj szkic" name="submit_load_draft" class="form__button"> ';
			echo '<input type="submit" value="Usuń zapisany szkic" name="submit_delete_draft" class="form__button">';
		}
		?>
		<a href="javascript:history.go(-1)" class="form__button">Powrót</a>
	</form>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
