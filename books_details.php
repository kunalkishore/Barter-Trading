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
			$id_user = $_SESSION['key'];
			$id_book = $_GET['id'];
			if(isset($_GET['b_n']))
				$b_n = $_GET['b_n'];
			else
				$a = $_GET['a'];
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
				if($id_book!=0)
				{
					$sql = "SELECT * FROM books WHERE id = '".$id_book."'";	//0 status inplies book is available
		          	$result = $con->query($sql);
		          	$count = 0;
					if ($result->num_rows > 0) {
    				// output data of each row
	    				while($row = $result->fetch_assoc()) {
	        				 $book_name = $row['book_name'];
	        				 $author_name = $row['author'];
	        				 $publication = $row['publication'];
	        				 $genre = $row['genre'];
	        				 $edition = $row['edition'];
	        				 $language = $row['language'];
	        				 $pages = $row['pages'];
	        				 $image = $row['image'];
	        				 if($image == "NA")
	        				 {
	        				 	$image = "images/default.jpg";
	        				 }
	        				 $first_name = $row['first_name'];
	        				 $owner_id = $row['user_id'];
	        				 $sql_rate = "SELECT rating from registration where id = '".$owner_id."'";
		        			 $result_rate = $con->query($sql_rate);
		        			 if($row_result = $result_rate->fetch_assoc())
		        			 {
		        			 	 $rating = $row_result['rating'];
		        			 }
	        				 $count++;
	    				}
					} else {
						$err = "Nothing to display!";    
					}
	          	}   
			}

			if(isset($_POST['order']))
			{
				//get the details of user
				//place the order here!
				$a = $_SESSION['items_received'] + $_SESSION['items_pending'];
				if($a<2 || $a<=(1/2 * $_SESSION['sent_successfully']))
				{
					if($_SESSION['points'] >= 1)
					{	
						$points = $_SESSION['points'] - 1;
						$_SESSION['points'] = $points ;
						$sql = "UPDATE books SET status = '1',order_by='".$id_user."', dispatched='0', delivered='0' where id = '".$id_book."' ";
						if ($con->query($sql) === TRUE) {
						   	//update the both user and owner records
						   	$sql_update = "UPDATE registration SET sent_pending = sent_pending + '1' WHERE id = '".$owner_id."'"; 
						   	if ($con->query($sql_update) === TRUE) {
    							echo "Record updated successfully";
							}
							$sql_update = "UPDATE registration SET points = '".$points."' , items_pending = items_pending + '1' WHERE id = '".$id_user."'"; 
							if ($con->query($sql_update) === TRUE) {
    							echo "Record updated successfully";
							}
							echo  "<script type='text/javascript'>alert('Your order has been sucessfuly placed!')</script>";
							die("<script>location.href = 'login_home.php'</script>");
						} else {
						    echo "Error updating record: " . $con->error;
						}
					}
					else
					{
						//you don't have enough points!
						//echo "not enough points!\n";
						//echo  "<script type='text/javascript'>alert('You don't have sufficient points to order the book!')</script>";
						echo '<script language="javascript">';
						echo 'alert("You don\'t have sufficient points to order the book!")';
						echo '</script>';
						die("<script>location.href = 'login_home.php'</script>");
					}
				}
				else
				{
					//the ratio is not maintained!
					//echo "disturbed ratio!";
					echo  "<script type='text/javascript'>alert('The ratio of books sent and received is not maintained!. You need to send out more books!')</script>";
					die("<script>location.href = 'login_home.php'</script>");
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
	<title>Product Details</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/books_details.css">
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
		<?php 
			if(isset($_GET['b_n']))
				echo "<form method=\"post\" action='result_books.php?book_name=".$b_n."'  id=\"detailpage\">" ; 
			else
				echo "<form method=\"post\" action='result_books.php?author=".$a."'  id=\"detailpage\">" ;?>
	      	<input type="submit" name="detail_page" value="Go back" class="css_button"> 
	    </form> 
	    <div style="float:left;width:70%;">
		<h2>Welcome</h2>
		Book Name       : <?php echo $book_name; ?><br>
		Author  		: <?php echo $author_name ; ?><br>
		Publication 	: <?php echo $publication ;?><br>
		Genre 			: <?php echo $genre ;?><br>
		Edition 		: <?php echo $edition ;?><br>
		Language  		: <?php echo $language ;?><br>
		Pages  			: <?php echo $pages ;?><br>
		Owner  			: <?php echo $first_name ;?><br>
		Rating  		: <?php echo $rating ;?><br>
		<br>
		<?php 
			if(isset($_GET['b_n']))
				echo "<form method=\"post\" action=\"books_details.php?id=".$id_book."&b_n=".$b_n."\"  id=\"booksdetails\"> ";
			else
				echo "<form method=\"post\" action=\"books_details.php?id=".$id_book."&a=".$a."\"  id=\"booksdetails\"> ";
			?>
	      	<input type="submit" name="order" value="Order" class="css_button"> 
	    </form>
		</div>
		<div>
			<img src="<?php echo $image;?>" alt="Book Cover" style="float:left;width:25%;margin-right:1%;margin-bottom: 0.5em" />
		</div>
	</div>
	<div id="footer1">
	</div><br>
	<div id="footer">
		CS345 Production
	</div>
</body>
