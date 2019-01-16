<?php
	function connectToDatabase() {
		include 'config.php';
		return mysqli_connect($host, $username, $password, $dbname);
	}

	function register($email, $password) {
		$con = connectToDatabase();
		mysqli_query($con, "INSERT INTO users (email, password) VALUES('${email}','${password}');");
		return true;
	}

	function login($email, $password) {
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT COUNT(id) AS cnt FROM users WHERE email='${email}' and password='${password}';");
		$row = mysqli_fetch_assoc($res);
		if($row['cnt'] == '1') {
			$_SESSION['authenticated'] = true;
			return true;
		}
		return false;
	}
	
	function getAssesment() {
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT * FROM assesments WHERE id=1");
		while($row = mysqli_fetch_assoc($res)){
			echo json_encode(["id" => $row['id'], "title" => $row['title'], "description" => $row['description'], "assesment" => $row['assesment']]);
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
	
	function addQuestion() {
		//$question, [$answer1, $answer2, $answer3, $answer4])
		$question = (isset($_POST['question'])) ? $_POST['question'] : false;
		$answer1 = (isset($_POST['answer1'])) ? $_POST['answer1'] : false;
		$answer2 = (isset($_POST['answer2'])) ? $_POST['answer2'] : false;
		$answer3 = (isset($_POST['answer3'])) ? $_POST['answer3'] : false;
		$answer4 = (isset($_POST['answer4'])) ? $_POST['answer4'] : false;
		$answers = [$answer1, $answer2, $answer3, $answer4];
		//var_dump($a); var_dump($_POST); die;
		if($answer1 && $answer2 && answer3 && answer4) {
			$answers = json_encode($answers);
			$con = connectToDatabase();
			mysqli_query($con, "INSERT INTO questions (question, answers) VALUES ('${question}','${answers}');");
			return true;
		}
		return false;
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