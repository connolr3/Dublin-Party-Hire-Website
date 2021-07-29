  <!--
  equipmentcategory.php
  purpose: allows user to specify how to want to view catalog by a specific quantity
  accessed from: equipment.php
  -->


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

<!-- Navigation Bar -->
<?php
  // Only display for customer side
  if (isset($_SESSION['user_email'])) {
    echo '<div class="topnav">
      <a href="customeraccount.php">Customer Home</a>
      <a href="newevent.php">Register Event</a>
      <a href="pastinvoices.php">View Past Orders</a>
      <a href="logout.php">Log Out</a>
    </div>';
  } else {
    echo '<a href="equipment.php">Back</a>';
  }

?>

<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:400px" id="committee_members">
    <h2 class="w3-wide w3-center">Equipment by Category</h2>
    <p class="w3-opacity w3-center"><i>Below are the details of equipment in the chosen category</i></p>
    Please see our
        <a href="deliverycharges.php" target="_blank">delivery charges</a><br>
  <!-- Equipment By Category in Table View -->
    <table class="styled-table">
    <thead>
        <tr>
            <th>Details</th>
            <th>Price (excl VAT)</th>
            <th>Setup Price (excl VAT)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

    <?php

    //declare variables:
    include("detail.php");
    $category = $_POST['category'];

    //Gets data for specified category
	  $q = "SELECT * FROM equipment WHERE category = '$category'"; 
		$result = $db->query($q);
	
    $num_results = mysqli_num_rows($result);

    //loop through results and display data in rows
		for($i=0; $i < $num_results; $i++)
		{
			$row = mysqli_fetch_assoc($result);
			echo "<tr>";
		                   echo "<td>".$row[product_name]."</td>";
		                   echo "<td>".$row[rental_price_excl_vat]."</td>";
		                   echo "<td>".$row[setup_price_excl_vat]."</td>";
                       if($row[sale]>0){
                        echo "<td>".($row[sale]*100)."% off!</td>";
                        }
                        else{
                         echo "<td></td>"; 
                         }

		    echo "</tr>";
		}
		mysqli_close($db);

    ?>
</table>

</tbody>
</table>



</body>
</html>




































