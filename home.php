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
	if(isset($_SESSION['key']))
	{
		//redirect to home page of user
		die("<script>location.href = 'login_home.php'</script>");
	}

	$nameErr = $passErr = $loginerr = "";
	$name = $user_name = $password =  "";
	$count = 0;

	if(isset($_POST['login_button']))
	{
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
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (empty($_POST["user_name"])) {
			    	$nameErr = "Name is required";
			   	} 
			   	else {
			    	$name = test_input($_POST["user_name"]);
			   	}
			   	if (empty($_POST["password"])) {
			    	$passErr = "Password is required";
			   	} 
				if(!empty($_POST["password"]) && !empty($_POST["user_name"]))
				{
					//if the user has entered the credentials
					$user_name = $_POST['user_name'];
					$password = $_POST['password'];
					$sql = "select * from registration where (user_name = '".$user_name."' && password = '".$password."')";
					$result = mysqli_query($con,$sql);
	     			while($row = mysqli_fetch_array($result)){
						//here we'll get all the details of the user
	     				//we will go to the home page and send the unique key and user_name to next page
	     				$key = $row['id'];
	     				$val = $row['validation'];
			            $count++;
					}
					if($count == 1)
					{
						$_SESSION['key'] = $key;
						$_SESSION['validation'] = $val;
						die("<script>location.href = 'login_home.php'</script>");
						//go to the home page
					} else {
						echo  "<script type='text/javascript'>alert('The email and password you entered did not match our records. Please double-check and try again.')</script>";
					}
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/home.css">
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
	<div id = "login">
		<form name='login_form' action='home.php' method="post" enctype='multipart/form-data'>
			<label class="username">User Name: </label><input class="textbox" type="text" name="user_name" value="<?php echo $name;?>">
			<font size="1"><span class="error">* <?php echo $nameErr;?></span></font><br>
			<label class="username">Password: </label><input class="textbox" type="password" name="password">
			<font size="1"><span class="error">* <?php echo $passErr;?></span></font><br>
			<font size="1"><span class="error">* Required fields</span></font><br>
			<input type="submit" class="css_button" value="LOG IN" type="submit" name="login_button">



			<button type="button" class="css_button" value="forgot" onclick="window.open('send_mail.php')">Forgot Password ?</button>
		</form>
	</div>
	<img class="image_holder" src="trade.jpg" alt="trade">
	<div id="footer">
		CS345 Production
	</div>
</body>