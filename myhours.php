<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work History</title>
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
        table, th, td {
            border: 1px solid black;
        }
        th {
            color: white;
            background-color: teal;
        }
        td {
            text-align: center;
            border: 3px solid black;
        }
        </style>
</head>
<body>
    <?php
        session_start();
        include("detail.php");
        $id = $_SESSION['staff_id'];
    ?>

    <div class="topnav">
        <a href="staff.php">Home</a>
        <a class="active" href="myhours.php">Working History</a>
        <a href="brokenitems.php">Broken Returns</a>
        <a href="logout.php">Log Out</a> 
    </div>

    
    <?php
        // Check if user is still logged in

        // Display user name
        $arg = "SELECT * FROM staff WHERE employee_id = '$id'";
        $arg = $db->query($arg);
        $argrow = mysqli_num_rows($arg);

        if ($argrow == 0) {
        echo '<script language="javascript">	

        document.location.replace("stafflogin.php");

        </script>';
        }
        $arg1 = mysqli_fetch_assoc($arg);

        $name = $arg1['full_name'];
        echo "<p>You are logged in as ".$name."</p>";

    ?>

    <br>
    <h2>Completed Shifts</h2>
    
    <?php 

        $qry = "SELECT shift_date, ROUND((clock_out_time - clock_in_time)/60 /60, 2)
        AS total_time FROM staff_shifts WHERE staff_id = '$id' AND clock_out_time IS NOT NULL";
        $qry = $db->query($qry);
        $nrow = mysqli_num_rows($qry);

        $qry2 = "SELECT SUM(ROUND((clock_out_time - clock_in_time)/60 /60, 2))
        AS all_time FROM staff_shifts WHERE staff_id = '$id' AND clock_out_time IS NOT NULL";
        $qry2 = $db->query($qry2);
        $rows2 = mysqli_fetch_assoc($qry2);

        if ($nrow != 0){
            echo "<table>
                <tr>
                    <th>Date</th>
                    <th>Total Hours Worked</th>
                </tr>";
            for($i=0; $i < $nrow; $i++){
                $rows = mysqli_fetch_assoc($qry);
                
                echo "<tr>";
                    echo "<td>".$rows['shift_date']."</td>";
                    echo "<td>".$rows['total_time']."</td>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td><b>Total Hours:</b></td>";
                echo "<td><b>".$rows2['all_time']."</b></td>";
            echo "</tr>";
            echo "</table>";
        }

        else {
            echo "<h5>No Completed Shifts</h5>";
        }

    ?>

    <?php

        $q = "SELECT * FROM event_hours INNER JOIN events ON event_hours.event_id = events.event_id 
        WHERE staff_id = '$id' AND (clock_in_time IS NOT NULL OR clock_out_time IS NOT NULL)";
        $query = $db->query($q);
        $row_results = mysqli_num_rows($query);


        for ($i = 0; $i < $row_results; $i++) {

            $row = mysqli_fetch_assoc($query);

            $event_date[$i] = $row['date'];
            $event_location[$i] = $row['location'];
            $van_assigned[$i] = $row['registration'];
            $dropoff_pickup[$i] = $row['dropoff_pickup'];
            $clockin[$i] = $row['clock_in_time'];
            $clockout[$i] = $row['clock_out_time'];

        }

    ?>
    <br><br>
    <h5>Event History</h5>

    <table>
        <tr>
            <th>Date</th>
            <th>Drop Offs</th>
            <th>Pick Ups</th>
            <th>Clock In Time</th>
            <th>Clock Out Time</th>
        </tr>

        <?php

            // Store distinct shift IDs in array
            $shift_idList = "SELECT DISTINCT event_hours.shift_id FROM event_hours 
            INNER JOIN staff_shifts ON staff_shifts.shift_id = event_hours.shift_id 
            WHERE event_hours.staff_id = '$id' AND clock_out_time IS NOT NULL 
            ORDER BY shift_date DESC";
            $shift_idList = $db->query($shift_idList);
            $num_rows = mysqli_num_rows($shift_idList);

            if ($num_rows == 0){
                echo "<tr>";
                echo "<td>No History Available</td>";
                echo "</tr>";
            }

            else {

                // Store Number of Dropoffs and Pickups per delivery
                for ($i = 0; $i < $num_rows; $i++) {
                    $shiftr = mysqli_fetch_assoc($shift_idList);
                    $hold = $shiftr['shift_id'];
                    $shift[$i] = $shiftr['shift_id'];
                    $drop = "SELECT COUNT(dropoff_pickup) AS dropoff FROM event_hours WHERE shift_id = '$hold' AND dropoff_pickup = 'Drop-Off'";
                    $drop = $db->query($drop);
                    $dropoff[$i] = mysqli_fetch_assoc($drop)['dropoff'];
                    $pick = "SELECT COUNT(dropoff_pickup) AS pickup FROM event_hours WHERE shift_id = '$hold' AND dropoff_pickup = 'Pick-Up'";
                    $pick = $db->query($pick);
                    $pickup[$i] = mysqli_fetch_assoc($pick)['pickup'];
                }


                $query = "SELECT * FROM staff_shifts WHERE staff_id = '$id' AND clock_out_time IS NOT NULL ORDER BY shift_date DESC";
                $result = $db->query($query);
                $row_results = mysqli_num_rows($result);

                for($i=0; $i < $row_results; $i++){
                    $row = mysqli_fetch_assoc($result);
                    echo "<tr>";
                    echo "<td>".$row['shift_date']."</td>";
                    echo "<td>".$dropoff[$i]."</td>";
                    echo "<td>".$pickup[$i]."</td>";
                    echo "<td>".$row['clock_in_time']."</td>";
                    echo "<td>".$row['clock_out_time']."</td>";
                }
            }

        ?> 
          
    </table>

</body>
</html>