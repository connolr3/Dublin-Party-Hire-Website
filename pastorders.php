
<!--
 pastorders.php
  purpose: displays all events a customer has, allows them to view past invoices and check a delivery status
  accessed from: customeraccout.php
  sends user to: pastinvoice.php - allow customer to view a past invoice
                checkorderstatus - allow customer to see if their equipment is loaded etc.
  -->

  <?php
//start session 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPH Equipment</title>

    <!-- Style code from https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_templates_band&stacked=h -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {font-family: "Lato", sans-serif}
    </style>

     <!-- CSS Navigation Bar, code from WS3 Schools-->
     <style>
        body {
          margin: 0;
          font-family: Arial, Helvetica, sans-serif;
        }
        
        .topnav {
          overflow: hidden;
          background-color: #333;
        }
        
        .topnav a {
          float: left;
          color: #f2f2f2;
          text-align: center;
          padding: 14px 16px;
          text-decoration: none;
          font-size: 17px;
        }
        
        .topnav a:hover {
          background-color: #ddd;
          color: black;
        }
        
        .topnav a.active {
          background-color: #4CAF50;
          color: white;
        }
        </style>

        <!-- styled table-->
        <style>
        .styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

.styled-table thead tr {
    background-color: #18e76e;
    color: #ffffff;
    text-align: left;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

.styled-table tbody tr.active-row {
    font-weight: bold;
    color: #009879;
}
</style>
</head>
<body>

<!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
<div class="w3-bar w3-black w3-card">
    <a href="customeraccount.php">Customers Home</a>
    <a href="newevent.php">Register Event</a>    
    <a href="pastorders.php">View Past Orders</a>
    <a href="logout.php">Log Out</a>
</div>
  </div>
  

  <!-- Page Content -->
 

<!--Show Customers Events -->
<div class="w3-container w3-content w3-center w3-padding-20" style="max-width:800px">
    <h4 class="w3-wide w3-center">Your Events</h4>
    <p class="w3-opacity w3-center"><i>Please find the details of your events</i></p>
    </p>
    <table class="styled-table">
    <thead>
        <tr>
            <th>Event Name</th>
            <th>Event Location </th>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            
        </tr>
    </thead>
    <tbody>
        
      <!-- php query which returns events a customer has booked-->
      <?php
      session_start();
      include ("detail.php"); 
      $cust_email = $_SESSION['user_email'];
			$q = "SELECT * FROM events WHERE cust_email = '$cust_email'";
			$result = $db->query($q);
            $num_results = mysqli_num_rows ($result);
			for($i=0; $i <$num_results; $i++)
			{
                $row = mysqli_fetch_assoc($result);
			echo "<tr>";
                           echo "<td>".$row[event_name]."</td>";
                           echo "<td>".$row[location]."</td>";
		                   echo "<td>".$row[date]."</td>";
		                   echo "<td>".$row[start_time]."</td>";
                           echo "<td>".$row[end_time]."</td>";
		                   echo "</tr>";
		}
		mysqli_close($db);		
      ?>		
      </table>
    </tbody>
</table>
</div>


<!--php script to show orders for an event -->
<div class="w3-container w3-content w3-center w3-padding-5" style="max-width:500px">
    <h4 class="w3-wide w3-center">View Equipment Ordered and Invoice for an Event</h4>
    <p class="w3-opacity w3-center"><i>Select the event for which you would like to view equipment rented</i></p>
    </p>
        
    <form action="pastinvoice.php" method="post">
        <select class="w3-input w3-border" name="rentals" style="width: 400px">
       <!-- php query which returns the names of categories in the equipment table to put into the select box in form -->
      <?php 
       session_start();
       include ("detail.php"); 
       $cust_email = $_SESSION['user_email'];
	   $q1 = "SELECT * FROM events WHERE cust_email = '$cust_email'";
         $result1 = $db->query($q1);
         $num_results = mysqli_num_rows($result1);
			for($i=0; $i <$num_results; $i++)
			{
               $row = mysqli_fetch_assoc($result1);
			   echo '<option value="'.$row['event_id'].'">'.$row['event_name'].'</option>'; 	 			
			}
			mysqli_close ($db);
      ?>		
      </select>
      <p><button class="w3-button w3-black" type="submit">View Equipment Rented</button></p>
      </form>





      <!--php script to check status of event -->
<div class="w3-container w3-content w3-center w3-padding-20" style="max-width:500px">
    <h4 class="w3-wide w3-center">Check the delivery status for an event</h4>
    <p class="w3-opacity w3-center"><i>Select the event for which you would like to view status</i></p>
    </p>
    <form action="checkorderstatus.php" method="post">
        <select class="w3-input w3-border" name="event" style="width: 400px">
       <!-- php query which returns the names of categories in the equipment table to put into the select box in form -->
      <?php 
       session_start();
       include ("detail.php"); 
       $cust_email = $_SESSION['user_email'];
	   $q1 = "SELECT * FROM events WHERE cust_email = '$cust_email'";
         $result1 = $db->query($q1);
         $num_results = mysqli_num_rows($result1);
			for($i=0; $i <$num_results; $i++)
			{
               $row = mysqli_fetch_assoc($result1);
			   echo '<option value="'.$row['event_id'].'">'.$row['event_name'].'</option>'; 	 			
			}
			mysqli_close ($db);
      ?>		
      </select>
      <p><button class="w3-button w3-black" type="submit">Check Delivery Status</button></p>
      </form>   
</body>
</html>


