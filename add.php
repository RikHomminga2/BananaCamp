<!DOCTYPE html>
<html>
	<head>
		<title></title>

	</head>
	<body>

		<form method="post" action="main.php">
			<p>Add question:</p>
			<input type="text" name="question"><br/>
			
			<p>Add answers:</p>
			<input type="text" name="answer1"><br/>
			<input type="text" name="answer2"><br/>
			<input type="text" name="answer3"><br/>
			<input type="text" name="answer4"><br/><br/>
			<input type="hidden" name="request" value="addQuestion">
			<input type="submit">Save
		</form>
		
		<form method="post" action="main.php">
			<input type="hidden" name="request" value="getQuestions">
			<input type="submit">
		</form>

	</body>
</html>