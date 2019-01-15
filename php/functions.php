<?php
	
	function doAssesment() {
		//db functionality 'hasTakenAssesment'
		header('Location: assesment.php');
	}
	
	function register($emailadres, $password) {
		$con = connectToDatabase();
		mysqli_query($con, "INSERT INTO users (emailadres, password) VALUES('${emailadres}','${password}');");
		login($emailadres, $password);
	}



function login($emailadres, $password) {
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT COUNT(id) AS cnt FROM users WHERE emailadres='${emailadres}' and password='${password}';");
		$row = mysqli_fetch_assoc($res);
		var_dump($row); die;
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