<!-- This is the Operations and Equipment Actions Page

The manager/admin at DPH will be redirected here from:
    admin.php if they select the operations and equipment button

    This page redirects to:

    newequip.php
    deleteequip.php
    newquant.php
    changeprice.php
    changesetup.php
    changecharges.php 
    admin.php
    logout.php and index.php



-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPH Equipment Action</title>

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
        <a href="equipment.php">View Equipment</a>
        <a href="#vans.php">View Vans</a>
        <a href="logout.php">Log Out</a> 
    </div>


<!-- Table containing all the links and descriptions of each link -->
<div class="w3-container w3-content w3-padding-11" style="max-width:1000px" id="employee_act">
   <h3 class="w3-wide w3-center">Operations and Equipment :</h3>
   <h5 class="w3-wide w3-center">Select the Relevant Link to Carry out the Desired Action:</h5>

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
    <td><button class="w3-button w3-black" onclick="window.open('equipment.php')">View Equipment</button></td>
        <td>Click here to view the equipment catalogue</td>
    </tr>
    <tr>
    <td><button class="w3-button w3-black" onclick="window.location.href='new_equip.php'">New Equipment</button></td>
        <td>Use this form to add new equipment to the catalogue</td>
    </tr>
    <tr>
    <td><button class="w3-button w3-black" onclick="window.location.href='new_quant.php'">Update Equipment Quantity</button></td>
        <td>Use this form to update the quantity on hand of an item</td>
    </tr>
    <tr>
    <td><button class="w3-button w3-black" onclick="window.location.href='deleteequip.php'">Delete Equipment</button></td>
        <td>Use this form to delete a specific piece of equipment </td>
    </tr>
    <tr>
    <td><button class="w3-button w3-black" onclick="window.location.href='change_price.php'">Update Rental Price</button></td>
        <td>Use this form to change the rental price of a product</td>
    </tr>
    <tr>
    <td><button class="w3-button w3-black" onclick="window.location.href='change_setup.php'">Update Setup Price</button></td>
        <td>Use this form to change the setup price of a product</td>
    </tr>
    <tr>
    <td><button class="w3-button w3-black" onclick="window.location.href='update_charges.php'">Update Charges</button></td>
        <td>Use this form to update either the VAT, flat delivery charge and delivery charge/km rate </td>
    </tr>
    <tr>
    <td><button class="w3-button w3-black" onclick="window.location.href='#vandetails'">View Vans</button></td>
        <td>Click here to view the Van's owned by DPH</td>
    </tr>


</table>
</div>
   <br>
    <br>
    <br>
    <br>
</div>
<br>
<br>

<!-- Table containing details of employees -->
<div class="w3-container w3-content w3-padding-11" style="max-width:400px" id="vandetails">
   <h1 class="w3-wide w3-center">Van Details</h1>
   <table class="styled-table">
    <thead>
        <tr>
            <th>Registration Number</th>
            <th>Capacity </th>  
        </tr>
    </thead>
    <tbody>

    <!-- php code to get the necessary information for the table -->
    <?php
	include("detail.php");
		$q1 = "SELECT * FROM vans";
		$result1 = $db->query($q1);
		
		$num_vans = mysqli_num_rows($result1);
        
        //loop to print out data in a table
		for($i=0; $i < $num_vans; $i++)
		{
			$row = mysqli_fetch_assoc($result1);
			echo "<tr>";
                           echo "<td>".$row[registration_no]."</td>";
                           echo "<td>".$row[capacity]."</td>";
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
 


</body>
</html>
