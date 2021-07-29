<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            Broken Items Links: brokenitems2.php, editbrokenitems.php
        TODO:
            Recount all input of broken items allow for deletion and new inputs
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broken Equipment Input</title>

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
    <div class="topnav">
        <a href="staff.php">Home</a>
        <a href="myhours.php">Working History</a>
        <a class="active" href="brokeninput.php">Broken Returns</a>
        <a href="logout.php">Log Out</a> 
    </div>

    <p>< <a href="brokenbackpage.php">Create New Broken Returns Event</a></p>

    <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:80%">
        <h4 class="w3-wide w3-center">Lost Inventory</h4>
        <p class="w3-opacity w3-center"><i>The following equipment was damaged or not returned <br> It has been sent to Admin to be confirmed, you can make changes below
        <table class="styled-table" style="margin-left:auto; margin-right:auto;">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Quantity Broken / Not Returned</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Display broken item inputs for a given event
            session_start();
            include ("detail.php");
            $q = "SELECT equipment.product_name, equipment.category, broken_returns.quantity FROM broken_returns INNER JOIN equipment ON broken_returns.equipment_id = equipment.id WHERE broken_returns.event_id = ".$_SESSION['event_id'];
            $result = $db->query($q);

            $num_results = mysqli_num_rows ($result);
            for ($i=1; $i <$num_results+1; $i++)
            {
                $row = mysqli_fetch_assoc($result);
                echo "<tr>";
                    echo "<td>".$row['product_name']."</td>";
                    echo "<td>".$row['category']."</td>";
                    echo "<td>".$row['quantity']."</td>";
                echo "</tr>";
            }
            echo '</table>';
        ?> 
        </tbody>
        </table>



        <br><br><br><br>
        Not happy?<br><br>
        <a href = "brokenitems2.php" target = "_self">Add more broken items</a><br>
        <a href = "editbrokenitems.php" target = "_self">Edit your input </a>


    </div>
</body>
</html>