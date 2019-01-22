<?php
	session_start();
	require_once('php/functions.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = isset($_POST['request']) ? $_POST['request'] : false;
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;

		if($request == 'login') {
			login() ? header('Location: profile.php') : header('Location: index.php');
		}
		if($request == 'register') {
			register() ? header('Location: profile.php') : header('Location: index.php');
		}
		
		if($request == 'addAssesment') {
			(addAssesment()) ? header('Location: add.php') : header('Location: error.php');
		}
			
		if($request == 'getAssesment') {
			getAssesment();
		}
		
		if($request == 'storeAssesmentResult') {
			$id = $_POST['id'];
			$result = $_POST['result'];
			storeAssesmentResult($id, $result);
			header('Location: profile.php');
		}
		if($request == 'getUserResultsAssesments'){
			getUserResultsAssesments();
		}
		
		if($request == 'addQuestion') {
			(addQuestion()) ? header('Location: add.php') : header('Location: error.php');
		}
		
		if($request == 'getQuestions') {
			getQuestions();
		}
		
		if($request == 'createExam') {
            createExam();
            header('Location: exams.html');
        }
		
		if($request == 'getExam') {
            getExam();
        }
		
	} else {
		header('Location: index.php');
	}