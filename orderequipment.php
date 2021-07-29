
<!-- 
orderequipment.php
Purpose: form to allow customer to order equipment for event just created
accessed from: newevent.php
Sends user to: customers can either
              editorder.php - page option to remove equipment
              orderequipmentsetup.php -  select setup for equipment if offered, and if the customer selects delivery
              vieweventequipment.php - if the customer is happy with order, this page gives them a final view of their order, as well as the option to print their invoice

To do;
display sale if there is one (in drop down menu or just at the side), and also add into cart/price
potentially allow cust to change delivery option
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
  <h4 class="w3-wide w3-center">Order Equipment</h4>
    <p class="w3-opacity w3-center"><i>Thank you for creating your event. Order equipment down below; <br>
    Don't know where to start? Have a browse of our 
        <a href="equipment.php" target="_blank"> Catalog</a><br>Your event will <b> not be saved</b> until you confirm your invoice.<br></i></p> <br>
    

<!-- SALE ITEMS DISPLAY-->
<img src = "sale.png" width="70" height="70" ><br>
<?PHP
        include ("detail.php"); 
        session_start();
        $querysale  = "SELECT * from equipment where sale > 0";
        $resultsale = $db->query($querysale);
        $num_results = mysqli_num_rows ($resultsale);
        for ($i=1; $i <$num_results+1; $i++)
        {
          $row = mysqli_fetch_assoc($resultsale);
          echo ($row[sale]*100)."%";
          echo " off our ".$row[product_name]." in ";
          echo $row[category]."!<br>";
         }
         ?>





















 <!--form for user to sepcify what category they want to order from--> 
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <br><label for="category">First select a Category<br></label>
        <select name='category',id='category'>
        <?PHP
        include ("detail.php"); 
        session_start();
        $q  = "SELECT DISTINCT category FROM equipment ";
        $result = $db->query($q);
        $num_results = mysqli_num_rows ($result);
        for ($i=1; $i <$num_results+1; $i++)
        {
            $row = mysqli_fetch_assoc($result);
            echo "<option value='".$row['category']."'> ".$row['category']."  </option>"; 
         }?>
        </select>
        <input type = "submit"name="submit-category" value = "Go"><br> 
        <br><br>
    </form>   




   





