<?php
session_start();
if (isset($_SESSION['valid_user_name'])){
	if (isset($_POST['question']) && isset($_POST['duskolia'])){
		$q = $_POST['question'];
		$d = $_POST['duskolia'];
		/*echo $_POST['question'].' '.$_POST['duskolia'];*/
		$db = new mysqli('localhost','xro','123','dbproject');
		if (mysqli_connect_errno()){
			echo 'Could not connect to database';
			exit;
		}
		
		$query = "insert into erwtisi(text,dyskolia) values ('$q','$d')";
		$result = $db->query($query);
		$id = mysqli_insert_id($db);
		if (!$result) {
		    printf("Error: %s\n", mysqli_error($db));
		    exit();
		}else {
			//echo 'added to database with id '.$id;
		}

		for($i=0;$i<4;$i++){
			$choice = 'choice'.($i+1);
			$correct = 'correct'.($i+1);
			
			
			if (isset($_POST[$choice]) && $_POST[$choice]!= ''){
				$c = $_POST[$choice];
				if (isset($_POST[$correct])){
					$cor = $_POST[$correct];
					//echo $_POST[$choice].' swsth';
					$query = "insert into epiloges(choices,correct) values ('$c',1)";
					$result = $db->query($query);
					$cid = mysqli_insert_id($db);
					if (!$result) {
		   				printf("Error: %s\n", mysqli_error($db));
		    			exit();
		    		}else {
						//echo 'added to database with id '.$cid;
					}

				}else{
					//echo $_POST[$choice].' lathos';
					$query = "insert into epiloges(choices,correct) values ('$c',0)";
					$result = $db->query($query);
					$cid = mysqli_insert_id($db);
					if (!$result) {
		   				printf("Error: %s\n", mysqli_error($db));
		    			exit();
		    		}else {
						//echo 'added to database with id '.$cid;
					}
				}


				$query = "insert into exei(qid,cid) values ($id,$cid)";
				$result = $db->query($query);
				$cid = mysqli_insert_id($db);
				if (!$result) {
		   			printf("Error: %s\n", mysqli_error($db));
		    		exit();
		    	}else {
					//echo 'H erwthsh kataxwrithke me epituxia ';

				}

			}
		}


		//pou apeuthinetai

		if (isset($_POST['a'])){
			
			addtodb($id,2);
		}
		if (isset($_POST['b'])) {
			addtodb($id,3);
		}
		if (isset($_POST['g']) ){
			addtodb($id,4);
		}
		if (isset($_POST['d'])) {
			addtodb($id,5);
		}
		if (isset($_POST['e']) ){
			addtodb($id,6);
		}
		if (isset($_POST['st'])) {
			addtodb($id,7);
		}

		echo "h eisagwgh egine me epituxia";


	}
}

function addtodb($id,$tid){
	$db = new mysqli('localhost','xro','123','dbproject');
	if (mysqli_connect_errno()){
		echo 'Could not connect to database';
		exit;
	}
	$query = "insert into apeuthinetai(qid,tid) values ($id,$tid)";
	$result = $db->query($query);
	$cid = mysqli_insert_id($db);
	if (!$result) {
		printf("Error: %s\n", mysqli_error($db));
		exit();
	}
}

?>