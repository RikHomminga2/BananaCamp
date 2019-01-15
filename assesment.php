<?php session_start(); if(!$_SESSION['authenticated']){ header('Location: index.php'); }?>
<html>
	<head>
		<title>Self assesment</title>
		<script src="scripts/functions.js"></script>
		<script src="https://d3js.org/d3.v5.min.js"></script>
		<script>
			document.addEventListener("DOMContentLoaded", fetchAssesment);
		</script>
	</head>
	<body>
		<main>
		</main>
	</body>
</html>