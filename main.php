<?php
	session_start();
	require_once('php/functions.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = $_POST['request'];
		$un = $_POST['username'];
		$pw = hash('sha512', $_POST['password']);
		if($request == 'login') {
			login($un, $pw);
		}
		if($request == 'register') {
			register($un, $pw);
		}
		if($request == 'getAssesment') {
			getAssesment();
		}
	}