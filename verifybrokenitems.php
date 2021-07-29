<!DOCTYPE html>
<html lang="en">
<head>

<!-- 
    Source:
        staffhours.php
    TO DO
        Allow admin to verify the broken items input by the staff member
        Give option to admin to skip through the various events so they can return to certain ones later
        Give option to admin to remove or add certain items that were incorrectly input 
        Amend broken_returns table to verified so it doesn't return to admin
        Amend equipment table by reducing quantities on hand
-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Broken Items</title>

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

        // Count allows the user to skip to the next verification or back to previous verification
        // Stored as session variable so count can be incremented / decremented when user requires
        if (!(isset($_SESSION['count']))) {
            $_SESSION['count'] = 0;
        }

        $count = $_SESSION['count'];

        // Select number of verifications that are required (unverified events)
        $q = "SELECT DISTINCT event_id FROM broken_returns WHERE verified = FALSE";
        $q = $db->query($q);
        $nrow = mysqli_num_rows($q);

        // Set the event_id session variable to the row corresponding the the count variable 
        for ($i = 0; $i < $nrow; $i++) {
            $row = mysqli_fetch_assoc($q);
            if ($i == $_SESSION['count']) {
                $_SESSION['event_id'] = $row['event_id'];
            }
        }

    ?>

    <div class="topnav">
        <a href="staffhours.php">Staff Hours</a>
        <a class="active" href="verifybrokenitems.php">Verify Broken Items</a>
        <a href="editbrokenitems.php">Delete Items</a>
        <a href="brokenitems2.php">Add Returns</a>
        <a href="logout.php">Log Out</a>
    </div>

    <?php
        // To save errors the 'next' link (to increment the count session variable) is only displayed when there are more events to verify
        if ($count < $nrow - 1) {
            echo '<h6>> <a href="nextpage.php">Next</a></h6>';
        }

        // Similarly for 'prev' link
        if ($count > 0) {
            echo '<h6>< <a href="prevpage.php">Prev</a></h6>';
        }
        // Inform the admin of outstanding event verifications
        echo 'Number of Events to Verify: '.$nrow;

        if (isset($_SESSION['success'])) {
            echo '<br><span style="color:green">'.$_SESSION['success'].'</span>';
            unset($_SESSION['success']);
        }
        // Only show rest of form if there are verifications outstanding
        if ($nrow > 0) {

    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">
            <?php
                // Display name of event for admiin
                $name = "SELECT event_name FROM events WHERE event_id = '".$_SESSION['event_id']."'";
                $name = $db->query($name);
                $name = mysqli_fetch_assoc($name)['event_name'];

                echo '<h4 class="w3-wide w3-center">Verify Broken Equipment for Event '.$name.'</h4>';
                echo '<p class="w3-opacity w3-center"><i>Please input any equipment that was not returned or was broken</i></p> <br>';
        

            ?>
            
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity Broken / Not Returned</th>
                </tr>
            </thead>
            <tbody>

            <?php
        
                // Same table output as the brokenitems.php page for the staff side of the input
                $q = "SELECT equipment.id, equipment.product_name, equipment.category, broken_returns.quantity FROM broken_returns INNER JOIN equipment ON broken_returns.equipment_id = equipment.id WHERE broken_returns.event_id = ".$_SESSION['event_id'];
                $result = $db->query($q);

                $num_results = mysqli_num_rows ($result);
                for ($i = 0; $i < $num_results; $i++)
                {
                    $row = mysqli_fetch_assoc($result);
                    echo "<tr>";
                        echo "<td>".$row['product_name']."</td>";
                        echo "<td>".$row['category']."</td>";
                        echo "<td>".$row['quantity']."</td>";
                    echo "</tr>";
                }
                echo '</table>';
                
                echo '<input type = "submit" name="verify" value = "Verify">';
            
                echo '</form>';

                // Verify button pressed -> update table and refresh page
                // Update equipment table with reduced quantity
                if (isset($_POST['verify'])) {
                    $event_id = $_SESSION['event_id'];
                    $verif = "UPDATE broken_returns SET verified = TRUE WHERE event_id = '$event_id'";
                    $run = $db->query($verif);

                    // rerun query to reset fetch assoc
                    // Decrement equipment quantity by broken amount
                    $q = "SELECT equipment.id, equipment.product_name, equipment.category, broken_returns.quantity FROM broken_returns INNER JOIN equipment ON broken_returns.equipment_id = equipment.id WHERE broken_returns.event_id = ".$_SESSION['event_id'];
                    $result = $db->query($q);
                    for ($i = 0; $i < $num_results; $i++) {
                        $row = mysqli_fetch_assoc($result);
                        $broken = $row['quantity'];
                        $id = $row['id'];
                        $equip = "UPDATE equipment SET quantity = quantity - '$broken' WHERE id = '$id'";
                        $runDeduction = $db->query($equip);
                    }

                    if ($count == $nrow - 1) {
                        $count = $count - 1;
                        $_SESSION['count'] = $count;
                    }
                    $_SESSION['success'] = 'Event Verified';
                    echo '<script language="javascript">	

                    document.location.replace("verifybrokenitems.php");
            
                    </script>'; 
                }

            
            ?>

            </tbody>
            </table>

            
            <br><br>
            Not happy?<br><br>
            <a href = "brokenitems2.php" target = "_self">Add more broken items</a><br>
            <a href = "editbrokenitems.php" target = "_self">Edit your input </a>

        </div>

        
        <?php
        // Close if statement
        }
        ?>

</body>

</html>