<?php
	include('config.php');
	if(isset($_GET['email'])){
	$email = trim(mysql_escape_string($_GET['email']));
	$key = trim(mysql_escape_string($_GET['key']));
	}
?>
<?php
	$password_new = $password_new_copy = "";
	$passnewErr = $passnewcopyErr = "";
	$other_error = 0;
	if(isset($email) && isset($_POST['password_change_button'])){
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
			if(empty($_POST['password_new'])){
				$passnewErr = "This field is required";
				$other_error =1;
			}
			if(empty($_POST['password_new_copy'])){
				$passnewcopyErr = "This field is required";
				$other_error =1;
			}
			if(!empty($_POST["password_new"]) && !empty($_POST["password_new_copy"])){
				$password_new = $_POST["password_new"];
				$password_new_copy = $_POST["password_new_copy"];
				if($_POST["password_new"] != $_POST["password_new_copy"])
				{
					echo  "<script type='text/javascript'>alert('The passwords you entered do not match!!')</script>";
					$other_error = 1;
				}
			}
			if($other_error == 0){
				$count = 0;
				$sql = "UPDATE registration SET password = '".$password_new."' WHERE email = '".$email."' ";
				$result = mysqli_query($con,$sql);
				//echo $sql;
				if ($con->query($sql) == TRUE) {
				    echo "Password changed successfully";
				    die("<script>location.href = 'home.php'</script>");
				} else {
				    echo "Error updating record: " . $con->error;
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
	</div>
	<div id="name">
		<h1>V-EXCHANGE</h1>
	</div>
	<div id="sign_up">
		<?php 
			$email = trim(mysql_escape_string($_GET['email']));
			$key = trim(mysql_escape_string($_GET['key']));
			$new_url="forgot_password.php?email=" . urlencode($email) . "&key=" . $key; 
		?>
		<form name='change_password_form' action=<?php echo $new_url;?> method="post" enctype='multipart/form-data'>
			<label class="username"> New Password: </label><input class="textbox" type="password" name="password_new">
			<font size="1"><span class="error">* <?php echo $passnewErr;?></span></font><br>
			<font size="1">(must be atleast 6 characters long)</font><br>

			<label class="username">Re-enter your new password: </label><input class="textbox" type="password" name="password_new_copy">
			<font size="1"><span class="error">* <?php echo $passnewcopyErr;?></span></font><br>
			<font size="1">(to confirm you typed it correctly)</font><br><br>

			<input type="submit" class="css_button" value="CHANGE PASSWORD" name="password_change_button">
		</form>
	</div>
	<div id="footer1">
	</div>
	<div id="footer">
		CS345 Production
	</div>
</body>
