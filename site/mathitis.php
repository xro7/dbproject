<html>



	<?php
	include('dbconnect.php');
	session_start();
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		if(!$username || !$password){
			echo 'Παρακαλώ συμπληρώστε όλα τα πεδία';
			exit;
		}

		$db = dbconnect();
		$query = "select * from mathitis where username='$username' and password = '$password'";
		$result = $db->query($query);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}

		$numresults = mysqli_num_rows($result);

		if ($numresults==1){
			
			$row = $result->fetch_assoc();
			$_SESSION['valid_user_name4'] = $row['name'];
			$_SESSION['valid_user_username4'] = $row['username'];
			$_SESSION['valid_user_id4'] = $row['id'];

			$cid = $row['classid'];
			$_SESSION['classid4'] = $cid; 
			//$query = "select id from taksi where id in ( select taksiid from tmhmata where id=$cid) ";
			$query = "select name,taksiid from tmimata where id = $cid ";
			$result = $db->query($query);
			if (!$result) {
		  	  printf("Error: %s\n", mysqli_error($db));
		    exit();
			}

			$row = $result->fetch_assoc();
			$_SESSION['classname4'] = $row['name'];
			$_SESSION['tasksiid4'] = $row['taksiid'];


		}else {

			echo 'Not a valid user.Please Login again';
			exit();
		}
	}

	if(isset($_SESSION['valid_user_name4'])){

	?>

	<head>
		<title><?php echo $_SESSION['valid_user_name4'];  ?></title>
		<link rel="stylesheet" href="../css/userstyle.css">
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	</head>
	<body>
		
		

		<div id="topmenu">
			<h1>Πληροφοριακό Σύστημα Εξετάσεων</h1>
		</div>

		<div id="head">
			<?php echo '<h1>Μαθητής '.$_SESSION['valid_user_name4'].' στο τμήμα '.$_SESSION['classname4'].'</h1>';?>
			<a href="http://localhost/dbproject"> Αρχική </a>
			<a href="logout.php"> logout </a>
		</div>
				
		<hr>
		<div id="main">
			<h3>Διαγωνίσματα για εξέταση</h3>
			<?php 

				$db = dbconnect();

				$q = 'select D.name,D.id from mathitis as M,grafei as G,diagwnisma as D where M.id ='.$_SESSION['valid_user_id4'].' and  M.id = G.studentid and D.id = G.testid';
				$r= $db->query($q);
				if (!$r) {
			    	printf("Error: %s\n", mysqli_error($db));
			   		exit();
				}
				$n = mysqli_num_rows($r);


				echo '<ul>';
				for($i=0;$i< $n;$i++){		
					$row = $r->fetch_assoc();
					$testname = $row['name'];
					$testid = $row['id'];
					
					echo '<li>';
					echo '<form action="taketest.php" method="post">';
				
					echo '<input type="hidden" name="testid" value="'.$testid.'">';
					echo '<input type="submit" value="'.$testname.'">';
					echo '</form>';
					echo '</li>';

				}
				echo '</ul>';	
			
		
			?>

			<hr>
			<h3>Παλιά διαγωνίσματα</h3>
			<?php 

				$db = dbconnect();

				$q = 'select D.name,G.grade from mathitis as M,grafei as G,diagwnisma as D where M.id ='.$_SESSION['valid_user_id4'].' and  M.id = G.studentid and G.grade <> -1';
				$r= $db->query($q);
				if (!$r) {
			    	printf("Error: %s\n", mysqli_error($db));
			   		exit();
				}
				$n = mysqli_num_rows($r);


				echo '<ul>';
				for($i=0;$i< $n;$i++){		
					$row = $r->fetch_assoc();
					$testname = $row['name'];
					$grade = $row['grade'];
					
					echo '<li>';
					echo 'Διαγωνίσμα :'.$testname.' Βαθμός :'. $grade;
					echo '</li>';

				}
				echo '</ul>';	
			
		
			?>


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