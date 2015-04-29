<?php
	include('config.php');
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	function GetImageExtension($imagetype){
		if(empty($imagetype))
			return false;
		switch($imagetype){
			case 'image/bmp' : return '.bmp';
			case 'image/gif': return '.gif' ;
			case 'image/jpeg' : return '.jpg';
			case 'image/png' : return '.png';
			default: return false;
		}
	} 
?>

<?php
	$other_error = 0;
	$instrument_name = $model = $description = $year_bought = $issues = $brand = $temp_name = "";
	$instrument_nameErr = $modelErr = $descriptionErr = $year_boughtErr = $issuesErr = $brandErr = "";
	$target_path = "NA";
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
			if(isset($_POST['add_button']))
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
				else
				{
					if (empty($_POST["instrument_name"])) {
		     			$instrument_nameErr = "instrument name is required";
		     			$other_error = 1;
					} else {
		     			$instrument_name = test_input($_POST["instrument_name"]);
		     		}
		     		if (empty($_POST["model"])) {
		     			$modelErr = "Model name is required";
		     			$other_error = 1;
					} else {
		     			$model = test_input($_POST["model"]);
		     		}
		     		if (!empty($_POST["description"])) {
		     			$description = test_input($_POST["description"]);
		     		}
		     		else 
		     			$description = "NULL";
		     		if (empty($_POST["brand"])) {
		     			$brandErr = "Brand is required";
		     			$other_error = 1;
					} else {
		     			$brand = test_input($_POST["brand"]);
		     		}
		     		if (!empty($_POST["issues"])) {
		     			$issues = test_input($_POST["issues"]);
		     		}
		     		else 
		     			$issues = "NULL";
		     		if (empty($_POST["year_bought"])) {
		     			$year_boughtErr = "Year bought is required";
		     			$other_error = 1;
					} else {
		     			$year_bought = test_input($_POST["year_bought"]);
		     			if (!preg_match("/^[0-9]*$/",$year_bought) || strlen($year_bought)!=4) {
		       				$year_boughtErr = "Only legitimate years allowed of length 4!";
		       				$other_error = 1; 
		     			}
		     		}
		     		if(!empty($_FILES["imageUploaded"]["name"])){
			     		$file_name = $_FILES["imageUploaded"]["name"];
			     		$temp_name = $_FILES["imageUploaded"]["tmp_name"];
			     		$imgtype = $_FILES["imageUploaded"]["type"];
			     		$ext = GetImageExtension($imgtype);
			     		$imagename = date("d-m-Y")."-".time().$ext;
			     		$target_path = "images/".$imagename;
			     	}

					if($other_error == 0)
					{
						//to get name and username
						$sql = "SELECT * FROM registration WHERE id = '".$_SESSION['key']."'";
						$result = $con->query($sql);
						if ($result->num_rows > 0) {
    						// output data of each row
    						if($row = $result->fetch_assoc()) {
        						$user_name = $row['user_name'];
        						$first_name = $row['first_name'];
        						$rating = $row['rating'];
        						$points = $row['points'];
        						$items_uploaded = $row['items_uploaded'];
    						}
						} 
						//echo $sql."\n";
						$items_uploaded ++ ;
						$points = $points + .4 ;
						//now you can insert the data!
						if(move_uploaded_file($temp_name, $target_path))
						{
							$sql = "INSERT INTO instruments (instrument_name,model,year_bought,image,brand,issues,description,date_upload,user_id,first_name,rating) VALUES ('".$instrument_name."','".$model."','".$year_bought."','".$target_path."','".$brand."','".$issues."','".$description."',now(),'".$_SESSION['key']."','".$first_name."','".$rating."')";
							//echo $sql."\n";
							if ($con->query($sql) == TRUE) {
								//we can now update the records in registration table
								$sql_update = "UPDATE registration SET points = '".$points."' , items_uploaded = '".$items_uploaded."' where id = '".$_SESSION['key']."'";
								//echo $sql_update."\n";
								if ($con->query($sql_update) == TRUE) {
	    							echo  "<script type='text/javascript'>alert('New instrument has been added to the record.')</script>";
							   		die("<script>location.href = 'login_home.php'</script>");
								} else {
	    							echo "Error updating record: " . $con->error;
								}
							} else {
							   	echo "Error: " . $sql . "<br>" . $con->error;
							}
						}
						else
						{
							$sql = "INSERT INTO instruments (instrument_name,model,year_bought,image,brand,issues,description,date_upload,user_id,first_name,rating) VALUES ('".$instrument_name."','".$model."','".$year_bought."','".$target_path."','".$brand."','".$issues."','".$description."',now(),'".$_SESSION['key']."','".$first_name."','".$rating."')";
							//echo $sql."\n";
							if ($con->query($sql) == TRUE) {
								//we can now update the records in registration table
								$sql_update = "UPDATE registration SET points = '".$points."' , items_uploaded = '".$items_uploaded."' where id = '".$_SESSION['key']."'";
								//echo $sql_update."\n";
								if ($con->query($sql_update) == TRUE) {
	    							echo  "<script type='text/javascript'>alert('New instrument has been added to the record.')</script>";
							   		die("<script>location.href = 'login_home.php'</script>");
								} else {
	    							echo "Error updating record: " . $con->error;
								}
							} else {
							   	echo "Error: " . $sql . "<br>" . $con->error;
							}
						}
					}
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
	<title>Add instruments</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/add.css">
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
	<div id = "add">
		<form name='add_form' action='add_instrument.php' method="post" enctype='multipart/form-data'>
			<label class="username">instrument Name: </label><input class="textbox" type="text" value="<?php echo $instrument_name;?>" name="instrument_name">
			<font size="1"><span class="error">* <?php echo $instrument_nameErr;?></span></font><br>
			<br>
			<label class="username">Model: </label><input class="textbox" type="text" value="<?php echo $model;?>" name="model">
			<font size="1"><span class="error">* <?php echo $modelErr;?></span></font><br>
			<br>
			<label class="username">Brand: </label><input class="textbox" type="text" value="<?php echo $brand;?>" name="brand">
			<font size="1"><span class="error">* <?php echo $brandErr;?></span></font><br><br>	
			<label class="username">Issues: </label><input class="textbox" style ="width:400px;" type="text" value="<?php echo $issues;?>" name="issues">
			<br>
			<br>	
			<label class="username">Description: </label><input class="textbox" style ="width:700px;" type="text" value="<?php echo $description;?>" name="description">
			<br><br>
			<label class="username">Year Bought: </label><input class="textbox" type="text" value="<?php echo $year_bought;?>" name="year_bought">
			<font size="1"><span class="error">* <?php echo $year_boughtErr;?></span></font>
			<div id= "image_holder">
				<label class="username">Upload Image</label><input type="file" name="imageUploaded" id="imageUploaded">
			</div>
			<br><br><br>
			<input type="submit" class="css_button" value="Add instrument" name="add_button">
		</form>		
	</div>
	<div id="footer1">
	</div><br>
	<div id="footer">
		CS345 Production
	</div>
</body>
