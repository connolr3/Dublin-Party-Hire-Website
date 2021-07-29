<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            admin.php
            employeeaction.php
        TODO: 
            This is quite an extensive page with several input options
            The goal is to assign staff members to shifts (day and hours) and then assign events to each shift
            First admin can select a date (after today) or hit submit and tomorrow is submitted as the date
            Then a list of events (delivery pickup & dropoff, collection outward & inward) is displayed 
                with it location and number of staff members already assigned to it.
            The admin is given a chance to assign workers to shifts based off the volumes of events that day
            Then the admin is required to assign events to each of the shifts and finish the order
    -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Drivers</title>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
     <!-- Style code from https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_templates_band&stacked=h -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Import CDN link for Select 2 used for drop downs etc -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Select2 CSS --> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

    <!-- jQuery --> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

    <!-- Select2 JS --> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

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
            border: 1px solid black;
        }
        p {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar code from WS3 Schools-->
    <div class="topnav">
        <a href="admin.php">Admin Home</a>
        <a href="employeeaction.php">Employee Actions</a>
        <a class="active" href="assigndriver.php">Assign Driver</a>
        <a href="logout.php">Log Out</a>
    </div>


    <?php

        session_start();
        include("detail.php");


        if (isset($_SESSION['date'])) {
            $date = $_SESSION['date'];
        }



// Admin creates a shift and while $_SESSION['shift_id'] is set, they can assign events to that shift 
// While Session Variable is active, we don't want the user changing date until the process has been exited and the Session Variable unset
if (!(isset($_SESSION['shift_id']))){

        // Date Input to begin
        echo '<h5>Select a Date or Press Submit</h5>';

        // Store $date as session variable so we can add next/prev date scrolling function
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST['submit'])) {

                if (empty($_POST['date'])) {
                    // If empty set date equal to tomorrow
                    $date = "";
                    $date = date("Y-m-d", time() + 86400);
                    $_SESSION['date'] = $date;
                } else {
                    $date = $_POST['date'];
                    // Only allow date inputs after today
                    if ($date <= date("Y-m-d")) {
                        $dateErr = "Please select a date in the future";
                    } else {
                        // Store as session variable for scrolling function
                        $_SESSION['date'] = $date;
                    }
                }

            }

        }


    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="customer_registration" id="customer_registration">
    
        <input type="date" name="date" id="date">
        <input type="submit" name="submit" value="Submit">

    </form>

    <?php

        // Date scrolling function
        // Allow user to move back and forth through days efficiently
        echo '> <a href="nextpageassigndriver.php">next</a>';
        if (!($date == date("Y-m-d", time() + 86400))){
            echo ' || < <a href="prevpageassigndriver.php">prev</a>';
        }
    
