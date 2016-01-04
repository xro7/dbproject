<?php
include('dbconnect.php');
session_start();
$db = dbconnect();
if (isset($_SESSION['valid_user_name4'])){

	if (isset($_POST['testid'])){
		$q= 'select * from periexei,erwtisi where testid ='.$_POST['testid'].' and qid = id';
		$result = $db->query($q);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}
		$numresults = mysqli_num_rows($result);
		$part = 20/$numresults ;
		$grade = 0.0;
		for($i=0;$i< $numresults;$i++){
			$row = $result->fetch_assoc();
			$id = $row['id'];
			
			echo '<h3> Eρώτηση: '.($i+1).' '. $row['text'].'</h3>';

			$q = 'select  id,choices,correct from epiloges  where qid='.$id;
			$result2 = $db->query($q);
			if (!$result2) {
		    	printf("Error: %s\n", mysqli_error($db));
		    	exit();
			}
			$swsth = 1;
			$numresults2 = mysqli_num_rows($result2);
			
			
			for($j=0;$j< $numresults2;$j++){
				$row = $result2->fetch_assoc();
				$cid=$row['id'];
				$correct = $row['correct'];
				if($correct==1 && isset($_POST[$cid])){
					//echo 'swsth ';
				}elseif ($correct==0 && !isset($_POST[$cid])){
					///echo 'swsth ';
				}else{
					$swsth=0;;
				}
			}
			if ($swsth){
				echo 'Σωστή';
				$grade = $grade+$part;
			}else{
				echo 'Λάθος';
			}
			




		}

		echo '<h2>Βαθμός : '.$grade.'<h2>';


	}

}

?>