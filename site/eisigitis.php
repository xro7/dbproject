<html>



	<?php
	header('Content-Type: text/html; charset=utf-8');
	session_start();
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

		$db = new mysqli('localhost','xro','123','dbproject');
		mysql_query("SET NAMES 'utf8'");
		mysql_query("SET CHARACTER SET 'utf8'");
		if (mysqli_connect_errno()){
			echo 'Could not connect to database';
			exit;
		}
		$query = "select * from eisigitis where username='$username' and password = '$password'";
		$result = $db->query($query);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}

		$numresults = mysqli_num_rows($result);

		if ($numresults==1){
			
			$row = $result->fetch_assoc();
			$_SESSION['valid_user_name'] = $row['name'];
			$_SESSION['valid_user_username'] = $row['username'];
			$_SESSION['valid_user_id'] = $row['id'];

		}else {

			echo 'Not a valid user.Please Login again';
			exit();
		}
	}

	if(isset($_SESSION['valid_user_name'])){

	?>

	<head>
		<title><?php echo $_SESSION['valid_user_username'];  ?></title>
		<link rel="stylesheet" href="../css/userstyle.css">
		<meta http-equiv="content-type" content="text/html" charset="UTF-8">

	</head>
	<body>
		
		<div id="menu">

			<div id="topmenu">
				<h1>Πληροφοριακό Σύστημα Εξετάσεων</h1>
			</div>

			<div id="input">
				<?php echo '<h1>Εισηγητής '.$_SESSION['valid_user_name'].'</h1>';?>
				<a href="logout.php"> logout </a>
				<hr>
				<h3>Προσθήκη νέας ερώτησης</h3>

				<form action="addquestion.php" method="post">
					
				<textarea name="question" id="question" cols="30" rows="10"></textarea>
				<p>Επιλογές</p>
				<input type="checkbox" name="correct1" value="correct1" >
				<input type="text" id="choice1" name="choice1">
				<input type="checkbox" name="correct2" value="correct2" >
				<input type="text" id="choice2" name="choice2">
				<input type="checkbox" name="correct3" value="correct3" >
				<input type="text" id="choice3" name="choice3">
				<input type="checkbox" name="correct4" value="correct4" >
				<input type="text" id="choice4" name="choice4">
			
				<br>
				<p>Απεθύνεται στις τάξεις:</p>

				<label><input type="checkbox" name="a" value="a" >a </label>
				<label><input type="checkbox" name="b" value="b" >b </label>
				<label><input type="checkbox" name="g" value="g" >g</label>
				<label><input type="checkbox" name="d" value="d" >d </label>
				<label><input type="checkbox" name="e" value="e" >e </label>
				<label><input type="checkbox" name="st" value="st" >st </label>
				<br>

				<p>Δυσκολία</p>

				<select name="duskolia">
					  <option value="easy">easy</option>
					  <option value="medium">medium</option>
					  <option value="hard">hard</option>
					
				</select>
				<br>
				<br>
				<input type="submit" value="Προσθήκη">
			
				</form>

		
			

			</div>
			<hr>
			<div id="main">

				
				<h2>Ερωτήσεις</h2>
				<?php

					$db = new mysqli('localhost','xro','123','dbproject');
					if (mysqli_connect_errno()){
						echo 'Could not connect to database';
						exit;
					}

					$query = "select * from erwtisi where approved=1";
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