<?php
	session_start();
	require_once('php/functions.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = isset($_POST['request']) ? $_POST['request'] : false;
		$un = isset($_POST['username']) ? $_POST['username'] : false;
		$pw = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;
		
		if($request == 'login') {
			($un && $pw) ? login($un, $pw): header('Location: index.php');
		}
		
		if($request == 'register') {
			($un && $pw) ? register($un, $pw) : header('Location: index.php');
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