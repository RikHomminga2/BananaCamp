<?php

	//foreach (glob("assets/questions.txt") as $filename) {   
	//	echo implode("<br>" , array_reverse(file($filename))) . "<br></br>";
	//}
    
    $question = "";
    $answer1 = "";
    $answer2 = "";
    $answer3 = "";
    $answer4 = "";
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["question"]) && !empty($_POST["answer1"]) && !empty($_POST["answer2"]) && !empty($_POST["answer3"]) && !empty($_POST["answer4"])) {
        
		$question = $_POST["question"];
		$answer1 = $_POST["answer1"];
		$answer2 = $_POST["answer2"];
		$answer3 = $_POST["answer3"];
        $answer4 = $_POST["answer4"];
		
		$result = "Question added!";
		echo $result;
		
		file_put_contents("assets/questions.txt", $question . $answer1 . $answer2 . $answer3 . $answer4 . "\n", FILE_APPEND);
	}

?>