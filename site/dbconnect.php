<?php
function dbconnect(){
	$db = new mysqli('localhost','xro','123','dbproject');
	if (mysqli_connect_errno()){
		echo 'Could not connect to database';
		exit;
	}else{
		return $db;
	}
}

?>