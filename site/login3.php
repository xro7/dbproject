<?php
session_start();
if(isset($_SESSION['valid_user_name3'])){
	header('Location: http://localhost/dbproject/site/elegktis.php');
}else{

	?>

<html>
	<head>
		<title>Login Καθηγητή</title>
		<link rel="stylesheet" href="../css/loginstyle.css">
		
	</head>

	<body>

	<div id="top">
		<h1>login καθηγητή</h1>
	</div>
	

	<div id="space"></div>
	<div id="form">
		
		<form action="elegktis.php" method="post">
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