<!DOCTYPE html>
<html>
	<head>
		<title></title>
	
		<?php
			include "addQuestion.php";
		?>

	</head>
	<body>

		<form method="post">
			<p>Add question:</p>
			<input type="text" name="question"><br/>
			
			<p>Add answers:</p>
			<input type="text" name="answer1"><br/>
			<input type="text" name="answer2"><br/>
			<input type="text" name="answer3"><br/>
			<input type="text" name="answer4"><br/><br/>
			<button>Save</button>
		</form>

	</body>
</html>