<?php
session_start();
if (isset($_SESSION['valid_user_name'])|| isset($_SESSION['valid_user_name2'])||isset($_SESSION['valid_user_name3'])){
	unset($_SESSION['valid_user_name']);
	unset($_SESSION['valid_user_username']);
	unset($_SESSION['valid_user_id']);
	unset($_SESSION['valid_user_name2']);
	unset($_SESSION['valid_user_username2']);
	unset($_SESSION['valid_user_id2']);
	unset($_SESSION['valid_user_name3']);
	unset($_SESSION['valid_user_username3']);
	unset($_SESSION['valid_user_id3']);
	unset($_SESSION['classname3']); 
	unset($_SESSION['tasksiid3']);
	session_destroy();
	header('Location: http://localhost/dbproject');
	exit();
}else{
	header('Location: http://localhost/dbproject');
	exit();
}

?>