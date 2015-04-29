<?php
	include('config.php');
?>

<?php
	session_start();
	if($_SESSION['validation'] == 1)
	{
		//redirect to the home page!
		die("<script>location.href = 'login_home.php'</script>");
	}
	else
		session_destroy();
?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" type="text/css" href="stylesheets/about.css">
</head>

<body>
	<div id = "header">
		<a href = "home.php" style="color:white;margin:10px;">Home</a>
		<a href = "about.php" style="color:white;margin:10px;">About</a>
		<a href = "sign_up.php" style="color:white;margin:10px;">Sign Up</a>
		<a href = "home.php" style="color:white;margin:10px;">Log In</a>
	</div>
	<div id="name">
		<h1>V-EXCHANGE</h1>
	</div>
	<p class = "para">
		Your account has not been validated yet!<br>
		An email has been sent to your registered email id. Please click on the link and validate the account.<br>
		Please LOGIN again after validating your account.
	</p>
	<div id="footer">
		CS345 Production
	</div>
</body>