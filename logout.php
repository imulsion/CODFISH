<?php 
	session_start();
	session_unset();
	session_destroy();
	$_SESSION = array();
	echo("<script type = 'text/javascript'>window.location.replace('index.php?logtype=2')</script>");
?>
