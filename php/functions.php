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
	
	function storeAssesment($result) {
		// $user = $_SESSION['blah'];
		$con = connectToDatabase();
		$result = json_encode($result);
		//mysqli_query($con, "INSERT INTO users (username, password) VALUES('${result}','${result}');");
		header('Location: profile.php');
	}
	
	function addQuestion($q, $arr) {
		$a = json_encode($arr);
		$con = connectToDatabase();
		mysqli_query($con, "INSERT INTO questions (question, answers) VALUES ('${q}','${a}');");
		header('Location: add.php');
	}
	
	function getQuestions() {
		$stager = [];
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT * FROM questions ORDER BY id DESC");
		while($row = mysqli_fetch_assoc($res)) {
			$stager[] = ["id" => $row['id'], "question" => $row['question'], "answers" => json_decode($row['answers'])];
		}
		echo json_encode($stager);
	}