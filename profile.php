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
			<form id="overlay" method="POST">
				<i class="fas fa-times" onclick="off()"></i><br>
				<h1>Edit bio</h1>
				<textarea rows="10" cols="2" class="iptfield" name="bio" id="bio">short description</textarea><br>
				<input type="text" class="iptfield" name="github" id="github" pattern="https?://.+" placeholder="Add GitHub URL"><br>
				<input type="text" class="iptfield" name="linkedin" id="linkedin" pattern="https?://.+" placeholder="Add LinkedIn URL"><br>
				<input type="submit" value="Save" class="formbtn" onclick="updateUserInfo()">
			</form>
			<section id="main-content-center" class="block">
			</section>
			<section id="main-content-right" class="block">
			</section>
		</section>
	</main>
<?php include 'includes/footer.php'; ?>