<?php
	use \google\appengine\api\mail\Message;
	include('config.php');
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	function send_validation($email)
	{
		// Generate a unique code:
		//$hash = md5(uniqid(rand(), true));
		$BASE_PATH = "dulcet-radar-91118.appspot.com";

		//$headers = "From: admin@vexchange.com \r\n";

		$url= $BASE_PATH . '/verify.php?email=' . urlencode($email) . "&key=$hash";

		// $message_body= 'MIME-Version: 1.0' ."\r\n";
		// $message_body. = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$message_body ='<html><p>To activate your account please click on Activate buttton</p>';
		$message_body.='<table cellspacing="0" cellpadding="0"> <tr>';
		$message_body .= '<td align="center" width="300" height="40" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;

		color: #ffffff; display: block;">';

		$message_body .= '<a href="'.$url.'" style="color: #ffffff; font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none;

		line-height:40px; width:100%; display:inline-block">Click to Activate</a>';
		$message_body .= '</td> </tr> </table></html>';

		$message = new Message();
		$message->setSender("dulcet-radar-91118@appspot.gserviceaccount.com");
		$message->addTo($email);
		$message->setSubject("Activate Your Email");
		$message->setTextBody($message_body);
//		$message->addAttachment('image.jpg', $image_data, $image_content_id);
		$message->send();
		return;
	}
?>

