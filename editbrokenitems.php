<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        Source:
            Staff side: (brokeninput.php, brokenitems2.php)
            Admin side: (verifybrokenitems.php, brokenitems2.php)
        TO DO
            Option only to fully delete broken item inputs inputs
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Equipment</title>

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
<?php
    include ("detail.php"); 
    session_start();

    // Admin / Driver Nav Bar
    $qry = "SELECT * FROM staff WHERE employee_id = '".$_SESSION['staff_id']."'";
    $res = $db->query($qry);
    $rows = mysqli_fetch_assoc($res);
    if ($rows['position'] == 'driver') {
        echo '<div class="topnav">
            <a href="staff.php">Home</a>
            <a href="myhours.php">Working History</a>
            <a href="brokeninput.php">Broken Returns</a>
            <a href="logout.php">Log Out</a> 
        </div>';
    } else {
        echo '<div class="topnav">
            <a href="staffhours.php">Staff Hours</a>
            <a href="verifybrokenitems.php">Verify Broken Items</a>
            <a class="active" href="editbrokenitems.php">Delete Items</a>
            <a href="brokenitems2.php">Add Returns</a>
            <a href="logout.php">Log Out</a>
        </div>';
    }
?>

<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<h4 class="w3-wide w3-center">Edit Inputs</h4>
    <p class="w3-opacity w3-center"><i>Remove items below</i></p> 
    <p class="w3-opacity w3-center"><i>Note: <b>all quantities</b> of item will be removed</i></p> <br>
    <select name='product',id='product'>
    <?PHP
        // form for user to sepcify the broken / unreturned item input they want to remove
        $q = "SELECT broken_returns.equipment_id, equipment.product_name, equipment.category, broken_returns.quantity FROM broken_returns INNER JOIN equipment ON broken_returns.equipment_id = equipment.id WHERE broken_returns.event_id = ".$_SESSION['event_id'];
        $result = $db->query($q);
        $num_results = mysqli_num_rows ($result);
        for ($i=1; $i <$num_results+1; $i++)
        {
            $row = mysqli_fetch_assoc($result);
            echo "<option value='".$row['equipment_id']."'> ".$row['product_name']." x ".$row['quantity']." </option>"; 
        }
        echo "</select>";
    ?>
    <input type = "submit"name="submit-category" value = "Remove Item"><br> 
    <br><br><br><br><br>


    <?PHP

    if (isset($_SESSION['success'])) {
        echo '<span style="color:red">'.$_SESSION['success'].'</span>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if(isset($_POST['submit-category'])){
            include ("detail.php");
            session_start();

            // Delete row
            $product = $_POST["product"];
            $deletequery = "DELETE FROM broken_returns WHERE equipment_id = ".$product." AND event_id = ".$_SESSION['event_id'];
            $result = $db->query($deletequery);
            $_SESSION['success'] = $product." has been removed";
            echo '<script language="javascript">	

            document.location.replace("editbrokenitems.php");
    
            </script>'; 

        }
    }
    ?>



    
    <a href = "brokenitems2.php" target = "_self">Add more Equipment</a><br><br><br>
    <?php
        // Driver / Admin links
        if ($rows['position'] == 'driver'){
            echo '<a href = "brokeninput.php" target = "_self">View your order</a>';
        } else {
            echo '<a href = "verifybrokenitems.php" target = "_self">View order</a>';
        }
    ?>



</form>   


</div>
</body>
</html>