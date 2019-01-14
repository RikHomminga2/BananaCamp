<?php
	function connectToDatabase() {
		include 'config.php';
		return mysqli_connect($host, $username, $password, $dbname);
	}
	
	function doAssesment() {
		//db functionality 'hasTakenAssesment'
		header('Location: assesment.php');
	}
	
	function login($un, $pw) {
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT COUNT(id) AS cnt FROM users WHERE username='${un}' and password='${pw}';");
		$row = mysqli_fetch_assoc($res);
		//var_dump($row); die;
		if($row['cnt'] == '1') {
			$_SESSION['authenticated'] = true;
			doAssesment();
		} else { 
			header('Location: index.php');
		}
	}
	
	function register($un, $pw) {
		$con = connectToDatabase();
		mysqli_query($con, "INSERT INTO users (username, password) VALUES('${un}','${pw}');");
		login($un, $pw);
	}
	
	function getAssesment() {
		echo json_encode(["question" => "What is your skill level on a scale from 1 - 10", "categories" => ['html','css','js', 'php', 'sql']]);
	}
	
	function storeAssesment() {
		// $user = $_SESSION['blah'];
		$con = connectToDatabase();
		mysqli_query();
		header('Location: profile.php');
	}