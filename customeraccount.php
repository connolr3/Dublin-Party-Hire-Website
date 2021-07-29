
<!-- 
customeraccount.php
Purpose: a kind of home page for customers
accessed from: login.php or registration.php
Sends user to: customers can either
              newevent.php - create new event
              pastorders.php - view past orders, and check delivery status
              equipment.php - view catalog
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
 
    <?php
     session_start();
     include ("detail.php");
    // Get the name of the customer that corresponds to their email and print it out
        include ("detail.php");

        //SQL query to get name
       
        $q= "Select * from customers where email = '".$_SESSION['user_email']."';";
        $result = $db->query($q);
        $row = mysqli_fetch_assoc($result);

        //assign name variable
        $name = $row['name'];
        
        echo " <h2 class='w3-wide w3-left'>   ".$name."'s homepage!</h2><br>";

        ?> 
         

<!-- Register Event -->
<br>
<br>
<div class="w3-container w3-content w3-padding-11" style="max-width:500px">
<p class="w3-opacity w3-center"><i>Any party, any function, anywhere. We've got you covered!</i></p>
<p class="w3-opacity w3-center"><i>Register an event today!</i></p>

<div class="w3-container w3-content w3-padding-11" style="max-width:200px">
<form action="newevent.php">
<p><button class="w3-button w3-black" type="submit">Register a New Event!</button></p>
</form>
</div>
<br>
<br>
<!-- View Previous Orders -->

<p class="w3-opacity w3-center"><i>DPH will always deliver, we know how to party ;)</i></p>
<p class="w3-opacity w3-center"><i>Need to check your orders? Check 'em out here!</i></p>

<div class="w3-container w3-content w3-padding-11" style="max-width:320px">
<form action="pastorders.php">
<p><button class="w3-button w3-black" type="submit">Check Past Invoices, or check on a Delivery!</button></p>
</form>
</div>
<br>
<br>
<!-- View Equipment on Offer -->

<p class="w3-opacity w3-center"><i>We know everyone has different tastes and needs.</i></p>
<p class="w3-opacity w3-center"><i>That's why we have a wide selection of equipment:</i></p>

<div class="w3-container w3-content w3-padding-11" style="max-width:200px">
<form action="equipment.php">
<p><button class="w3-button w3-black" type="submit">View Equipment Catalog!</button></p>
</form>
</div>
<br>
</div>

</body>
</html> 
