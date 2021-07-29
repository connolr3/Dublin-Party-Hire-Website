<!DOCTYPE html>
<html lang="en">
<head>
  <!--
    Source:
        stafflogin.php -> nav bar (myhours.php, brokenitems.php)
    TODO:
        List off all upcoming shifts for logged in employee (if not currently clocked into a shift)
        If clocked into shift, list off all events to be serviced during the shift
        If user forgets to clock out on the day of the shift, clock out is automatically set to 23:59:59
        If user forgets to clock in shift is removed from the page and sent to aidan to be corrected
  -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Page</title>
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
        <a class="active" href="staff.php">Home</a>
        <a href="myhours.php">Working History</a>
        <a href="brokenitems.php">Broken Returns</a>
        <a href="logout.php">Log Out</a> 
    </div>

    <?php

        // Display user name
        $arg = "SELECT * FROM staff WHERE employee_id = '$id'";
        $arg = $db->query($arg);
        $argrow = mysqli_num_rows($arg);

        // User Timed out
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

    <h2>Shift Schedule</h2>
 

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
    method="POST" name="inout" id="inout">
      <table>
        <?php

          // Check if user is clocked into a shift
          $on_shift = "SELECT on_shift FROM staff WHERE employee_id = '$id'";
          $on_shift = $db->query($on_shift);
          $on_shift = mysqli_fetch_assoc($on_shift)['on_shift'];

          // if not clocked currently clocked into shift, display all upcoming shifts
          if ($on_shift == 0) {

            $inclocked = "SELECT * FROM staff_shifts 
            WHERE staff_id = '$id' AND shift_date >= CURDATE() AND clock_in_time IS NULL 
            ORDER BY shift_date, set_clock_in";
            $inclocked = $db->query($inclocked);
            $inNumrow = mysqli_num_rows($inclocked);

            echo '<tr>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Clock in</th>
            </tr>';

            for ($i = 0; $i < $inNumrow; $i++) {
              $inRow = mysqli_fetch_assoc($inclocked);
              echo '<tr>
              <td>'.$inRow["shift_date"].'</td>
              <td>'.$inRow["set_clock_in"].'</td>
              <td>'.$inRow["set_clock_out"].'</td>';
              // Only permit clocking in if shift is today
              if ($inRow['shift_date'] == date("Y-m-d")) {
                // Store key details as session variables
                $_SESSION['shift_id'] = $inRow["shift_id"];
                $_SESSION['shift_date'] = $inRow['shift_date'];
                echo '<td><input type = "submit" name = "in" value = "Clock In"</td>';
              }
              echo '</tr>';
            }

            echo ' </table>';

          }

          // if already clocked in, run through deliveries for the shift
          else {

            // if user has timed out between clocking in and clocking out, we must rewrite session variables
            if (isset($_SESSION['shift_id']) && isset($_SESSION['shift_date'])) {
              $shift_id = $_SESSION['shift_id'];
              $shift_date = $_SESSION['shift_date'];
            } else {
              $getShift = "SELECT * FROM staff_shifts WHERE clock_out_time IS NULL AND clock_in_time IS NOT NULL AND staff_id = '$id'";
              $getShift = $db->query($getShift);
              $row = mysqli_fetch_assoc($getShift);
              $_SESSION['shift_id'] = $row['shift_id'];
              $shift_id = $_SESSION['shift_id'];
              $_SESSION['shift_date'] = $row['shift_date'];
              $shift_date = $_SESSION['shift_date'];
            }
            
            // if fotgotten to clock out and the day-of-shift has expired, auto set clock out to day-end
            if ($shift_date < date("Y-m-d")) {
              $close = "UPDATE staff_shifts SET clock_out_time = 235959 WHERE shift_id = '$shift_id'";
              $close = $db->query($close);
              $close2 = "UPDATE staff SET on_shift = FALSE WHERE employee_id = '$id'";
              $close2 = $db->query($close2);
              // Unset session variables and refresh the page
              unset($_SESSION['shift_id']);
              unset($_SESSION['shift_date']);
              echo '<script language="javascript">	

              document.location.replace("staff.php");
      
              </script>';
            }


            // Get all Deliveries
            $event_deliveries = "SELECT shift_id, registration, dropoff_pickup, event_name, location, start_time, end_time, vans_loading, van_enroute
            FROM event_hours INNER JOIN events ON event_hours.event_id = events.event_id 
            WHERE shift_id = '$shift_id' AND delivery_status = 1
            ORDER BY dropoff_pickup";
            $event_deliveriesres = $db->query($event_deliveries);
            $row_numdel = mysqli_num_rows($event_deliveriesres);

            // Get all Collections
            $event_collections = "SELECT event_id, shift_id, registration, dropoff_pickup, event_name, location, start_time, end_time, vans_loading, van_enroute
            FROM event_hours INNER JOIN events ON event_hours.event_id = events.event_id 
            WHERE shift_id = '$shift_id' AND delivery_status = 0
            ORDER BY dropoff_pickup";
            $event_collectionsres = $db->query($event_collections);
            $row_numcol = mysqli_num_rows($event_collectionsres);

            // No events for shift (assume duties are adminsitrative or warehouse based)
            if ($row_numdel + $row_numcol == 0){
              echo "No Deliveries Today! You're on Warehouse Duty :)";    
            }

            else {

              if ($row_numdel > 0) {
                
                // Display goods for delivery
                echo '<h4>Goods For Delivery</h4>';
                echo '<table>';
                echo '<tr>
                <th>Event Name</th>
                <th>Location</th>
                <th>Van Assigned</th>
                <th>Drop Off / Pick Up</th>
                <th>Required Arrival Time</th>
                <th>Track Completion</th>
                </tr>';

                for($i=0; $i < $row_numdel; $i++){

                  $row_results = mysqli_fetch_assoc($event_deliveriesres);
                  
                  echo "<tr>";
                  echo "<td>".$row_results['event_name']."</td>";
                  echo "<td>".$row_results['location']."</td>";
                  echo "<td>".$row_results['registration']."</td>";
                  echo "<td>".$row_results['dropoff_pickup']."</td>";
                  if ($row_results['dropoff_pickup'] == "Drop-Off"){
                    echo "<td>".$row_results['start_time']."</td>";
                  } else {
                    echo "<td>".$row_results['end_time']."</td>";
                  }
                  // Register order tracking of delivery for Customer
                  // Only necessary to display 1 input for all items
                  if ($i == 0) {
                    // 'Loading in Progress' only required if goods are being dropped off
                    if ($row_results['dropoff_pickup'] == "Drop-Off"){
                      // Display next phase in tracking delivery and if good are in-transit, display no further input options
                      if ($row_results['vans_loading'] == NULL){
                        echo "<td><input type='submit' name='loading' value='Begin Loading'</td>";
                      } else if ($row_results['van_enroute'] == NULL){
                        echo "<td><b>Loading Complete</b><br><input type='submit' name='delivery' value='Vans Enroute'</td>";
                      } else {
                        echo "<td><b>Goods In-Transit</b></td>";
                      }
                    } else {
                      if ($row_results['van_enroute'] == NULL){
                        echo "<td><input type='submit' name='delivery' value='Vans Enroute'</td>";
                      } else {
                        echo "<td><b>Goods In-Transit</b></td>";
                      }
                    }
                  }

                }

                echo '</table>';

              }
              echo '<br>';


              if ($row_numcol > 0) {

                echo '<h4>Goods For Collection</h4>';
                echo '<table>';
                echo '<tr>
                  <th>Event Name</th>
                  <th>Drop Off / Pick Up</th>
                  <th>Expected Arrival Time</th>
                </tr>';

                for($i=0; $i < $row_numcol; $i++){

                  $row_results = mysqli_fetch_assoc($event_collectionsres);

                  // Store IDs in array for SQL input
                  $event_collectionsIDs[$i] = $row_results['event_id'];

                  echo "<tr>";
                  echo "<td>".$row_results['event_name']."</td>";
                  // Drop-off for DPH is a pick-up for the customers -> goods outward
                  if ($row_results['dropoff_pickup'] == 'Drop-Off') {
                    echo '<td>Customer Pick-Up</td>';
                  } else {
                    echo '<td>Customer Drop-Off</td>';
                  }
                  // Display relevant time
                  if ($row_results['dropoff_pickup'] == "Drop-Off"){
                    echo "<td>".$row_results['start_time']."</td>";
                  } else {
                    echo "<td>".$row_results['end_time']."</td>";
                  }

                }

                echo '</table>';

              }

            }

            echo '<br><br>';

            if ($row_numcol + $row_numdel > 0) {
              // Drop Off and Pick Up breakdown
              echo '<b>Equipment Requirements</b><br><br>';
              echo '<a href="dropofflist.php" target="_blank">Drop Off Requirements</a>';
              echo ' || ';
              echo '<a href="pickuplist.php" target="_blank">Pick Up List</a>';
            }
            
            // option to end shift
            echo '<br><br><br>';
            echo '<h4>ALL DONE?</h4>';
            echo '<h5>Clock Out Below</h5><br>';
            echo "<td><input type='submit' name='out' value='Clock Out'</td>";

          }
        
      ?> 

    </form>

    <?php

      // for employee clocking in
      if (isset($_POST['in'])) {

        $shift_id = $_SESSION['shift_id'];

        $qin = "UPDATE staff_shifts SET clock_in_time = CURRENT_TIMESTAMP
        WHERE shift_id = '$shift_id'";
        $qin = $db->query($qin);


        $qin2 = "UPDATE staff SET on_shift = TRUE WHERE employee_id = '$id'";
        $qin2 = $db->query($qin2);
        echo '<script language="javascript">	

        document.location.replace("staff.php");

        </script>';

      }

      // for employee clocking out
      else if (isset($_POST['out'])) {

        $shift_id = $_SESSION['shift_id'];

        $qin = "UPDATE staff_shifts SET clock_out_time = CURRENT_TIMESTAMP
        WHERE shift_id = '$shift_id'";
        $qin = $db->query($qin);


        $qin2 = "UPDATE staff SET on_shift = FALSE WHERE employee_id = '$id'";
        $qin2 = $db->query($qin2);
        echo '<script language="javascript">	

        document.location.replace("staff.php");

        </script>';

      }

      // for registering the loading of goods
      // communally input for all Drop-Off events for a given shift
      else if (isset($_POST['loading'])){

        $sql1 = "UPDATE event_hours SET vans_loading = CURRENT_TIMESTAMP
        WHERE shift_id = '$shift_id' AND dropoff_pickup = 'Drop-Off'";
        

        // Reset to null if goods are for collection from warehouse
        // Some shifts may have collection and delivery items
        for($i=0; $i < $row_numcol; $i++) {

          $sqlcol = "UPDATE event_hours SET vans_loading = NULL
          WHERE shift_id = '$shift_id' AND event_id = '".$event_collectionsIDs[$i]."'";
          $input = $db->query($sqlcol);

        }

        $sqlquery1 = $db->query($sql1);
        $sqlquery2 = $db->query($sql2);
        echo '<script language="javascript">	

        document.location.replace("staff.php");

        </script>';
      } 

      // for registering the departing from the warehoure to the event
      // communally input for all events for a given shift
      else if (isset($_POST['delivery'])) {
        $sql = "UPDATE event_hours SET van_enroute = CURRENT_TIMESTAMP
        WHERE shift_id = '$shift_id'";
        $sqlquery = $db->query($sql);

        // Reset to null if goods are for collection from warehouse
        // Some shifts may have collection and delivery items
        for($i=0; $i < $row_numcol; $i++) {

          $sqlcol = "UPDATE event_hours SET van_enroute = NULL
          WHERE shift_id = '$shift_id' AND event_id = '".$event_collectionsIDs[$i]."'";
          $input = $db->query($sqlcol);

        }

        echo '<script language="javascript">	

        document.location.replace("staff.php");

        </script>';
      }


    ?>



</body>
</html>