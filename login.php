<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">	
		<title>Login</title>
	</head>
	<body>
		<h1>Login</h1>
		<form name="formLogin" method="post" action="">
			<input type="text" name="username" placeholder="username" />
			<input type="password" name="password" placeholder="password" />
			<input type="submit" name="submitLogin" value="login" />
		</form>
		<?php
		if(isset($_POST['submitLogin'])){
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			if($username != "banana" && $password != "camp"){
				echo "login is invalid";
			}else{
				header ("Location: profile.php");
			}
		
		}
		?>		
	</body>
</html>