<!--form to specify which product and quantity they want to order, based on category chosen above--> 
   <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?PHP  
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
      include ("detail.php");
      session_start();
      if(isset($_POST['submit-category'])){
        $category = $_POST["category"];
        #drop down select option for the products
        echo '<label for="product">Select a Product in <b>'.$category.' </b><br></label>';
        echo "<select name='product',id='product'>";
        $q  = "SELECT * FROM `equipment` where category = '".$category."';";
        $result = $db->query($q);
        $num_results = mysqli_num_rows ($result);
        for ($i=1; $i <$num_results+1; $i++)
        {
        $row = mysqli_fetch_assoc($result);
        #if there's a sale make sure it's display in dropdown
        if ($row['sale']>0){
          echo "<option value='".$row['id']."'> ".$row['product_name']." (".$row['rental_price_excl_vat']." rental cost -  ".($row['sale']*100)."% off!)</option>";
        }
        else{#else just display name, rental price
          echo "<option value='".$row['id']."'> ".$row['product_name']." (".$row['rental_price_excl_vat']." rental cost)</option>"; 
        }
        
        }
        echo '</select><br><br><br>
        <label for="quantity">Quantity:</label>
        <input type = "number" name = "quantity" id = "quantity" size =2 >
        <input type = "submit"name="submit-order" value = "Add to Order">
        </form>';
      };

   

      #category, product, quantity all selected, now check if they want setup
      if (isset($_POST['submit-order'])){
       
        $error = False;
        include ("detail.php");
        session_start();
        $productid = $_POST["product"];
        $quantity = ($_POST["quantity"]);
        $setuprequired = 0;
        

        #save the sale, rental and setup price to input into equipment_rented table
        #we input these as they could be changed post-order. we want the customer
        #to be able to see past invoices with the price they paid
        #if we just took the prices and sale from the equipment table, they could have been changed
        #post-order, and the customer would see a different total price to the one they 'paid' directly after ordering
        $querysale = "select * from equipment where id = ".$productid;
        $result = $db->query($querysale);
        $row = mysqli_fetch_assoc($result);
        $saleoffered= $row['sale'];
        $rental_price = $row['rental_price_excl_vat'];
        $setup_price = $row['setup_price_excl_vat'];
     



        #find the total amount of equipment that DPH has
        $stockquantityquery  = "SELECT quantity from equipment  WHERE id = ".$productid;
        $result = $db->query($stockquantityquery);
        $num_results = mysqli_num_rows ($result);
        $row = mysqli_fetch_assoc($result);
        $stockquantity = $row['quantity'];


        #find the amount of product that is rented, at different times (still available)
        $rentedquantityquery = "select sum(quantity) as 'quantity' from equipment_rented where equipment_id = ".$productid." and (
          ((select start_date from events where events_id = event_id)>'".$_SESSION['end_date']."')
          or
          ((select end_date from events where events_id = event_id)<'". $_SESSION['start_date']."')
          
        )";
        $result = $db->query($rentedquantityquery);
        $num_results = mysqli_num_rows ($result);
        $row = mysqli_fetch_assoc($result);
       $rentedquantityavailable = $row['quantity'];

     #find the total amount of product registered as rented (at all times) 
        $amountrentedquery = "select sum(quantity) as 'quantity' from equipment_rented where equipment_id = ".$productid;
        $result = $db->query($amountrentedquery);
        $num_results = mysqli_num_rows ($result);
        $row = mysqli_fetch_assoc($result);
        $rentedquantitotal = $row['quantity'];

        #the amount of product that is rented, but not availble during required times is;
        $unavailablequantity = $rentedquantitotal - $rentedquantityavailable;

      #the amount of product that is available to customer to rent for event is;
      $available_quantity =$stockquantity - $unavailablequantity;


        #if the desired quantity is available, then proceed to order
    if ($quantity<=$available_quantity){
                #check if we should ask the user if they'd like set up
                $querysetup = "Select setup from equipment where id = '".$productid."';";
                $resultsetup = $db->query($querysetup);
                $row = mysqli_fetch_assoc($resultsetup);

                #No set up required for equipment, or delivery not desired - can input as is
                if ($row['setup']=="0" or (!$_SESSION['delivery'])) {
                    #check whether the customer has already got some of the same product
                      $checkexistingquery = "Select * from equipment_rented where events_id = ".$_SESSION['event_id']." and equipment_id = ".$productid;
                      $resultcheckexisting = $db->query($checkexistingquery);
                      $num_results = mysqli_num_rows ($resultcheckexisting);
                      $row = mysqli_fetch_assoc($result);

                      #product is not already ordered, insert as a new row
                      if($num_results==0){
                      $insertquery = "INSERT INTO equipment_rented (equipment_id,	events_id,	quantity,	setup,sale,rental_price,setup_price) VALUES (".$productid.",".$_SESSION['event_id'].",".$quantity.",0,".$saleoffered.",".$rental_price.",".$setup_price.")";
                      $result = $db->query($insertquery);
                   
                    }
                     #otherwise, increment the quantiy already ordered by the new quantity
                     else{
                      $updatequery = "UPDATE equipment_rented SET quantity= quantity + ".$quantity."
                      WHERE events_id = ".$_SESSION['event_id']." and equipment_id = ".$productid;
                      $result = $db->query($updatequery);
                      header("Refresh:0");
                      
                     }
                }
               
                #If the equipment is difficult to set up and the customers has opted for delivery, offer them setup
                else if ($row['setup']=="1" and  $_SESSION['delivery'] ){
                  #these are saved as session variable so that we can save them for the insert query on orderequipmentsetup.php
                  $_SESSION['product'] = $_POST["product"];
                  $_SESSION['quantity'] = $quantity;
                  $_SESSION['saleoffered']=$saleoffered;
                  $_SESSION['rental_price']=$rental_price;
                  $_SESSION['setup_price']=$setup_price;
                  echo '<script language="javascript">	
                  document.location.replace("orderequipmentsetup.php");
                  </script>';
                }
    }
   
   
   
    #else, tell user quantity was not available
    else{
      if ($available_quantity<0){
        echo" <img src = 'face.jpg' width='30' height='30' > <br>We're sorry - we're fresh out of this product ";
      }
      else{
     echo" <img src = 'face.jpg' width='30' height='30' > <br>We're sorry - it looks like we only have ";
     echo $available_quantity." of this equipment available...";
    }}
        }
    }?>
<br><br><br> 
























 
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

Not happy? <a href = "editorder.php" target = "_self"> Remove an Item </a><br>
All set? <a href = "checkcustomer.php" target = "_self">View Order </a><br



</div>
</body>

</html>