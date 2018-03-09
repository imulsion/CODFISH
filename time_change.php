<?php
	session_start();
	$conn = mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16");
	if(!$conn)
	{
		echo("<script type = 'text/javascript'>window.location.replace('user_acc.php?update=0');</script>");
	}
	$ssh=ssh2_connect('',);//fill in when rhett tells me what it is
	ssh2_auth_password($ssh,'','');//...
	$stream=ssh2_exec($ssh,'python /path/to/script');//...
	$c_times=explode(',',$stream);
	$u_times = array(60*$_POST["mondayHours"]+$_POST["mondayMinutes"],60*$_POST["tuesdayHours"]+$_POST["tuesdayMinutes"],60*$_POST["wednesdayHours"]+$_POST["wednesdayMinutes"],60*$_POST["thursdayHours"]+$_POST["thursdayMinutes"],60*$_POST["fridayHours"]+$_POST["fridayMinutes"],60*$_POST["saturdayHours"]+$_POST["saturdayMinutes"],60*$_POST["sundayHours"]+$_POST["sundayMinutes"]);
	$sql = "UPDATE `users` SET";
	
	if(isset($_POST["googleMonday"]))
	{
		$sql." Monday_calendar='".$c_times[0]."'";
	}
	else
	{
		$sql." Monday_user='".$u_times[0]."' Monday_calendar='1440'";
	}
	
	if(isset($_POST["googleTuesday"]))
	{
		$sql." Tuesday_calendar='".$c_times[1]."'";
	}
	else
	{
		$sql." Tuesday_user='".$u_times[1]."' Tuesday_calendar='1440'";
	}
	
	if(isset($_POST["googleWednesday"]))
	{
		$sql." Wednesday_calendar='".$c_times[2]."'";
	}
	else
	{
		$sql." Wednesday_user='".$u_times[2]."' Wednesday_calendar='1440'";
	}
	
	if(isset($_POST["googleThursday"]))
	{
		$sql." Saturday_calendar='".$c_times[3]."'";
	}
	else
	{
		$sql." Thursday_user='".$u_times[3]."' Thursday_calendar='1440'";
	}
	
	if(isset($_POST["googleFriday"]))
	{
		$sql." Friday_calendar='".$c_times[4]."'";
	}
	else
	{
		$sql." Friday_user='".$u_times[4]."' Friday_calendar='1440'";
	}	
	
	if(isset($_POST["googleSaturday"]))
	{
		$sql." Saturday_calendar='".$c_times[5]."'";
	}
	else
	{
		$sql." Saturday_user='".$u_times[5]."' Saturday_calendar='1440'";
	}
	
	if(isset($_POST["googleSunday"]))
	{
		$sql." Sunday_calendar='".$c_times[6]."'";
	}
	else
	{
		$sql." Sunday_user='".$u_times[6]."' Sunday_calendar='1440'";
	}
	
	$sql." WHERE UserID='".$_SESSION["UserID"]."'";
	$result = mysqli_query($conn,$sql);
	if(!$result)
	{
		echo("<script type = 'text/javascript'>window.location.replace('user_acc.php?update=0');</script>");
	}
	else
	{
		echo("<script type = 'text/javascript'>window.location.replace('user_acc.php?update=1');</script>"); 
	
	}
?>
