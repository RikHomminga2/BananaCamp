<?php
	session_start();
	require_once('php/functions.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = isset($_POST['request']) ? $_POST['request'] : false;
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;

		if($request == 'login') {
			($email && $password) ? login($email, $password): header('Location: index.php');
		}
		if($request == 'register') {
			($email && $password) ? register($email, $password) : header('Location: index.php');
		}
	
		
		if($request == 'getAssesment') {
			getAssesment();
		}
		
		if($request == 'storeAssesment') {
			$result = $_POST['result'];
			storeAssesment($result);
		}
		
		if($request == 'addQuestion') {
			$question = $_POST['question'];
			$answer1 = $_POST['answer1'];
			$answer2 = $_POST['answer2'];
			$answer3 = $_POST['answer3'];
			$answer4 = $_POST['answer4'];
			addQuestion($question, [$answer1, $answer2, $answer3, $answer4]);
		}
		
		if($request == 'getQuestions') {
			getQuestions();
		}
		
	} else {
		header('Location: index.php');
	}