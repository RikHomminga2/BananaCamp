<?php
	function openDatabaseConnection() {
		include 'config.php';
		$con = mysqli_connect($host, $username, $password, $dbname);
		if(mysqli_connect_errno()) {
			die('failed to open connection to database: ' . mysqli_connect_error());
		}
		return $con;
	}
	
	function closeDatabaseConnection($con) {
		return mysqli_close($con);
	}
	
	function register() {
		$con = openDatabaseConnection();
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;
		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : false;
		$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : false;
		if($email && $password && $firstname && $lastname) {
			mysqli_query($con, "INSERT INTO users (email, password) VALUES('${email}', '${password}');");
			login();
			$users_id = $_SESSION['users_id'];
			mysqli_query($con, "INSERT INTO profiles (users_id, firstname, lastname) VALUES ('${users_id}', '${firstname}', '${lastname}');");
			closeDatabaseConnection($con);
			return true;
		}
		return false;	
	}

	function login() {
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? hash('sha512', $_POST['password']) : false;
		if($email && $password) {
			$con = openDatabaseConnection();
			$res = mysqli_query($con, "SELECT * FROM users WHERE email='${email}' and password='${password}';");
			$row = mysqli_fetch_assoc($res);
			if($row['id'] != '') {
				$_SESSION['authenticated'] = true;
				$_SESSION['users_id'] = $row['id'];
				$_SESSION['role'] = $row['role'];
				closeDatabaseConnection($con);
				return true;
			}
		}
		return false;
	}
	
	function logout() {
		session_destroy();
		echo json_encode(["result" => true, "page" => "index.php"]);
	}
	
	function getUserInfo() {
		$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : false;
		if(!$users_id) { header('Location: index.php'); }
		$con = openDatabaseConnection();
		$res = mysqli_query($con, "SELECT * FROM profiles WHERE users_id=${users_id}");
		$row = mysqli_fetch_assoc($res);
		echo json_encode($row);
	}
	
	function updateUserInfo() {
		$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : false;
		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : false;
		$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : false;
		$bio = isset($_POST['bio']) ? $_POST['bio'] : false;
		$github = isset($_POST['github']) ? $_POST['github'] : false;
		$linkedin = isset($_POST['linkedin']) ? $_POST['linkedin']: false;
		if($users_id && $firstname && $lastname && $bio && $github && $linkedin) {
			$con = openDatabaseConnection();
			$sql = "UPDATE profiles SET firstname='${firstname}', lastname='${lastname}', bio='${bio}', linkedin='${linkedin}', github='${github}' WHERE users_id=${users_id}";
			mysqli_query($con, $sql);
			return true;
		}
		return false;
	}
	
	function getAssesment() {
		$con = openDatabaseConnection();
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
			$con = openDatabaseConnection();
			mysqli_query($con, "INSERT INTO assesment_results (assesments_id, users_id, results) VALUES('${id}','${users_id}','${result}');");
			closeDatabaseConnection($con);
			echo json_encode(["result" => true, "page" => "profile.php"]);
		} else {
			echo json_encode(["result" => false]);
		}
	}
	
	function getAssesmentResultForUser(){
		$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : false;
		$con = openDatabaseConnection();
		$res = mysqli_query($con, 	
			"SELECT assesments.id, assesments.title, assesments.description, assesments.assesment, assesment_results.results
			FROM assesments 
			INNER JOIN assesment_results
			ON assesments.id = assesment_results.assesments_id
			WHERE users_id=${users_id} LIMIT 1");
		while($row = mysqli_fetch_assoc($res)){
			echo json_encode(["id" => $row['id'], "title" => $row['title'], "assesment" => json_decode($row['assesment']), "results" => json_decode($row['results'])]);
		}
	}
	
	function addAssesment() {
		$title = (isset($_POST['title'])) ? $_POST['title'] : false;
		$description = (isset($_POST['description'])) ? $_POST['description'] : false;
		$categories = (isset($_POST['categories'])) ? explode(',', $_POST['categories']) : false;
		if($title && $description && $categories) {
			$categories = json_encode($categories);
			$con = openDatabaseConnection();
			mysqli_query($con, "INSERT INTO assesments (title, description, assesment) VALUES ('${title}', '${description}', '${categories}')");
			closeDatabaseConnection($con);
			return true;
		}
		return false;
	}
	
	function addQuestion() {
		$question = (isset($_POST['question'])) ? $_POST['question'] : false;
		$answer1 = (isset($_POST['answer1'])) ? $_POST['answer1'] : false;
		$answer2 = (isset($_POST['answer2'])) ? $_POST['answer2'] : false;
		$answer3 = (isset($_POST['answer3'])) ? $_POST['answer3'] : false;
		$answer4 = (isset($_POST['answer4'])) ? $_POST['answer4'] : false;
		$category = (isset($_POST['category'])) ? $_POST['category'] : 'default';
		$answers = [$answer1, $answer2, $answer3, $answer4];
		if($answer1 && $answer2 && $answer3 && $answer4) {
			$answers = json_encode($answers);
			$con = openDatabaseConnection();
			mysqli_query($con, "INSERT INTO questions (question, answers, category) VALUES ('${question}','${answers}', '${category}');");
			closeDatabaseConnection($con);
			return true;
		}
		return false;
	}
	
	function getQuestions() {
		$stager = [];
		$con = openDatabaseConnection();
		$res = mysqli_query($con, "SELECT * FROM questions ORDER BY id DESC");
		while($row = mysqli_fetch_assoc($res)) {
			$stager[] = ["id" => $row['id'], "question" => $row['question'], "answers" => json_decode($row['answers'])];
		}
		echo json_encode($stager);
	}
	
	function getQuestions2() {
		$stager = [];
		$con = openDatabaseConnection();
		$res = mysqli_query($con, "SELECT * FROM questions ORDER BY id DESC");
		while($row = mysqli_fetch_assoc($res)) {
			$stager [] = ["id" => $row['id'], "question" => $row['question'], "answers" => json_decode($row['answers'])];
		}
		closeDatabaseConnection($con);
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
        $con = openDatabaseConnection();
        mysqli_query($con, "INSERT INTO exams (description, questions) VALUES ('${description}', '${stager}');");
		closeDatabaseConnection($con);
    }
	
	function getExam(){
		$con = openDatabaseConnection();
		$res = mysqli_query($con, "SELECT * FROM exams WHERE id=3");
		while($row = mysqli_fetch_assoc($res)){
			$stager1 [] = ['id' => $row['id'], 'description' => $row['description'], 'question_ids' => json_decode($row['questions'])];
		}	
		array_push($stager1, getQuestions2());
		echo json_encode($stager1);
	}
	
	function storeExamResult(){
		$id = isset($_POST['id']) ? $_POST['id'] : false;
		$result = isset($_POST['result']) ? $_POST['result'] : false;
		$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : false;
		if($id && $result && $users_id) {
			$con = openDatabaseConnection();
			mysqli_query($con, "INSERT INTO exam_results (exams_id, users_id, results) VALUES('${id}','${users_id}','${result}');");
			closeDatabaseConnection($con);
			echo json_encode(["result" => true, "page" => "profile.php"]);
		} else {
			echo json_encode(["result" => false, "page" => "error.php"]);
		}	
	}
