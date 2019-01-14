<?php session_start(); if(!$_SESSION['authenticated']){ header('Location: index.php'); }?>
<html>
	<head>
		<title>Self assesment</title>
		<script src="assesment.js"></script>
		<script>
			document.addEventListener("DOMContentLoaded", fetchAssesment);
		</script>
	</head>
	<body>
		<main>
		</main>
	</body>
</html>