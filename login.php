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
		}
		
			if(count(file("assets/questions.txt")) != 0){
			
					$file = file("assets/questions.txt");
						
						foreach(($file) as $line) {
									
							//$csv = explode('|', $line);
							echo $line;
						}
		}
		?>
	</body>
</html>