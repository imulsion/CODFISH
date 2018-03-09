<?php
	 session_start();
	 if(isset($_SESSION["UserID"]))
	 {
	 	echo("<script type = 'text/javascript'>window.location.replace('index.php?logtype=2');</script>");
	 	die();
	 }
?>
<!DOCTYPE html>
<html>
<head>

<title id = "status"></title>
</head>
<body>
<h1 id = "head"></h1>
<p id = "details"></p>
<?php
	$conn = mysqli_connect("localhost","sk6g16","CODFISH","ug_sk6g16");
	if(!$conn)
	{
		echo('<script type = "text/javascript">');
		echo('document.getElementById("status").innerHTML="Connection Error";'); 
		echo('document.getElementById("head").innerHTML="Connection Problem";'); 
		echo('document.getElementById("details").innerHTML="There was a problem connecting to the database. Please contact customer support.";');
		echo('</script>');
	}
	else
	{
		$username = mysqli_real_escape_string($conn,$_POST["signup_Username"]);
		$password = mysqli_real_escape_string($conn,$_POST["signup_Password"]);
		$email = mysqli_real_escape_string($conn,$_POST["email"]);
		$sqlcheck = "SELECT * FROM `users` WHERE UserID='$username'";
		$result = mysqli_query($conn,$sqlcheck) or die(mysqli_error($conn));
		if(mysqli_num_rows($result)>0)
		{
			echo("<script type = 'text/javascript'>window.location.replace('index.php?serror=0');</script>");
			die();
		}
		$sqlcheck = "SELECT * FROM `users` WHERE email='$email'";
		$result = mysqli_query($conn,$sqlcheck) or die(mysqli_error($conn));
		if(mysqli_num_rows($result)>0)
		{
			echo("<script type = 'text/javascript'>window.location.replace('index.php?serror=1');</script>");
			die();
		} 
		$defaulttime = 60*$_POST["hour"]+$_POST["min"];
		$sql = "INSERT INTO users(UserID,Pass,email,default_time) VALUES ('$username','$password','$email','$defaulttime')";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		$_SESSION["UserID"] = $username;
		echo('<script type = "text/javascript">');
		echo('document.getElementById("status").innerHTML="Success";');
		echo('document.getElementById("head").innerHTML="Welcome to SAC";');
		echo('document.getElementById("details").innerHTML="You have successfully signed up to the SAC service.";');
		echo('</script>');
		echo('Click <a href = "index.php">here</a> to return to the main page or <a href = "user_acc.php"> here</a> to control your alarm settings');
	}
?>
</body>
</html>
