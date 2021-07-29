<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            staffhours.php

        TODO:
            Admin has chosen a specific shift to be further explored
            We will show a breakdown first of the scheduled clock in /out vs logged hours
                and will allow the admin to alter the hours
            Then a breakdown of the events serviced by the shift are also shown with the tracker updates
                Times should be the same for the 'Began Loading' and 'Van Departed' as they are logged as one input
    -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Breakdown</title>
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
        th, td {
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
        $shift_id = $_SESSION['shift_id'];
        $employee = $_SESSION['employee'];


    ?>



    <h2>Shift Summary</h2>
    <?php
        // Detail highlights
        $q1 = "SELECT * FROM staff_shifts WHERE shift_id = '$shift_id'";
        $result1 = $db->query($q1);
        $row1 = mysqli_fetch_assoc($result1);

        $q2 = "SELECT * FROM staff WHERE employee_id = '$employee'";
        $result2 = $db->query($q2);
        $row2 = mysqli_fetch_assoc($result2);

        $stDate = strtotime($row1['shift_date']);
        $stDate = date("m/d/Y", $stDate);


        echo "<p>Details for <b>".$row2['full_name']."<br>Shift ID: ".$shift_id."</b> on <b>".$stDate.'</b></p>';

    ?>

    <!-- Success Message for the alteration of staff hours. Deletes session variable after use -->
    <span style='color:green;'><?php echo $_SESSION['success'];?></span><br>
    <?php unset($_SESSION['success']); ?>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
    method="POST" name="alter_time" id="alter_time">
    <?php

        // FORM 1: Displays hours worked vs hours scheduled
        echo '<table>
            <tr>
                <th></th>
                <th>Date</th>
                <th>Clock In</th>
                <th>Clock Out</th>
            </tr>
            <tr>
                <th> Hours Worked </th>
                <td>'.$row1['shift_date'].'</td>
                <td>'.$row1['clock_in_time'].'</td>
                <td>'.$row1['clock_out_time'].'</td>
           </tr>
            <tr>
                <th> Scheduled Hours </th>
                <td></td>
                <td>'.$row1['set_clock_in'].'</td>
                <td>'.$row1['set_clock_out'].'</td>
            </tr>
        </table><br>';

        // Option to alter the hours
        echo '<input type="submit" name="alter" value="Alter Hours">';
        echo '</form>';


        // Allow admin to alter hours for shift
        // Stored as session variable boolean so new form would stay open if there are errors
        if (isset($_POST['alter'])){

            $_SESSION['alter'] = TRUE;

        }

        if ($_SESSION['alter']) {

            echo '<br>';
            // Form for altering hours
            echo '<form action="" method="POST" name="alter_hours" id="alter_hours">';

            echo '<label for="clock_in">Set Clock In</label>
            <input type="time" name="clock_in" id="clock_in" value="<?php echo $clockIn;?>">';

            echo '<br>';

            echo '<label for="clock_out">Set Clock Out</label>
            <input type="time" name="clock_out" id="clock_out" value="'.$clockOut.'">';

            echo '<br><br>';

            echo '<input type="submit" name="next" value="Submit">';

            echo '</form>';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_POST["next"])) {
                    $clockIn = $clockOut = $clockOutErr = "";
                    if (empty($_POST["clock_in"])){
                        // If empty, time is unchanged
                        $clockIn = $row1['clock_in_time'];
                    } else {
                        $clockIn = $_POST['clock_in'];
                    }
                    
                    if (empty($_POST["clock_out"])){
                        // If empty, time is unchanged
                        $clockOut = $row1['clock_out_time'];
                    } else {
                        $clockOut = $_POST['clock_out'];
                        if ($clockOut < $clockIn) {
                            $clockOutErr = "Clock out cannot be before clock in";
                        }
                    }

                    // SQL input
                    if ($clockOutErr == "" && $clockOut != "") {
                        $query = "UPDATE staff_shifts SET clock_in_time = '$clockIn', clock_out_time = '$clockOut'
                        WHERE shift_id = '$shift_id'";
                        $run = $db->query($query);
                        // Success Message
                        $_SESSION['success'] = "Hours Successfully Changed";
                        // Exit if statement
                        $_SESSION['alter'] = FALSE; 
                        echo '<script language="javascript">	

                        document.location.replace("shiftdetails.php");
                
                        </script>';
                    } else if ($clockOutErr != "") {
                        // Error message (only clock out error)
                        echo "<br><span style='color:#CC3333;'>".$clockOutErr."</span><br>";
                    }

                }
            }
       
        }

        
        
        echo '<br><br>';

        echo '<h4>Events Serviced</h4>';

        // Present events serviced and times relating to the events
        $qry = "SELECT vans_loading, van_enroute, registration, dropoff_pickup, event_name, county, start_time, end_time, delivery_status
        FROM event_hours INNER JOIN events ON event_hours.event_id = events.event_id
        WHERE shift_id = '$shift_id'";
        $res = $db->query($qry);
        $nrow = mysqli_num_rows($res);


        if ($nrow == 0) {
            echo '<h5><b>No events serviced during shift</b></h5>';
        }

        else {

            echo '<table>
                <tr>
                    <th>Event Name</th>
                    <th>County</th>
                    <th>Collection / Delivery</th>
                    <th>Returns / Outgoing</th>
                    <th>Required Time of Arrival</th>
                    <th>Began Loading</th>
                    <th>Van Departed</th>
                </tr>';

            for ($i = 0; $i < $nrow; $i++) {
                $row = mysqli_fetch_assoc($res);

                // If Statements for presenting consistency in the output
                echo '<tr>
                    <td>'.$row['event_name'].'</td>
                    <td>'.$row['county'].'</td>';
                    if ($row['delivery_status'] == 1) {
                        echo '<td>For Delivery</td>';
                        echo '<td>'.$row['dropoff_pickup'].'</td>';
                    } else {
                        echo '<td>For Collection</td>';
                        if ($row['dropoff_pickup'] == 'Drop-Off') {
                            echo '<td>Out-Going</td>';
                        } else {
                            echo '<td>Returns</td>';
                        }
                    }
                    if ($row['dropoff_pickup'] == 'Drop-Off') {
                        echo '<td>'.$row['start_time'].'</td>';
                        if ($row['vans_loading'] == '') {
                            echo '<td>- -</td>';
                        } else {
                            echo '<td>'.$row['vans_loading'].'</td>';
                        }
                    } else {
                        echo '<td>'.$row['end_time'].'</td>';
                        echo '<td>Not Required</td>';
                    }
                    if ($row['van_enroute'] == '') {
                        echo '<td>- -</td>';
                    } else {
                        echo '<td>'.$row['van_enroute'].'</td>';
                    }
                echo '</tr>';

            }

            echo '</table>';

        }


    ?>

</body>
</html>