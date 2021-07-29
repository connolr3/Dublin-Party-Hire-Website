<!-- This page shows a table of the customers who have spent the most in a given time period with DPH

This page is accessed by the reports.php page through the Best Customers Button

It redirects to the admin.php, logout.php and reports.php page through the navigation bar -->

<!-- php script to start a session -->
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPH Best Customers</title>

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

        <!-- styled table code from w3s schools-->
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

<!-- Navigation Bar with links to other pages -->
<div class="topnav">
  <a class="active" href="admin.php">Admin Home</a>
  <a href="reports.php">Back to Reports</a>
  <a href="logout.php">Log Out</a>
</div>
<body>

<!-- php script to process form input -->
<?php


$start_date = $end_date = $dateErr = $end_dateErr = $start_dateErr = "";
include("detail.php");

//If the start date is empty, set the startdate variable to 0 otherwise, set it to the input value
if(empty($_POST['start_date']))
    {
        $start_date = "0";
        $start_dateErr = "When there is no start date, data from the beginning is shown";
    }else{
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            //start date cannot be greater than end date
            if($start_date > $end_date)
            {
                $dateErr = "Start date cannot come after end date";
            }
            //start date should not be after the current day
            if($start_date > date("Y-m-d"))
            {
                $dateErr = "Please choose a date before today";
            }
            //start date should have an end date if chosen
            if($start_date!="0" && empty($end_date))
            {
                $end_dateErr = "Please select an end date";
            }
    }
?>

<!-- Page content -->
<div class="w3-container w3-content w3-padding-11" style="max-width:550px">
<h3 class="w3-wide w3-center">You can select a date range for which to view best customers:</h3>

<!-- Form for accepting data -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<table>
<tr>
                <td>Start Date:</td>
                <td>
                <input type="date" name="start_date" value="<?php echo $start_date ?>" id="start_date">
                    <span class = "error">* <?php echo $dateErr;?></span>
                </td>
            </tr> 
            <tr>
                <td>End Date:</td>
                <td>
                <input type="date" name="end_date" value="<?php echo $end_date ?>" id="end_date">
                <span class = "error">* <?php echo $end_dateErr;?></span>
                </td>
            </tr> 
        </table>
      <p><button class="w3-button w3-black" type="submit">View</button></p>
      </form>
      <br>
      <br>
</div>

<!-- Table to display the best customers and rank them based on how much they spent (descending) -->
<div class="w3-container w3-content w3-padding-11" style="max-width:450px">
   <h1 class="w3-wide w3-center">Best Customers</h1>
   <table class="styled-table">
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Customer Email </th>
            <th>Total Spent €</th> 
        </tr>
    </thead>
    <tbody>

<!-- php script to retrieve the relevant data -->
    <?php

    //if there is no start date, get all the data
        if($start_date == "0"){
        echo $start_dateErr;
		$q1 = "SELECT DISTINCT customers.name, customers.email, SUM(receipts.event_cost) as 'total' FROM customers, receipts, events WHERE events.event_id = receipts.event_id AND customers.email = events.cust_email GROUP BY customers.email ORDER BY total DESC;";
        $result1 = $db->query($q1);
    
		$num_employees = mysqli_num_rows($result1);
        
        //loop to print out data in a table
		for($i=0; $i < $num_employees; $i++)
		{
			$row = mysqli_fetch_assoc($result1);
			echo "<tr>";
                           echo "<td>".$row[name]."</td>";
                           echo "<td>".$row[email]."</td>";
                           echo "<td>".$row[total]."</td>";
		                   echo "</tr>";
		}
    

            
    }else{
        
        //if there is a start date and end date, get the relevant data in the date range
        if($start_date != "0" && $dateErr == "" && $end_dateErr == ""){

            $q2 = "SELECT DISTINCT customers.name, customers.email, SUM(receipts.event_cost) as 'total' FROM customers, receipts, events WHERE events.event_id = receipts.event_id AND customers.email = events.cust_email AND receipts.purchase_date BETWEEN '$start_date' AND '$end_date' GROUP BY customers.email ORDER BY total DESC;";
            $result2 = $db->query($q2);
    
            $num_employees = mysqli_num_rows($result2);
    
             //loop to print out data in a table
            for($i=0; $i < $num_employees; $i++)
            {
                $row = mysqli_fetch_assoc($result2);
                echo "<tr>";
                       echo "<td>".$row[name]."</td>";
                       echo "<td>".$row[email]."</td>";
                       echo "<td>".$row[total]."</td>";
                       echo "</tr>";
            }
    }
            
}

 
		mysqli_close($db);
	?>
<br>
    </tbody>
</table>




</body>
</html>


