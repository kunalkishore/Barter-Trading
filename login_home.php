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
		else
		{
			//you are on the right page!!
			//login_home($_SESSION['key']);
			$id = $_SESSION['key'];
			//$con = mysqli_connect(constant("HOSTNAME"), constant("USERNAME"), constant("PASS"), constant("DBNAME"));
			$con = new mysqli(null,
				  'root', // username
				  '',     // password
				  'barter',
				  null,
				  '/cloudsql/dulcet-radar-91118:barter-trading'
				  );
			if (mysqli_connect_errno())
			{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else{
				$sql = "SELECT * FROM registration where (id = '".$id."')";
				//echo $sql;
				$result = mysqli_query($con,$sql);
		     	if($row = mysqli_fetch_array($result)){
				    $_SESSION['first_name'] = $first_name = $row["first_name"];
				    $_SESSION['last_name'] = $last_name = $row["last_name"];
				    $_SESSION['points'] = $points = $row['points'];
				    $_SESSION['rating'] = $rating = $row['rating'];
				    $_SESSION['items_uploaded'] = $items_uploaded = $row['items_uploaded'];
				    $_SESSION['items_received'] = $items_received = $row['items_received'];
				    $_SESSION['sent_successfully'] = $sent_successfully = $row['sent_successfully'];
				    $_SESSION['sent_pending'] = $sent_pending = $row['sent_pending'];
				    $_SESSION['items_pending'] = $items_pending = $row['items_pending'];
				}
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
	<link rel="stylesheet" type="text/css" href="stylesheets/login_home.css">
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
	<!--
	<div id="button_container">
		<div class="divider"></div><br>
		<button type="button" class="css_button" value = "books" onclick="window.open('search_books.php')">Books</button><br>
		<div class="divider"></div><br>
		<div class="divider"></div><br>
		<button type="button" class="css_button" value = "books" onclick="window.open('search_instruments.php')">Instruments</button><br>
		<div class="divider"></div><br>
		<div class="divider"></div><br>
		<button type="button" class="css_button" value = "books" onclick="window.open('search_gadgets.php')">Gadgets</button><br>
	</div>-->

	<div id="button_container">
		<div class="divider"></div><br>
		<FORM METHOD="LINK" ACTION="search_books.php">
		<INPUT TYPE="submit" class = "css_button" VALUE="Search Books">
		</FORM>
		
		<div class="divider"></div><br>
		<FORM METHOD="LINK" ACTION="search_instruments.php">
		<INPUT TYPE="submit" class = "css_button" VALUE="Search Instruments">
		</FORM>
		
		<div class="divider"></div><br>
		<FORM METHOD="LINK" ACTION="search_gadgets.php">
		<INPUT TYPE="submit" class = "css_button" VALUE="Search Gadgets">
		</FORM>
	</div>

	<div id = "home">
		<h2>Welcome</h2>
		Name: <?php echo $first_name; ?><br>
		Points: <?php echo $points ; ?><br>
		Items Uploaded: <?php echo $items_uploaded ;?><br>
		Rating: <?php echo $rating ;?><br><br>
		<!--<button type="button" class="css_button" value="update" onclick="window.open('update_details.php')">Update details</button>

		<FORM METHOD="LINK" ACTION="change_password.php">
		<INPUT TYPE="submit" class = "css_button" VALUE="Change Password">
		</FORM>-->

		<button type="button" class="css_button" value="change" onclick="window.open('change_password.php')">Change Password</button>
	</div>
	<div id="footer1">
	</div>
	<div id="footer">
		CS345 Production
	</div>
</body>
