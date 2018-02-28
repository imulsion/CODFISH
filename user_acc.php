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
<script type = 'text/javascript'>
function reveal(x)
{
	if(x)
	{
		document.getElementById("email").style.visibility="visible";
		document.getElementById("email_button").style.visibility="visible";
	}
	else
	{
		document.getElementById("username").style.visibility="visible";
		document.getElementById("username_button").style.visibility="visible";
	}
}
function toggleDisabled(x,classname)
{
	var dropdowns = document.getElementsByClassName(classname);
	dropdowns[0].disabled= x?true:false;
	dropdowns[1].disabled= x?true:false;
}
</script>
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
<table>
<tr>
<td>
<p>Username: <?php echo($_SESSION["UserID"]);?></p>
</td>
<td>
<a onclick="reveal(0)" href="#">Change</a>
</td>
</tr>
<tr>
<td>
<input id="username" class = "textInput" type = "text" name= "username" style="visibility:hidden">
<input id = "username_button" class = "buttonChange" type = "submit" value = "Update" style = "visibility:hidden">
</td>
</tr>
<tr>
<td>
<p>Email: <?php echo($INFO["email"]);?></p>
</td>
<td>
<a onclick="reveal(1)" href="#">Change</a>
</td>
</tr>
<tr>
<td>
<input id = "email" class = "textInput" type = "text" name = "email" style="visibility:hidden">
<input id="email_button" class = "buttonChange" type = "submit" value = "Update" style="visibility:hidden">
</td>
</tr>
</table>
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
<select class = "googleMonday" name = "mondayHours" <?php if($INFO["Monday_calendar"]!=1440){echo("disabled");}?>><?php	printTimes(0,"Monday_user",$INFO);?></select>:
<select class = "googleMonday" name = "mondayMinutes" <?php if($INFO["Monday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(1,"Monday_user",$INFO);?></select>
<label for="googleMonday">Sync with Google calendar</label>
<input type = "checkbox" onchange="toggleDisabled(this.checked,'googleMonday')" name="googleMonday" <?php if($INFO["Monday_calendar"]!=1440){echo("checked");}?>>
</div>
<div class = "timesInner">
<select class = "googleTuesday" name = "tuesdayHours" <?php if($INFO["Tuesday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(0,"Tuesday_user",$INFO);?></select>:
<select class = "googleTuesday" name = "tuesdayMinutes" <?php if($INFO["Tuesday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(1,"Tuesday_user",$INFO);?></select>
<label for="googleTuesday">Sync with Google calendar</label>
<input type = "checkbox" onchange="toggleDisabled(this.checked,'googleTuesday')" name="googleTuesday" <?php if($INFO["Tuesday_calendar"]!=1440){echo("checked");}?>>
</div>
<div class = "timesInner">
<select class = "googleWednesday" name = "wednesdayHours" <?php if($INFO["Wednesday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(0,"Wednesday_user",$INFO);?></select>:
<select class = "googleWednesday" name = "wednesdayMinutes" <?php if($INFO["Wednesday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(1,"Wednesday_user",$INFO);?></select>
<label for="googleWednesday">Sync with Google calendar</label>
<input type = "checkbox" onchange="toggleDisabled(this.checked,'googleWednesday')" name="googleWednesday" <?php if($INFO["Wednesday_calendar"]!=1440){echo("checked");}?>>
</div>
<div class = "timesInner">
<select class = "googleThursday" name = "thursdayHours" <?php if($INFO["Thursday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(0,"Thursday_user",$INFO);?></select>:
<select class = "googleThursday" name = "thursdayMinutes" <?php if($INFO["Thursday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(1,"Thursday_user",$INFO);?></select>
<label for="googleThursday">Sync with Google calendar</label>
<input type = "checkbox" onchange="toggleDisabled(this.checked,'googleThursday')" name="googleThursday" <?php if($INFO["Thursday_calendar"]!=1440){echo("checked");}?>>
</div>
<div class = "timesInner">
<select class = "googleFriday" name = "fridayHours" <?php if($INFO["Friday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(0,"Friday_user",$INFO);?></select>:
<select class = "googleFriday" name = "fridayMinutes" <?php if($INFO["Friday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(1,"Friday_user",$INFO);?></select>
<label for="googleFriday">Sync with Google calendar</label>
<input type = "checkbox" onchange="toggleDisabled(this.checked,'googleFriday')" name="googleFriday" <?php if($INFO["Friday_calendar"]!=1440){echo("checked");}?>>
</div>
<div class = "timesInner">
<select class = "googleSaturday" name = "saturdayHours" <?php if($INFO["Saturday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(0,"Saturday_user",$INFO);?></select>:
<select class = "googleSaturday" name = "saturdayMinutes" <?php if($INFO["Saturday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(1,"Saturday_user",$INFO);?></select>
<label for="googleSaturday">Sync with Google calendar</label>
<input type = "checkbox" onchange="toggleDisabled(this.checked,'googleSaturday')" name="googleSaturday" <?php if($INFO["Saturday_calendar"]!=1440){echo("checked");}?>>
</div>
<div class = "timesInner">
<select class = "googleSunday" name = "sundayHours" <?php if($INFO["Sunday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(0,"Sunday_user",$INFO);?></select>:
<select class = "googleSunday" name = "sundayMinutes" <?php if($INFO["Sunday_calendar"]!=1440){echo("disabled");}?>><?php printTimes(1,"Sunday_user",$INFO);?></select>
<label for="googleSunday">Sync with Google calendar</label>
<input type = "checkbox" onchange="toggleDisabled(this.checked,'googleSunday')" name="googleSunday" <?php if($INFO["Sunday_calendar"]!=1440){echo("checked");}?>>
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
		











