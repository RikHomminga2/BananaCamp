<?php
require_once 'includes/banner.php';
if($_SESSION['role'] != 'trainer'){
	header ('Location: profile.php');
}
?>

	<script>
		addEventListener('DOMContentLoaded', function() {
			setTimeout(function() { location.href = 'trainer.php'}, 1000);});
	</script>
		<main class="main-trainer">
			<h1>TRAINER INTERFACE</h1>
			<nav>
				<button class="navbtn" onclick="toggle('view-students')">View students</button>
				<button class="navbtn" onclick="toggle('add-assesment')">Add an assesment</button>
				<button class="navbtn" onclick="toggle('add-question')">Add a question</button>
				<button class="navbtn" onclick="toggle('get-questions')">Add an exam</button>
			</nav>
			<section class="hidden" id="view-students">
				<h2>Student list</h2>
			</section>
			<section class="hidden" id="add-assesment">
				<h2>Add an assesment</h2>
				<form method="post" action="main.php">
					<input type="text" class="iptfield" name="title" placeholder="Title"><br/>
					<input type="text" class="iptfield" name="description" placeholder="Description"><br/>
					<input type="text" class="iptfield" name="categories" placeholder="Fill in comma separated categories"><br/>
					<input type="hidden" name="request" value="addAssesment">
					<input type="submit" class="formbtn" value="Save">
				</form>
			</section>
			<section class="hidden" id="add-question">
				<h2>Add a question</h2>
				<form method="post" action="main.php" id="add-question">
					<input type="text" class="iptfield" name="question" placeholder="Question"><br/>
					<input type="text" class="iptfield" name="answer1" placeholder="Fill in correct answer"><br/>
					<input type="text" class="iptfield" name="answer2" placeholder="Fill in wrong answer"><br/>
					<input type="text" class="iptfield" name="answer3" placeholder="Fill in wrong answer"><br/>
					<input type="text" class="iptfield" name="answer4" placeholder="Fill in wrong answer"><br/>
					<input type="text" class="iptfield" name="category" placeholder="category"><br/>
					<select form="add-question" class="formbtn" name="level">
						<option value="easy" selected>Easy</option>
						<option value="moderate">Moderate</option>
						<option value="hard">Hard</option>
					</select><br>
					<input type="hidden" name="request" value="addQuestion">
					<input type="submit" class="formbtn" value="Save">
				</form>
			</section>
			<section class="hidden" id="get-questions">
				<h2>Add an exam</h2>
			</section>
			<br>
			<h1 style="color:#228B22">ADDED SUCCESSFULLY!</h1>
		</main>
<?php include 'includes/footer.php'; ?>