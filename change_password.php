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
	$other_error = 0;
	$password_old = $password_new =$password_new_copy = "";	
	$passnewErr = $passoldErr= $passnewcopyErr = "";
	// if(isset($_SESSION['key']))
	// {
	// 	//redirect to home page of user
	// 	//die("<script>location.href = 'login_home.php'</script>");
	// }
	if(isset($_SESSION['key']) && isset($_SESSION['validation']))
	{
		if($_SESSION['validation']==0)
		{
			//the account has not been validated yet.
			echo  "<script type='text/javascript'>alert('Your account is not validated yet!.')</script>";
			die("<script>location.href = 'validation.php'</script>");
		}else
		{
			$id_user = $_SESSION['key'];
			if(isset($_POST['password_change_button']))
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
					if (empty($_POST["password_old"])) {
		     			$passoldErr = "Old Password is required";
		     			$other_error = 1;
					}
					if (empty($_POST["password_new"])) {
		     			$passnewErr = "New Password is required";
		     			$other_error = 1;
					}
					if (empty($_POST["password_new_copy"])) {
		     			$passnewcopyErr = "This field is required";
		     			$other_error = 1;
					}
					if (!empty($_POST["password_new"]) && !empty($_POST["password_old"]) && !empty($_POST["password_new_copy"]))
					{
						if($_POST["password_new"] != $_POST["password_new_copy"])
						{
							echo  "<script type='text/javascript'>alert('The passwords you entered do not match!!')</script>";
							$other_error = 1;
						}
					}
					//no we need to check the database if the old password is valid
					if($other_error == 0)
					{
						$count = 0;
						$password_new = $_POST["password_new"] ;
						$password_old = $_POST["password_old"] ;
						$sql = "SELECT * FROM registration WHERE password = '".$password_old."' ";
						$result = mysqli_query($con,$sql);
						if ($result->num_rows > 0){
							$sql = "UPDATE registration SET password = '".$password_new."' WHERE id = '".$id_user."'";
							$result = mysqli_query($con,$sql);

							if ($con->query($sql) == TRUE) {
							    echo "Record updated successfully";
							} else {
							    echo "Error updating record: " . $con->error;
							}
						}
						else{
							$passoldErr = "Enter the correct password";
						}
					}
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
	<div id="change">
		<form name='change_password_form' action='change_password.php' method="post" enctype='multipart/form-data'>
			<label class="username"> Old Password: </label><input class="textbox" type="password" name="password_old">
			<font size="1"><span class="error">* <?php echo $passoldErr;?></span></font><br><br>

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