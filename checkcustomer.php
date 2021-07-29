
<!-- TO DO
checkcustomer.php
Purpose: checkcustomer redirects user to appropriate page, depending on whether they are logged in or not
Customer is logged-in if sessionvariable['user_email'] is set
Accessed from:  orderequipment.php
Sends user to: invoice.php - if user is logged in, they are sent straight to invoice.php 
                loginghost.php or registerghost.php - if user is not logged in, they can choose to login as an existing customer or register as a new one

-->
<?php
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
    min-width: 600px;
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
<!-- Navigation Bar -->
<?php
  // Only display for customer side
  if (isset($_SESSION['user_email'])) {
    echo '<div class="topnav">
      <a href="customeraccount.php">Customer Home</a>
      <a href="newevent.php">Register Event</a>
      <a href="pastinvoices.php">View Past Orders</a>
      <a href="logout.php">Log Out</a>
    </div>';
  } else {

    echo '<div class="topnav">
    <a href="index.php"> Home</a>
  </div>';



  }

?>
  <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">
  <h4 class="w3-wide w3-center">Thank you for creating your event</h4>
    
<?PHP  
   session_start();
   include("detail.php");

   #if the customer is not logged in, ask them whether they want to sign in or register
    if (is_null($_SESSION['user_email'])){
    echo '<p class="w3-opacity w3-center"><i>We\'re not finished yet - It looks like you\'re not logged in! </i></p> ';
    echo "We don't want to waste all your hard work. Your event will <b> not be saved</b> until you confirm your invoice.<br></i></p> <br>";
    echo 'Would you like to <a href = "loginghost.php" target = "_self">login </a> or ';
    echo '<a href = "registerghost.php" target = "_self">register </a> as a new user? ';
    }
 


 #if the session variable is not null, the customer is logged in and can be sent straight to the thank you page
    else {
        header( 'Location: https://stu33001.scss.tcd.ie/group_3/invoice.php' ) ; 
    }

?>

</div>
</body>

</html>