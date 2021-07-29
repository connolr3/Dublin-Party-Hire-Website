<!--
 pastinvoice.php
  purpose: display invoice for an event created in the past
  accessed from: pastorders.php
  -->
  <?php
//start session 
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

<!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
<div class="w3-bar w3-black w3-card">
    <a href="customeraccount.php">Customers Home</a>
    <a href="newevent.php">Register Event</a>    
    <a href="pastorders.php">View Past Orders</a>
    <a href="logout.php">Log Out</a>
</div>
  </div>
  

  <!-- Page Content -->
 
  <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:1000px">
    <h4 class="w3-wide w3-center">Invoice</h4>
    <img src="cart-icon.png" alt="error loading image sorry" width="50" height="50" ><br>
  
  
   <?php
     session_start();
     include ("detail.php");
   

     #get customer details
     $querycustomer = "SELECT * FROM customers where email =  '".$_SESSION['user_email']."'";
     $resultcustomer = $db->query($querycustomer);
     $rowcustomer = mysqli_fetch_assoc($resultcustomer);
     #display customer details
     echo "<br><b>Customer </b>";
     echo "<br>".$rowcustomer['name'];
     echo "<br>".$rowcustomer['address'];
     echo "<br>Phone: ".$rowcustomer['phone'];
     echo "<br>Email Adress: ".$rowcustomer['email'];
 

      #get event details
      $queryevent = "SELECT * FROM events where event_id =  ".$_POST["rentals"];
      $resultevent = $db->query($queryevent);
      $rowevent = mysqli_fetch_assoc($resultevent);
      #display event details
      echo "<br><b>Event </b>";
      echo "<br>".$rowevent['event_name'];
      echo "<br>".$rowevent['location'];
      echo ", ".$rowevent['county'];
      #save the county,start_time and end_time for use later
      $county = $rowevent['county'];
      $start_time = $rowevent['start_time'];
      $end_time = $rowevent['end_time'];
     
    
        #get the vat rate,and delivery cost at the time of order, stored in receipts
      $queryvat = "select * from receipts where event_id =  ".$_POST["rentals"];
      $resultVAT = $db->query($queryvat);
        $rowVAT = mysqli_fetch_assoc($resultVAT);
        $vatrate = $rowVAT['vat_charged'];
        $delivery_charge = $rowVAT['delivery_charged'];



      #display the timings for delivery/collection
      if ($_SESSION['delivery']){
        echo "<br><br><b>Delivery</b>";
        echo "<br>Equipment to be delivered by DPH on ".$rowevent['start_date']." at ".$start_time.".";
        echo "<br>Equipment will be collected by DPH on ".$rowevent['end_date']." at ".$end_time.".";
   
      }
        else{
            echo "<br><br><b>Customer Collection</b>";
            echo "<br>Equipment can be collected by customer on ".$rowevent['start_date']." ".$start_time.".";
            echo "<br>Equipment to be returned by customer by ".$rowevent['end_date']." ".$end_time.".";
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
    
    
    
    
    <?php
     session_start();
     include ("detail.php");
        #get all the releveant details for the equipment rented for the event
        $q = "SELECT product_name, category, equipment_rented.quantity,rental_price,setup_price,equipment_rented.setup as 'setup',equipment_rented.sale as 'sale'  FROM equipment_rented  INNER JOIN equipment ON id = equipment_id WHERE events_id =  ".$_POST["rentals"]."";
     
     
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
                 
                 
                 #compute final totals and charges
               
                $vat = (round(($total)*($vatrate),2));

                $equip_plus_vat = $total+$vat;
                $equip_plus_delivery = $equip_plus_vat+$delivery_charge;


               


#Table to display the final totals: vat, delivery charges etc
echo "<table class='styled-table'>";
echo " <tr>
                <td><b>Cost of equipment</b></td>
                <td>€". $total."</td>
</tr>";

echo "  <tr>
<td><b>VAT on equipment @".$vatrate."%</b></td>
<td>€". $vat."</td>
</tr>";

echo "  <tr>
<td><b>Cost of equipment (incl VAT)</b></td>
<td>€". $equip_plus_vat."</td>
</tr>";

echo " <tr>
                <td><b>Delivery Charge</b></td>
                <td>€". $delivery_charge."</td>
</tr>";

echo " <tr>
                <td><b>Total cost of event</b></td>
                <td>€". $equip_plus_delivery."</td>
</tr>";


echo "</table>";
$_SESSION['cost']=$equip_plus_delivery;

?> 


<a href="#" onclick="window.print();return false;" title="Print"> Print </a>
<br>




</div>
</body>
</html>