<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>SAC Homepage</title>
<script type = "text/javascript">
function passCheck()
{
	if(document.getElementById("Pass").innerHTML===document.getElementById("CPass").innerHTML)
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
<table style = "width:100%"><tr><td width = "33%"><?php if(isset($_GET["logtype"])){echo("<p style = 'color:red'>");if($_GET["logtype"]==1){echo("Login successful</p>");}else if($_GET["logtype"]==0){echo("Login failed</p>");}else{echo("You have been successfully logged out");}}?></td><td width = "33%">
<h1 align="center">Welcome to SAC. Log in or Sign up to continue</h1></td>
<td width = "33%"><p align="right"><?php if(isset($_SESSION["UserID"])){echo("Welcome ".$_SESSION["UserID"]."</p><a style='float:right' href = 'logout.php'>Logout</a>");}?></td></tr></table>

<br />
<table style = "width:100%">
<tr><td width = "50%">
<div align = "center">
<form action = "login.php" method = "post">
<fieldset>
<legend>Log in:</legend><br/><br/><br/>
Username:<br/><br/>
<input type = "text" name = "login_Username"><br/><br/>
Password:<br/><br/>
<input type = "password" name = "login_Password"><br/><br/>
<input type = "submit" value="Submit"><br/><br/><br/><br/><br/><br/><br/>
</fieldset>
</form>
</div>
</td>
<td width = "50%">
<div align = "center">
<form action = "signup.php" method = "post" onsubmit="return passCheck()">
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
<option value = "0">00</option>
<option value = "1">01</option>
<option value = "2">02</option>
<option value = "3">03</option>
<option value = "4">04</option>
<option value = "5">05</option>
<option value = "6">06</option>
<option value = "7">07</option>
<option value = "8">08</option>
<option value = "9">09</option>
<option value = "10">10</option>
<option value = "11">11</option>
<option value = "12">12</option>
<option value = "13">13</option>
<option value = "14">14</option>
<option value = "15">15</option>
<option value = "16">16</option>
<option value = "17">17</option>
<option value = "18">18</option>
<option value = "19">19</option>
<option value = "20">20</option>
<option value = "21">21</option>
<option value = "22">22</option>
<option value = "23">23</option>
</select><b>:</b>
<select name = "min">
<option value = "0">00</option>
<option value = "1">01</option>
<option value = "2">02</option>
<option value = "3">03</option>
<option value = "4">04</option>
<option value = "5">05</option>
<option value = "6">06</option>
<option value = "7">07</option>
<option value = "8">08</option>
<option value = "9">09</option>
<option value = "10">10</option>
<option value = "11">11</option>
<option value = "12">12</option>
<option value = "13">13</option>
<option value = "14">14</option>
<option value = "15">15</option>
<option value = "16">16</option>
<option value = "17">17</option>
<option value = "18">18</option>
<option value = "19">19</option>
<option value = "20">20</option>
<option value = "21">21</option>
<option value = "22">22</option>
<option value = "23">23</option>
<option value = "24">24</option>
<option value = "25">25</option>
<option value = "26">26</option>
<option value = "27">27</option>
<option value = "28">28</option>
<option value = "29">29</option>
<option value = "30">30</option>
<option value = "31">31</option>
<option value = "32">32</option>
<option value = "33">33</option>
<option value = "34">34</option>
<option value = "35">35</option>
<option value = "36">36</option>
<option value = "37">37</option>
<option value = "38">38</option>
<option value = "39">39</option>
<option value = "40">40</option>
<option value = "41">41</option>
<option value = "42">42</option>
<option value = "43">43</option>
<option value = "44">44</option>
<option value = "45">45</option>
<option value = "46">46</option>
<option value = "47">47</option>
<option value = "48">48</option>
<option value = "49">49</option>
<option value = "50">50</option>
<option value = "51">51</option>
<option value = "52">52</option>
<option value = "53">53</option>
<option value = "54">54</option>
<option value = "55">55</option>
<option value = "56">56</option>
<option value = "57">57</option>
<option value = "58">58</option>
<option value = "59">59</option>
</select><br/><br/>
<input type = "submit" value = "Submit"><br/><br/>
</fieldset>
</form>
</div>
</td></tr></table>
</body>
</html>
