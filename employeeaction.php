<!-- This is the Employee Action homepage

The manager/admin at DPH will be redirected here from:
    admin.php if they select the employee actions button

    This page redirects to:

    itself: the employeedetails section
    newstaffmember.php
    delete_empl.php
    assigndriver.php
    staffhours.php
    admin.php
    logout.php and index.php



-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Actions</title>

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
    <?php
        session_start();
        include("detail.php");
        $id = $_SESSION['staff_id'];
    ?>

<!-- Navigation Bar with Links to other pages -->
    <div class="topnav">
        <a class="active" href="admin.php">Admin Home</a>
        <a href="staffhours.php">Staff Hours</a>
        <a href="newstaffmember.php">Add New Employee</a>
        <a href="#employeedetails">Employee Details</a>
        <a href="assigndriver.php">Assign Driver</a>
        <a href="logout.php">Log Out</a> 
    </div>


<!-- Table containing all the links and descriptions of each link -->
<div class="w3-container w3-content w3-padding-11" style="max-width:1000px" id="employee_act">
   <h3 class="w3-wide w3-center">Employee Actions :</h3>
   <h5 class="w3-wide w3-center">Select the Relevant Link to Complete the Desired Tasks Related to Employees and Scheduling:</h5>

</div>
<div class="w3-container w3-content w3-padding-11" style="max-width:500px">
   <table class="styled-table">
   <thead>
        <tr>
            <th>Link</th>
            <th>Details </th>
        </tr>
    </thead>

    <tr>
        <td><button class="w3-button w3-black" onclick="window.location.href='#employeedetails'">View Employee Details</button></td>
        <td>View employee id, name, position and phone number</td>
    </tr>
    <tr>
        <td><button class="w3-button w3-black" onclick="window.location.href='newstaffmember.php'">Add New Employee</button></td>
        <td>Form to add new employees</td>
    </tr>
    <tr>
        <td><button class="w3-button w3-black" onclick="window.location.href='change_employee.php'">Change Employee Details</button></td>
        <td>Change an employee's details</td>
    </tr>
    <tr>
        <td><button class="w3-button w3-black" onclick="window.location.href='authentication.php'">Change Passwords</button></td>
        <td>Click here to change passwords for all DPH employees</td>
    </tr>
    <tr>
        <td> <button class="w3-button w3-black" onclick="window.location.href='delete_empl.php'">Remove Employee</button></td>
        <td>Form to delete an employee</td>
    </tr>
    <tr>
        <td><button class="w3-button w3-black" onclick="window.location.href='assigndriver.php'">Assign Driver</button></td>
        <td>Form to assign drivers to shifts</td>
    </tr>
    <tr>
        <td><button class="w3-button w3-black" onclick="window.location.href='staffhours.php'">View Staff Hours and Activities</button></td>
        <td>View employees working hours, their incomplete shifts and verify broken items</td>
    </tr>
</table>
</div>
   <br>
    <br>
    <br>
    <br>
</div>

<!-- Table containing details of employees -->
  <div class="w3-container w3-content w3-padding-11" style="max-width:500px" id="employeedetails">
   <h1 class="w3-wide w3-center">Employee Details</h1>
   <table class="styled-table">
    <thead>
        <tr>
            <th>Employee ID</th>
            <th>Full Name </th>
            <th>Phone Number</th>
            <th>Position</th>   
        </tr>
    </thead>
    <tbody>

    <!-- php code to get the necessary information for the table -->
    <?php
	include("detail.php");
		$q1 = "SELECT * FROM staff";
		$result1 = $db->query($q1);
		
		$num_employees = mysqli_num_rows($result1);
        
        //loop to print out data in a table
		for($i=0; $i < $num_employees; $i++)
		{
			$row = mysqli_fetch_assoc($result1);
			echo "<tr>";
                           echo "<td>".$row[employee_id]."</td>";
                           echo "<td>".$row[full_name]."</td>";
		                   echo "<td>".$row[phone_number]."</td>";
		                   echo "<td>".$row[position]."</td>";
		                   echo "</tr>";
		}
		mysqli_close($db); 
	?>
</table>
<br>
    </tbody>
</table>

</div>

<div class="w3-container w3-content w3-padding-11" style="max-width:800px">
 