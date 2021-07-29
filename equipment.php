  <!--
  equipment.php
  purpose: allows user to specify how to want to view catalog
  accessed from: customeraccount.php
  sends user to: equipmentqunatity.php - if user wants to view by a specific quantity
                  allequipment.php - if user wants to view all equipment
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

</head>

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
  } 

?>
<body>

<!--php script to show equipment based on categories -->
<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">
    <h4 class="w3-wide w3-center">Equipment by Category</h4>
    <p class="w3-opacity w3-center"><i>Use the form below to see our equipment by category</i></p>
    </p>
        <form action="equipmentcategory.php" method="post">
        <select class="w3-input w3-border" name="category" style="width: 400px">
      
      <!-- php query which returns the names of categories in the equipment table to put into the select box in form -->
      <?php
        include ("detail.php"); 
			$q1 = "SELECT DISTINCT category FROM equipment";
			$result1 = $db->query($q1);
            $num_results = mysqli_num_rows ($result1);
			for($i=0; $i <$num_results; $i++)
			{
        $row = mysqli_fetch_assoc($result1);
				echo '<option value="'.$row['category'].'">'.$row['category'].'</option>'; 			
			}
			mysqli_close ($db);
      ?>		
      </select>
      <p><button class="w3-button w3-black" type="submit">View</button></p>
      </form>
</div>


<!--php script to show all equipment -->
<div class="w3-container w3-content w3-center w3-padding-64" style="max-width:500px">
    <h4 class="w3-wide w3-center">All Equipment</h4>
    <p class="w3-opacity w3-center"><i>Below is a table of all equipment DPH offer</i></p>
    <p class="w3-opacity w3-center"><i>All prices are in euro and exclude VAT</i></p>
    </p>
        <form action="allequipment.php" method="post">	
       <p><button class="w3-button w3-black" type="submit">View</button></p>
      </form>



</body>
</html>


