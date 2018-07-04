<?php

function dateFormat($date) {
	$names_of_months = array(
		'01' => 'stycznia',
		'02' => 'lutego',
		'03' => 'marca',
		'04' => 'kwietnia',
		'05' => 'maja',
		'06' => 'czerwca',
		'07' => 'lipca',
		'08' => 'sierpnia',
		'09' => 'września',
		'10' => 'października',
		'11' => 'listopada',
		'12' => 'grudnia'
	);

	if(substr($date, 0, 10) == date('Y-m-d')) {
		return "Dzisiaj, ".substr($date, 10);
	} else if(substr($date, 0, 10) == date("Y-m-d", strtotime("-1 day"))) {
		return "Wczoraj, ".substr($date, 10);
	} else {
		$year = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$day = substr($date, 8, 2);
		$full_date = $day.' '.$names_of_months[$month];
		if($year !== date('Y')) {
			$full_date .= ' '.$year;
		}
		if(!empty(substr($date, 10))) {
			$full_date .= ', '.substr($date, 10);
		}
		return $full_date;
	}
}
