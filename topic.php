<?php

require_once('core/init.php');

if(!$section_id = Input::get('id')) {
	Redirect::to(404);
} else {
	$section = new Section($section_id);
	$user = new User();
	if(!$user->isLoggedIn()) {
		Redirect::to(404);
	} else {
		$user_data = $user->data();
		$section_data = $section->data();
	}
}

if(Input::exists('post', "submit_topic")) {
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'topic_name' => array(
			'name' => 'nazwa tematu',
			'required' => true
		),
		'post_contents' => array(
			'name' => 'treść posta',
			'required' => true
		)
	));

	$validate2 = new Validate();
	$validation2 = $validate2->check($_FILES, array(
		'topic_img' => array(
			'name' => 'grafika tematu',
			'img_verify' => true,
			'img_types' => true,
			'img_width' => 1000,
			'img_height' => 300
		)
	));

	if($validation->passed() && $validation2->passed()) {
		$post = new Post();
		if(Input::get('post_type') == 1) {
			$post_type = 1;
		} else {
			$post_type = 0;
		}
		$user_total_posts = intval($user_data->user_total_posts) + 1;

		try {
			$topic = new Topic();
			$topic->create(array(
				'topic_by' => $user_data->user_login,
				'topic_section' => $section_id,
				'topic_name' => Input::get('topic_name')
			));

			$last_topic = $section->lastTopic($section_id);

			$post->create(array(
				'post_topic' => $last_topic->topic_id,
				'post_section' => $section_id,
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

			if($validation2->passed()) {
				move_uploaded_file($_FILES['topic_img']['tmp_name'], 'style/img/topic/'.$last_topic->topic_id.'.'.substr($_FILES['topic_img']['type'], 6));
			}

			Session::flash('viewtopic', 'Pomyślnie dodano nowy temat!');
			Redirect::to('viewtopic.php?id='.$last_topic->topic_id);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Tworzenie teamtu</title>
	<?php require_once('head.inc'); ?>
</head>
<body>

<?php
	require_once('header.inc.php');
	require_once('mobile_menu.inc');
	
if(Input::exists('post', "submit_preview")) {
	echo '<section class="post-preview wrapper">';
		echo '<h2 class="left-border-heading">Podgląd posta</h2>';
		echo '<div class="post">';
			if(Input::get('post_type') != 1) {
				echo '<div class="post__left">';
					echo '<a class="post__info" href="profile.php?id='.$user_data->user_login.'">'.$user_data->user_login.'</a>';
					echo '<div class="post__avatar"><img src="'.imageType('style/img/avatars/'.$user_data->avatar).'"></div>';
					foreach ($user->showGroups($user_data->user_id) as $group_name => $group_color) {
						echo '<div class="post__rang" style="color: #'.$group_color.'">'.$group_name.'</div>';
					}
				echo '</div>';
			}
			if(Input::get('post_type') != 1) {
				echo '<div class="post__right">';
			} else {
				echo '<div class="post__center">';
			}

				echo '<div class="post__contents">'.BbcodeParser::get(Input::get('post_contents')).'</div>';
				if(Input::get('post_hide') != '') {
					echo '<div class="post__contents post__contents--hide"><h2 class="hide-header">Ukryta wiadomość:</h2>'.BbcodeParser::get(Input::get('post_hide')).'</div>';
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
}
?>

<main class="post-create wrapper">
	<form method="post" enctype="multipart/form-data" class="form">
		<h2 class="left-border-heading">Tworzenie nowego tematu</h2>
		<?php
		if(Input::exists('post', "submit_topic")) {
			if(!$validation->passed() || !$validation2->passed()) {
				echo '<div class="info-box">';
				foreach($validation->errors() as $error) {
					echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
				}
				foreach($validation2->errors() as $error) {
					echo '<p class="info-box__item info-box__item--error"><i class="info-box__icon icon-attention"></i> '.$error .'</p>';
				}
				echo '</div>';
			}
		}
		?>
		<h3 class="form__heading">Nazwa tematu</h3>
		<input type="text" class="form__input" name="topic_name" placeholder="Nazwa tematu" value="<?php echo Input::get('topic_name'); ?>">
		<h3 class="form__heading">Treść posta</h3>
		<div class="textarea-panel">
			<?php include('bbcode-panel.php'); ?>
			<textarea id="bbcode-menu" class="form__input form__input--textarea" name="post_contents" placeholder="Treść posta"><?php echo HtmlParser::get(Input::get('post_contents')); ?></textarea>
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
		<h3 class="form__heading">Grafika tematu</h3>
		<input type="file" name="topic_img" class="form__input">
		<input type="submit" value="Stwórz temat" name="submit_topic" class="form__button">
		<input type="submit" value="Podgląd" name="submit_preview" class="form__button">
		<a href="javascript:history.go(-1)" class="form__button">Powrót</a>
	</form>
</main>

<?php @require_once('footer_main.inc') ?>

</body>
</html>
