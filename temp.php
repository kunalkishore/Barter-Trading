<?php
	include('config.php');
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	function hello()
	{
		echo "hello!";
		return;
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
			$con = mysqli_connect(constant("HOSTNAME"), constant("USERNAME"), constant("PASS"), constant("DBNAME"));
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else{
				//pending requests
				if ($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					if(isset($_GET['dis_gadgets']))
					{	$dis =  $_GET['dis_gadgets'] ;
						//we need to update the dispatch status of this
						$sql_update = "UPDATE gadgets SET dispatched = '1' where id = '".$dis."'";
						if ($con->query($sql_update) === TRUE) {
	    					//echo "Record updated successfully";
						} else {
	    					//echo "Error updating record: " . $con->error;
						}
						$sql_update = "UPDATE registration SET sent_pending = sent_pending - '1' where id = '".$id."'";
						if ($con->query($sql_update) === TRUE) {
	    					//echo "Record updated successfully";
						} else {
	    					//echo "Error updating record: " . $con->error;
						}
					}
				}
				$count_request_gadgets = 0;
				$count_order_gadgets = 0;
				$sql_request_gadgets = "SELECT * FROM gadgets where (user_id = '".$id."' && status = '1' && delivered !='1') ";
				$result_request_gadgets = $con->query($sql_request_gadgets);
				if ($result_request_gadgets->num_rows > 0) {
				    while($row = $result_request_gadgets->fetch_assoc()) {
				        $row_request_gadgets[$count_request_gadgets]['id'] = $row['id'];
				        $row_request_gadgets[$count_request_gadgets]['gadget_name'] = $row['gadget_name'];
				        $row_request_gadgets[$count_request_gadgets]['model'] = $row['model'];
				        $row_request_gadgets[$count_request_gadgets]['dispatched'] = $row['dispatched'];
				        $row_request_gadgets[$count_request_gadgets]['delivered'] = $row['delivered'];
				        $row_request_gadgets[$count_request_gadgets]['order_by'] = $row['order_by'];

				        //to find who ordered!
				        $sql_o = "SELECT * from registration where id='".$row_request_gadgets[$count_request_gadgets]['order_by']."'";
				        $result_o = $con->query($sql_o);
				        if($row_o = $result_o->fetch_assoc()) {
        					$row_request_gadgets[$count_request_gadgets]['order_name'] = $row_o['user_name'];
        					$row_request_gadgets[$count_request_gadgets]['address'] = $row_o['address'];
    					}

				        $count_request_gadgets ++;
				    }
				}
				else
				{
					$err_request_gadgets = "Nothing to display!";
				}
				//pending orders
				if ($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					if(isset($_GET['del_gadgets']))
					{	$del =  $_GET['del_gadgets'] ;
						$owner_id = $_GET['owner_gadgets'];
						$rate = $_POST['rating'];
						//we need to update the dispatch status of this
						$sql_update = "UPDATE gadgets SET delivered = '1' where id = '".$del."'";
						if ($con->query($sql_update) === TRUE) {
	    					//echo "Record order updated successfully";
						} else {
	    					//echo "Error updating record: " . $con->error;
						}
						if($rate == "rate_me")
						{	$sql_update = "UPDATE registration SET points = points+'1' ,sent_successfully = sent_successfully +'1' where id='".$owner_id."' ";
							if ($con->query($sql_update) === TRUE) {
		    					//echo "Record order updated successfully";
							} else {
		    					//echo "Error updating record: " . $con->error;
							}
						}
						else
						{
							$sql_update = "UPDATE registration SET rating = rating * sent_successfully +'".$rate."' where id='".$owner_id."' ";
							if ($con->query($sql_update) === TRUE) {
		    					//echo "Record order updated successfully";
							} else {
		    					//echo "Error updating record: " . $con->error;
							}	
							$sql_update = "UPDATE registration SET sent_successfully = sent_successfully +'1' where id='".$owner_id."' ";
							if ($con->query($sql_update) === TRUE) {
		    					//echo "Record order updated successfully";
							} else {
		    					//echo "Error updating record: " . $con->error;
							}
						}
						$sql_update = "UPDATE registration SET items_received = items_received + '1' , items_pending = items_pending - '1' where id = '".$id."'";
						if ($con->query($sql_update) === TRUE) {
	    					//echo "Record updated successfully";
						} else {
	    					//echo "Error updating record: " . $con->error;
						}
					}
				}
				$sql_order_gadgets = "SELECT * FROM gadgets where (order_by = '".$id."' && status = '1' && delivered !='1') ";
				$result_order_gadgets = $con->query($sql_order_gadgets);
				if ($result_order_gadgets->num_rows > 0) {
				    while($row = $result_order_gadgets->fetch_assoc()) {
				        $row_order_gadgets[$count_order_gadgets]['id'] = $row['id'];
				        $row_order_gadgets[$count_order_gadgets]['gadget_name'] = $row['gadget_name'];
				        $row_order_gadgets[$count_order_gadgets]['model'] = $row['model'];
				        $row_order_gadgets[$count_order_gadgets]['dispatched'] = $row['dispatched'];
				        $row_order_gadgets[$count_order_gadgets]['delivered'] = $row['delivered'];
				        $row_order_gadgets[$count_order_gadgets]['owner'] = $row['user_id'];
				        //to get the details of person who is delivering
				        $sql_o = "SELECT * from registration where id='".$row_order_gadgets[$count_order_gadgets]['owner']."'";
				        $result_o = $con->query($sql_o);
				        if($row_o = $result_o->fetch_assoc()) {
        					$row_order_gadgets[$count_order_gadgets]['owner_name'] = $row_o['user_name'];
        					$row_order_gadgets[$count_order_gadgets]['address'] = $row_o['address'];
    					}
				        $count_order_gadgets ++;
				    }
				}
				else
				{
					$err_order_gadgets = "Nothing to display!";
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
		<a href = "login_browse.php" style="color:white;margin:10px;">Browse</a>
		<a href = "logout.php" style="color:white;margin:10px;">Log Out</a>
	</div>
	<div id="name">
		<h1>V-EXCHANGE</h1>
	</div>
	<div id = "home">
		<u>Pending gadgets to be Sent:</u>
		<?php 	if($count_request_gadgets == 0)
					echo "<br>".$err_request_gadgets ;
				else
				{
					echo "<table id='gadget_table'>
					<tr>
					<th>gadget Name</th>
					<th>model</th>
					<th>Dispatched</th>
					<th>Delivered</th>
					<th>Deliver To</th>
					<th>Address</th>
					</tr>";
					$cr = 0;
					while($cr<$count_request_gadgets)
					{
						echo "</tr><td>".$row_request_gadgets[$cr]['gadget_name']."</td>";
						echo "<td>".$row_request_gadgets[$cr]['model']."</td>";
						if($row_request_gadgets[$cr]['dispatched']==0)
						{
							//we want a button that will allow to update the status!
							//echo "<td>".$row_request[$cr]['dispatched']."</td>";
							//echo "<td><button type ='button' name='".$row_request[$cr]['id']."' class = 'css_button' onclick=''>Dispatched</button></td>";
							echo "<form  method=\"post\" action='login_pending.php?dis_gadgets=".$row_request_gadgets[$cr]['id']."'> ";
							echo "<td><input type=\"submit\" name='".$row_request_gadgets[$cr]['id']."' value=\"Dispatched\" class=\"css_button\" /></td> ";
							echo "</form>";
						}
						else
						{
							echo "<td>Dispatched</td>";
						}
						echo "<td>".$row_request_gadgets[$cr]['delivered']."</td>";
						echo "<td>".$row_request_gadgets[$cr]['order_name']."</td>";
						echo "<td>".$row_request_gadgets[$cr]['address']."</td></tr>";
						$cr++;
					}
					echo "</table>";
				}
		?>
		<br><br>
		<u>Pending gadgets to be Received:</u>
		<?php 	if($count_order_gadgets == 0)
					echo "<br>".$err_order_gadgets ;
				else
				{
					echo "<table id='gadget_table'>
					<tr>
					<th>gadget Name</th>
					<th>model</th>
					<th>Dispatched</th>
					<th>Delivered</th>
					<th>Delivered By</th>
					</tr>";
					$co = 0;
					while($co<$count_order_gadgets)
					{
						echo "</tr><td>".$row_order_gadgets[$co]['gadget_name']."</td>";
						echo "<td>".$row_order_gadgets[$co]['model']."</td>";
						echo "<td>".$row_order_gadgets[$co]['dispatched']."</td>";
						if($row_order_gadgets[$co]['delivered']==0)
						{
							echo "<form  method=\"post\" action='login_pending.php?del_gadgets=".$row_order_gadgets[$co]['id']."&owner_gadgets=".$row_order_gadgets[$co]['owner']."'> ";
							echo "<td><select class = 'dropdown' name='rating'>
									<option value = 'rate_me'>Rate Me</option>
									<option value = '1'>1</option>
									<option value = '2'>2</option>
									<option value = '3'>3</option>
									<option value = '4'>4</option>
									<option value = '5'>5</option></select>
								  <input type=\"submit\" name='".$row_order_gadgets[$co]['id']."' value=\"Delivered\" class=\"css_button\" /></td> ";
							echo "</form>";
						}
						else
						{
							echo "<td>Delivered</td>";
						}
						echo "<td>".$row_order_gadgets[$co]['owner_name']."</td>";
						$co++;
					}
					echo "</table>";
				}
		?>
	</div>
	<div id="footer1">
	</div>
	<div id="footer">
		CS345 Production
	</div>
</body>
