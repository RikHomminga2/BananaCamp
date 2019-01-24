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
			storeAssesmentResult();
		}
		if($request == 'getAssesmentResultForUser'){
			getAssesmentResultForUser();
		}
		
		if($request == 'addQuestion') {
			(addQuestion()) ? header('Location: add.php') : header('Location: error.php');
		}
		
		if($request == 'getQuestions') {
			getQuestions();
		}
		
		if($request == 'createExam') {
            createExam();
            header('Location: exams.php');
        }
		
		if($request == 'getExam') {
            getExam();
        }
		
		if($request == 'storeExamResult'){
			storeExamResult();	
		}
		
		if($request == 'updateUserInfo') {
			(updateUserInfo()) ? header('Location: profile.php') : header('Location: error.php');
		}
	
		if($request == 'getUserInfo') {
			getUserInfo();
		}

	} else {
		header('Location: index.php');
	}
