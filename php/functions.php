<?php
	function connectToDatabase() {
	include 'config.php';
	return mysqli_connect($host, $username, $password, $dbname);
	}
	
	function doAssesment() {
		//db functionality 'hasTakenAssesment'
		header('Location: assesment.php');
	}
	
	function register($email, $password) {
		$con = connectToDatabase();
		mysqli_query($con, "INSERT INTO users (email, password) VALUES('${email}','${password}');");
		login($email, $password);
	}

	function login($email, $password) {
			$con = connectToDatabase();
			$res = mysqli_query($con, "SELECT COUNT(id) AS cnt FROM users WHERE email='${email}' and password='${password}';");
			$row = mysqli_fetch_assoc($res);
			
			if($row['cnt'] == '1') {
				$_SESSION['authenticated'] = true;
				doAssesment();
			} else { 
				header('Location: index.php');
			}
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