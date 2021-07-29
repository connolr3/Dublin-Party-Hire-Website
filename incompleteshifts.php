<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            staffhours.php, admin.php
        TODO:
            Display all shifts for which the scheduled date has expired and no input exists for clock in or clock out
            Uses scrolling function (next/prev) to move between shifts
            Allows user to 'alter' shift hours or 'delete' shift if unfulfilled
    -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incompleted Shifts</title>
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

        // Count allows the user to skip to the next shift or back to previous shift
        // Stored as session variable so count can be incremented / decremented when user requires
        if (!(isset($_SESSION['count']))) {
            $_SESSION['count'] = 0;
        }

        $count = $_SESSION['count'];

        // Select number of shifts that are required (incomplete shifts)
        $q = "SELECT * FROM staff_shifts WHERE shift_date < CURDATE() AND (clock_in_time IS NULL OR clock_out_time IS NULL)";
        $q = $db->query($q);
        $nrow = mysqli_num_rows($q);

        // Set the event_id session variable to the row corresponding the the count variable 
        for ($i = 0; $i < $nrow; $i++) {
            $row = mysqli_fetch_assoc($q);
            if ($i == $_SESSION['count']) {
                $_SESSION['shift_id'] = $row['shift_id'];
            }
        }

    ?>

    <div class="topnav">
        <a href="admin.php">Admin Home</a>
        <a href="staffhours.php">Staff Hours</a>
        <a class="active" href="incompleteshifts.php">Incomplete Shifts</a>
        <a href="verifybrokenitems.php">Verify Broken Items</a>
        <a href="logout.php">Log Out</a>     
    </div>

    <?php

        // To save errors the 'next' link (to increment the count session variable) is only displayed when there are more incomplete shifts
        if ($count < $nrow - 1) {
            echo '<h6>> <a href="nextpageincompleteshifts.php">Next</a></h6>';
        }

        // Similarly for 'prev' link
        if ($count > 0) {
            echo '<h6>< <a href="prevpageincompleteshifts.php">Prev</a></h6>';
        }
        // Inform the admin of outstanding incomplete shifts
        echo '<br>Number of Incompleted Shifts: '.$nrow;

    ?>

    <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:80%">


    <?php
        // Only run code if incomplete shifts exist
        if ($nrow > 0) {

    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

            <?php
                echo '<h3 class="w3-wide w3-center">Incompleted Shifts</h3>';
                echo '<p class="w3-opacity w3-center"><i>Please <b>Alter Shift </b>Clock in / Clock out Time <br><br>or <br><br><b>Delete shifts </b>if Unfulfilled</i></p> <br>';
            ?>
        

        <!-- Success Message for the alteration of staff hours. Deletes session variable after use -->
        <span style='color:#CC3333;'><?php echo $_SESSION['success'];?></span><br>
        <?php unset($_SESSION['success']); ?>  

        <table class="styled-table" style="width:60%; margin-left:auto; margin-right:auto;">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Date
                    <th>Clock In</th>
                    <th>Clock Out</th>
                </tr>
            </thead>
            <tbody>

            <?php
                
        
                // Table to display details relating to the current incomplete shift
                $q = "SELECT shift_date, clock_in_time, clock_out_time, set_clock_in, set_clock_out, full_name FROM staff_shifts 
                INNER JOIN staff ON staff_shifts.staff_id = staff.employee_id 
                WHERE staff_shifts.shift_id = ".$_SESSION['shift_id'];
                $result = $db->query($q);
                $row = mysqli_fetch_assoc($result);

                echo "<tr>";
                    echo "<th> Hours Worked </th>";
                    echo "<td>".$row['full_name']."</td>";
                    echo "<td>".$row['shift_date']."</td>";
                    if ($row['clock_in_time'] == "") {
                        echo "<td>Unset</td>";
                    } else {
                        echo "<td>".$row['clock_in_time']."</td>";
                    }
                    if ($row['clock_out_time'] == ""){
                        echo "<td>Unset</td>";
                    } else {
                        echo "<td>".$row['clock_out_time']."</td>";
                    }
                echo "</tr>";

                echo "<tr>";
                    echo "<th> Scheduled Hours </th>";
                    echo '<td></td>';
                    echo '<td></td>';
                    echo "<td>".$row['set_clock_in']."</td>";
                    echo "<td>".$row['set_clock_out']."</td>";
                echo "</tr>";

                echo '</table>';
                
                // Option to alter or delete
                echo '<input type="submit" name="alter" value="Alter Hours">';
                echo ' || <input type="submit" name="delete" value="Shift Unfulfilled">';
            
                echo '</form>';

                // Allow admin to alter hours for shift
                // Stored as session variable boolean so new form would stay open if there are errors
                if (isset($_POST['alter'])){

                    $_SESSION['alter'] = TRUE;

                }

                if ($_SESSION['alter']) {

                    echo '<br><br><br>';

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

                            // Update staff_shifts table
                            if ($clockOutErr == "" && $clockOut != "") {
                                $query = "UPDATE staff_shifts SET clock_in_time = '$clockIn', clock_out_time = '$clockOut'
                                WHERE shift_id = '".$_SESSION['shift_id']."'";
                                $run = $db->query($query);
                                // Success statement
                                $_SESSION['success'] = "Hours Successfully Changed: ".$clockIn." - ".$clockOut;
                                // Exit if statement
                                $_SESSION['alter'] = FALSE;
                                // If count is on last page option, decrement so empty tables is not shown 
                                if ($count == $nrow - 1) {
                                    $count = $count - 1;
                                    $_SESSION['count'] = $count;
                                }
                                echo '<script language="javascript">	

                                document.location.replace("incompleteshifts.php");
                        
                                </script>';
                            } else {
                                // Echo clock out error
                                echo "<br><span style='color:#CC3333;'>".$clockOutErr."</span><br>";
                            }

                        }
                    }
            
                }

                // If deletion is required
                if (isset($_POST['delete'])) {
                    // If count is on last page option, decrement so empty tables is not shown 
                    if ($count == $nrow - 1) {
                        $count = $count - 1;
                        $_SESSION['count'] = $count;
                    }

                    // Delete all records of the shift
                    $qry = "DELETE FROM staff_shifts WHERE shift_id = '".$_SESSION['shift_id']."'";
                    $qr2 = "DELETE FROM event_hours WHERE shift_id = '".$_SESSION['shift_id']."'";
                    $res = $db->query($qry);
                    $res2 = $db->query($qry2);
                    //Success message
                    $_SESSION['success'] = "Shift Removed";
                    echo '<script language="javascript">	

                    document.location.replace("incompleteshifts.php");
            
                    </script>';

                }


            
            ?>

            </tbody>
            </table>

        <?php

            // end if statement
            } else {
                if (isset($_SESSION['success'])) {
                    //<!-- Success Message for the alteration of staff hours. Deletes session variable after use -->
                    echo '<span style="color:#CC3333;">'.$_SESSION["success"].'</span><br>';
                    unset($_SESSION['success']);
                } 
            }

        ?>


        </div>
</body>
</html>