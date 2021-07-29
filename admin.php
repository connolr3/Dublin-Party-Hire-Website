<!-- This is the admin homepage

The manager/admin at DPH will be redirected here from:

    stafflogin.php if they are an admin
    
    They can also be redirected here from the navigation bar atop:
    employeeaction.php -> contains forms etc for various employee actions
    equipaction.php -> various forms for changing equipment quantity, price etc
    reports.php -> queries that generate forms

    This page sends the user to the above pages and also to logout.php and index.php by extension

--> 

<!-- Php Session to return the admin's name -->
<?php 
session_start();
include("detail.php");

//get the admin's name to display at the top of page using session variables
$id = $_SESSION['staff_id'];
$q = "SELECT full_name from staff WHERE employee_id=$id" ;
$result = $db->query($q);
$row = mysqli_fetch_assoc($result);

$admin_name = $row['full_name'];
$_SESSION['full_name'] = $admin_name;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPH Admin</title>

    <!-- Style code from https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_templates_band&stacked=h -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

<!-- styled table code from w3schools-->
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


<!-- Navigation Bar with Links to other pages -->
    <div class="topnav">
        <a class="active" href="admin.php">Admin Home</a>
        <a href="staffhours.php">Staff Hours</a> 
        <a href="employeeaction.php">Employee Actions</a>
        <a href="equipaction.php">Operations & Equip Actions</a>
        <a href="reports.php">Reports</a>
        <a href="logout.php">Log Out</a> 
    </div>

<h2> <?php echo "Welcome ".$admin_name." to the admin home page";?> </h2>
</div>
<?php
    // Display incomplete shifts urgency message if exists
    $q = "SELECT * FROM staff_shifts WHERE shift_date < CURDATE() AND (clock_in_time IS NULL OR clock_out_time IS NULL)";
    $q = $db->query($q);
    $nrow = mysqli_num_rows($q);

    if ($nrow > 0) {
        echo '<span style="color:red">Incomplete Shifts Exist<br>Number of Incompleted Shifts: '.$nrow.'<br>';
        echo '<a href="incompleteshifts.php">Incomplete Shifts</a>';
        echo '</span>';
    }
?>
<br>
<br>
<!-- Redirct to employee action Section -->
<div class="w3-container w3-content w3-padding-11" style="max-width:1000px" id="employee_act">
   <h3 class="w3-wide w3-center">Employee Actions :</h3>
   <h5 class="w3-center">Select this Button to See the Tasks Related to Employees and Scheduling:</h5>
   <div class="w3-container w3-content w3-padding-11" style="max-width:250px">
   <td><button class="w3-button w3-black w3-center" onclick="window.location.href='employeeaction.php'">Go to Employee Actions</button></td>
   <br>
   </div>
</div>
<br>
<br>
<br>
<br>

<!-- Operations Actions Section -->
<div class="w3-container w3-content w3-padding-11" style="max-width:1000px" id="equip_act">
   <h3 class="w3-wide w3-center">Operations Actions</h3>
   <h5 class="w3-center">Select this Button to See the Tasks Related to Operations and Equipment:</h5>
   <div class="w3-container w3-content w3-padding-11" style="max-width:300px">
   <td><button class="w3-button w3-black w3-center" onclick="window.location.href='equipaction.php'">Go to Operations & Equip Actions</button></td>
   <br>
   <br>
   </div>


</div>
<br>
<br>

<!-- Reports Sectiom-->
<div class="w3-container w3-content w3-padding-11" style="max-width:800px" id="reports">
<h3 class="w3-wide w3-center">Reports</h3>
   <h5 class="w3-center">Select this Button to See Reports:</h5>
   <div class="w3-container w3-content w3-padding-11" style="max-width:150px">
   <td><button class="w3-button w3-black w3-center" onclick="window.location.href='reports.php'">Reports</button></td>
  </div>
  </div>
  <br>
  <br>
  <br>

</body>
</html>