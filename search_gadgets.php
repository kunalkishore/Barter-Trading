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
	$text_name1 = "";
	$text_name2 = "";
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
		else
		{
			//you are on the right page!!
			if(isset($_POST['gadget_name']))	
			{
				if($_POST['text_name1']!="")
				{
					die("<script>location.href = 'result_gadgets.php?gadget_name=".$_POST['text_name1']."'</script>");
				}
				else
					$text_name1 = " * Required field!";
			}	
			if(isset($_POST['model_name']))	
			{
				if($_POST['text_name2']!="")
				{
					die("<script>location.href = 'result_gadgets.php?model=".$_POST['text_name2']."'</script>");
				}
				else
					$text_name2 = " * Required field!";
			}
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
	<link rel="stylesheet" type="text/css" href="stylesheets/search_books.css">
</head>
<body>
	<div id = "header">
		<a href = "login_home.php" style="color:white;margin:10px;">Member Home</a>
		<a href = "add.php" style="color:white;margin:10px;">Add</a>
		<a href = "login_pending.php" style="color:white;margin:10px;">Pending</a>
		<a href = "login_inventory.php" style="color:white;margin:10px;">Inventory</a>
		<a href = "logout.php" style="color:white;margin:10px;">Log Out</a>
	</div>
	<div id="name">
		<h1>V-EXCHANGE</h1>
	</div>
	<div id = "container1">
		<form  method="post" action="search_gadgets.php"  id="searchgadget"> 
	      	<label class = "username">Search by name:</label><br><input class="textbox" type="text" name = "text_name1" ><font size="1"><span class="error"><?php echo $text_name1;?></span></font><br><br>
	      	<input type="submit" name="gadget_name" value="Gadget" class="css_button"> 
	    </form> 
	</div>
	<div id = "container2">
		<form  method="post" action="search_gadgets.php"  id="searchmodel"> 
	      	<label class = "username">Search by model:</label><br><input class="textbox" type="text" name ="text_name2"><font size="1"><span class="error"><?php echo $text_name2;?></span></font><br><br>
	      	<input type="submit" name="model_name" value="Model" class="css_button"> 
	    </form> 
	</div>
	<div id="footer1">
	</div><br>
	<div id="footer">
		CS345 Production
	</div>
</body>
