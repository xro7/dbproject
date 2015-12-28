<?php
session_start();
if(isset($_SESSION['valid_user_name'])){
	header('Location: http://localhost/dbproject/site/eisigitis.php');
}else{

	?>

<html>
	<head>
		<title>Login Εισηγητή</title>
		<link rel="stylesheet" href="../css/loginstyle.css">
		
	</head>

	<body>

	<div id="top">
		<h1>login Εισηγητή</h1>
	</div>
	

	<div id="space"></div>
	<div id="form">
		
		<form action="eisigitis.php" method="post">
		  <label for="username">Username</label>
		  <input type="text" name="username" placeholder="Username">
		  <br>
		  <label for="password">Password</label>
		  <input type="text" name="password" placeholder="Password">
		  <br><br>
		  <input type="submit" value="Login">
		</form>

	</div>

	</body>
</html>

<?php } ?>