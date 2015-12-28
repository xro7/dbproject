<?php
session_start();
if (isset($_SESSION['valid_user_name'])){
	unset($_SESSION['valid_user_name']);
	unset($_SESSION['valid_user_username']);
	unset($_SESSION['valid_user_id']);
	session_destroy();
	header('Location: http://localhost/dbproject');
	exit();
}else{
	header('Location: http://localhost/dbproject');
	exit();
}

?>