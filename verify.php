<?php
	include('config.php');
	$email = trim(mysql_escape_string($_GET['email']));
	$key = trim(mysql_escape_string($_GET['key']));
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
		$sql ="SELECT * FROM registration where (email = '".$email."' && validation= '1')";
		$result = mysqli_query($con,$sql);
		if (mysqli_num_rows($result) == 1)
		{
		echo  "<script type='text/javascript'>alert('Your Account already exists. Please Login Here')</script>";
		die("<script>location.href = 'login.php'</script>");
		}
		else
		{
			if (isset($email) && isset($key))
			{					
				mysqli_query($con, "UPDATE registration SET validation=1 WHERE email ='".$email."' ") or die(mysql_error());
				if (mysqli_affected_rows($con) == 1)
				{
					echo  "<script type='text/javascript'>alert('Your Account has been activated. Please Login Here')</script>";
					die("<script>location.href = 'login.php'</script>");
				} 
				else
				{
				echo '<div>Account could not be activated.</div>';
				}
			}
		}
		mysqli_close($con);
	}
?>
