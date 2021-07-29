<!-- This is the change equipment set price page that lets the admin change the setup price for each equipment

This page is accessed through equipaction.php when the user clicks the update rental price button

This page redirects to:

    equipaction.php
    admin.php
    logout.php and index.php


-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Rental Price</title>

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

<!-- Nav Bar -->
    <div class="topnav">
        <a class="active" href="admin.php">Admin Home</a>
        <a href="equipaction.php">Back to Operations & Equipment</a>
        <a href="logout.php">Log Out</a> 
    </div>

<!-- PhP Code that will determine the input and figure out error messages -->
    <?php
        session_start();
        include("detail.php");

    // function to test and filter data
    function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
        $admin_homelink = "admin.php";
        $equippagelink = "equipaction.php";
        $category = $product_id = $setup_price = $currprice = $product_name = "";
        $categoryErr = $productErr = $priceErr = "";

        //make sure category isn't empty
        if(empty($_POST['category']))
        {
            $categoryErr = "Please select a category";
        }else{
            $category = test_input($_POST['category']);
        }

        //make sure product id isn't empty
        if(empty($_POST['product_id']))
        {
            $productErr = "Please select a product";
        }
        else{
            $product_id = test_input($_POST['product_id']);

            $q3 = "SELECT setup_price_excl_vat, product_name FROM equipment WHERE id = '$product_id'";
            $result3 = $db->query($q3);
            $row = mysqli_fetch_assoc($result3);

            //get current setup price
            $currprice = $row['setup_price_excl_vat'];
            $product_name = $row['product_name'];

        }


        //make sure price is valid
        if(!is_numeric($_POST['setup_price']))
        {
            $priceErr = "Please enter a valid price value";
        }else{
            $setup_price = test_input($_POST['setup_price']);
        }

    //if validations are successful, update table
    if($priceErr == "" && $categoryErr == "" && $productErr == "" ){

    $sql  = "UPDATE equipment SET setup_price_excl_vat = '$setup_price' WHERE id = '$product_id'";

    $result_sql = $db->query($sql);

    $update_message = "Thank you, the setup price has been updated.";
    $update_message2 = "The new setup price for ".$product_name." is: €".$setup_price;

    //print success message
    echo '<b><span style="color">'.$update_message.'</b>';
    echo '<p><b><span style="color">'.$update_message2.'</b></p>';
    echo '<p><a href="'.$admin_homelink.'">Take me back to admin.</a></p>';
    echo '<p><a href="'.$equippagelink.'">Take me back to the operations and equipment page.</a></p>';

    
    //clear form
    $category = $product_id = $rental_price = $currprice = $product_name = "";
    

 
}

?>

<!-- The form to gather the necessary data -->
<div class="w3-container w3-content w3-padding-11" style="max-width:800px">
<h2>Use this form to change the setup price for a specific product:</h2>

<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
<table>
<tr>
<h5><b>Select your category and press submit<b></h5>
<td>
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
      <span class = "error"><?php echo $categoryErr;?></span>
      </td>
      </tr>
      <tr>
      <td>
      <p><button class="w3-button w3-black" type="submit">Select this Category</button></p>
      </td>
    </tr>

<tr>
<td>
<h5><b>Select your product and press submit</b></h5> 
<select class="w3-input w3-border" name="product_id" style="width: 400px">
      
      <!-- php query which returns the different product in each category -->
      <?php
        include ("detail.php"); 

            $category = $_POST["category"];
			$q2 = "SELECT * FROM equipment WHERE category = '$category'";
			$result2 = $db->query($q2);
            $num_results = mysqli_num_rows($result2);
			for($i=0; $i <$num_results; $i++)
			{
                $row = mysqli_fetch_assoc($result2);
				echo '<option value="'.$row['id'].'"'.(strcmp($row['id'],$_POST['product_id'])==0?' selected="selected"':'').'>'.$row['product_name'].'</option>'; 			
			}
			mysqli_close ($db);
      ?>		
      </select>
      <span class = "error"><?php echo $productErr;?></span>
      </td>
    </td>

<!-- Print out the current price for the selected product -->
      </tr>
    <tr>
    <td>
      <p><button class="w3-button w3-black" type="submit">Select this Product</button></p>
     </td>
    </tr>
<tr>
<td>
<h5><b>New Price</b></h5>
<p >
      <?php 
            echo "Current Price: ".$currprice." euro";
        ?>
      </p>
<input class= "w3-input w3-border" type="number" name = "setup_price" max="100000.00" min="0.01" step="0.01" >
<span class = "error"><?php echo $priceErr;?></span>
</td>

</tr>
</table>
<!-- Javascript for confirm message and button -->
<script>
function ConfirmDelete()
{
  var x = confirm("Are you sure you want to update the setup price?");
  if (x)
      return true;
  else
    return false;
}
</script>
<!-- Confirm if you want the price changed or not -->
<button class="w3-button w3-black" onclick="return ConfirmDelete();" type="submit" value="Update Price" name="actionupdate" >Update Price</button>
<p><button class="w3-button w3-black" type="reset">Reset</button></p>
</form>


</div>
</body>
</html>