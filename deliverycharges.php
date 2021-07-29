<!-- 
deliverycharges.php
Purpose: allow customer to view delivery charges by count
accessed from: index.php, newevent.php, allequipment.php,equipmentcategory.php
-->




<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPH Equipment</title>
    <!-- Style code from https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_templates_band&stacked=h -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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


<!--php script to show all equipment and equipment data -->
<div class="w3-container w3-content w3-center w3-padding-20" style="max-width:600px" id="all_equipment">
    <h2 class="w3-wide w3-center">Delivery Charges</h2>
    <p class="w3-opacity w3-center"><i>The table includes all prices in euro and excluding VAT</i></p>

    <table class="styled-table">
    <thead>
        <tr>
            <th>County</th>
            <th>Delivery Charge</th>
            
        </tr>
    </thead>
    <tbody>
    <?php
	include("detail.php");
		$q = "SELECT county, (flat_rate+(dist_from_dub_km*rate_per_km)) as 'charge' FROM `delivery_charges` WHERE chargetype = 'delivery';";
		$result = $db->query($q);
		$num_members = mysqli_num_rows($result);
        //loop to print out data in a table
		for($i=0; $i < $num_members; $i++)
		{
			$row = mysqli_fetch_assoc($result);
			echo "<tr>";
                           echo "<td>".$row[county]."</td>";
                           echo "<td>€".$row[charge]."</td>";
		                   echo "</tr>";
		}
		mysqli_close($db);
	?>
</table>
<br>
    </tbody>
</table>


</body>
</html>