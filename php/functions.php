<?php
	function connectToDatabase() {
		include 'config.php';
		return mysqli_connect($host, $username, $password, $dbname);
	}

	function register() {
		$con = connectToDatabase();
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;
		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : false;
		$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : false;
		if($email && $password && $firstname && $lastname) {
			mysqli_query($con, "INSERT INTO users (email, password) VALUES('${email}', '${password}');");
			login();
			$users_id = $_SESSION['users_id'];
			mysqli_query($con, "INSERT INTO profiles (users_id, firstname, lastname) VALUES ('${users_id}', '${firstname}', '${lastname}');");
			return true;
		}
		return false;	
	}

	function login() {
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;
		if($email && $password) {
			$con = connectToDatabase();
			$res = mysqli_query($con, "SELECT * FROM users WHERE email='${email}' and password='${password}';");
			$row = mysqli_fetch_assoc($res);
			if($row['id'] != '') {
				$_SESSION['authenticated'] = true;
				$_SESSION['users_id'] = $row['id'];
				return true;
			}
		}
		return false;
	}
	
	function getAssesment() {
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT * FROM assesments WHERE id=1");
		while($row = mysqli_fetch_assoc($res)) {
			echo json_encode(["id" => $row['id'], "title" => $row['title'], "description" => $row['description'], "assesment" => $row['assesment']]);
		}
	}
	
	function storeAssesmentResult() {
		$id = isset($_POST['id']) ? $_POST['id'] : false;
		$result = isset($_POST['result']) ? $_POST['result'] : false;
		$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : false;
		if($id && $result && $users_id) {
			$con = connectToDatabase();
			mysqli_query($con, "INSERT INTO assesment_results (assesment_id, user_id, results) VALUES('${id}','${users_id}','${result}');");
			echo json_encode(["result" => true]);
		} else {
			echo json_encode(["result" => false]);
		}
	}
	
	function getAssesmentResultForUser(){
		$con = connectToDatabase();
		$res = mysqli_query($con, 	
			"SELECT assesments.id, assesments.title, assesments.description, assesments.assesment, assesment_results.results
			FROM assesments 
			INNER JOIN assesment_results
			ON assesments.id = assesment_results.assesment_id
			WHERE user_id=16");
		while($row = mysqli_fetch_assoc($res)){
			echo json_encode(["id" => $row['id'], "title" => $row['title'], "assesment" => $row['assesment'], "results" => $row['results']]);
		}
	}
	
	function addAssesment() {
		$title = (isset($_POST['title'])) ? $_POST['title'] : false;
		$description = (isset($_POST['description'])) ? $_POST['description'] : false;
		$categories = (isset($_POST['categories'])) ? explode(',', $_POST['categories']) : false;
		if($title && $description && $categories) {
			$categories = json_encode($categories);
			$con = connectToDatabase();
			mysqli_query($con, "INSERT INTO assesments (title, description, assesment) VALUES ('${title}', '${description}', '${categories}')");
			return true;
		}
		return false;
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
		if($answer1 && $answer2 && $answer3 && $answer4) {
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
	
	function getQuestions2() {
		$stager = [];
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT * FROM questions ORDER BY id DESC");
		while($row = mysqli_fetch_assoc($res)) {
			$stager [] = ["id" => $row['id'], "question" => $row['question'], "answers" => json_decode($row['answers'])];
		}
		return $stager;
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
	
	function getExam(){
		$con = connectToDatabase();
		$res = mysqli_query($con, "SELECT * FROM exams WHERE id=1");
		while($row = mysqli_fetch_assoc($res)){
			$stager1 [] = ['id' => $row['id'], 'description' => $row['description'], 'q_id' => $row['questions']];
		}	
		array_push($stager1, getQuestions2());
		echo json_encode($stager1);
	}