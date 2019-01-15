<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Trainer interface</title>
		<script src="exam.js"></script>
		<script>
			document.addEventListener('DOMContentLoaded', fetchQuestions)
		</script>

	</head>
	<body>
		<main>
			<h1>TRAINER INTERFACE</h1>
			<hr>
			<section id="addQuestion">
				<h2>ADD A QUESTION</h2>
				<!-- form for adding questions -->
				<form method="post" action="main.php">
					<input type="text" name="question">&nbsp;question<br/>
					<input type="text" name="answer1">&nbsp;correct answer<br/>
					<input type="text" name="answer2">&nbsp;wrong answer<br/>
					<input type="text" name="answer3">&nbsp;wrong answer<br/>
					<input type="text" name="answer4">&nbsp;wrong answer<br/><br/>
					<input type="hidden" name="request" value="addQuestion">
					<input type="submit" value="save">
				</form>
			</section>
			<hr>
			<section id="getQuestions">
				<h2>CREATE AN EXAM</h2>
			</section>
		</main>
	</body>
</html>