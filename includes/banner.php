<?php

session_start();

if (!isset($_SESSION['users_id'])) {
    header ("Location: index.php");
}

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="scripts/functions.js"></script>
		<script src="https://d3js.org/d3.v5.min.js"></script>
		<link rel="stylesheet" href="styles/style.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		<title></title>
	</head>
	<body class="grid">
		<header class="banner">
            <button class="formbtn" onclick="fetchSignOut()">Sign out</button>
		</header>