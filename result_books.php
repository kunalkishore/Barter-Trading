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
			$id = $_SESSION['key'];
			//you are on the right page!!
			$b = 0;
			$a = 0;
			if(isset($_GET['book_name']))
			{
				$b=1;
				$book_name = $_GET['book_name'];
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
					//find all the books
					if($book_name!="")
					{
						$count = 0;
						$err = "";
						$sql = "SELECT * FROM books WHERE (book_name LIKE '%$book_name%' and status = 0 and user_id!='".$id."')";	//0 status inplies book is available
			          	$result = $con->query($sql);
						if ($result->num_rows > 0) {
	    				// output data of each row
		    				while($row = $result->fetch_assoc()) {
		    					 $new_array[$count]['id'] = $row['id'];
		        				 $new_array[$count]['book_name'] = $row['book_name'];
		        				 $new_array[$count]['author_name'] = $row['author'];
		        				 $new_array[$count]['publication'] = $row['publication'];
		        				 $new_array[$count]['genre'] = $row['genre'];
		        				 $new_array[$count]['edition'] = $row['edition'];
		        				 $new_array[$count]['language'] = $row['language'];
		        				 $new_array[$count]['pages'] = $row['pages'];
		        				 $new_array[$count]['first_name'] = $row['first_name'];
		        				 $new_array[$count]['user_id'] = $row['user_id'];
		        				 $sql_rate = "SELECT rating from registration where id = '".$new_array[$count]['user_id']."'";
		        				 $result_rate = $con->query($sql_rate);
		        				 if($row_result = $result_rate->fetch_assoc())
		        				 {
		        				 	 $new_array[$count]['rating'] = $row_result['rating'];
		        				 }
		        				 $count++;
		    				}
						} else {
							$err = "Nothing to display!";    
						}
		          	}
		          	else
		          	{
		          		$err = "Enter a valid Search String";
		          	}   
				}
			}
			else
			{
				$a=1;
				$author = $_GET['author'];
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
					//find all the books
					if($author!="")
					{
						$count = 0;
						$err = "";
						$sql = "SELECT * FROM books WHERE (author LIKE '%$author%' and status = 0 and user_id!='".$id."')";	//0 status inplies book is available
			          	$result = $con->query($sql);
						if ($result->num_rows > 0) {
	    				// output data of each row
		    				while($row = $result->fetch_assoc()) {
		    					 $new_array[$count]['id'] = $row['id'];
		        				 $new_array[$count]['book_name'] = $row['book_name'];
		        				 $new_array[$count]['author_name'] = $row['author'];
		        				 $new_array[$count]['publication'] = $row['publication'];
		        				 $new_array[$count]['genre'] = $row['genre'];
		        				 $new_array[$count]['edition'] = $row['edition'];
		        				 $new_array[$count]['language'] = $row['language'];
		        				 $new_array[$count]['pages'] = $row['pages'];
		        				 $new_array[$count]['first_name'] = $row['first_name'];
		        				 $new_array[$count]['user_id'] = $row['user_id'];
		        				 $sql_rate = "SELECT rating from registration where id = '".$new_array[$count]['user_id']."'";
		        				 $result_rate = $con->query($sql_rate);
		        				 if($row_result = $result_rate->fetch_assoc())
		        				 {
		        				 	 $new_array[$count]['rating'] = $row_result['rating'];
		        				 }
		        				 $count++;
		    				}
						} else {
							$err = "Nothing to display!";    
						}
		          	} 
		          	else
		          	{
		          		$err = "Enter a valid Search String";
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
	<title>Member Home</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/result_books.css">
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
	<div id = "home">
		<form method="post" action="search_books.php"  id="searchpage"> 
	      	<input type="submit" name="search_page" value="Go back to Search" class="css_button"> 
	    </form> 
		<?php 
			if($count == 0)
				echo "<u>".$err."</u><br>"; 
			else 
			{	
				echo "<table id = 'book_table'><tr> 
				
					<th> Book Name</th>
					<th> Author Name</th>
					<th> Owner</th>
					<th> Ratings</th>
					</tr>";
				$c = 0;
				while($c < $count)
					{	
						//echo "<tr><td><input type='checkbox' name='color[]'' id='color' value='".$c."'></td>";
						if($b==1)
							echo "<td><a href = 'books_details.php?id=".$new_array[$c]['id']."&b_n=".$book_name."' style='color:white;''>".$new_array[$c]['book_name']."</td>";
						else 
							echo "<td><a href = 'books_details.php?id=".$new_array[$c]['id']."&a=".$author."' style='color:white;''>".$new_array[$c]['book_name']."</td>";
						echo "<td>".$new_array[$c]['author_name']."</td>";
						echo "<td>".$new_array[$c]['first_name']."</td>";
						echo "<td>".$new_array[$c]['rating']."</td></tr>";
						$c++;
					}
				echo "</table>";
			} 
			?>
			<br>
	</div>
	<div id="footer1">
	</div><br>
	<div id="footer">
		CS345 Production
	</div>
</body>
