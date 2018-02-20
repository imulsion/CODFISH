<?php 
	session_start();
	$conn = mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16");
	if(!$conn)
	{
		die(mysqli_error($conn));
	}
	else
	{
		$username = $_POST["login_Username"];
		$password = $_POST["login_Password"];
		$sql = "SELECT * FROM `users` WHERE UserID='$username' AND Pass='$password'";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		if(mysqli_num_rows($result)==0)
		{
			if(isset($_SESSION["UserID"])){unset($_SESSION["UserID"]);}
			echo("<script type = 'text/javascript'>window.location.replace('index.php?logtype=0');</script>");
		}
		else
		{
			$values = mysqli_fetch_assoc($result);
			$_SESSION["UserID"]=$values["UserID"];
			echo("<script type = 'text/javascript'>window.location.replace('index.php?logtype=1');</script>");
		}
	}
?>
