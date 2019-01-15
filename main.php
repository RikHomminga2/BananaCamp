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
			storeAssesment();
		}
	} else {
		header('Location: index.php');
	}