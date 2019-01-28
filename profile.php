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
				<i id="show" class="far fa-edit"></i><br>
					<form id="bio">
					  <i id="hide" class="far fa-times-circle"></i><br><br>
					  <textarea rows="8" cols="20" name="bio" class="xsfields" placeholder="Fill in your bio.."></textarea><br>
					  <input type="text" name="github" class="xsfields" placeholder="Your Github link"></br>
					  <input type="text" name="linkedin" class="xsfields" placeholder="Your LinkedIn link"><br>
					  <input type="Submit" class="xsbtn">
					</form>
			</section>
			<section id="main-content-center" class="block">
				<nav>
					<button>Assesment 1</button>
					<button>Exam 1</button>
					<button>Complete Progress</button>
				</nav>
				<br>
			</section>
			<section id="main-content-right" class="block">
				Exams
			</section>
		</section>
	</main>
<?php include 'includes/footer.php'; ?>