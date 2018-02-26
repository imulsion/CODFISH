<?php
	session_start(); 
	if($_SERVER["HTTPS"]!="on")
	{
		header("Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		die();
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>SAC Homepage</title>
<script type = "text/javascript">
function validate(x)
{
	if(x)
	{
		if(!passCheck())
		{
			return false;
		}
		if( (document.forms["signup"]["signup_Username"].value==="")||(document.forms["signup"]["email"].value==="") )
		{
			document.getElementById("error").innerHTML = "Error: One or more required fields is empty";
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		if( (document.forms["signup"]["login_Username"].value==="")||(document.forms["login"]["login_Password"].value===""))
		{
			document.getElementById("l_error").innerHTML = "Error: Username/password pair not found";
			return false;
		}
		else
		{
			return true;
		}
	}
}
function passCheck()
{
	if(document.getElementById("Pass").value===document.getElementById("CPass").value)
	{
		return true;
	}
	else
	{
		alert("Your passwords do not match");
		return false;
	}
}
</script>
</head>
<body>
<table style = "width:100%"><tr><td width = "33%">
<?php 
	if(isset($_GET["logtype"])&&!isset($_GET["serror"]))
	{
		echo("<p style = 'color:red'>");
		if($_GET["logtype"]==1)
		{
			echo("Login successful</p>");
		}	
		else if($_GET["logtype"]==0)
		{	
			echo("Login failed: Your username or password was incorrect.</p>");
		}
		else if($_GET["logtype"]==2)
		{
			echo("Error: You are already signed in to an account.</p>");
		}
		else
		{
			echo("You have been successfully logged out</p>");
		}
	}
	else if(!isset($_GET["logtype"])&&isset($_GET["serror"]))
	{
		echo("<p style = 'color:#FF0000'>");
		if($_GET["serror"]==0)
		{
			echo("Error: This username has already been taken by another user.</p>");
		}
		else
		{
			echo("Error: This email address is already registered in this website.</p>");
		}
	}
	else{}
?>
</td><td width = "33%">
<h1 align="center">Welcome to SAC. Log in or Sign up to continue</h1></td>
<td width = "33%"><p align="right"><?php if(isset($_SESSION["UserID"])){echo("Welcome <a href = 'user_acc.php'>".$_SESSION["UserID"]."</a></p><a style='float:right' href = 'logout.php'>Logout</a>");}?></td></tr></table>

<br />
<table style = "width:100%">
<tr><td width = "50%">
<div align = "center">
<form name = "login" action = "login.php" method = "post" onsubmit = "return validate(0)">
<fieldset>
<legend>Log in:</legend><br/><br/><br/>
Username:<br/><br/>
<input type = "text" name = "login_Username"><br/><br/>
Password:<br/><br/>
<input type = "password" name = "login_Password">
<p id = "l_error" style = "color:red"></p>
<br/>
<input type = "submit" value="Submit"><br/><br/><br/><br/><br/><br/><br/>
</fieldset>
</form>
</div>
</td>
<td width = "50%">
<div align = "center">
<form action = "signup.php" method = "post" onsubmit="return validate(1);" name = "signup">
<fieldset>
<legend>Sign up:</legend><br/><br/>
Username:<br/>
<i>Your username will be your unique identifier</i><br/><br/>
<input type = "text" name = "signup_Username"><br/><br/>
Password:<br/>
<i>Please use a strong password with a mix of numbers and uppercase and lowercase letters</i><br/><br/>
<input type = "password" id = "Pass"  name = "signup_Password"><br/><br/>
Confirm Password:<br/>
<i>Please re-type your password here.</i><br/><br/>
<input type = "password" id = "CPass" name = "signup_CPassword"><br/><br/>
Email address:<br/>
<i>Please enter a valid email</i><br/><br/>
<input type = "text" name = "email"><br/><br/>
Default time:<br/>
<i>Please enter a default alarm time using the 24-h clock format. The alarm will go off at this time if another time is not specified.</i><br/><br/>
<select name = "hour">
<?php 
	for($i=0;$i<24;$i++)
	{
		if($i<10)
		{
			echo("<option value = '{$i}'>0{$i}</option>"); 
		}
		else
		{
			echo("<option value = '{$i}'>{$i}</option>");
		}
	}
?>
</select><b>:</b>
<select name = "min">
<?php 
	for($j=0;$j<60;$j++)
	{
		if($j<10)
		{
			echo("<option value = '{$j}'>0{$j}</option>");
		}
		else
		{
			echo("<option value = '{$j}'>{$j}</option>");
		}
	}
?>
</select>
<p style = "color:red" id = "error"></p>
<br/><br/>
<input type = "submit" value = "Submit"><br/><br/>
</fieldset>
</form>
</div>
</td></tr></table>
</body>
</html>
