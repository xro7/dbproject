<?php
session_start();
if (isset($_SESSION['valid_user_name2'])){
	$db = new mysqli('localhost','xro','123','dbproject');
	if (mysqli_connect_errno()){
		echo 'Could not connect to database';
		exit;
	}
	
	if(isset($_POST['approve'])){
		//echo $_POST['approve'];
		$id = $_POST['approve'];
		$query = "update erwtisi set approved=1 where id=$id";
		$result = $db->query($query);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}else{
			echo "Η ερώτηση εγκρίθηκε με επιτυχία";
			echo "<a href=\"elegktis.php\">goback</a>";
		}
	}elseif (isset($_POST['disapprove'])){
		//echo $_POST['disapprove'];
		$id = $_POST['disapprove'];
		$query = "select cid from exei where qid=$id";
		$result = $db->query($query);
		
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}
		$numresults = mysqli_num_rows($result);
		//echo 'epilgoes #'.$numresults ;
		for($i=0;$i< $numresults;$i++){
			$row = $result->fetch_assoc();
			$cid  = $row['cid'];
			//echo $cid;
			$query = "delete from  epiloges where id=$cid";
			$result2 = $db->query($query);
		
			if (!$result2) {
		  	  printf("Error: %s\n", mysqli_error($db));
		  	  exit();
			}
		}



		$query = "delete from  erwtisi where id=$id";
		$result = $db->query($query);
	
		if (!$result) {
	  	  printf("Error: %s\n", mysqli_error($db));
	  	  exit();
		}

		echo "Η ερώτηση διαγράφηκε με επιτυχία  ";
		echo "<a href=\"elegktis.php\">goback</a>";

	}
}

?>