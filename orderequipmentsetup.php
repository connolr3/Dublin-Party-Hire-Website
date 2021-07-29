

<!-- 
orderequipmentsetup.php
Purpose: form to allow customer to select setup for equipment if offered, and if the customer selects delivery
accessed from: orderequipment.php
Sends user to: orderequipment.php
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
  <h4 class="w3-wide w3-center">Order Equipment</h4>
    <p class="w3-opacity w3-center"><i>Thank you for creating your event. Order equipment down below;</i></p> <br>
 Hmmmmmm....
 <img src = "face.jpg" width="30" height="30" ><br>
    <br>This product may be difficult to set up.<br> Would you like to purchase set up?<br>
    <!-- Display the price to the customer-->
    <?PHP 
     include ("detail.php");
     session_start();
     $insertquery = "SELECT * from equipment where  id = ".$_SESSION['product'];
     $result = $db->query($insertquery);
     $row = mysqli_fetch_assoc($result);
     echo "<i>Each ".$row['product_name']." costs ".$row['setup_price_excl_vat']." to set up</i><br><br><br><br>";
    ?>
    <!-- form for user to select setup or not-->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="radio" id="yes" name="setup" value="0">
    <label for="no"> No - I would like to set up this equipment myself </label><br>
    <input type="radio" id="yes" name="setup" value="1">
    <label for="yes">Yes - I would like to purchase set up</label><br><br>
    <input type = "submit" name="submitsetup" value = "Order"><br>
    
    </form>
<br><br><br> 
<img src="cart-icon.png" alt="error loading image sorry" width="50" height="50" >
<?PHP  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "Equipment has been added to your event!" ;
        include ("detail.php");
        session_start();
        $setuprequired = 0;
        $setuprequired = $_POST["setup"];

        #check whether the customer has already got some of the same product
        $checkexistingquery = "Select * from equipment_rented where events_id = ".$_SESSION['event_id']." and equipment_id = ".$_SESSION['product'];
        $resultcheckexisting = $db->query($checkexistingquery);
        $num_results = mysqli_num_rows ($resultcheckexisting);
        $row = mysqli_fetch_assoc($result);

         #product is not already ordered, insert as a new row
        if($num_results==0){
          $insertquery = "INSERT INTO equipment_rented (equipment_id,	events_id,	quantity,	setup,sale,rental_price,setup_price) VALUES (".$_SESSION['product'].",".$_SESSION['event_id'].",".$_SESSION['quantity'].",$setuprequired".",".$_SESSION['saleoffered'].",".$_SESSION['rental_price'].",".$_SESSION['setup_price'].")";
          $result = $db->query($insertquery);
          
         }

            #otherwise, increment the quantiy already ordered by the new quantity
            #NOTE: it is assumed that the customer can't choose to have some of a product setup
            #eg: if they order 4 tables, they can't pay for two of them only to be setup
            #it is all or nothing
            #the update will therefore 
            #1. add qunatity desired to the quantity already ordered
            #2. reset setup as the what the customer specifies this time 
         else {
          $updatequery = "UPDATE equipment_rented SET quantity= quantity + ".$_SESSION['quantity']."
          , setup = ".$setuprequired. " WHERE events_id = ".$_SESSION['event_id']." and equipment_id = ".$_SESSION['product'];
          $result = $db->query($updatequery);
         }
         #return to orderequipment.php 
        sleep(1);
        header( 'Location: https://stu33001.scss.tcd.ie/group_3/orderequipment.php' ) ; 
    }
?>

</div>
</body>

</html>


