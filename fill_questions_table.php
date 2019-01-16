<?php
	require 'php/functions.php';
	$f = fopen('assets/questions.txt', 'r') or die('failed to open files');
	while(!feof($f)) {
		list($q, $a1, $a2, $a3, $a4) = explode(',', fgets($f));
		addQuestion($q, [$a1, $a2, $a3, $a4]);
	}