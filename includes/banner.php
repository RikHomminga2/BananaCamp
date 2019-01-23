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
		<link rel="stylesheet" href="styles/style.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
		<title></title>
	</head>
    <body class="body-profile">
        <header class="header-profile"><a href ="signout.php" class="btn">Sign out</a>
        </header>