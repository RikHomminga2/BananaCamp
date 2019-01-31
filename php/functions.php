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
		$email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) && !empty($_POST['password']) ? hash('sha512', $_POST['password']) : false;
		$firstname = isset($_POST['firstname']) && !empty($_POST['firstname']) ? $_POST['firstname'] : false;
		$lastname = isset($_POST['lastname']) && !empty($_POST['lastname']) ? $_POST['lastname'] : false;
		if($email && $password && $firstname && $lastname) {
			mysqli_query($con, "INSERT INTO users (email, password) VALUES('${email}', '${password}');");
			if(mysqli_affected_rows($con) == 1) { $users_id = mysqli_insert_id($con); }
			else { return false; }
			mysqli_query($con, "INSERT INTO profiles (users_id, firstname, lastname) VALUES ('${users_id}', '${firstname}', '${lastname}');");
			$res = mysqli_affected_rows($con);
			closeDatabaseConnection($con);
		}
		return $res == 1 ? login() : false;
	}

	function login() {
		$email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) && !empty($_POST['password']) ? hash('sha512', $_POST['password']) : false;
		if($email && $password) {
			$con = openDatabaseConnection();
			$res = mysqli_query($con, "SELECT * FROM users WHERE email='${email}' and password='${password}';");
			if(mysqli_num_rows($res) != 1) { return false; }
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
		echo mysqli_num_rows($res) == 1 ? json_encode(mysqli_fetch_assoc($res)) : json_encode([]);
	}
	
	function updateUserInfo() {
		$res = 0;
		$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : false;
		$bio = isset($_POST['bio']) && !empty($_POST['bio']) ? $_POST['bio'] : false;
		$github = isset($_POST['github']) && !empty($_POST['github']) && !($_POST['github'] == 'https://') ? $_POST['github'] : 'https://www.github.com';
		$github = parse_url($github)['scheme'] == 'https' ? $github : "https://${github}";
		$linkedin = isset($_POST['linkedin']) && !empty($_POST['linkedin']) && !($_POST['linkedin'] == 'https://') ? $_POST['linkedin'] : 'https://www.linkedin.com';
		$linkedin = parse_url($linkedin)['scheme'] == 'https' ? $linkedin : "https://${linkedin}";
		if($users_id && $bio && $github && $linkedin) {
			$con = openDatabaseConnection();
			$sql = "UPDATE profiles SET bio='${bio}', linkedin='${linkedin}', github='${github}' WHERE users_id=${users_id}";
			mysqli_query($con, $sql);
			$res = mysqli_affected_rows($con);
		}
		echo json_encode($res == 1 ? ["result" => true, "page" => "profile.php"] : ["result" => false, "page" => "error.php"]);
	}
	
	function getAssesment() {
		$con = openDatabaseConnection();
		$res = mysqli_query($con, "SELECT * FROM assesments LIMIT 1");
		echo json_encode(mysqli_fetch_assoc($res));
	}
	
	function getAllAssesments() {
		$con = openDatabaseConnection();
		$stager = [];
		$res = mysqli_query($con, "SELECT * FROM assesments");
		closeDatabaseConnection($con);
		while($row = mysqli_fetch_assoc($res)) {
			$stager[] = $row;
		}
		echo json_encode($stager);
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
	
	function getExamResultForUser(){
		$users_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : false;
		$stager = [];
		$con = openDatabaseConnection();
		$res = mysqli_query($con, 	
			"SELECT exams.id, exams.description, exams.level, exams.category, exam_results.results
			FROM exams 
			INNER JOIN exam_results
			ON exams.id = exam_results.exams_id
			WHERE users_id=${users_id}");
		while($row = mysqli_fetch_assoc($res)){
			$stager[] = ["id" => $row['id'], "description" => $row['description'], "category" => $row['category'], "level" => $row['level'], "results" => json_decode($row['results'])];
		}
		echo json_encode($stager);
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
		$question = isset($_POST['question']) ? trim($_POST['question']) : false;
		$answer1 = isset($_POST['answer1']) ? trim($_POST['answer1']) : false;
		$answer2 = isset($_POST['answer2']) ? trim($_POST['answer2']) : false;
		$answer3 = isset($_POST['answer3']) ? trim($_POST['answer3']) : false;
		$answer4 = isset($_POST['answer4']) ? trim($_POST['answer4']) : false;
		$category = isset($_POST['category']) ? trim($_POST['category']) : 'default';
		$level = isset($_POST['level']) ? trim($_POST['level']) : 'easy';
		$answers = [$answer1, $answer2, $answer3, $answer4];
		if($answer1 && $answer2 && $answer3 && $answer4) {
			$answers = json_encode($answers);
			$con = openDatabaseConnection();
			mysqli_query($con, "INSERT INTO questions (question, answers, category, level) VALUES ('${question}','${answers}', '${category}', '${level}');");
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
	
	function getFilterQuestions(){
		$category = $_POST['category'];
		$stager = [];
		$con = openDatabaseConnection();
		$res = mysqli_query($con, "SELECT * FROM questions WHERE category='$category'");
		while($row = mysqli_fetch_assoc($res)) {
			$stager [] = ["id" => $row['id'], "question" => $row['question'], "answers" => json_decode($row['answers'])];
		}
		closeDatabaseConnection($con);
		echo json_encode($stager);	
	}
	
	function createExam() {
        $description = isset($_POST['description']) && !empty($_POST['description']) ? trim($_POST['description']) : 'no description';
		$level = isset($_POST['level']) ? trim($_POST['level']) : 'easy';
		$category = isset($_POST['category']) ? trim($_POST['category']) : 'uncategorized';
        $stager = [];
        foreach ($_POST as $k => $v) {
            if (!($k == 'description' || $k == 'request')) {
                $stager[] = $v;
            }   
        }
        $stager = json_encode($stager);
        $con = openDatabaseConnection();
        mysqli_query($con, "INSERT INTO exams (description, questions, level, category) VALUES ('${description}', '${stager}', '${level}', '${category}');");
		closeDatabaseConnection($con);
    }
	
	function getExam($exam_id){
		$con = openDatabaseConnection();
		$res = mysqli_query($con, "SELECT * FROM exams WHERE id=".$exam_id."");
		while($row = mysqli_fetch_assoc($res)){
			$stager1 [] = ['id' => $row['id'], 'description' => $row['description'], 'question_ids' => json_decode($row['questions'])];
		}	
		array_push($stager1, getQuestions2());
		echo json_encode($stager1);
	}
	
	function getHtmlExams(){
		$con = openDatabaseConnection();
		$res = mysqli_query($con,"SELECT * FROM exams WHERE category='html'");
		while($row = mysqli_fetch_assoc($res)){
			$stager [] = ['id' => $row['id'], 'description' => $row['description'], 'questions' => json_decode($row['questions']), 'level' => $row['level'], 'category' => $row['category']];
		}
		closeDatabaseConnection($con);
		echo json_encode($stager);
	}
	
	function getCssExams(){
		$con = openDatabaseConnection();
		$res = mysqli_query($con,"SELECT * FROM exams WHERE category='css'");
		while($row = mysqli_fetch_assoc($res)){
			$stager [] = ['id' => $row['id'], 'description' => $row['description'], 'questions' => json_decode($row['questions']), 'level' => $row['level'], 'category' => $row['category']];
		}
		closeDatabaseConnection($con);
		echo json_encode($stager);	
	}
	
	function getJavascriptExams(){
		$con = openDatabaseConnection();
		$res = mysqli_query($con,"SELECT * FROM exams WHERE category='javascript'");
		while($row = mysqli_fetch_assoc($res)){
			$stager [] = ['id' => $row['id'], 'description' => $row['description'], 'questions' => json_decode($row['questions']), 'level' => $row['level'], 'category' => $row['category']];
		}
		closeDatabaseConnection($con);
		echo json_encode($stager);
	}
	
	function getPhpExams(){
		$con = openDatabaseConnection();
		$res = mysqli_query($con,"SELECT * FROM exams WHERE category='php'");
		while($row = mysqli_fetch_assoc($res)){
			$stager [] = ['id' => $row['id'], 'description' => $row['description'], 'questions' => json_decode($row['questions']), 'level' => $row['level'], 'category' => $row['category']];
		}
		closeDatabaseConnection($con);
		echo json_encode($stager);
	}
	
	function getSqlExams(){
		$con = openDatabaseConnection();
		$res = mysqli_query($con,"SELECT * FROM exams WHERE category='sql'");
		while($row = mysqli_fetch_assoc($res)){
			$stager [] = ['id' => $row['id'], 'description' => $row['description'], 'questions' => json_decode($row['questions']), 'level' => $row['level'], 'category' => $row['category']];
		}
		closeDatabaseConnection($con);
		echo json_encode($stager);
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
