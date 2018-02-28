<?php
	session_start();
	$conn = mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16");
	if(!$conn)
	{
		echo("<script type = 'text/javascript'>window.location.replace('user_acc.php?update=0');</script>");
	}
	$data = array(60*$_POST["mondayHours"]+$_POST["mondayMinutes"],60*$_POST["tuesdayHours"]+$_POST["tuesdayMinutes"],60*$_POST["wednesdayHours"]+$_POST["wednesdayMinutes"],60*$_POST["thursdayHours"]+$_POST["thursdayMinutes"],60*$_POST["fridayHours"]+$_POST["fridayMinutes"],60*$_POST["saturdayHours"]+$_POST["saturdayMinutes"],60*$_POST["sundayHours"]+$_POST["sundayMinutes"]);
	$sql = "UPDATE `users` SET Monday_user={$data[0]},Tuesday_user={$data[1]},Wednesday_user={$data[2]},Thursday_user={$data[3]},Friday_user={$data[4]},Saturday_user={$data[5]},Sunday_user={$data[6]} WHERE UserID='$_SESSION[UserID]'";
	$result = mysqli_query($conn,$sql);
	if(!$result)
	{
		echo("<script type = 'text/javascript'>window.location.replace('user_acc.php?update=0');</script>");
	}
	echo("<script type = 'text/javascript'>window.location.replace('user_acc.php?update=1');</script>"); 
?>
