<html>



	<?php
	include('dbconnect.php');
	session_start();
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		if(!$username || !$password){
			echo 'Please go back and enter the credentials, then try again';
			exit;
		}

		$db = dbconnect();
		$query = "select * from kathigitis where username='$username' and password = '$password'";
		$result = $db->query($query);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}

		$numresults = mysqli_num_rows($result);

		if ($numresults==1){
			
			$row = $result->fetch_assoc();
			$_SESSION['valid_user_name3'] = $row['name'];
			$_SESSION['valid_user_username3'] = $row['username'];
			$_SESSION['valid_user_id3'] = $row['id'];

			$cid = $row['classid'];
			$_SESSION['classid3'] = $row['classid'];
			//$query = "select id from taksi where id in ( select taksiid from tmhmata where id=$cid) ";
			$query = "select name,taksiid from tmimata where id = $cid ";
			$result = $db->query($query);
			if (!$result) {
		  	  printf("Error: %s\n", mysqli_error($db));
		    exit();
			}

			$row = $result->fetch_assoc();
			$_SESSION['classname3'] = $row['name'];
			$_SESSION['tasksiid3'] = $row['taksiid'];


		}else {

			echo 'Not a valid user.Please Login again';
			exit();
		}
	}

	if(isset($_SESSION['valid_user_name3'])){

	?>

	<head>
		<title><?php echo $_SESSION['valid_user_username3'];  ?></title>
		<link rel="stylesheet" href="../css/userstyle.css">
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	</head>
	<body>
		
		<div id="menu">

			<div id="topmenu">
				<h1>Πληροφοριακό Σύστημα Εξετάσεων</h1>
			</div>

			<div id="input">
				<?php echo '<h1>Καθηγητής '.$_SESSION['valid_user_name3'].' στο τμήμα '.$_SESSION['classname3'].'</h1>';?>
				<a href="http://localhost/dbproject"> Αρχική </a>
				<a href="logout.php"> logout </a>

				<h3>Διαγωνίσματα:</h3>

				<?php  
				$db = dbconnect();

					$query = 'select name from diagwnisma where kathigitisid='.$_SESSION['valid_user_id3'];
					$result = $db->query($query);
					if (!$result) {
					    printf("Error: %s\n", mysqli_error($db));
					    exit();
					}

					$numresult = mysqli_num_rows($result);
					
					for($j=0;$j< $numresult;$j++){
						$row = $result->fetch_assoc();
						
						echo '<ul>';
						echo '<li>'.($j+1). ' '.$row['name'].'</li>';
						echo '</ul>';
					}

				?>

				<hr>
				
 				<h3>Δημιουργία τυχαίου διαγωνίσματος</h3>

				<form action="createtest.php" method="post">

					<p>Όνομα διαγωνίσματος</p>

					<input type="text" name="name">
	

					<p>Επιλογή δυσκολίας</p>

					<select name="duskolia">
						  <option value="easy">easy</option>
						  <option value="medium">medium</option>
						  <option value="hard">hard</option>
						
					</select>
					<br>
					<br>
					<input type="submit" name= "random" value="Δημιουργία">
							
				</form> 

		
			

			</div>
			<hr>
			<div id="main">




				<hr>

				
				<h3>Δημιουργία διαγωνίσματος με επιλογή ερωτήσεων</h3>

				<p>Όνομα διαγωνίσματος</p>

				<form action="createtest.php" method="post">

				<input type="text" name="name">
				<?php

					$db = dbconnect();

					$query = 'select * from erwtisi where approved=1 and id in (select qid from apeuthinetai where tid ='.$_SESSION['tasksiid3'].' )';
					$result = $db->query($query);
					if (!$result) {
					    printf("Error: %s\n", mysqli_error($db));
					    exit();
					}

					$numresults = mysqli_num_rows($result);
					for($i=0;$i< $numresults;$i++){
						$row = $result->fetch_assoc();
						echo '<h3> Eρώτηση: '.($i+1).' '. $row['text'].', Δυσκολία: '.$row['dyskolia'].'</h3>';
						$id = $row['id'];


						////////////prosthiki onomatos eisigiti kai elegkti////////////
						$q = 'select name from eisigitis where id in (select  eisigitis from erwtisi  where id='.$id.')';
						$res = $db->query($q);
						if (!$res) {
					    	printf("Error: %s\n", mysqli_error($db));
					    	exit();
						}
						$q = 'select name from elegktis where id in (select  elegktis from erwtisi  where id='.$id.')';
						$res2 = $db->query($q);
						if (!$res2) {
					    	printf("Error: %s\n", mysqli_error($db));
					    	exit();
						}
						$numres = mysqli_num_rows($res);
						$numres2 = mysqli_num_rows($res2);
						if($numres<1 || $numres2<1){
							echo 'something went wrong';
					    	exit();
						}else{
							$row = $res->fetch_assoc();
							$row2 = $res2->fetch_assoc();
							echo '<p> Εισηγητής: '. $row['name'].', Eλεγκτής: '. $row2['name'].'</p>';
						}
						////////////////////////////////////////////////////////////////////////
						

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

						//$q = 'select  choices from epiloges  where correct=1 and id IN (select cid from exei where qid='.$id.')';
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
						echo "<label><input type=\"checkbox\" name=\"question$i\" value=\"$id\" >Επιλογή Ερώτησης </label>";
						//echo "<input type=\"submit\" value=\"Υποβολή\"></form>";

					}
/*mysqli_insert_id() to get the id */
/*select * from social.user U where U.id IN (select F.id2 from social.user U,social.friend F where U.id=122 and U.id=F.id1 );*/

				?>

				<input type="submit" name= "manual" value="Δημιουργία">
							
				</form> 
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