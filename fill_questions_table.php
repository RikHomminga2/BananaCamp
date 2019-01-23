<?php
	require 'php/functions.php';
	$f = fopen('assets/questions.txt', 'r') or die('failed to open files');
	while(!feof($f)) {
		list($q, $a1, $a2, $a3, $a4, $cat) = explode(',', fgets($f));
		$_POST['question'] = $q;
		$_POST['answer1'] = $a1;
		$_POST['answer2'] = $a2;
		$_POST['answer3'] = $a3;
		$_POST['answer4'] = $a4;
		$_POST['category'] = $cat;
		addQuestion($q, [$a1, $a2, $a3, $a4], $cat);
	}