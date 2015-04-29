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
	$nameErr = $emailErr = $genderErr = $websiteErr = "";
	$name = $email = $gender = $comment = $website = "";
	$other_error = 0;
	$book_name = $author_name = $publication = $genre = $edition = $language = $page = $temp_name="";
	$book_nameErr = $author_nameErr = $publicationErr = $genreErr = $editionErr = $languageErr = $pageErr = "";
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
				// $conn = mysql_connect(':/cloudsql/dulcet-radar-91118:barter-trading ',
				// 'root', // username
				// ''      // password
				// );
				// mysql_select_db('<database-name'>);
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
					if (empty($_POST["book_name"])) {
		     			$book_nameErr = "Book name is required";
		     			$other_error = 1;
					} else {
		     			$book_name = test_input($_POST["book_name"]);
		     		}
		     		if (empty($_POST["author_name"])) {
		     			$author_nameErr = "Author name is required";
		     			$other_error = 1;
					} else {
		     			$author_name = test_input($_POST["author_name"]);
		     			if (!preg_match("/^[a-zA-Z ]*$/",$author_name)) {
		       				$author_nameErr = "Only letters and white space allowed";
		       				$other_error = 1; 
		     			}
		     		}
		     		if (!empty($_POST["publication"])) {
		     			$publication = test_input($_POST["publication"]);
		     		}
		     		else 
		     			$publication = "NULL";
		     		if (empty($_POST["genre"])) {
		     			$genreErr = "Genre is required";
		     			$other_error = 1;
					} else {
		     			$genre = test_input($_POST["genre"]);
		     		}
		     		if (!empty($_POST["edition"])) {
		     			$edition = test_input($_POST["edition"]);
		     		}
		     		else 
		     			$edition = "NULL";
		     		if (empty($_POST["language"])) {
		     			$languageErr = "Language is required";
		     			$other_error = 1;
					} else {
		     			$language = test_input($_POST["language"]);
		     			if (!preg_match("/^[a-zA-Z ]*$/",$language)) {
		       				$languageErr = "Only letters and white space allowed";
		       				$other_error = 1; 
		     			}
		     		}
		     		if (!empty($_POST["page"])) {
						$page = test_input($_POST["page"]);
			     		// check if name only contains letters and whitespace
			     		if (!preg_match("/^[0-9]*$/",$page) && strlen($page)>6) {
			       			$pageErr = "Only legitimate numbers are allowed";
			       			$other_error = 1; 
			     		}
			     	}
			     	else
			     		$page = "NULL";
			     	
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
						$points = $points + .2 ;
						//now you can insert the data!
						if(move_uploaded_file($temp_name, $target_path)){
							$sql = "INSERT INTO books (book_name,author,publication,genre,edition,image,language,pages,date_upload,user_id,first_name,rating) VALUES ('".$book_name."','".$author_name."','".$publication."','".$genre."','".$edition."','".$target_path."','".$language."','".$page."',now(),'".$_SESSION['key']."','".$first_name."','".$rating."')";
							//echo $sql."\n";
							if ($con->query($sql) == TRUE) {
								//we can now update the records in registration table
								$sql_update = "UPDATE registration SET points = '".$points."' , items_uploaded = '".$items_uploaded."' where id = '".$_SESSION['key']."'";
								//echo $sql_update."\n";
								if ($con->query($sql_update) == TRUE) {
	    							echo  "<script type='text/javascript'>alert('New book has been added to the record.')</script>";
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
							$sql = "INSERT INTO books (book_name,author,publication,genre,edition,image,language,pages,date_upload,user_id,first_name,rating) VALUES ('".$book_name."','".$author_name."','".$publication."','".$genre."','".$edition."','".$target_path."','".$language."','".$page."',now(),'".$_SESSION['key']."','".$first_name."','".$rating."')";
							//echo $sql."\n";
							if ($con->query($sql) == TRUE) {
								//we can now update the records in registration table
								$sql_update = "UPDATE registration SET points = '".$points."' , items_uploaded = '".$items_uploaded."' where id = '".$_SESSION['key']."'";
								//echo $sql_update."\n";
								if ($con->query($sql_update) == TRUE) {
	    							echo  "<script type='text/javascript'>alert('New book has been added to the record.')</script>";
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
	<title>Add Books</title>
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
		<form name='add_form' action='add_book.php' method="post" enctype='multipart/form-data'>
			<label class="username">Book Name: </label><input class="textbox" type="text" value="<?php echo $book_name;?>" name="book_name">
			<font size="1"><span class="error">* <?php echo $book_nameErr;?></span></font><br>
			<br>
			<label class="username">Author: </label><input class="textbox" type="text" value="<?php echo $author_name;?>" name="author_name">
			<font size="1"><span class="error">* <?php echo $author_nameErr;?></span></font><br>
			<br>
			<label class="username">Publication: </label><input class="textbox" type="text" value="<?php echo $publication;?>" name="publication">
			<br><br>
			<label class="username">Genre: </label>	<select class="textbox" name="genre">
													<option value="AandA">Action and Adventure</option>
													<option value="AandP">Arts and Photography</option>
													<option value="Bios">Biography</option>
													<option value="Chem">Chemistry</option>
													<option value="Children">Children</option>
													<option value="Civil">Civil</option>
													<option value="Comics">Comics</option>
													<option value="CandI">Computer and Internet</option>
													<option value="Fiction">Fiction</option>
													<option value="GandG">GRE and GMAT</option>
													<option value="Horror">Horror</option>
													<option value="Literature">Literature</option>
													<option value="Maths">Mathematics</option>
													<option value="Others">Others</option>
													<option value="Physics">Physics</option>
													<option value="Sport">Sports</option>
													<option value="Travel">Travel</option>
												</select>
			<font size="1"><span class="error">* <?php echo $genreErr;?></span></font><br>
			<br>
			<label class="username">Edition: </label><input class="textbox" type="text" value="<?php echo $edition;?>" name="edition">
			<br><br>	
			<label class="username">Language: </label><input class="textbox" type="text" value="<?php echo $language;?>" name="language">
			<font size="1"><span class="error">* <?php echo $languageErr;?></span></font><br>
			<br>	
			<label class="username">Pages: </label><input class="textbox" type="text" value="<?php echo $page;?>" name="page">
			<font size="1"><span class="error"> <?php echo $pageErr;?></span></font>
			<br><br>
			<div id= "image_holder">
				<label class="username">Upload Image</label><input type="file" name="imageUploaded" id="imageUploaded">
			</div>
			<input type="submit" class="css_button" value="Add Book" name="add_button">
		</form>		
	</div>
	<div id="footer1">
	</div><br>
	<div id="footer">
		CS345 Production
	</div>
</body>
