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
		echo '<form action="evaluate.php" method="post">';
		echo '<input type="hidden" name="testid" value="'.$_POST['testid'].'">';
		for($i=0;$i< $numresults;$i++){
			$row = $result->fetch_assoc();
			$id = $row['id'];
			
			echo '<h3> Eρώτηση: '.($i+1).' '. $row['text'].'</h3>';

			$q = 'select  id,choices from epiloges  where qid='.$id;
			$result2 = $db->query($q);
			if (!$result2) {
		    	printf("Error: %s\n", mysqli_error($db));
		    	exit();
			}
			
			$numresults2 = mysqli_num_rows($result2);
			echo '<p>Απαντήσεις:</p>';
			for($j=0;$j< $numresults2;$j++){
				$row = $result2->fetch_assoc();
				$cid=$row['id'];
				echo '<ul>';
				echo "<label><input type=\"checkbox\" name=\"".$cid."\" value=\"$cid\" >".$row['choices']."</label>";
				echo '</ul>';
			}




		}
		echo '<input type="submit" value="Υποβολή">';
		echo '<form>';

	}



}else{
	echo "Κάτι πήγε στραβά.";
	echo "<a href=\"mathitis.php\">goback</a>";
}

?>