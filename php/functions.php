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
		$con = connectToDatabase();
		$q = mysqli_query($con, "SELECT * FROM assesments WHERE id =1");
		while($r = mysqli_fetch_assoc($q)){
			echo json_encode(["id" => $r['id'], "title" => $r['title'], "description" => $r['description'], "assesment" => $r['assesment']]);
		}
	}
	
	function getResultsUser (){
		$con = connectToDatabase();
		$q = mysqli_query($con, "SELECT * FROM users WHERE id=3");
		while($r = mysqli_fetch_assoc($q)){
		$str = $r['password'];
		echo json_encode(var_dump($str)); 
		
		}
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
	
	function createExam() {
        $description = $_POST['description'];
        $stager = [];
        foreach ($_POST as $k => $v) {
            if (!($k == 'description' || $k == 'request')) {
                $stager[] = $v;
            }   
        }
        $stager = json_encode($stager);
        $con = connectToDatabase();
        mysqli_query($con, "INSERT INTO exams (description, questions) VALUES ('${description}', '${stager}');");
    }