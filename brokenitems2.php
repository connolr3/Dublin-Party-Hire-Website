<!DOCTYPE html>
<html lang="en">
<head>

<!-- 
    Source:
        Staff side: (brokenitems.php, brokeninput.php, editbrokenitems.php)
        Admin side: (verifybrokenitems.php, editbrokenitems.php)
    TO DO
        Proper input for broken items after brokenitems.php used as a location to select the event
        User can input equipment relating to events they were assigned and quantities that were broken or not returned
        Also admin can access this page as part of the verify items links if they want to add items that were forgotten
-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broken Returns</title>

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

    <?php

        include ("detail.php"); 
        session_start();
        $event_id = $_SESSION['event_id'];

        // Admin / Driver nav bars
        $qry = "SELECT * FROM staff WHERE employee_id = '".$_SESSION['staff_id']."'";
        $res = $db->query($qry);
        $rows = mysqli_fetch_assoc($res);
        if ($rows['position'] == 'driver') {
            echo '<div class="topnav">
                <a href="staff.php">Home</a>
                <a href="myhours.php">Working History</a>
                <a href="brokeninput2.php">Broken Returns</a>
                <a href="logout.php">Log Out</a> 
            </div>';

            echo '<h6>< <a href="brokenbackpage.php">Back to Select Event</a></h6>';
        } else {
            echo '<div class="topnav">
                <a href="staffhours.php">Staff Hours</a>
                <a href="verifybrokenitems.php">Verify Broken Items</a>
                <a href="editbrokenitems.php">Delete Items</a>
                <a class="active" href="brokeninput2.php">Add Returns</a>
                <a href="logout.php">Log Out</a>
            </div>';
        }
    ?>


    <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">
        <h4 class="w3-wide w3-center">Log Broken Equipment</h4>
            <p class="w3-opacity w3-center"><i>Please input any equipment that was not returned or was broken</i></p> <br>
    

        <!--form to specify which product and quantity they want to order, based on category chosen above--> 
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?PHP  

                $qry = "SELECT * FROM events WHERE event_id = $event_id";
                $qry = $db->query($qry);
                $qry = mysqli_fetch_assoc($qry);
                $name = $qry['event_name'];

                #drop down select option for the products
                echo '<label for="product">Select a Product ordered for <b>'.$name.' </b><br></label>';
                echo "<select name='product',id='product'>";
                $q  = "SELECT id, product_name, equipment_rented.quantity FROM `equipment`, equipment_rented WHERE events_id = '$event_id' AND equipment_id = id";
                $result = $db->query($q);
                $num_results = mysqli_num_rows ($result);
                for ($i=1; $i < $num_results+1; $i++)
                {
                    $row = mysqli_fetch_assoc($result);
                    echo "<option value='".$row['id']."'> ".$row['product_name']."; ".$row['quantity']."</option>"; 
                }
                echo '</select><br><br><br>';

                echo 'Number Broken / Not Returned<br>';
                echo '<label for="quantity">Quantity:</label>
                <input type = "number" name = "quantity" id = "quantity" size =2 >
                <input type = "submit" name="submit_order" value = "Order">
                
                </form>';
            

                if (isset($_POST['submit_order'])){
                    
                    // Check if an input already exists for given item and ensure total quantity input is less than total quantity ordered
                    $productid = $_POST["product"];
                    $quantity = ($_POST["quantity"]);

                    $q  = "SELECT quantity FROM equipment_rented 
                        WHERE events_id = '$event_id' AND equipment_id = '$productid'";
                    $q = $db->query($q);
                    $row = mysqli_fetch_assoc($q);
                    $qty = $row['quantity'];
                    
                    $s = "SELECT * FROM broken_returns WHERE equipment_id = '$productid' AND event_id = '$event_id'";
                    $s = $db->query($s);
                    $num_rows = mysqli_num_rows($s);

                    // Previous input for item -> add new quatity to previous input
                    if ($num_rows > 0) {

                        $quant = mysqli_fetch_assoc($s)['quantity'];
                        $quantity = $quantity + $quant;

                    }

                    // Check if total quantity is less than quantity ordered
                    // Then update or insert depending on whether or not input already exists
                    if ($quantity <= $qty) {
                        if ($num_rows == 0) {
                            $insertquery = "INSERT INTO broken_returns (equipment_id, event_id, quantity, verified) 
                                VALUES ('$productid','$event_id','$quantity', FALSE)";
                            $result = $db->query($insertquery);
                            echo "<p style='color:green;'>Confirmed. Added to Broken Returns</p><br>" ;
                        } else {

                            $insertquery = "UPDATE broken_returns SET quantity = '$quantity' WHERE equipment_id = '$productid' AND event_id = '$event_id'";
                            $result = $db->query($insertquery);
                            echo "<p style='color:green;'>Confirmed. Updated Broken Returns</p><br>" ;
                        }
                    } else {
                        echo "<p style='color:red;'>Quantity broken cannot exceed quatity ordered</p>";
                    }

                }
            ?>
        <br><br><br> 
        <?php
            // Admin / Driver Input
            if ($rows['position'] == 'driver'){
                echo '<a href = "brokeninput.php" target = "_self">View your order</a>';
            } else {
                echo '<a href = "verifybrokenitems.php" target = "_self">View order</a>';
            }
        ?>
    </div>

</body>

</html>