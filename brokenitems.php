<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 
        Source:
            Staff Nav Bar: (staff.php, myhours.php)
            pickuplist.php
        TO DO
            Primary Broken Items Page where user can choose one of their previous Pick-Up events
            Then they are sent to brokenitems2.php where they can input the items
    -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broken Items</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Import CDN link for Select 2 used for drop downs etc -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Select2 CSS --> 
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

    <!-- jQuery --> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

    <!-- Select2 JS --> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <style>
        .error {
            color: #FF0000;
        }
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
</head>
<body>

    <div class="topnav">
        <a href="staff.php">Home</a>
        <a href="myhours.php">Working History</a>
        <a class="active" href="brokenitems.php">Broken Returns</a>
        <a href="logout.php">Log Out</a> 
    </div>

    <h4>Log Broken Equipment</h4>
    <p>Please input any equipment that was not returned or was broken</p>
    <br>

    <?php

        session_start();
        include("detail.php");

        $staff_id = $_SESSION['staff_id'];


        $search_event = "";
        $eventErr = "";


        //function to test input
        // function to test and filter data
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            
            // make sure the event is not empty
            if(empty($_POST['event'])){

                $eventErr = "Event cannot be empty";

            }else{
                $search_event = test_input($_POST['event']); 
            }

            // insert data into the table
            // If Validation Successful, Run MySQL query
            if ($eventErr == "") {
                
                $_SESSION['event_id'] = $search_event;
                echo '<script language="javascript">	

                document.location.replace("brokenitems2.php");
        
                </script>'; 

            } 

        }  

    ?>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
    method="POST" name="inout" id="inout">
        <table>
                <?php
                    // Output in drop down menu only event that users attended for pickup
                    include ("detail.php"); 
			        $q1 = "SELECT events.event_name, events.event_id FROM events INNER JOIN event_hours ON events.event_id = event_hours.event_id 
                    WHERE event_hours.staff_id = '$staff_id' AND events.end_date <= CURDATE() AND event_hours.dropoff_pickup = 'Pick-Up'";

			        $result1 = $db->query($q1);
                    $num_results = mysqli_num_rows($result1);
                
                    if ($num_results == 0) {
                        echo "<p><b>No completed Pickups YET</b></p>";
                    }

                    else {
                        echo '<tr>
                        <td>Select Event: </td>
                        <td>
                        <select class="w3-input w3-border" name="event" id="event" style="width: 400px">';

                        for($i=0; $i < $num_results; $i++)
                        {
                            $row = mysqli_fetch_assoc($result1);
                            // Ensure event has not already been verified
                            $qt = "SELECT verified FROM broken_returns WHERE event_id = '".$row['event_id']."'";                            
                            $qt = $db->query($qt);
                            $is_Verified = mysqli_fetch_assoc($qt)['verified'];
                            if ($is_Verified != 1) {
                                echo '<option value="'.$row['event_id'].'">'.$row['event_name'].'</option>'; 	
                            }				
                        }
                        mysqli_close ($db);

                        echo '</select>
                                <input type="button" value="Selected Option" id="but_read">
                                <div id="result"></div>
                                    <span class = "error">* <?php echo $eventErr;?></span>
                                </td>
                            </tr>
                        </table>
                        <p><input type="submit" value="Submit"> or <input type="reset" value="Reset"></p>';

                    }
                ?>		
                    </form>


    <script>

        $(document).ready(function(){
        
        // Initialize select2
        $("#event").select2();

        // Read selected option
        $('#but_read').click(function(){
        var event_name = $('#event option:selected').text();
        var event_id = $('#event').val();

        $('#result').html("id : " + event_id + ", name : " + event_name);

        });
        });

        </script>

        <script>

        $(document).ready(function(){
        
        // Initialize select2
        $("#employee").select2();

        // Read selected option
        $('#empl_read').click(function(){
        var empl_name = $('#employee option:selected').text();
        var empl_id = $('#employee').val();

        $('#result1').html("id : " + empl_id + ", name : " + empl_name);

        });
        });

    </script>


</body>        
</html>