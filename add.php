<?php
	include('config.php');
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
?>

<?php	
	session_start();
	//$_SESSION['key'] = 1;
	//$_SESSION['validation'] = 1;  
	if(isset($_SESSION['key']) && isset($_SESSION['validation']))
	{
		if($_SESSION['validation']==0)
		{
			//the account has not been validated yet.
			echo  "<script type='text/javascript'>alert('Your account is not validated yet!.')</script>";
			die("<script>location.href = 'validation.php'</script>");
		}
	}
	else
	{
		//this will lead back to home_page
		echo  "<script type='text/javascript'>alert('You are not athorized to access the page!.')</script>";
		die("<script>location.href = 'home.php'</script>");
	}
?>


<head>
	<title>Member Home</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/login_home.css">
</head>
<body>
	<div id = "header">
		<a href = "login_home.php" style="color:white;margin:10px;">Member Home</a>
		<a href = "add.php" style="color:white;margin:10px;">Add</a>
		<a href = "login_pending.php" style="color:white;margin:10px;">Pending</a>
		<a href = "login_inventory.php" style="color:white;margin:10px;">Inventory</a>
		<a href = "login_browse.php" style="color:white;margin:10px;">Browse</a>
		<a href = "logout.php" style="color:white;margin:10px;">Log Out</a>
	</div>
	<div id="name">
		<h1>V-EXCHANGE</h1>
	</div>
	<div id="button_container">
		<div class="divider"></div><br>
		<button type="button" class="css_button" value = "books" onclick="window.open('add_book.php')">Books</button><br>
		<div class="divider"></div><br>
		<div class="divider"></div><br>
		<button type="button" class="css_button" value = "instruments" onclick="window.open('add_instrument.php')">Instruments</button><br>
		<div class="divider"></div><br>
		<div class="divider"></div><br>
		<button type="button" class="css_button" value = "gadgets" onclick="window.open('add_gadget.php')">Gadgets</button><br>
	</div>
	<div id="footer1">
	</div>
	<div id="footer">
		CS345 Production
	</div>
</body>
