 <!-- 
editorder.php
Purpose: allows customer to remove a product from their cart (using a delete for the sql equipment_rented table)
accessed from: orderequipment.php
sends user to: orderequipment.php
NOTE: the user only has option to remve all quantities of a product,introducing the option to remove some of a quantity is a feature that could be added with more time
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
    min-width: 600px;
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

    echo '<div class="topnav">
    <a href="index.php"> Home</a>
  </div>';



  }

?>

<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:1000px">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<h4 class="w3-wide w3-center">Edit your Order</h4>
    <p class="w3-opacity w3-center"><i>Remove items below</i></p> 
    <p class="w3-opacity w3-center"><i>Note: <b>all quantities</b> of item will be removed</i></p> Your event will <b> not be saved</b> until you confirm your invoice.<br></i></p> <br>
    <select name='product',id='product'>
    <?PHP
    # form for user to sepcify what product they want to remove
    include ("detail.php"); 
    session_start();
    $q = "SELECT id, product_name, category, equipment_rented.quantity,rental_price_excl_vat,setup_price_excl_vat,equipment_rented.setup FROM equipment_rented INNER JOIN equipment ON id = equipment_id WHERE events_id =  ".$_SESSION['event_id'];
        $result = $db->query($q);
        $num_results = mysqli_num_rows ($result);
        for ($i=1; $i <$num_results+1; $i++)
        {
        $row = mysqli_fetch_assoc($result);
        echo "<option value='".$row['id']."'> ".$row['product_name']." (".$row['category'].") X".$row['quantity']." </option>"; 
        }
        echo "</select>";
    ?>
    <input type = "submit"name="submit-category" value = "Remove Item"><br> 
    <br><br><br><br><br>


    <?PHP
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if(isset($_POST['submit-category'])){
            include ("detail.php");
            session_start();
            $product = $_POST["product"];
            $deletequery = "DELETE FROM equipment_rented WHERE equipment_id = ".$product." and events_id = ".$_SESSION['event_id'];
            $result = $db->query($deletequery);
            
            header( 'Location: https://stu33001.scss.tcd.ie/group_3/editorder.php' ) ; 
            echo 'Product has been removed';
        }
    }
    ?>










 
<table class="styled-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Rental Price € (excl VAT)</th>
            <th>Setup Price € (excl VAT)</th>
            <th>Sale </th>
            <th>Total € (excl VAT)</th>
            
        </tr>
    </thead>
    <tbody>



  <!-- Shopping cart-->
  <img src="cart-icon.png" alt="error loading image sorry" width="50" height="50" ><br>
    <?php
     session_start();
     include ("detail.php");
        #get all the releveant details for the equipment rented for the event
        $q = "SELECT product_name, category, equipment_rented.quantity,rental_price,setup_price,equipment_rented.setup as 'setup',equipment_rented.sale as 'sale'  FROM equipment_rented  INNER JOIN equipment ON id = equipment_id WHERE events_id =  ".$_SESSION['event_id']."";
     
        $result = $db->query($q);
        $rowrentalcost = 0;
        $rowsetupcost = 0;
        $total = 0;
                $num_results = mysqli_num_rows ($result);
                for ($i=1; $i <$num_results+1; $i++)
                {
                    $row = mysqli_fetch_assoc($result);
                    $rowrentalcost = doubleval($row[rental_price])*doubleval($row[quantity]);
                    $rowsetupcost = doubleval($row[setup_price])*doubleval($row[quantity]);
                    echo "<tr>";
		                    echo "<td>".$row[product_name]."</td>";
		                    echo "<td>".$row[category]."</td>";
                            echo "<td>".$row[quantity]."</td>";
                            echo "<td>".$row[rental_price]."</td>";
                            if($row[setup])
                                echo "<td>".$row[setup_price]."</td>";
                           else{
                            echo "<td>N/A</td>";  
                           }
                            
                           #display sale, and total with sale applied if present
                           if($row[sale]>0){
                           echo "<td>".($row[sale]*100)."% off</td>";
                           echo "<td>".(($rowrentalcost+$rowsetupcost)*(1-$row[sale]))."</td>";
                           $total = $total + (($rowrentalcost+$rowsetupcost)*(1-$row[sale]));
                           }
                           else{#else, jist display n/a and the total without a sale applied
                            echo "<td></td>"; 
                            echo "<td>".($rowrentalcost+$rowsetupcost)."</td>"; 
                            $total = $total + $rowrentalcost+$rowsetupcost;
                            }
		                  echo "</tr>";
                 }

                 #final row of table displays total only
                 $total = round($total,2);
                 echo "<td><b>Cost of equipment</b></td>";
		         echo "<td></td>";
                 echo "<td></td>";
                 echo "<td></td>";
                 echo "<td></td>";
                 echo "<td></td>";
                 echo "<td><b>€".$total."</b></td>";  
                 echo '</table></tbody></table>';      
?> 











</tbody>
</table>









</form>   


Not happy? <a href = "orderequipment.php" target = "_self"> Add more equipment </a><br>


<form action="checkcustomer.php">
<p><button class="w3-button w3-black" type="submit">View Order</button></p>
</form>
</div>
</body>
</html>