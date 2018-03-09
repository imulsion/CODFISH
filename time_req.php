<?php
	$username=$_POST["UserID"];
	
	$conn=mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16");
	if(!$conn)
	{
		die(mysqli_error($conn));
	}
	if(isset($_POST["time_get"]))
	{
		$sql = "SELECT ".$_POST["day"]."_calendar FROM `users` WHERE UserID='".$username."'";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		if(mysqli_num_rows($result)==0)
		{
			echo("<p style = 'color:red'>Error: Username not found.</p>");
			die();
		}
		$data=mysqli_fetch_row($result);
		if($data[0]==1440)
		{	
			$sql = "SELECT ".$_POST["day"]."_user FROM `users` WHERE UserID='".$username."'";
			$result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
			$data=mysqli_fetch_row($result);
		}
		if($data[0]==1440)
		{
			$sql = "SELECT default_time FROM `users` WHERE UserID='".$username."'";
			$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
			$data=mysqli_fetch_row($result);
		}
		$hours = floor($data[0]/60);
		$mins = $data[0]%60;
		if($hours<10)
		{
			echo("0".$hours.":");
		}
		else
		{
			echo($hours.":");
		}
		if($mins<10)
		{
			echo("0".$mins);
		}
		else
		{
			echo($mins);
		}
	}
	
	
	if(isset($_POST["default_time"]))
	{
		$dtime = $_POST["default_time"];
		$sql = "UPDATE `users` SET default_time='".$dtime."' WHERE UserID='".$username."'";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
	if(isset($_POST["time_before_event"]))
	{
		$tevent = $_POST["time_before_event"];
		$sql = "UPDATE `users` SET Mins_before_first_event='".$tevent."' WHERE UserID='".$username."'";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
	
?>
