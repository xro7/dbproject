<html>



	<?php
	include('dbconnect.php');
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		if(!$username || !$password){
			echo 'Please go back and enter the credentials, then try again';
			exit;
		}

/*		if(!get_magic_quotes_gpc()){
			$username = addslashes($username);
			$password = addslashes($password);

		}*/

		$db = dbconnect();
		$query = "select * from elegktis where username='$username' and password = '$password'";
		$result = $db->query($query);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}

		$numresults = mysqli_num_rows($result);

		if ($numresults==1){
			
			$row = $result->fetch_assoc();
			$_SESSION['valid_user_name2'] = $row['name'];
			$_SESSION['valid_user_username2'] = $row['username'];
			$_SESSION['valid_user_id2'] = $row['id'];

		}else {

			echo 'Not a valid user.Please Login again';
			exit();
		}
	}

	if(isset($_SESSION['valid_user_name2'])){

	?>

	<head>
		<title><?php echo $_SESSION['valid_user_username2'];  ?></title>
		<link rel="stylesheet" href="../css/userstyle.css">
		<meta charset="UTF-8">

	</head>
	<body>
		
		<div id="menu">

			<div id="topmenu">
				<h1>Πληροφοριακό Σύστημα Εξετάσεων</h1>
			</div>


			<?php echo '<h1>Ελεγκτης '.$_SESSION['valid_user_name2'].'</h1>';?>
			<a href="http://localhost/dbproject"> Αρχική </a>
			<a href="logout.php"> logout </a>
			

			</div>
			<hr>
			<div id="main">

				
				<h2>Ερωτήσεις που αναμένουν έγκριση</h2>
				<?php

					$db = new mysqli('localhost','xro','123','dbproject');
					if (mysqli_connect_errno()){
						echo 'Could not connect to database';
						exit;
					}

					$query = "select * from erwtisi where approved=0";
					$result = $db->query($query);
					if (!$result) {
					    printf("Error: %s\n", mysqli_error($db));
					    exit();
					}

					$numresults = mysqli_num_rows($result);
					for($i=0;$i< $numresults;$i++){
						$row = $result->fetch_assoc();
						$id = $row['id'];
						echo "<form action=\"approve.php\" method=\"post\">";

						echo '<h3> Eρώτηση: '.($i+1).' '. $row['text'].', Δυσκολία: '.$row['dyskolia'].'</h3>';
						
						$q = 'select  choices from epiloges  where qid='.$id;
						$result2 = $db->query($q);
						if (!$result2) {
					    	printf("Error: %s\n", mysqli_error($db));
					    	exit();
						}
						$numresults2 = mysqli_num_rows($result2);
						echo '<p>Απαντήσεις:</p>';
						for($j=0;$j< $numresults2;$j++){
							$row = $result2->fetch_assoc();
							
							echo '<ul>';
							echo '<li>'.($j+1). ' '.$row['choices'].'</li>';
							echo '</ul>';
						}

						$q = 'select  choices from epiloges  where correct=1 and qid='.$id;
						$result3 = $db->query($q);
						if (!$result3) {
					    	printf("Error: %s\n", mysqli_error($db));
					    	exit();
						}
						$numresults3 = mysqli_num_rows($result3);
						echo '<p>Σωστές απαντήσεις:</p>';
						for($j=0;$j< $numresults3;$j++){
							$row = $result3->fetch_assoc();
							
							echo '<ul>';
							echo '<li>'.$row['choices'].'</li>';
							echo '</ul>';
						}

						$q = 'select  name from taksi where id IN (select tid from apeuthinetai where qid='.$id.')';
						$result4 = $db->query($q);
						if (!$result4) {
					    	printf("Error: %s\n", mysqli_error($db));
					    	exit();
						}
						$numresults4 = mysqli_num_rows($result4);
						echo '<p>Aπεθύνεται σε μαθητές:</p>';
						for($j=0;$j< $numresults4;$j++){
							$row = $result4->fetch_assoc();
							
							echo '<ul>';
							echo '<li> '.$row['name'].'</li>';
							echo '</ul>';
						}
						echo "<label><input type=\"checkbox\" name=\"approve\" value=\"$id\" >Έγκριση</label>";
						echo "<label><input type=\"checkbox\" name=\"disapprove\" value=\"$id\" >Απόρριψη </label>";
						echo "<input type=\"submit\" value=\"Υποβολή\"></form>";
					}
/*mysqli_insert_id() to get the id */
/*select * from social.user U where U.id IN (select F.id2 from social.user U,social.friend F where U.id=122 and U.id=F.id1 );*/

				?>
			</div>
			
		</div>

	
	<?php

	/*echo 'users found :'.$numresults;
	*/
	}else{
		echo 'Problem with login. Please try again';
	}


	?>

	</body>

</html>