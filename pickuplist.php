<!DOCTYPE html>
<html lang="en">
<head>

<!-- 
    Source:
        staff.php (only if clocked in for a shift)
    TO DO:
        Present a list of goods to be picked up from event
-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pick Up List</title>

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
        
        table {
            border: 1px solid black;
        }
        td {
            text-align: center;
            border: 1px solid black;
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
        $q = "SELECT event_id FROM event_hours WHERE shift_id = '".$_SESSION['shift_id']."' AND dropoff_pickup = 'Pick-Up'";
        $q = $db->query($q);
        $nrow = mysqli_num_rows($q);

        // Set the event_id session variable to the row corresponding the the count variable 
        for ($i = 0; $i < $nrow; $i++) {
            $row = mysqli_fetch_assoc($q);
            $eventidList[$i] = $row['event_id'];
            if ($i == $_SESSION['count']) {
                $_SESSION['event_id'] = $row['event_id'];
            }
        }

    ?>



    <?php
        // To save errors the 'next' link (to increment the count session variable) is only displayed when there are more events to verify
        if ($count < $nrow - 1) {
            echo '<h6>> <a href="nextpagepick.php">Next</a></h6>';
        }

        // Similarly for 'prev' link
        if ($count > 0) {
            echo '<h6>< <a href="prevpagepick.php">Prev</a></h6>';
        }
        // Inform the admin of outstanding event verifications
        echo '<h4>Number of Pickups / Event Returns: <b>'.$nrow.'</b></h4>';

    ?>



        <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:80%">
            <?php

                if ($nrow > 0) {

                    // Display name of event for admiin
                    $eventList = "SELECT * FROM events WHERE event_id = '".$_SESSION['event_id']."'";
                    $eventList = $db->query($eventList);
                    $row = mysqli_fetch_assoc($eventList);
    
                    echo '<h4 class="w3-wide w3-center">Drop Off Equipment Requirements for <b>'.$row['event_name'].'</b></h4>';
                    echo '<p class="w3-opacity w3-center"><i>Please input any equipment that was not returned or was broken using the link below</i></p> <br>';
            
                    echo '<table style="width:60%; margin-left:auto; margin-right:auto; background-color:orange">
                        <tr>
                            <td style="background-color:#CCFFCC"><b>Event Name</b></td>
                            <td style="background-color:#CCFFCC"><b>Location</b></td>
                            <td style="background-color:#CCFFCC"><b>County</b></td>
                            <td style="background-color:#CCFFCC"><b>Time of Arrival</b></td>
                            <td style="background-color:#CCFFCC"><b> Delivery / Collection</b></td>
                        </tr>
                        <tr>
                            <td style="background-color:#CCFFFF">'.$row['event_name'].'</td>
                            <td style="background-color:#CCFFFF">'.$row['location'].'</td>
                            <td style="background-color:#CCFFFF">'.$row['county'].'</td>
                            <td style="background-color:#CCFFFF">'.$row['end_time'].'</td>';
                            if ($row['delivery_status'] == 1) {
                                $del_col = "For Delivery";
                            } else {
                                $del_col = "For Collection";
                            }
                            echo '<td style="background-color:#CCFFFF">'.$del_col.'</td>
                        </tr>
                    </table>';

                ?>
                
            <table class="styled-table" style="width:60%; margin-left:auto; margin-right:auto">
                <thead>
                    <tr>
                        <th style="text-align:center">Category</th>
                        <th style="text-align:center">Product Name</th>
                        <th style="text-align:center">Quantity</th>
                    </tr>
                </thead>
                <tbody>

                <?php
            
                    // Same table output as the brokenitems.php page for the staff side of the input
                    $q = "SELECT equipment_rented.quantity, equipment_rented.setup, equipment.category, equipment.product_name 
                        FROM equipment_rented INNER JOIN equipment ON equipment_rented.equipment_id = equipment.id 
                        WHERE equipment_rented.events_id = '".$_SESSION['event_id']."'";
                    $result = $db->query($q);

                    $num_results = mysqli_num_rows ($result);

                    if ($num_results == 0) {
                        echo '<tr>
                            <td colspan="3">No Equipment has been rented for this event</td>
                        </tr>';
                    } else {  

                        // Ensure event is for pick-up
                        $q2 = "SELECT end_date FROM events WHERE event_id = '".$_SESSION['event_id']."'";
                        $result2 = $db->query($q2);
    
                        if (mysqli_fetch_assoc($result2)['end_date'] == date("Y-m-d")) {
                        
                            // reset query rows
                            $result = $db->query($q);

                            for ($i=0; $i < $num_results; $i++)
                            {
                                $row = mysqli_fetch_assoc($result);
                                echo "<tr>";
                                    echo "<td>".$row['category']."</td>";
                                    echo "<td>".$row['product_name']."</td>";
                                    echo "<td>".$row['quantity']."</td>";
                                echo "</tr>";
                            }

                        }

                    }

                    echo '</table>';

                

            ?>

            </tbody>
            </table>

        </div>

        <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">
            <a href="brokenitems.php" style="position:5px"><input type="submit" value="Log Broken Returns"></a>
        </div>

        <?php
                // Close if statement
                }
                ?>

</body>

</html>