<?php

	$other_error = 0;
	$first_name = $user_name = $last_name = $email = $address = $college = $city = $state = $country = $postal_code = $password1 = $password2 = "";	
	$first_nameErr = $user_nameErr = $last_nameErr = $emailErr = $addressErr = $collegeErr = $cityErr = $stateErr = $countryErr = $postal_codeErr = $pass1Err = $pass2Err= "";
	session_start();
	if(isset($_SESSION['key']))
	{
		//redirect to home page of user
		die("<script>location.href = 'login_home.php'</script>");
	}
	if(isset($_POST['signup_button']))
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
			if (empty($_POST["user_name"])) {
     			$user_nameErr = "Name is required";
     			$other_error = 1;	
			}
			else
			{
				$user_name = test_input($_POST["user_name"]);
			}
   			if (empty($_POST["first_name"])) {
     			$first_nameErr = "Name is required";
     			$other_error = 1;
			} else {
     			$first_name = test_input($_POST["first_name"]);
     			// check if name only contains letters and whitespace
     			if (!preg_match("/^[a-zA-Z ]*$/",$first_name)) {
       				$first_nameErr = "Only letters and white space allowed";
       				$other_error = 1; 
     			}
   			}
   			if (!empty($_POST["last_name"])) {
     			$last_name = test_input($_POST["last_name"]);
     			// check if name only contains letters and whitespace
     			if (!preg_match("/^[a-zA-Z ]*$/",$last_name)) {
       				$last_nameErr = "Only letters and white space allowed"; 
       				$other_error = 1;
     			}
			}
			if (empty($_POST["email"])) {
     			$emailErr = "Email is required";
     			$other_error = 1;
   			} else {
    	 		$email = test_input($_POST["email"]);
     			// check if e-mail address is well-formed
     			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       				$emailErr = "Invalid email format"; 
       				$other_error = 1;
     			}
   			}
   			if (empty($_POST["address"])) {
     			$addressErr = "Address is required";
     			$other_error = 1;
			}
			else
			{
				$address = test_input($_POST["address"]);
			}
			if (empty($_POST["college"])) {
     			$collegeErr = "College is required";
     			$other_error = 1;
			}
			else
			{
				$college = test_input($_POST["college"]);
			}
			if (empty($_POST["city"])) {
     			$cityErr = "City name is required";
     			$other_error = 1;
			}
			else
			{
				$city = test_input($_POST["city"]);
			}
			if (empty($_POST["state"])) {
     			$stateErr = "State is required";
     			$other_error = 1;
			}
			else
			{
				$state = test_input($_POST["state"]);
			}
			if (empty($_POST["country"])) {
     			$countryErr = "Country name is required";
     			$other_error = 1;
			}
			else
			{
				$country = test_input($_POST["country"]);
			}
			if (empty($_POST["postal_code"])) {
     			$postal_codeErr = "Postal Code is required";
     			$other_error = 1;
			}
			else
			{
				$postal_code = test_input($_POST["postal_code"]);
     			// check if name only contains letters and whitespace
     			if (!preg_match("/^[0-9]*$/",$postal_code)) {
       				$postal_codeErr = "Only numbers are allowed";
       				$other_error = 1; 
     			}
			}
			if (empty($_POST["password1"])) {
     			$pass1Err = "Password is required";
     			$other_error = 1;
			}
			else if(strlen($_POST["password1"])<6)
			{
				$pass1Err = "Password length should be more than 5 characters!";
     			$other_error = 1;
			}
			if (empty($_POST["password2"])) {
     			$pass2Err = "Password Confirmation is required";
     			$other_error = 1;
			}
			if (!empty($_POST["password1"]) && !empty($_POST["password2"]))
			{
				if($_POST["password1"] != $_POST["password2"])
				{
					echo  "<script type='text/javascript'>alert('The passwords you entered do not match!!')</script>";
					$other_error = 1;
				}
			}
			//no we need to check the database if the user_name is already being used or not!!
			if($other_error == 0)
			{
				$count = 0;
				$sql = "SELECT user_name from registration where (user_name = '".$_POST['user_name']."' || email = '".$_POST['email']."')";
				$result = mysqli_query($con,$sql);
	     		while($row = mysqli_fetch_array($result)){
			            $count++;
				}
				if($count == 0) //the user_name is not in use
				{
					//now we can input the data in the table!!
					$sql = "INSERT INTO registration (user_name, first_name, last_name, password , college ,address, city, state , country , pin_code , email ) VALUES ('".$user_name."','".$first_name."','".$last_name."','".$_POST['password1']."','".$college."','".$address."','".$city."','".$state."','".$country."','".$postal_code."','".$email."')";
					$_SESSION['validation'] = 0;
					if ($con->query($sql) === TRUE) {
    					echo  "<script type='text/javascript'>alert('Your account has been created. Please validate it.!')</script>";
    					send_validation($email);
    					die("<script>location.href = 'validation.php'</script>");
					} else {
    					echo "Error: " . $sql . "<br>" . $conn->error;
					}
					$con->close();
				} else {
					echo  "<script type='text/javascript'>alert('The user name or Email ID is already in use. Try again!')</script>";
					$user_name = "";
					$email = "";				
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Sign Up</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/sign_up.css">
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
	<div id="sign_up">
		<form name='sign_form' action='sign_up.php' method="post" enctype='multipart/form-data'>

			<label class="username">User Name: </label><input class="textbox" type="text" value="<?php echo $user_name;?>" name="user_name">
			<font size="1"><span class="error">* <?php echo $user_nameErr;?></span></font><br>
			<font size="1">(a name you want as your login identity, can be your name or a nickname)</font><br>

			<label class="username">First Name: </label><input class="textbox" type="text" name="first_name" value="<?php echo $first_name;?>" >
			<font size="1"><span class="error">* <?php echo $first_nameErr;?></span></font><br>
			<font size="1">(the name others will see, usually your real name)</font><br>

			<label class="username">Last Name: </label><input class="textbox" type="text" value="<?php echo $last_name;?>" name="last_name">
			<font size="1"><span class="error"><?php echo $last_nameErr;?></span></font><br>
			<font size="1">(the name others will see, usually your real name)</font><br>

			<label class="username">Email: </label><input class="textbox" type="text" name="email" value="<?php echo $email;?>">
			<font size="1"><span class="error">* <?php echo $emailErr;?></span></font><br>
			<font size="1">(your email address)</font><br>

			<label class="username">Address: </label><input class="textbox" type="text" name="address" value="<?php echo $address;?>">
			<font size="1"><span class="error">* <?php echo $addressErr;?></span></font><br>
			<font size="1">(your postal address)</font><br>

			<label class="username">College: </label><input class="textbox" type="text" name="college" value="<?php echo $college;?>">
			<font size="1"><span class="error">* <?php echo $collegeErr;?></span></font><br>
			<font size="1">(current college you are studying in)</font><br>

			<label class="username">City: </label><input class="textbox" type="text" name="city" value="<?php echo $city;?>">
			<font size="1"><span class="error">* <?php echo $cityErr;?></span></font><br>
			<font size="1">(your current city)</font><br>

			<label class="username">State: </label><input class="textbox" type="text" name="state" value="<?php echo $state;?>">
			<font size="1"><span class="error">* <?php echo $stateErr;?></span></font><br>

			<label class="username">Country: </label><input class="textbox" type="text" name="country" value="<?php echo $country;?>">
			<font size="1"><span class="error">* <?php echo $countryErr;?></span></font><br>

			<label class="username">Postal Code: </label><input class="textbox" type="text" name="postal_code" value="<?php echo $postal_code;?>">
			<font size="1"><span class="error">* <?php echo $postal_codeErr;?></span></font><br>

			<label class="username">Password: </label><input class="textbox" type="password" name="password1">
			<font size="1"><span class="error">* <?php echo $pass1Err;?></span></font><br>
			<font size="1">(mut be atleast 6 characters long)</font><br>

			<label class="username">Re-enter your password: </label><input class="textbox" type="password" name="password2">
			<font size="1"><span class="error">* <?php echo $pass2Err;?></span></font><br>
			<font size="1">(to confirm you typed it correctly)</font><br><br>

			<input type="submit" class="css_button" value="SIGN UP" name="signup_button">
		</form>
	</div>
	<div id="footer1">
	</div>
	<div id="footer">
		CS345 Production
	</div>
</body>