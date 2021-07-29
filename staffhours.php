<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            admin.php
        TODO:
            List off employee shift history and total hours worked
            Allow admin to choose specific employee and/or date constraint
            If admin chooses a specific employee show a breakdown of their shifts 
                and allows admin to investigate further their shifts and alter hours on another page
    -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Hours - Admin</title>
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
        $id = $_SESSION['staff_id'];

        $employee = $start_date = $end_date = "";
        $start_dateErr = $end_dateErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["employee"])){
                // If ALL employees to be displayed
                $employee = "0";
            } else {
                $employee = $_POST["employee"];         
            }

            if (empty($_POST['opendate'])) {
                $start_date = "";
            } else {
                $start_date = $_POST["opendate"];
                if ($start_date > date("Y-m-d")){
                    $start_dateErr = "Please select a day before today";
                }
            }

            if (empty($_POST['closedate'])) {
                // If end date is empty set it to today
                // This is just if user inputs a start date and no end date
                // Has no effect if user does not input a start date as SQL date constrained queries are only run if start_date is input
                $end_date = date("Y-m-d");
            } else {
                $end_date = $_POST["closedate"];
                if ($end_date < $start_date){
                    $start_dateErr = "Please select an end date after the start date";
                }
            }
        }

    ?>

    <div class="topnav">
        <a href="admin.php">Admin Home</a>
        <a class="active" href="staffhours.php">Staff Hours</a>
        <a href="incompleteshifts.php">Incomplete Shifts</a>
        <a href="verifybrokenitems.php">Verify Broken Items</a>
        <a href="logout.php">Log Out</a>     
    </div>

    <!-- All employee who either forgot to clock out or didn't even clock in -->
    <p><a href="incompleteshifts.php">Correct Incompleted Shifts</a></p>

    <h2>Employee History</h2>
    You Can Narrow Down Your Selections Below <br>

    <!-- Option to add constraints of Employee and Date Range to the Output -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
    method="POST" name="constaints" id="constraints">
        <table>   
            <tr>
                <td>Select Employee</td>
                <td>Date Range</td>
            </tr>  
            <tr>
                <td>
                    <?php
                        // Staff Drop Down List
                        $q = "SELECT * FROM staff WHERE position != 'manager'";
                        $result = $db->query($q);
                        $num_rows = mysqli_num_rows($result);

                        /* Checkbox to choose multiple employees
                        $all_ids = '';
                        for ($i = 0; $i < $num_rows; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            $all_ids .= '<input type="checkbox" name="id_list[]" 
                            value="'.$row['employee_id'].'">'.$row['full_name'].'<br>';
                        }

                        if ($all_ids == ''){
                            echo '';
                        } else {
                            echo $all_ids;
                        }*/

                        echo "<select name='employee' id='employee'>";
                        echo "<option value='0'>ALL</option>";
                        for ($i = 0; $i < $num_rows; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            echo '<option value='.$row['employee_id'].'>'.$row['full_name'].'</option>';
                        }
                        echo "</select>";
                    ?>
                </td>
                <td>
                    <input type="date" name="opendate">
                    <span class = "error"><?php echo $start_dateErr;?></span>
                </td>
                <td>
                    <input type="date" name="closedate">
                    <span class = "error"><?php echo $end_dateErr;?></span>
                </td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Submit"> || <input type="reset" value="Reset">

    </form>

    <br><br>

    <?php

        if ($start_dateErr == "" && $end_dateErr == "") {
            // If user selects to see all employees' shifts, only display number of shifts and total hours worked for each employee
            if ($employee == 0) {

                // Has not selected a date range for the shifts
                if ($start_date == ''){

                    // Go thorugh each staff member and output relevant data onto tables
                    $getStaffID = "SELECT * FROM staff WHERE position = 'driver' ORDER BY full_name";
                    $getStaff = $db->query($getStaffID);
                    $nrow = mysqli_num_rows($getStaff);

                    echo '<table>';
                    echo '<tr>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>Number of Shifts</th>
                            <th>Total Hours</th>
                        </tr>';

                    for ($i = 0; $i < $nrow; $i++) {
                        $staffRow = mysqli_fetch_assoc($getStaff);
                        $employeeID = $staffRow['employee_id'];

                        $getHours = "SELECT ROUND(SUM(SUBTIME(clock_out_time, clock_in_time))/10000, 2) AS total_hours, 
                            MIN(shift_date) AS start_date, COUNT(shift_id) AS num_shifts 
                            FROM staff_shifts WHERE clock_out_time IS NOT NULL AND staff_id = '$employeeID'";
                        $getHours = $db->query($getHours);
                        $line = mysqli_fetch_assoc($getHours);

                        if ($line['num_shifts'] == 0) {
                            echo '<tr>
                            <td>'.$staffRow['full_name'].'</td>
                            <td>-</td>
                            <td>0</td>
                            <td>0</td>
                            </tr>';
                        }
                        else {
                            echo '<tr>
                                    <td>'.$staffRow['full_name'].'</td>
                                    <td>'.$line['start_date'].'</td>
                                    <td>'.$line['num_shifts'].'</td>
                                    <td>'.$line['total_hours'].'</td>
                                </tr>';
                        }

                    }

                    echo '</table>';


                } 
                
                // Selected a date range
                else {

                    $stDate = strtotime($start_date);
                    $stDate = date("m/d/Y", $stDate);

                    $endDate = strtotime($end_date);
                    $endDate = date("m/d/Y", $endDate);

                    echo '<h4>Date Range: '.$stDate.' - '.$endDate.'</h4>';

                    // Go thorugh each staff member and output relevant data onto tables
                    $getStaffID = "SELECT * FROM staff ORDER BY full_name";
                    $getStaff = $db->query($getStaffID);
                    $nrow = mysqli_num_rows($getStaff);

                    echo '<table>';
                    echo '<tr>
                            <th>Name</th>
                            <th>Number of Shifts</th>
                            <th>Total Hours</th>
                        </tr>';

                    for ($i = 0; $i <= $nrow; $i++) {
                        $staffRow = mysqli_fetch_assoc($getStaff);
                        $employeeID = $staffRow['employee_id'];

                        $getHours = "SELECT SUM(ROUND((clock_out_time - clock_in_time)/60/60, 2)) AS total_hours, MIN(shift_date) AS start_date, COUNT(shift_id) AS num_shifts FROM staff_shifts
                        WHERE clock_out_time IS NOT NULL AND staff_id = '$employeeID' AND shift_date >= '$start_date' AND shift_date <= '$end_date'";
                        $getHours = $db->query($getHours);
                        $line = mysqli_fetch_assoc($getHours);

                        if ($line['num_shifts'] == 0) {
                            echo '<tr>
                            <td>'.$staffRow['fullname'].'</td>
                            <td>0</td>
                            <td>0</td>
                            </tr>';
                        }
                        else {
                            echo '<tr>
                                    <td>'.$staffRow['full_name'].'</td>
                                    <td>'.$line['num_shifts'].'</td>
                                    <td>'.$line['total_hours'].'</td>
                                </tr>';
                        }
                    }

                    echo '</table>';
                }

            } 
            
            // Individual staff member has been selected
            // Here we will be more comprehensive breakdown of employee history 
            // We will display shift breakdowns and hours worked
            // We will allow admin to reach links where they can alter hours and see a further breakdown of events in an individual shift
            else {

                $_SESSION['employee'] = $employee;

                $stname = "SELECT * FROM staff WHERE employee_id = '$employee'";
                $stname = $db->query($stname);
                $stname = mysqli_fetch_assoc($stname)['full_name'];

                echo '<p>Work History for '.$stname.'</p>';

                // No Date Input
                if ($start_date == ''){

                    // Select shifts
                    $query = "SELECT * FROM staff_shifts WHERE staff_id = '$employee' AND clock_out_time IS NOT NULL ORDER BY shift_date DESC";
                    $result = $db->query($query);
                    $row_results = mysqli_num_rows($result);

                    // Store shift IDs
                    for ($i = 0; $i < $row_results; $i++) {
                        $row = mysqli_fetch_assoc($result);
                        $shiftIDList[$i] = $row['shift_id']; 
                    }

                    $result = $db->query($query);


                } 
                
                // Date Input
                else {

                    $stDate = strtotime($start_date);
                    $stDate = date("m/d/Y", $stDate);

                    $endDate = strtotime($end_date);
                    $endDate = date("m/d/Y", $endDate);

                    echo '<h4>Date Range: '.$stDate.' - '.$endDate.'</h4>';


                    // Select shifts
                    $query = "SELECT * FROM staff_shifts WHERE staff_id = '$employee' AND clock_out_time IS NOT NULL 
                    AND shift_date >= '$start_date' AND shift_date <= '$end_date'
                    ORDER BY shift_date DESC";
                    $result = $db->query($query);
                    $row_results = mysqli_num_rows($result);

                    // Store IDs
                    for ($i = 0; $i < $row_results; $i++) {
                        $row = mysqli_fetch_assoc($result);
                        $shiftIDList[$i] = $row['shift_id']; 
                    }

                }

                echo '<table>';

                if ($row_results == 0){
                    echo "<tr>";
                    echo "<td>No History Available</td>";
                    echo "</tr>";
                }

                else {                    

                    // Store Number of Dropoffs and Pickups per delivery
                    for ($i = 0; $i < $row_results; $i++) {
                        $drop = "SELECT COUNT(dropoff_pickup) AS dropoff FROM event_hours WHERE shift_id = '".$shiftIDList[$i]."' AND dropoff_pickup = 'Drop-Off'";
                        $drop = $db->query($drop);
                        $dropoff[$i] = mysqli_fetch_assoc($drop)['dropoff'];
                        $pick = "SELECT COUNT(dropoff_pickup) AS pickup FROM event_hours WHERE shift_id = '".$shiftIDList[$i]."' AND dropoff_pickup = 'Pick-Up'";
                        $pick = $db->query($pick);
                        $pickup[$i] = mysqli_fetch_assoc($pick)['pickup'];
                    }

                    echo '<tr>
                        <th>Date</th>
                        <th>Dropoffs</th>
                        <th>Pickups</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>More / Alter Hours</th>
                    </tr>';

                    $result = $db->query($query);

                    for($i=0; $i < $row_results; $i++){
                        $row = mysqli_fetch_assoc($result);

                        // Form for input button which corresponds to an individual shift
                        echo '<form method="POST" name="shift" action"">';

                        echo "<tr>";
                        echo "<td>".$row['shift_date']."</td>";
                        echo "<td>".$dropoff[$i]."</td>";
                        echo "<td>".$pickup[$i]."</td>";
                        echo "<td>".$row['clock_in_time']."</td>";
                        echo "<td>".$row['clock_out_time']."</td>";

                        echo '<td><input type="submit" value="'.$row['shift_id'].'" name="more_details"/></td>';

                        echo "</tr>";

                        echo '</form>';

                    }

                    echo '</table>';

                }

            }

            
            // For Further Breakdown of employee shifts and option to alter hours
            if (isset($_POST['more_details'])){
                $shiftInfo = $_POST['more_details'];
                $_SESSION['shift_id'] = $shiftInfo;
                echo '<script language="javascript">	

                window.open("https://stu33001.scss.tcd.ie/group_3/shiftdetails.php", "_blank");
        
                </script>';
            }
            
            
        }

    ?>



</body>
</html>