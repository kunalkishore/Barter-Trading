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
		$BASE_PATH = "dulcet-radar-91118.appspot.com";
		$url= $BASE_PATH . '/forgot_password.php?email=' . urlencode($email) . "&key=$hash";

		// $message_body = 'MIME-Version: 1.0\r\n';
		// $message_body. = 'Content-type: text/html; charset=iso-8859-1\r\n';
		$message_body ='<html><p>To change your password please click on change password buttton</p>';
		$message_body.='<table cellspacing="0" cellpadding="0"> <tr>';
		$message_body .= '<td align="center" width="300" height="40" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;

		color: #ffffff; display: block;">';

		$message_body .= '<a href="'.$url.'" style="color: #ffffff; font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none;

		line-height:40px; width:100%; display:inline-block">Click to Change Password</a>';
		$message_body .= '</td> </tr> </table></hmtl>';

 
		// $submit_url = "https://api.mailgun.net/v3/sandbox07dfa29eead44f329e2d9e0441efeef7.mailgun.org"; 

		// $data = array(
		// 	'from' => 'kishore.kunal92@gmail.com',
		// 	'to' => $email,
		// 	'subject' => 'Activate Your Email',
		// 	'text' => $message
		// );

		// $curl = curl_init(); 

		// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
		// curl_setopt($curl, CURLOPT_USERPWD, "api:key-1a33b06039ea8c9bdfb356df99ea6776");
		// curl_setopt($curl, CURLOPT_POST, true);
		// curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		// $result = curl_exec($curl);

		// curl_close($curl);


		$message = new Message();
		$message->setSender("dulcet-radar-91118@appspot.gserviceaccount.com");
		$message->addTo($email);
		$message->setSubject("Change Password");
		$message->setTextBody($message_body);
//		$message->addAttachment('image.jpg', $image_data, $image_content_id);
		$message->send();
		
		return;
	}
?>
<?php
	$email = $emailErr ="";
	$other_error = 0;
	// if(isset($_SESSION['key']))
	// {
	// 	//redirect to home page of user
	// 	//die("<script>location.href = 'login_home.php'</script>");
	// }
	if(isset($_POST['send_mail_button'])){
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
			if (empty($_POST["email"])) {
     			$emailErr = "This field is required";
     			$other_error = 1;
			}
			if($other_error == 0)
			{
				$count = 0;
				$email = $_POST["email"] ;
				$sql = "SELECT * FROM registration WHERE email = '".$email."' ";
				$result = mysqli_query($con,$sql);
				if ($result->num_rows > 0) 
				{
					if ($con->query($sql) == TRUE) {
					    echo  "<script type='text/javascript'>alert('A mail has been sent to your email id!.')</script>";
					    send_validation($email);
						die("<script>location.href = 'home.php'</script>");
					}
				}
				else
				{
					$emailErr = "Enter a valid email id";
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Change Password</title>
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
		<form name='login_form' action='send_mail.php' method="post" enctype='multipart/form-data'>
			<label class="username"> Email Id: </label><input class="textbox" type="text" name="email">
			<font size="1"><span class="error">* <?php echo $emailErr;?></span></font><br>
			<font size="1">(Enter your registerd email id)</font><br>

			<input type="submit" class="css_button" value="SEND MAIL" name="send_mail_button">
		</form>
	</div>
	<div id="footer1">
	</div>
	<div id="footer">
		CS345 Production
	</div>
</body>