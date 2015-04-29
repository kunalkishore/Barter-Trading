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
			//login_home($_SESSION['key']);
			$id = $_SESSION['key'];
			$err_book = "";
			$err_instrument = "";
			$err_gadget = "";
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
			{
				$sql = "SELECT * from books where user_id = '".$id."' ";
				$count_book = 0;
				$result = $con->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) 
					{
						$book[$count_book]['book_name'] = $row['book_name'];
						$book[$count_book]['author'] = $row['author'];
						$book[$count_book]['status'] = $row['status'];
						$book[$count_book]['order_by'] = $row['order_by'];
						$sql_o ="SELECT * FROM registration where id= '".$book[$count_book]['order_by']."'";
						$result_o = $con->query($sql_o);
						if ($result_o->num_rows > 0) {
					        if($row_o = $result_o->fetch_assoc()) {
	        					$book[$count_book]['order_by_name'] = $row_o['first_name'];
	    					}
    					}
    					else
    						$book[$count_book]['order_by_name'] = "";
						$count_book ++;
					}
				}
				else 
				{
					$err_book = "Nothing has been yet uploaded!";
				}
				$sql = "SELECT * from instruments where user_id = '".$id."' ";
				$count_instrument = 0;
				$result = $con->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) 
					{
						$instrument[$count_instrument]['instrument_name'] = $row['instrument_name'];
						$instrument[$count_instrument]['model'] = $row['model'];
						$instrument[$count_instrument]['status'] = $row['status'];
						$instrument[$count_instrument]['order_by'] = $row['order_by'];
						$sql_o ="SELECT * FROM registration where id= '".$instrument[$count_instrument]['order_by']."'";
						$result_o = $con->query($sql_o);
						if ($result_o->num_rows > 0) {
					        if($row_o = $result_o->fetch_assoc()) {
	        					$instrument[$count_instrument]['order_by_name'] = $row_o['first_name'];
	    					}
    					}
    					else
    						$instrument[$count_instrument]['order_by_name'] = "";
						$count_instrument ++;
					}
				}
				else 
				{
					$err_instrument = "Nothing has been yet uploaded!";
				}
				$sql = "SELECT * from gadgets where user_id = '".$id."' ";
				$count_gadget = 0;
				$result = $con->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) 
					{
						$gadget[$count_gadget]['gadget_name'] = $row['gadget_name'];
						$gadget[$count_gadget]['model'] = $row['model'];
						$gadget[$count_gadget]['status'] = $row['status'];
						$gadget[$count_gadget]['order_by'] = $row['order_by'];
						$sql_o ="SELECT * FROM registration where id= '".$gadget[$count_gadget]['order_by']."'";
						$result_o = $con->query($sql_o);
						if ($result_o->num_rows > 0) {
					        if($row_o = $result_o->fetch_assoc()) {
	        					$book[$count_gadget]['order_by_name'] = $row_o['first_name'];
	    					}
    					}
    					else
    						$gadget[$count_gadget]['order_by_name'] = $row_o['first_name'];
						$count_gadget ++;
					}
				}
				else 
				{
					$err_gadget = "Nothing has been yet uploaded!";
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
	<title>Pending Items</title>
	<link rel="stylesheet" type="text/css" href="stylesheets/login_pending.css">
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
		<u>Books Uploaded:</u> 
		<?php 	if($count_book == 0)
					echo "<br>".$err_book ;
				else
				{
					echo "<table id='book_table'>
					<tr>
					<th>Book Name</th>
					<th>Author</th>
					<th>Availability</th>
					<th>Deliver To</th>
					</tr>";
					$c = 0;
					while($c < $count_book)
					{
						echo "<tr><td>".$book[$c]['book_name']."</td>";
						echo "<td>".$book[$c]['author']."</td>";
						if($book[$c]['status']==1)
							echo "<td>Not Available</td>";
						else
							echo "<td>Available</td>";
						echo "<td>".$book[$c]['order_by_name']."</td></tr>";
						$c++;
					}
					echo "</table>";
				}
		?>
		<br><br>
		<u>Instruments Uploaded:</u> 
		<?php 	if($count_instrument == 0)
					echo "<br>".$err_instrument ;
				else
				{
					echo "<table id='book_table'>
					<tr>
					<th>Instrument Name</th>
					<th>Model</th>
					<th>Availability</th>
					<th>Deliver To</th>
					</tr>";
					$c = 0;
					while($c < $count_instrument)
					{
						echo "<tr><td>".$instrument[$c]['instrument_name']."</td>";
						echo "<td>".$instrument[$c]['model']."</td>";
						if($instrument[$c]['status']==1)
							echo "<td>Not Available</td>";
						else
							echo "<td>Available</td>";
						echo "<td>".$instrument[$c]['order_by_name']."</td></tr>";
						$c++;
					}
					echo "</table>";
				}
		?>
		<br><br>
		<u>Gadgets Uploaded:</u> 
		<?php 	if($count_gadget == 0)
					echo "<br>".$err_gadget ;
				else
				{
					echo "<table id='book_table'>
					<tr>
					<th>Gadget Name</th>
					<th>MOdel</th>
					<th>Availability</th>
					<th>Deliver To</th>
					</tr>";
					$c = 0;
					while($c < $count_gadget)
					{
						echo "<tr><td>".$gadget[$c]['gadget_name']."</td>";
						echo "<td>".$gadget[$c]['model']."</td>";
						if($gadget[$c]['status']==1)
							echo "<td>Not Available</td>";
						else
							echo "<td>Available</td>";
						echo "<td>".$gadget[$c]['order_by_name']."</td></tr>";
						$c++;
					}
					echo "</table>";
				}
		?>
	</div>
	<!--<div id="footer1">
	</div>
	<div id="footer">
		CS345 Production
	</div>-->
</body>
