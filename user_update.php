<?php 
	session_start();
	$conn = mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16");
	if(!$conn)
	{
		die(mysqli_error($conn));
	}
	
	if(empty($_POST["email"])||!isset($_POST["email"]))
	{
		if(empty($_POST["username"])||!isset($_POST["username"]))
		{
			header("Location: user_acc.php");
		} 
		else
		{
			$sql = "UPDATE `users` SET UserID='".$_POST["username"]."' WHERE UserID='".$_SESSION["UserID"]."'";
			$_SESSION["UserID"]=$_POST["username"];
		}
	}
	else
	{
		if(empty($_POST["username"])||!isset($_POST["username"]))
		{
			$sql = "UPDATE `users` SET email='".$_POST["email"]."' WHERE UserID='".$_SESSION["UserID"]."'";
		}	
		else
		{
			$sql = "UPDATE `users` SET UserID='".$_POST["username"]."' email='".$_POST["email"]."' WHERE UserID='".$_SESSION["UserID"]."'";
			$_SESSION["UserID"]=$_POST["username"];
		}
	}
	if(!mysqli_query($conn,$sql)){ die(mysqli_error($conn));}
	header("Location: user_acc.php");
?>
	
