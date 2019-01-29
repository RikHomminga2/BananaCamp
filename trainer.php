<?php
	session_start();
	require_once 'includes/banner.php';
?>	
		<main>
			<h1>TRAINER INTERFACE</h1>
			<nav>
				<button onclick="toggle('view-students')">View students</button>
				<button onclick="toggle('add-assesment')">Add an assesment</button>
				<button onclick="toggle('add-question')">Add a question</button>
				<button onclick="toggle('getQuestions')">Add an exam</button>
			</nav>
			<section class="hidden" id="view-students">View students</section>
			<section class="hidden" id="add-assesment">
				<h2>Add an assesment</h2>
				<form method="post" action="main.php">
					<input type="text" class="fields" name="title" placeholder="Title"><br/>
					<input type="text" class="fields" name="description" placeholder="Description"><br/>
					<input type="text" class="fields" name="categories" placeholder="Fill in comma separated categories"><br/>
					<input type="hidden" name="request" value="addAssesment">
					<input type="submit" class="fields" value="Save">
				</form>
			</section>
			<section class="hidden" id="add-question">
				<h2>Add a question</h2>
				<form method="post" action="main.php" id="add-question">
					<input type="text" class="fields" name="question" placeholder="Question"><br/>
					<input type="text" class="fields" name="answer1" placeholder="Fill in correct answer"><br/>
					<input type="text" class="fields" name="answer2" placeholder="Fill in wrong answer"><br/>
					<input type="text" class="fields" name="answer3" placeholder="Fill in wrong answer"><br/>
					<input type="text" class="fields" name="answer4" placeholder="Fill in wrong answer"><br/>
					<input type="text" class="fields" name="category" placeholder="category"><br/>
					<select form="add-question" class="fields" name="level">
						<option value="easy" selected>Easy</option>
						<option value="moderate">Moderate</option>
						<option value="hard">Hard</option>
					</select>
					<input type="hidden" name="request" value="addQuestion">
					<input type="submit" class="fields" value="Save">
				</form>
			</section>
			<section class="hidden" id="getQuestions">
				<h2>Add an exam</h2>
			</section>
		</main>
<?php
	include 'includes/footer.php';
?>