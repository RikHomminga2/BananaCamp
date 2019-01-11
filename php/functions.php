<?php
	function connectToDatabase() {
		include 'config.php';
		return mysqli_connect($host, $username, $password, $dbname);
	}
	
	function login($un, $pw) {
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT COUNT(id) AS cnt FROM users WHERE username='${un}' and password='${pw}';");
		$row = mysqli_fetch_assoc($res);
		($row['cnt'] == '1') ? header('Location: profile.php') : header('Location: index.php');
	}
	
	function register($un, $pw) {
		$con = connectToDatabase();
		mysqli_query($con, "INSERT INTO users (username, password) VALUES('${un}','${pw}');");
		login($un, $pw);
	}