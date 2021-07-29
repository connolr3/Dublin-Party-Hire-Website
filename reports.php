<!-- This is the Reports Page

The manager/admin at DPH will be redirected here from:
    admin.php if they select the reports button

    This page redirects to:

    rentalfreq.php
    productsales.php
    bestcust.php
    admin.php
    logout.php and index.php



-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPH Reports</title>

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
        <a href="rentalfreq.php">Rental Frequency</a>
        <a href="productsales.php">Individual Product Revenue</a>
        <a href="bestcust.php">Best Customers</a>
        <a href="logout.php">Log Out</a> 
    </div>


<!-- Table containing all the links and descriptions of each link -->
<div class="w3-container w3-content w3-padding-11" style="max-width:1000px" id="employee_act">
   <h3 class="w3-wide w3-center">Reports :</h3>
   <h5 class="w3-wide w3-center">Select the Relevant Link to View the Desired Report:</h5>

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
        <td><button class="w3-button w3-black" onclick="window.location.href='rentalfreq.php'">Rental Frequency</button></td>
        <td>View how many times a specific product was ordered</td>
    </tr>
    <tr>
        <td><button class="w3-button w3-black" onclick="window.location.href='productsales.php'">Revenue by Product</button></td>
        <td>View the revenue brought in by the purchase of each individual product</td>
    </tr>
    <tr>
        <td> <button class="w3-button w3-black" onclick="window.location.href='totalproductsales.php'">Total Sales</button></td>
        <td>View total product sales and units sold</td>
    </tr>
    <tr>
        <td> <button class="w3-button w3-black" onclick="window.location.href='bestcust.php'">Best Customers</button></td>
        <td>View DPH's best customers based on how much they spent</td>
    </tr>
</table>
</div>
   <br>
    <br>
    <br>
    <br>
</div>


</body>
</html>