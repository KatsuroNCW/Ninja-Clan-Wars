<?php

function imageType($path) {
	//return pathinfo($path, PATHINFO_EXTENSION);

	$types = array('jpg', 'gif', 'png', 'jpeg');
	foreach ($types as $type) {
		$file = $path.'.'.$type;
		if(file_exists($file)) {
			return $file;
		}
	}
	return false;
}