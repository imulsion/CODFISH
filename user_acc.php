<?php 
	session_start();
	if(!isset($_SESSION["UserID"]))
	{
		echo("<p style = 'color:#FF0000'>You must be logged in to view this page. Click <a href = 'index.php'>here</a> to sign in.</p>");
		die();
	}
	else
	{
		$conn = mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16");
		if(!$conn)
		{
			die(mysqli_error($conn));
		}
		$sql = "SELECT * FROM `users` WHERE UserID='{$_SESSION["UserID"]}'";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		$INFO = mysqli_fetch_assoc($result);
	}
	function printTimes($type,$day,$DATA)
	{
		if($type==0)
		{
			for($i=0;$i<24;$i++)
			{
				if(floor($DATA[$day]/60)==$i)
				{
					if($i<10)
					{
						echo("<option value = '".$i."'selected>0".$i."</option>");
					}
					else
					{
						echo("<option value = '".$i."' selected>".$i."</option>");
					}
				}
				else
				{
					if($i<10)
					{
						echo("<option value = '".$i."'>0".$i."</option>");
					}
					else
					{
						echo("<option value = '".$i."'>".$i."</option>");
					}
				}	
			}
		}
		else
		{
			for($i=0;$i<60;$i++)
			{
				if($DATA[$day]%60==$i)
				{
					if($i<10)
					{
						echo("<option value = '".$i."' selected>0".$i."</option>");
					}
					else
					{
						echo("<option value = '".$i."' selected>".$i."</option>");
					}
				}
				else
				{
					if($i<10)
					{
						echo("<option value = '".$i."'>0".$i."</option>");
					}
					else
					{
						echo("<option value = '".$i."'>".$i."</option>");
					}
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Account settings</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="webInterface.css">
</head>
<body>
<table style = "width:100%"><tr>
<td>
<div style = "width:50%;float:left;">
<p style = "float:left;">Welcome <?php echo($_SESSION["UserID"]);?></p> 
</div>
</td>
<td>
<div style = "width:50%;float:right;">
<a href = "logout.php">Logout</a>
</div>
</td>
</tr>
</table>
<form method = "post" action="user_update.php">
<fieldset>
<legend>User Info</legend>
<div id = "userInfo">
<p>Username: <?php echo($_SESSION["UserID"]);?></p>
<input class = "textInput" type = "text" name= "email">
<input class = "buttonChange" type = "submit" value = "Change">
<br />
<p>Email: <?php echo($INFO["email"]);?></p>
<input class = "textInput" type = "text" name = "email">
<input class = "buttonChange" type = "submit" value = "Change">
</div>
</fieldset>
</form>
<br /><br />
<form method = "post" action = "time_change.php">
<fieldset>
<legend>Alarm  Times</legend>
<div id ="alarmTimes">
<div id = "daysBox">
<div class="days">Monday</div>
<div class="days">Tuesday</div>
<div class="days">Wednesday</div>
<div class="days">Thursday</div>
<div class="days">Friday</div>
<div class="days">Saturday</div>
<div class="days">Sunday</div>
</div>
<div id = "timesBox">
<div class = "timesInner">
<select name = "mondayHours"><?php	printTimes(0,"Monday_user",$INFO);?></select>:
<select name = "mondayMinutes"><?php printTimes(1,"Monday_user",$INFO);?></select>
</div>
<div class = "timesInner">
<select name = "tuesdayHours"><?php printTimes(0,"Tuesday_user",$INFO);?></select>:
<select name = "tuesdayMinutes"><?php printTimes(1,"Tuesday_user",$INFO);?></select>
</div>
<div class = "timesInner">
<select name = "wednesdayHours"><?php printTimes(0,"Wednesday_user",$INFO);?></select>:
<select name = "wednesdayMinutes"><?php printTimes(1,"Wednesday_user",$INFO);?></select>
</div>
<div class = "timesInner">
<select name = "thursdayHours"><?php printTimes(0,"Thursday_user",$INFO);?></select>:
<select name = "thursdayMinutes"><?php printTimes(1,"Thursday_user",$INFO);?></select>
</div>
<div class = "timesInner">
<select name = "fridayHours"><?php printTimes(0,"Friday_user",$INFO);?></select>:
<select name = "fridayMinutes"><?php printTimes(1,"Friday_user",$INFO);?></select>
</div>
<div class = "timesInner">
<select name = "saturdayHours"><?php printTimes(0,"Saturday_user",$INFO);?></select>:
<select name = "saturdayMinutes"><?php printTimes(1,"Saturday_user",$INFO);?></select>
</div>
<div class = "timesInner">
<select name = "sundayHours"><?php printTimes(0,"Sunday_user",$INFO);?></select>:
<select name = "sundayMinutes"><?php printTimes(1,"Sunday_user",$INFO);?></select>
</div>
<input class = "button" type = "submit" value = "Update times">
<p style = "color:red"><?php
if(isset($_GET["update"]))
{
	if($_GET["update"])
		echo("Times successfully updated");
	else
		echo("Error: Times failed to update");
}
?></p>
</div>
</div>
</div>
</fieldset>
</form>
</body>
</html>
		











