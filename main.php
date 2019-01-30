<?php
	session_start();
	require_once('php/functions.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = isset($_POST['request']) ? $_POST['request'] : false;
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;

		if($request == 'login') {
			if(login()){
				if($_SESSION['role'] == 'trainer'){
					header('Location: trainer.php');
				}else{
					header('Location: profile.php');	
				}	
			}else{
				header('Location: index.php');
			}
		}
		
		if($request == 'logout') {
			logout();
		}
		
		if($request == 'register') {
			register() ? header('Location: profile.php') : header('Location: index.php');
		}
		
		if($request == 'getAllAssesments'){
			getAllAssesments();
		}
		
		if($request == 'addAssesment') {
			(addAssesment()) ? header('Location: trainer.php') : header('Location: error.php');
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
			(addQuestion()) ? header('Location: trainer.php') : header('Location: error.php');
		}
		
		if($request == 'getQuestions') {
			getQuestions();
		}
		
		if($request == 'createExam') {
            createExam();
            header('Location: trainer.php');
        }
		
		if($request == 'getExam') {
			$exam_id = $_POST['id'];
            getExam($exam_id);
        }
		
		if($request == 'getHtmlExams'){
			getHtmlExams();
		}
		
		if($request == 'getCssExams'){
			getCssExams();
		}
		
		if($request == 'getJavascriptExams'){
			getJavascriptExams();
		}
		
		if($request == 'getPhpExams'){
			getPhpExams();
		}
		
		if($request == 'getSqlExams'){
			getSqlExams();
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
