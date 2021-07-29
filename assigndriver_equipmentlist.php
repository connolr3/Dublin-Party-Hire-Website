<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            assigndriver.php
        TODO:
            Present Admin with events and equipment ordered for a date set on assigndriver.php
            Admin can scroll through events using next/prev
    -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Breakdown</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Equipment</title>

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

        include("detail.php");
        session_start();
        $date = $_SESSION['date'];


        
        // Count allows the user to skip to the next verification or back to previous verification
        // Stored as session variable so count can be incremented / decremented when user requires
        if (!(isset($_SESSION['count']))) {
            $_SESSION['count'] = 0;
        }

        $count = $_SESSION['count'];

        // Select number of event that are ordered for day (regardless of whether they are goods in or goods out)
        $q = "SELECT DISTINCT event_id FROM events WHERE start_date = '$date' OR end_date = '$date'";
        $q = $db->query($q);
        $nrow = mysqli_num_rows($q);

        // Set the event_id session variable to the row corresponding the the count variable 
        for ($i = 0; $i < $nrow; $i++) {
            $row = mysqli_fetch_assoc($q);
            if ($i == $_SESSION['count']) {
                $_SESSION['event_id'] = $row['event_id'];
            }
        }

    

        // To save errors the 'next' link (to increment the count session variable) is only displayed when there are more events to be seen
        if ($count < $nrow - 1) {
            echo '<h6>> <a href="nextpageassignevents.php">Next</a></h6>';
        }

        // Similarly for 'prev' link
        if ($count > 0) {
            echo '<h6>< <a href="prevpageassignevents.php">Prev</a></h6>';
        }
        // Inform the admin of number of event 
        echo '<h4>Number of Events: <b>'.$nrow.'</b></h4>';

    ?>


    <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:80%">
        <?php
            // Display name of event for admiin
            $eventList = "SELECT * FROM events WHERE event_id = '".$_SESSION['event_id']."'";
            $eventList = $db->query($eventList);
            $row = mysqli_fetch_assoc($eventList);

            echo '<h4 class="w3-wide w3-center">Equipment Requirements for <b>'.$row['event_name'].'</b></h4>';

            // Relay relevant event information
            echo '<table style="width:60%; margin-left:auto; margin-right:auto; background-color:orange">
                <tr>
                    <td style="background-color:#CCFFCC"><b>Event Name</b></td>
                    <td style="background-color:#CCFFCC"><b>Location</b></td>
                    <td style="background-color:#CCFFCC"><b>County</b></td>
                </tr>
                <tr>
                    <td style="background-color:#CCFFFF">'.$row['event_name'].'</td>
                    <td style="background-color:#CCFFFF">'.$row['location'].'</td>
                    <td style="background-color:#CCFFFF">'.$row['county'].'</td>
                </tr>
            </table>';


        ?>

        <!-- Display Equipment Ordered -->
        <!-- dropofflist.php and pickuplist.php (driver-side) are more comprehensive in their output -->
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
        
                $q = "SELECT equipment_rented.quantity, equipment_rented.setup, equipment.category, equipment.product_name FROM equipment_rented
                    INNER JOIN equipment ON equipment_rented.equipment_id = equipment.id 
                    WHERE equipment_rented.events_id = '".$_SESSION['event_id']."'";
                $result = $db->query($q);

                $num_results = mysqli_num_rows ($result);
                for ($i=1; $i <$num_results+1; $i++)
                {
                    $row = mysqli_fetch_assoc($result);
                    echo "<tr>";
                        echo "<td>".$row['category']."</td>";
                        echo "<td>".$row['product_name']."</td>";
                        echo "<td>".$row['quantity']."</td>";
                    echo "</tr>";
                }
                echo '</table>';

            
            ?>

        </tbody>
        </table>

    </div>

</body>

</html>