// end loop for open Session Variable
}
    

        // date is set, begin 
        if ($dateErr == "" && $date != "") {

            // Customers can either have goods delivered or can collect from warehouse

            //  All drop-off Deliveries
            $q1 = "SELECT * FROM events WHERE start_date = '$date' AND delivery_status = '1' ORDER BY start_time ASC";
            // All Pick-Up Deliveries
            $q2 = "SELECT * FROM events WHERE end_date = '$date'AND delivery_status = '1' ORDER BY end_time ASC";
            // All Drop-Off (goods out) Collections
            $q3 = "SELECT * FROM events WHERE start_date = '$date'AND delivery_status = 0 ORDER BY start_time ASC";
            // All Pick-Up (goods returned) Collections
            $q4 = "SELECT * FROM events WHERE end_date = '$date'AND delivery_status = 0 ORDER BY end_time ASC";


            $res1 = $db->query($q1);
            $res2 = $db->query($q2);
            $res3 = $db->query($q3);
            $res4 = $db->query($q4);

            $nrow1 = mysqli_num_rows($res1);
            $nrow2 = mysqli_num_rows($res2);
            $nrow3 = mysqli_num_rows($res3);
            $nrow4 = mysqli_num_rows($res4);

            // For table display,
            // One big table (Line 700)
            //      -> seperated into Deliveries and Collection horizontally
            //      -> seperated into Goods In and Goods Out vertically
            // Find the max of deliveries in vs out & Collections in vs out
            $nrowdelivery = max($nrow1, $nrow2);
            $nrowpickup = max($nrow3, $nrow4);

            echo '<br><br>';

            echo '<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">';

            // If Events exist for selected day
            if ($nrowdelivery > 0 || $nrowpickup > 0) {


                // Store all event IDs, names and requirements (dropoff / pickup) in seperate arrays
                for ($i = 0; $i < $nrow1; $i++) {
                    $rowres1 = mysqli_fetch_assoc($res1);
                    $eventIDList[$i] = $rowres1['event_id'];
                    $eventNameList[$i] = $rowres1['event_name'];
                    $eventReqList[$i] = 'Drop-Off';
                }

                for ($i = 0; $i < $nrow2; $i++) {
                    $rowres2 = mysqli_fetch_assoc($res2);
                    $eventIDList[$i + $nrow1] = $rowres2['event_id'];
                    $eventNameList[$i + $nrow1] = $rowres2['event_name'];
                    $eventReqList[$i + $nrow1] = 'Pick-Up';
                }

                for ($i = 0; $i < $nrow3; $i++) {
                    $rowres3 = mysqli_fetch_assoc($res3);
                    $eventIDList[$i + $nrow1 + $nrow2] = $rowres3['event_id'];
                    $eventNameList[$i + $nrow1 + $nrow2] = $rowres3['event_name'];
                    $eventReqList[$i + $nrow1 + $nrow2] = 'Drop-Off';
                }

                for ($i = 0; $i < $nrow4; $i++) {
                    $rowres4 = mysqli_fetch_assoc($res4);
                    $eventIDList[$i + $nrow1 + $nrow2 + $nrow3] = $rowres4['event_id'];
                    $eventNameList[$i + $nrow1 + $nrow2 + $nrow3] = $rowres4['event_name'];
                    $eventReqList[$i + $nrow1 + $nrow2 + $nrow3] = 'Pick-Up';
                }

                $ntotalrow = $nrow1 + $nrow2 + $nrow3 + $nrow4;

                // Store number of staff assigned to each event in array
                for ($i = 0; $i < $ntotalrow; $i++) {
                    $qry = "SELECT * FROM event_hours WHERE event_id = '".$eventIDList[$i]."' AND dropoff_pickup = '".$eventReqList[$i]."'";
                    $qry = $db->query($qry);
                    $driversAssignedList[$i] = mysqli_num_rows($qry);
                }



                // Present form for creating shifts if one not already selected and stored in Session Variable
                // If user creates a shift, they will be required to add events to shifts 
                //      this if statement seperates the two actions
                if (!(isset($_SESSION['shift_id']))){

                    echo '<h4>Use the Form Below to Assign Workers to Events</h4>';
                    echo '<form action="" method="POST">';

                        // Store driver ids, names and date of last shift scheduled before the selected date
                        $st_id = "SELECT * FROM staff WHERE position = 'driver'";
                        $st_id = $db->query($st_id);
                        $st_idrows = mysqli_num_rows($st_id);

                        for ($i = 0; $i < $st_idrows; $i++) {
                            $rowStaff = mysqli_fetch_assoc($st_id);

                            $staff_idList[$i] = $rowStaff['employee_id'];
                            $staff_nameList[$i] = $rowStaff['full_name'];

                            // Store most recent shift from $date, Take first row value as sql ordered by date
                            $last_worked = "SELECT shift_date FROM staff_shifts WHERE staff_id = '".$staff_idList[$i]."' AND shift_date < '$date' ORDER BY shift_date DESC";
                            $last_worked = $db->query($last_worked);
                            if (mysqli_num_rows($last_worked) == 0) {
                                $last_workedList[$i] = "None";
                            } else {
                                $last_workedList[$i] = mysqli_fetch_assoc($last_worked)['shift_date'];
                            }
                        }

                        // Form 1:
                        // Select Driver to be assigned a shift and the time of clock in and out. Date is set by $date
                        // Drop Down stores employee ID but displays their name and the date of their last shift to the user
                        echo '<table>';
                        echo '<tr>';
                        echo '<td>Select Driver</td>';                   
                        echo "<td><select name='employee' id='employee'>";
                        echo '<option value="none">Not Selected</option>';
                        for ($i = 0; $i < $st_idrows; $i++) {
                            echo '<option value='.$staff_idList[$i].'>'.$staff_nameList[$i].'. Previous Shift: '.$last_workedList[$i].'</option>';
                        }
                        echo "</select></td>";
                        echo '</tr>';
                        echo '<tr>
                            <td>Set Clock In</td>
                            <td>
                                <input type="time" name="start_time">
                                <span class = "error">* '.$startErr.'</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Set Clock Out</td>
                            <td>
                                <input type="time" name="end_time">
                                <span class = "error">* '.$endErr.'</span>
                            </td>
                        </tr>';
                        echo '</table>';

                        // OR allow user to select an existing shift, created in the past, to be updated
                        $q = "SELECT * FROM staff_shifts INNER JOIN staff ON staff_id = employee_id WHERE shift_date = '$date'";
                        $run = $db->query($q);
                
                        $nrow = mysqli_num_rows($run);

                        if ($nrow > 0) {
                            echo '<br><b>OR</b></br>';

                            echo '<table>';
                            echo "<tr>
                                <td>Select Existing Shift : </td>
                                <td><select name='shift' id='shift'>";
                                echo '<option value="none">Not Selected</option>';
                                for ($i = 0; $i < $nrow; $i++) {
                                    $row = mysqli_fetch_assoc($run);
                                    echo '<option value='.$row['shift_id'].'>'.$row['full_name'].'. Shift ID: '.$row['shift_id'].'</option>';
                                }
                                echo '</select></td>
                            </tr>';
                            echo '</table>';
                        }

                        echo '<br><input type="submit" name="shiftToEmployee" value="Submit">';
                        
                    echo '</form>';

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {

                        $employee = $startTime = $endTime = "";
                        $employeeErr = $startErr = $endErr = "";

                        // Validations
                        if (isset($_POST['shiftToEmployee'])) {
                            if (empty($_POST["employee"])){
                                $employeeErr = "Choose Employee";
                            } else {
                                $employee = $_POST['employee'];
                            }    
                            
                            if (empty($_POST['start_time'])){
                                $startErr = "Start Time Required";
                            } else {
                                $startTime = $_POST['start_time'];
                            }

                            if (empty($_POST['end_time'])) {
                                $endErr = "End Time Required";
                            } else {
                                $endTime = $_POST['end_time'];
                                if ($endTime < $startTime) {
                                    $endErr = "Shift must start before it stops";
                                }
                            }
                        }

                        // Action for creating new shift 
                        if ($employeeErr == "" && $endErr == "" && $startErr == "" && $endTime != "" && $employee != 'none') {
                            // Ensure both form options are not selected
                            if ($_POST['shift'] == 'none') {
                                // Check if employee value exists for the date
                                $employeeCheck = "SELECT * FROM staff_shifts WHERE shift_date = '$date' AND staff_id = '$employee'";
                                $employeeCheck = $db->query($employeeCheck);
                                if (mysqli_num_rows($employeeCheck) == 0) {
                                    $update = "INSERT INTO staff_shifts (staff_id, shift_date, set_clock_in, set_clock_out)
                                        VALUES ('$employee', '$date', '$startTime', '$endTime')";
                                    $go = $db->query($update);
                                    $getShift = "SELECT shift_id FROM staff_shifts WHERE staff_id = '$employee' AND shift_date = '$date'";
                                    $getShift = $db->query($getShift);
                                    $shift = mysqli_fetch_assoc($getShift)['shift_id'];
                                    // Store shift as session variable and refresh to exit if statement and move onto the next stage
                                    $_SESSION['shift_id'] = $shift;
                                    echo '<script language="javascript">	

                                    document.location.replace("assigndriver.php");
                                
                                    </script>';
                                } else {
                                    echo '<span style="color:red">';
                                        echo "Shift already exists for this employee";
                                    echo '</span>';
                                }
                            } else {
                                echo '<span style="color:red">';
                                    echo "Only input for one of options above";
                                echo '</span>';
                            }
                        }
                        // Action for accessing created shift
                        else if (isset($_POST['shift']) && $_POST['shift'] != 'none'){
                            // Store shift as session variable and refresh to exit if statement and move onto the next stage
                            $_SESSION['shift_id'] = $_POST['shift'];
                            // Select if van in use 
                            $vanq = "SELECT registration FROM event_hours WHERE shift_id = '".$_SESSION['shift_id']."'";
                            $vanq = $db->query($vanq);
                            if (mysqli_num_rows($vanq) > 0) {
                                $_SESSION['van'] = mysqli_fetch_assoc($vanq)['registration'];
                            }
                            echo '<script language="javascript">	

                            document.location.replace("assigndriver.php");
                        
                            </script>';
                        }
                        // Error action
                        else {
                            if ($employee == 'none' && $_POST['shift'] == 'none') {
                                echo '<span style="color:red">';
                                    echo "Please select an option above";
                                echo '</span>';
                            } else { 
                                echo '<span style="color:red">';
                                echo $employeeErr;
                                echo "<br>".$startErr. "<br>".$endErr;
                                echo '</span>';
                            }
                        }
                    
                    }
                    
                    echo '<br><br><br>';
                    // New tab link to delete shift page
                    echo '<a href="deleteshifts.php" target="_blank"><input type="submit" value="See and Delete Existing Shifts"></a>';

                }

                // Shift has been selected / created, now we assign events to a shift
                else {

                    $shift = $_SESSION['shift_id'];

                    // Get staff ID
                    $staffID = "SELECT staff_id FROM staff_shifts WHERE shift_id = '$shift'";
                    $staffID = $db->query($staffID);
                    $staffID = mysqli_fetch_assoc($staffID)['staff_id'];


                    // Relay all events stored in selected shift - $shift
                    $shiftsAssigned = "SELECT * FROM event_hours INNER JOIN events ON event_hours.event_id = events.event_id WHERE shift_id = '$shift'";
                    $shiftsAssigned = $db->query($shiftsAssigned);
                    $shiftrows = mysqli_num_rows($shiftsAssigned);

                    if ($shiftrows > 0) {
                        echo '<span style="color:#330000"><h6>Current Shifts Assigned</h6></span>';
                        echo '<span style="color:#3399FF">';
                        for ($i = 0; $i < $shiftrows; $i++) {
                            $rowShift = mysqli_fetch_assoc($shiftsAssigned);
                            $count = $i + 1;
                            echo $count.". ".$rowShift['event_name']." for ".$rowShift['dropoff_pickup']."<br>";
                        }
                        echo '</span>';
                    }

                    echo '<br><br>';

                    // Assign Shifts
                    echo '<h4>Assign Events to Shifts Below</h4>';

                    // Error messages displays if set
                    if (isset($_SESSION['error'])){
                        echo '<span style="color:red" class="error">'.$_SESSION['error'].'</span>';
                        unset($_SESSION['error']);
                    }

                    // Form for selecting event
                    echo '<form action="assigndriver.php" method="POST">';

                        echo 'Select Event : ';
                        echo "<select name='event' id='event'>";
                            for ($i = 0; $i < $ntotalrow; $i++) {
                                echo '<option value='.$eventIDList[$i].'>'.$eventNameList[$i].' for '.$eventReqList[$i].'</option>';
                            }
                        echo "</select>";
                        if (isset($_SESSION['error'])) {
                            echo "<span style='color:red' class='error'>Field has reset</span><br>";
                        }
                        echo '<br>';
                        // Value for event id is stored in select:option but that does not account for drop off / pick up
                        echo 'Select Drop-Off / Pick-Up as Chosen above: ';
                        echo "<select name='method' id='method'>";
                            echo '<option value="Pick-Up">Pick Up</option>';
                            echo '<option value="Drop-Off">Drop Off</option>';
                        echo "</select>";
                        if (isset($_SESSION['error'])) {
                            echo "<span style='color:red' class='error'>Field has reset</span><br>";
                        }

                        echo "<br><br><input type='submit' name='next1' value='Next'>";

                    echo '</form>';


                    // Store boolean to keep loop open when form is submitted
                    if (isset($_POST['next1'])) {

                        $_SESSION['next1'] = TRUE;

                    }


                    // If event is input, check if event is for collection or for delivery
                    // If for delivery input van registration for the shift
                    if ($_SESSION['next1'] == TRUE) {


                        $eventInput = $_POST['event'];

                        // Store in session variable as secondary form will remove any $_POST items from previous form
                        if (!(isset($_SESSION['event_idInput']))) {
                            $_SESSION['event_idInput'] = $eventInput;
                        } else {
                            $eventInput = $_SESSION['event_idInput'];
                        }

                        // Get whether event is for dropoff or pickup as input does not save this
                        /*for ($i = 0; $i < $ntotalrow; $i++) {
                            if ($eventIDList[$i] == $eventInput) {
                                $eventmethod = $eventReqList[$i];
                            }
                        }*/
                        $eventmethod = $_POST['method'];

                        // Similar (Line 511)
                        if (!(isset($_SESSION['eventmethod']))) {
                            $_SESSION['eventmethod'] = $eventmethod;
                        } else {
                            $eventmethod = $_SESSION['eventmethod'];
                        }

                        // Check if event is Delivery or Collection
                        $del_col = "SELECT delivery_status FROM events WHERE event_id = $eventInput";
                        $del_col = $db->query($del_col);
                        $del_col = mysqli_fetch_assoc($del_col)['delivery_status'];

                        // If for delivery, assign van
                        // Only assign van once so that the user does not have to reiterate the input
                        if (!(isset($_SESSION['van']))) {

                            // Once van is assigned store it as a session variable for the entire shift
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                                $van = $_POST['reg'];
                                $_SESSION['van'] = $van;                           

                            }

                            if ($del_col == 1) {
                                echo '<br><br>';
                                // Van selection form
                                echo '<form action="" method="POST">';

                                    $vans = "SELECT * FROM vans";
                                    $vans = $db->query($vans);
                                    $nrowVans = mysqli_num_rows($vans);

                                    echo '<span style="color:blue">Select Van for shift : </span>';
                                        echo "<select name='reg' id='reg'>";
                                        for ($i = 0; $i < $nrowVans; $i++) {
                                            $resVan = mysqli_fetch_assoc($vans);
                                            echo '<option value='.$resVan['registration_no'].'>'.$resVan['registration_no'].'</option>';
                                        }
                                    echo "</select>";

                                    echo "<br><br><input type='submit' name='next2' value='Next'>";
                                
                                echo '</form>';

                            }

                        } else {
                            // If van is already stored as a session variable, call it
                            $van = $_SESSION['van'];
                        }  

                        // call session variables from form 1 (event ID and method)
                        $eventmethod = $_SESSION['eventmethod'];
                        $eventInput = $_SESSION['event_idInput'];


                        // Input into SQL

                        // If for delivery, Van must be selected and stored
                        if ($del_col == 1 && $van != NULL) {
                            // Check for duplicates
                            $check = "SELECT * FROM event_hours WHERE event_id = '$eventInput' AND staff_id = '$staffID' AND dropoff_pickup = '$eventmethod'";
                            $check = $db->query($check);
                            if (mysqli_num_rows($check) == 0) {

                                // ensure eventmethod is valid (ie Ensure event actually occurs on this day)
                                if ($eventmethod == 'Pick-Up') {
                                    $check2 = "SELECT * FROM events WHERE end_date = '$date' AND event_id = '$eventInput'";
                                } else {
                                    $check2 = "SELECT * FROM events WHERE start_date = '$date' AND event_id = '$eventInput'";
                                }
                                $check2 = $db->query($check2);
                                if (mysqli_num_rows($check2) > 0) {

                                    $input = "INSERT INTO event_hours (event_id, staff_id, registration, dropoff_pickup, shift_id)
                                        VALUES ('$eventInput', '$staffID', '$van', '$eventmethod', '$shift')";
                                    $input = $db->query($input); 
                                    // set next1 to False to exit if statement
                                    $_SESSION['next1'] = FALSE;
                                    // unset stored session variables (keep 'van' as stores for entire shift, not just single event input)
                                    unset($_SESSION['eventmethod']);
                                    unset($_SESSION['event_idInput']);
                                    echo '<script language="javascript">	

                                    document.location.replace("assigndriver.php");
                                
                                    </script>';
                                } else {
                                    $_SESSION['error'] = "Invalid Input For Drop-Off / Pick-Up. Try Again";
                                    unset($_SESSION['eventmethod']);
                                    unset($_SESSION['event_idInput']);
                                }
                            // Error for duplicate
                            } else {
                                $_SESSION['error'] = "Duplicate Input";
                                unset($_SESSION['eventmethod']);
                                unset($_SESSION['event_idInput']); 
                            }
                        }

                        // If for collection
                        else if ($del_col == 0) {
                            // Check for duplication
                            $check = "SELECT * FROM event_hours WHERE event_id = '".$_SESSION['event_idInput']."' AND staff_id = '$staffID' AND dropoff_pickup = '$eventmethod'";
                            $check = $db->query($check);
                            if (mysqli_num_rows($check) == 0) {
                                $input = "INSERT INTO event_hours (event_id, staff_id, registration, dropoff_pickup, shift_id)
                                    VALUES ('$eventInput', '$staffID', 'Not Required', '$eventmethod', '$shift')";
                                $input = $db->query($input);
                                // unset stored session variables (keep 'van' as stores for entire shift, not just single event input)
                                $_SESSION['next1'] = FALSE;
                                unset($_SESSION['eventmethod']);
                                unset($_SESSION['event_idInput']);                               
                                echo '<script language="javascript">	
                                
                                    document.location.replace("assigndriver.php");
                            
                                </script>';
                            } else {
                                $_SESSION['error'] = "Duplicate Input";
                                unset($_SESSION['eventmethod']);
                                unset($_SESSION['event_idInput']); 
                            }                            
                        }

                        /*else {
                            $_SESSION['error'] = "Error Occured. Try Again";
                            $_SESSION['next1'] = FALSE;
                            echo '<script language="javascript">	

                            document.location.replace("assigndriver.php");
                        
                            </script>';
                        }*/

                        

                    }
                    
                    
                    
                    //Close input band
                    echo '<br><span style="color:red"><h4><b>Please Submit Shift Below when you are Finished</b></h4></span>';
                    echo '<a href="finishinputshifts.php"><input type="submit" value="Finish Shift Inputs"></a>';

                }

                echo '</div>';


                echo '<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:80%">';

                    
                    // Diplay events for the day
                    $_SESSION['date'] = $date;
                    echo '<h4>Events on <b>'.$date.'</b></h4>';


                    // Table split horizontally by Delivery / Collections
                    // Table split vertically by Goods Out / Goods In
                    echo '<a href="assigndriver_equipmentlist.php" target="_blank"><input type="submit" value="See Equipment Details"></a>';
                    echo '<br>';
                    echo '<p class="w3-wide w3-center"><i>Table</i> <br><b>Left side</b> - All <span style="color:blue">Out-Going</span> Items ||| All <span style="color:blue">In-Coming</span> Items - <b>Right Side</b></p>';
                echo '</div>';



                // Re-Initiate the results, Queries on Line 200
                $res1 = $db->query($q1);
                $res2 = $db->query($q2);
                $res3 = $db->query($q3);
                $res4 = $db->query($q4);
            

                echo '<table style="width:85%; margin-left:auto; margin-right:auto">
                    <tr>
                        <td colspan="5" style="background-color:#CCFFCC">Goods Out - for Drop-Off</td>
                        <td style="background-color:teal"></td>
                        <td colspan="5" style="background-color:#CCFFCC">Goods In - for Pick-Up</td>
                    <tr>
                        <th>Event Name</th>
                        <th>Location</th>
                        <th>County</th>
                        <th>Out-Going</th>
                        <th># Staff Assigned</th>
                        <td ></td>
                        <th>Event Name</th>
                        <th>Location</th>
                        <th>County</th>
                        <th>In-Coming</th>
                        <th># Staff Assigned</th>
                    </tr>';
                echo '<tr><td colspan="11"</tr>';


                if ($nrowdelivery > 0) {
                    
                    echo '<tr>
                        <th colspan="11">Deliveries</th>
                    </tr>';

                    
                    for ($i = 0; $i < $nrowdelivery; $i++) {

                        $row1 = mysqli_fetch_assoc($res1);
                        $row2 = mysqli_fetch_assoc($res2);

                        for ($j = 0; $j < $ntotalrow; $j++) {
                            if ($eventIDList[$j] == $row1['event_id'] && $eventReqList[$j] == 'Drop-Off') {
                                $driverAssignedDrop = $driversAssignedList[$j];
                            } 

                            if ($eventIDList[$j] == $row2['event_id'] && $eventReqList[$j] == 'Pick-Up') {
                                $driverAssignedPick = $driversAssignedList[$j];
                            }
                        }
                        

                        echo '<tr>
                            <td style="height:100px">'.$row1['event_name'].'</td>
                            <td style="height:100px">'.$row1['location'].'</td>
                            <td style="height:100px">'.$row1['county'].'</td>
                            <td style="height:100px">'.$row1['start_time'].'</td>
                            <td style="height:100px">'.$driverAssignedDrop.'</td>
                            <td style="height:100px; background-color:teal"> ||| </td>
                            <td style="height:100px">'.$row2['event_name'].'</td>
                            <td style="height:100px">'.$row2['location'].'</td>
                            <td style="height:100px">'.$row2['county'].'</td>
                            <td style="height:100px">'.$row2['end_time'].'</td>
                            <td style="height:100px">'.$driverAssignedPick.'</td>
                        </tr>';

                    }

                }

                echo '<tr><td colspan="11"</tr>';

                if ($nrowpickup > 0) {

                    echo '<tr>
                        <th colspan="11">Collections</th>
                    </tr>';

                
                    for ($i = 0; $i < $nrowpickup; $i++) {

                        $row3 = mysqli_fetch_assoc($res3);
                        $row4 = mysqli_fetch_assoc($res4);

                        for ($j = 0; $j < $ntotalrow; $j++) {
                            if ($eventIDList[$j] == $row3['event_id'] && $eventReqList[$j] == 'Drop-Off') {
                                $driverAssignedDrop = $driversAssignedList[$j];
                            } 

                            if ($eventIDList[$j] == $row4['event_id'] && $eventReqList[$j] == 'Pick-Up') {
                                $driverAssignedPick = $driversAssignedList[$j];
                            }
                        }

                        echo '<tr>
                            <td style="height:100px">'.$row3['event_name'].'</td>
                            <td style="height:100px">'.$row3['location'].'</td>
                            <td style="height:100px">'.$row3['county'].'</td>
                            <td style="height:100px">'.$row3['start_time'].'</td>
                            <td style="height:100px">'.$driverAssignedDrop.'</td>
                            <td style="height:100px; background-color:teal"> ||| </td>
                            <td style="height:100px">'.$row4['event_name'].'</td>
                            <td style="height:100px">'.$row4['location'].'</td>
                            <td style="height:100px">'.$row4['county'].'</td>
                            <td style="height:100px">'.$row4['end_time'].'</td>
                            <td style="height:100px">'.$driverAssignedPick.'</td>
                        </tr>';

                    }

                }

                echo '</table>';

            } else {
                echo 'No Events Booked for '.$date;
            }

        // Error message for invalid date input
        } else {
            echo "<br><span style='color:red'>".$dateErr.'<span>';
        }

    ?>

    <br><br><br><br>

</body>
</html>