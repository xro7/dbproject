<?php
include('dbconnect.php');
session_start();
$db = dbconnect();
if (isset($_SESSION['valid_user_name3'])){
	$kathigitisid = $_SESSION['valid_user_id3'];
	$tid = $_SESSION['tasksiid3'];
	if(isset($_POST['random'])&& $_POST['name']!=""){
		$name = $_POST['name'];
		$d = $_POST['duskolia'];
		$q = "select id from erwtisi where dyskolia = '$d' and id IN (select qid from apeuthinetai where tid='$tid') order by rand() limit 10";
		$result = $db->query($q);
		if (!$result) {
	    	printf("Error: %s\n", mysqli_error($db));
	    	exit();
		}
		$numresults = mysqli_num_rows($result);
		if ($numresults == 0){
			echo "Δεν υπάρχουν διαθέσιμες ερωτήσεις ";
			echo "<a href=\"kathigitis.php\">goback</a>";
		}
		else{

			$query = "insert into diagwnisma(name,kathigitisid) values ('$name','$kathigitisid')";
			$res = $db->query($query);
			$testid = mysqli_insert_id($db);
			if (!$res) {
			    printf("Error: %s\n", mysqli_error($db));
			    exit();
			}				
		
			for($j=0;$j< $numresults;$j++){
				$row = $result->fetch_assoc();
				$qid = $row['id'];
				$query = "insert into periexei(testid,qid) values ('$testid','$qid')";
				$res = $db->query($query);
				//$testid = mysqli_insert_id($db);
				if (!$res) {
		   			printf("Error: %s\n", mysqli_error($db));
		    		exit();
				}	
		

			}
			echo "To διαγώνισμα δημιουργήθηκε με επιτυχία ";
			echo "<a href=\"kathigitis.php\">goback</a>";

		}
	}elseif(isset($_POST['manual'])&& $_POST['name']!=""){
		$name = $_POST['name'];
		$query = 'select * from erwtisi where approved=1 and id in (select qid from apeuthinetai where tid ='.$_SESSION['tasksiid3'].' )';
		$result = $db->query($query);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}
		$checkempty = 0;
		$numresults = mysqli_num_rows($result);

		$query = "insert into diagwnisma(name,kathigitisid) values ('$name','$kathigitisid')";
		$res = $db->query($query);
		$testid = mysqli_insert_id($db);
		if (!$res) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}	

		for($i=0;$i< $numresults;$i++){
			$question = 'question'.$i;
			if (isset($_POST[$question])){
				$checkempty = 1;
				$qid = $_POST[$question];
				$query = "insert into periexei(testid,qid) values ($testid,$qid)";
				//echo $testid.' '. $qid;
				$res2 = $db->query($query);
				if (!$res2) {
		  	  		printf("Error: %s\n", mysqli_error($db));
		   	  	    exit();
				}	
			}
		}

		echo "To διαγώνισμα δημιουργήθηκε με επιτυχία ";
		echo "<a href=\"kathigitis.php\">goback</a>";
/*		if ($checkempty==0){
			echo "Συμπηρώστε όλα τα πεδία ";
			echo "<a href=\"kathigitis.php\">goback</a>";
			exit();
		}*/



	}else{
			echo "Συμπηρώστε όλα τα πεδία ";
			echo "<a href=\"kathigitis.php\">goback</a>";
	}
}else{
	 echo "<a href=\"kathigitis.php\">goback</a>";
}
?>