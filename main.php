<?php
	session_start();
	require_once('php/functions.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = isset($_POST['request']) ? $_POST['request'] : false;
		$emailadres = isset($_POST['emailadres']) ? $_POST['emailadres'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;


		if($request == 'login') {
			($emailadres && $password) ? login($emailadres, $password): header('Location: index.php');
		}
		if($request == 'register') {
			($emailadres && $password) ? register($emailadres, $password) : header('Location: index.php');
		}
	
		
		if($request == 'getAssesment') {
			getAssesment();
		}
		
		if($request == 'storeAssesment') {
			storeAssesment();
		}
	} else {
		header('Location: index.php');
	}