<?php include 'includes/banner.php'; ?>
<script>document.addEventListener("DOMContentLoaded", populateProfile);</script>
	<main class="main-profile">
		<section id="main-top">
			<section id="main-top-left" class="block">
			</section>
			<section id="main-top-right" class="block">
				
			</section>
		</section>
		<section id="main-content">
			<section id="main-content-left" class="block">
				<i id="show" class="far fa-edit" onclick="on()"></i><br>
			</section>
			<form id="overlay">
				<i class="fas fa-times" onclick="off()"></i><br>
				<h1>Edit bio</h1>
				<textarea rows="10" cols="2" class="iptfield" placeholder="Edit bio"></textarea><br>
				<input type="text" class="iptfield" placeholder="Add link to Github"><br>
				<input type="text" class="iptfield" placeholder="Add link to LinkedIn"><br>
				<input type="submit" value="Save" class="formbtn">
			</form>
			<section id="main-content-center" class="block">
				<nav>
					<button class="navbtn">Assesment 1</button>
					<button class="navbtn">Exam 1</button>
					<button class="navbtn">Complete Progress</button>
				</nav>
				<br>
			</section>
			<section id="main-content-right" class="block">
				Exams
			</section>
		</section>
	</main>
<?php include 'includes/footer.php'; ?>