<?php
	//$jsonstr = file_get_contents("php://input");
	$jsonstr = $_POST["data"];
	echo($jsonstr);
	/*
	$text = json_decode($jsonstr);
	echo("primary");
	$conn = mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16") or die(mysqli_error($conn));
	$sql = "INSERT INTO `test` VALUES({$text})";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));	
	*/
?>
