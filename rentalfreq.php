<!-- This page shows a table of the rental frequency for each individual project over time

This page is accessed by the reports.php page through the Rental Frequency Button

It redirects to the admin.php, logout.php and reports.php page through the navigation bar -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Frequency</title>

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

<!-- Php script to process form inputs -->
    <?php
        session_start();
        include("detail.php");
        
        function test_input($data) {
            $data = trim($data); 
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $start_date = $end_date = $product = "";
        $start_dateErr = $end_dateErr = $productErr = $dateErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $category = $_POST["category"]; 

            //make sure product isn't empty
            if(empty($_POST['equip'])){
                $productErr ="Please select a product";
            }else{
                $product = test_input($_POST['equip']);
            }

             //Check if start date is empty
             if(empty($_POST['start_date'])){
                $start_date ="";
                $dateErr = "When there is no start date, data from the beginning is shown";

            }else{
                $start_date = test_input($_POST['start_date']);
                $end_date = test_input($_POST['end_date']);
    
                //start date cannot be greater than end date
                if($start_date > $end_date)
                {
                    $end_dateErr = "Start date cannot come after end date";
                }

                if($start_date !="" && !empty($end_date))
                {
                    $start_dateErr = "Please choose a start date";
                }
                //start date should not be after the current day
                if($start_date > date("Y-m-d"))
                {
                    $start_dateErr = "Please choose a date before today";
                }
                //start date should have an end date if chosen
                if($start_date!="0" && empty($end_date))
                {
                    $end_dateErr = "Please select an end date";
                }
        }

        }

    ?>

<!-- Nav Bar -->
    <div class="topnav">
        <a class="active" href="admin.php">Admin Home</a>
        <a href="reports.php">Back to Reports</a>
        <a href="logout.php">Log Out</a>
    </div>

<!-- Start of form-->
   <div class="w3-container w3-content w3-padding-11" style="max-width:550px">
   <h3 class="w3-wide w3-center">Select the Product for which you would like to view rental frequencies:</h3>

   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <select class="w3-input w3-border" name="category" style="width: 400px">
      
      <!-- php query which returns the different category of products -->
      <?php
        include ("detail.php"); 
			$q1 = "SELECT DISTINCT category FROM equipment";
			$result1 = $db->query($q1);
            $num_results = mysqli_num_rows ($result1);
			for($i=0; $i <$num_results; $i++)
			{
        $row = mysqli_fetch_assoc($result1);
        echo '<option value="'.$row['category'].'"'.(strcmp($row['category'],$_POST['category'])==0?' selected="selected"':'').'>'.$row['category'].'</option>'; 	 			
			}
			mysqli_close ($db);
      ?>		
      </select>
      <p><button class="w3-button w3-black" type="submit">Select this category</button></p>
  <br>

   <h3 class="w3-wide w3-center">Select the Specific product in the category:</h3>

        <select class="w3-input w3-border" name="equip"  style="width: 400px">
      
      <!-- php query which returns the different products in each category -->
      <?php
        include ("detail.php"); 

			$q2 = "SELECT * FROM equipment WHERE category = '$category'";
			$result2 = $db->query($q2);
            $num_results = mysqli_num_rows($result2);
			for($i=0; $i <$num_results; $i++)
			{
        $row = mysqli_fetch_assoc($result2);
				echo '<option value="'.$row['id'].'">'.$row['product_name'].'</option>'; 			
			}
			mysqli_close ($db);
      ?>		
      </select>
      <span class = "error"><?php echo $productErr;?></span>
      <table>
      <tr>
                <td><b>Start Date:</b></td>
                <td>
                <input type="date" name="start_date" id="start_date" value="<?php echo $start_date;?>" size="30">
                    <span class = "error"><?php echo $start_dateErr;?></span>
                </td>
        </tr> 
            <tr>
                <td><b>End Date:</b></td>
                <td>
                <input type="date" name="end_date" id="end_date" value="<?php echo $end_date;?>" size="30">
                    <span class = "error"><?php echo $end_dateErr;?></span>
                </td>
            </tr> 
        </table>


      <p><button class="w3-button w3-black" type="submit">View</button></p>
      </form>
  </div>
  <br>

<!-- Product Details Table -->
  <div class="w3-container w3-content w3-padding-11" style="max-width:500px" id="table">
   <h1 class="w3-wide w3-center">Product Details</h1>
   <table class="styled-table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Rental Frequency</th>   
        </tr>
    </thead>
    <tbody>

    <!-- Php script to run the query and print the table -->
<?php
    include("detail.php");
    //if no start date is chosen
    if($start_date == "" && $productErr == "")
    {

    echo $dateErr;
    $q3 = "SELECT equipment.product_name, purchase_date, COUNT(equipment_rented.equipment_id) as 'count' FROM equipment_rented, equipment, receipts WHERE equipment_rented.equipment_id = '$product'
    AND equipment.id = '$product' AND receipts.event_id = equipment_rented.events_id;"; 
    $result3 = $db->query($q3);

    $num_results = mysqli_num_rows($result3);
    
    //loop to print out data in a table 
    for($i=0; $i < $num_results; $i++)
    {
        $row = mysqli_fetch_assoc($result3);
        echo "<tr>";
                       echo "<td>".$row[product_name]."</td>";
                       echo "<td>".$row[count]."</td>";
        echo "</tr>";
    }
    mysqli_close($db);

}
else{

    //if a start date is selected and there are no error messages i.e. form validations are clear
    if($end_dateErr == "" && $start_dateErr == "" && $productErr == "")
    {
        $q4 = "SELECT product_name, purchase_date, COUNT(equipment_rented.equipment_id) as 'count' FROM equipment_rented, equipment, receipts WHERE equipment_rented.equipment_id = '$product' 
        AND equipment.id = '$product'receipts.event_id = equipment_rented.events_id AND receipts.purchase_date BETWEEN '$start_date' AND '$end_date';"; 

        $result4 = $db->query($q4);

        $num_results = mysqli_num_rows($result4);
    
        //loop to print out data in a table 
        for($i=0; $i < $num_results; $i++)
        {
            $row = mysqli_fetch_assoc($result4);
            echo "<tr>";
                       echo "<td>".$row[product_name]."</td>";
                       echo "<td>".$row[count]."</td>";
            echo "</tr>";
        }
        mysqli_close($db);
    }

}
?>

<br>
    </tbody>
</table>

</body>
</html>