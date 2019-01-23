<?php

session_start();

if (!isset($_SESSION['users_id'])) {
    header ("Location: index.php");
}

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<script src="scripts/functions.js"></script>
		<script src="https://d3js.org/d3.v5.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" href="styles/style.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
		<title>Profile</title>
	</head>
	<body class="body-profile">
		<header class="header-profile"><button onclick="fetchSignOut()">sign out</button>
		</header>