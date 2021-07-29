<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales By Product</title>

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
    margin: 10px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
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

        $category = $product_name = $start_date = $end_date = "";
        $categoryErr = $productErr = $start_dateErr = $end_dateErr = $dateErr = "";

        if(empty($_POST['equip'])){

            $productErr = "Please select a product";
        }
        //check if start date is empty
        if(empty($_POST['start_date']))
        {
            $start_date = "";
            $dateErr = "When a start date is not chosen, data from the beginning is shown";


        }else{
            $start_date = $_POST['start_date'];
        }

        //if a start date is chosen then an end date must also be chosen
        if($start_date!= "" && empty($_POST['end_date']))
        {
            $end_dateErr = "Please enter an end date";
        }else{
            $end_date = $_POST['end_date'];
        }
        
        //end date cannot come before the start date
        if($start_date !="" && $end_date < $start_date)
        {
            $start_dateErr = "The start date cannot come after the end date";
        }

        //start date must be before or on today
        if($start_date > date("Y-m-d"))
            {
                $start_dateErr = "Please choose a date before today";
            }
    ?>

<!-- Nav Bar -->
    <div class="topnav">
        <a class="active" href="admin.php">Admin Home</a>
        <a href="reports.php">Back to Reports</a>
        <a href="logout.php">Log Out</a> 
    </div>

<!-- Quick Links Sectiom-->
   <div class="w3-container w3-content w3-padding-5" style="max-width:1000px">
   <h3>Use the form below to select the product for which you would like to view sales:</h3>
   </div>
<!-- Form customer can fill out to view product sales for each product -->
   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
   <b>Category</b>
        <select class="w3-input w3-border" name="category" value="<?php echo $category;?>" style="width: 500px">Category
        <span class = "error">* <?php echo $categoryErr;?></span>
      
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
      <p><b>Product Name</b></p>
      <select class="w3-input w3-border" name="equip" style="width: 500px">
      
      <!-- php query which returns the different products in a category -->
      <?php
        include ("detail.php"); 

            $category = $_POST["category"];
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
      <span class = "error"> <?php echo '<i style="color:red">'.$productErr.'</i>';?></span>
      <br>
      <table>
      <tr>
                <td><b>Start Date:</b></td>
                <td>
                <input type="date" name="start_date" id="start_date" value="<?php echo $start_date;?>" size="30">
                    <span class = "error"> <?php echo $start_dateErr;?></span>
                </td>
        </tr> 
            <tr>
                <td><b>End Date:</b></td>
                <td>
                <input type="date" name="end_date" id="end_date" value="<?php echo $end_date;?>" size="30">
                    <span class = "error"> <?php echo $end_dateErr;?></span>
                </td>
            </tr> 
        </table>
      <p><button class="w3-button w3-black" type="submit">Submit</button></p>
      </form>
 
<!-- Product Details -->
  <div class="w3-container w3-content w3-padding-5" style="max-width:2000px" id="table">
<h4 class= "w3-wide w3-center">Product Details</h4>
   <div class="w3-wide w3-left">
   <table class="styled-table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity Sold</th> 
            <th>Rental Revenue</th>  
            <th>Set up Revenue</th>
            <th>Total Before Discount</th>
            <th>Total After Discount</th>
        </tr>
    </thead>
    <tbody>
<?php
    include("detail.php");
    $equip_id = $_POST["equip"];

    //if there is no start date, show all data
    if($start_date == "")
    {
        echo $dateErr;
        $q3 = "SELECT product_name, SUM(equipment_rented.quantity) AS 'amount_sold', 
        SUM((equipment.rental_price_excl_vat*equipment_rented.quantity)) AS 'revenue', 
        SUM(equipment.setup_price_excl_vat*equipment_rented.quantity) AS 'setup', 
        ROUND(SUM(((equipment.rental_price_excl_vat+equipment.setup_price_excl_vat)*equipment_rented.quantity)*(1-equipment_rented.sale)),2) AS 'spent'
        FROM equipment_rented, equipment WHERE equipment.id = '$equip_id' AND equipment_rented.equipment_id = equipment.id;" ;
        $result3 = $db->query($q3);
        $num_results = mysqli_num_rows($result3);
    
        //loop to print out data in a table 
        for($i=0; $i < $num_results; $i++)
        {
            $row = mysqli_fetch_assoc($result3);
            $revenue = $row[revenue];
            $setup = $row[setup];
            $total = $revenue + $setup;
            $spent = $row['spent'];

            echo "<tr>";
                       echo "<td>".$row[product_name]."</td>";
                       echo "<td>".$row[amount_sold]."</td>";
                       echo "<td>".$revenue."</td>";
                       echo "<td>".$setup."</td>";
                       echo "<td>".$total."</td>";
                       echo "<td>".$spent."</td>";
            echo "</tr>";
        }
        
    }else{
        
        //if there are no errors show date bound data
        if($start_dateErr == "" && $end_dateErr == "" && $productErr == ""){
            $q4 = "SELECT product_name, purchase_date, SUM(equipment_rented.quantity) AS 'amount_sold', 
                SUM((equipment.rental_price_excl_vat*equipment_rented.quantity)) AS 'revenue', 
                SUM(equipment.setup_price_excl_vat*equipment_rented.quantity) AS 'setup', 
                ROUND(SUM(((equipment.rental_price_excl_vat+equipment.setup_price_excl_vat)*equipment_rented.quantity)*(1-equipment_rented.sale)),2) AS 'spent' 
                FROM equipment_rented, equipment, receipts WHERE equipment.id = '$equip_id' 
                AND receipts.event_id = equipment_rented.events_id AND equipment_rented.equipment_id = equipment.id 
                AND receipts.purchase_date BETWEEN '$start_date' AND '$end_date';" ;
            
            $result4 = $db->query($q4);
            $num_results = mysqli_num_rows($result4);
    
            //loop to print out data in a table 
            for($i=0; $i < $num_results; $i++)
            {
                $row = mysqli_fetch_assoc($result4);
                $revenue = $row[revenue];
                $setup = $row[setup];
                $total = $revenue + $setup;
                $spent = $row['spent'];

                echo "<tr>";
                       echo "<td>".$row[product_name]."</td>";
                       echo "<td>".$row[amount_sold]."</td>";
                       echo "<td>".$revenue."</td>";
                       echo "<td>".$setup."</td>";
                       echo "<td>".$total."</td>";
                       echo "<td>".$spent."</td>";
                       
                echo "</tr>";
            }
        }
    mysqli_close($db);
}

    
?>

<br>
    </tbody>
</table>
</div>
</body>
</html>

