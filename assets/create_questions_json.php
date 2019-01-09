<?php
	$stager = array();
	$f = fopen('questions.txt', 'r');
	while(!feof($f)) {
		array_push($stager, explode(',', fgets($f)));
	}
	echo json_encode($stager);
		
		