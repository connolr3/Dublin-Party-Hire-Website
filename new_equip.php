<!-- This is the form to add new equipment to the catalogue

This page is accessed from equipaction.php pag when new equipment is selected

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
    <title>New Equipment</title>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
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
<body>
    <!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
    <a class="active" href="admin.php">Admin Home</a>
    <a href="equipaction.php">Back to Operations & Equipment</a>
    <a href="logout.php">Log Out</a>
   

  </div>

  <h4>Use the Form Below to Add New Equipment</h4>

<!-- Php Script to add new equipmeny -->

<?php

//declare variables and connect to the database
    session_start();
    include("detail.php");

    $admin_homelink = "admin.php";
    $equippagelink = "equipaction.php";

    $equip_name = $category = $rental_price = $setup_price = $setup = $quantity = "";
    $equipErr = $categoryErr = $rentalErr = $setupErr = $quantErr = "";

    //function to test input
    // function to test and filter data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // make sure the name is not empty
        if(empty($_POST['equip_name'])){

            $equipErr = "Equipment name cannot be empty";

        }else{
            $equip_name = test_input($_POST['equip_name']); 
       
         //make sure name contains only white space, apostrophes and letters
         //using the appropriate regex
         if(!preg_match("/^([a-zA-Z ']*)$/", $equip_name))
        {
            $equipErr = "Only letters, white space and apostrophes can be used";
        }
    }

    // make sure category is not empty
    if (empty($_POST['category'])) {

        $categoryErr = "Category Type is Required";

    } else {
             $category = test_input($_POST['category']);


            //make sure the product doesn't already exist if there is a product with the exact same name
            //and category
            $q1 = "SELECT * FROM equipment where product_name = '$equip_name' AND category = '$category'";
            $result1 = $db->query($q1);
            $num_rows1= mysqli_num_rows($result1);
            if($num_rows1 >= 1)
            {
                $equipErr = "Looks like this equipment already exists.";
            }

        }
    //make sure rental price isn't empty
    if(empty($_POST['rental_price'])){

            $rentalErr = "Rental price cannot be empty or 0";

        }else{
            $rental_price = test_input($_POST['rental_price']); 

    }


    //make sure setup price is set
    if (!isset($_POST['setup_price'])) {
        $setupErr = "Please enter a set-up price. Enter 0 if no set-up is required";
    } else {
        $setup_price = test_input($_POST['setup_price']);

    }

    //check if quantity is 0
    if (!is_numeric($_POST['quantity'])) {
        $quantErr = "Please enter a quantity. Enter 0 if no equipment is on hand";

    } else {
        if($_POST['quantity'] < 0){
            $quantErr = "Quantity cannot be negative";
        }else{
              $quantity = test_input($_POST['quantity']);
    }
        }
     
    //if setup price is 0 then setup is not required and vice versa
    if($setup_price!=0)
    {
        $setup = 1;
    }
    else{
        $setup = 0;
    }
   


    //escape string to allow for apostrophes
    $equip_name = $db->real_escape_string($equip_name);

    // insert data into the table
     // If Validation Successful, Run MySQL query
     if ($equipErr == "" && $categoryErr == "" && $rentalErr == "" && $setupErr == ""  && $quantErr == "") {//     && $equip_name != "") {
         
        $q  = "INSERT INTO equipment (";
        $q .= "category, product_name, rental_price_excl_vat, setup_price_excl_vat, quantity, setup";
        $q .= ") VALUES (";
        $q .= " '$category', '$equip_name', '$rental_price', '$setup_price', '$quantity', '$setup')";
    
        $result = $db->query($q);

        //echo a confirmation message
        echo "<p><b>Confirmed: </b>" .$equip_name. " has been added to the catalogue.</p>";
        echo "<p><b>Category: </b>" .$category."</p>";
        echo "<p><b>Rental Price: </b>".$rental_price."</p>";
        echo "<p><b>Setup Price: </b>".$setup_price."</p>";
        echo "<p><b>Quantity on Hand: </b>".$quantity."</p>";

        echo '<p><a href="'.$admin_homelink.'">Take me back to admin.</a></p>';
        echo '<p><a href="'.$equippagelink.'">Take me back to the operations and equipment page.</a></p>';

        //reset variables to clear form
        $equip_name = $category = $rental_price = $setup_price = $setup = $quantity = "";

     }   

     
}


?>

  <!-- Form to input data -->

<p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="new_equip" id="new_equip">
        <table>
            <tr>
                <td>Product Name: </td>
                <td>
                    <input type="text" name="equip_name" id= "equip_name" value="<?php echo $equip_name;?>" size="40">
                    <span class = "error">* <?php echo $equipErr;?></span>
                </td>
            </tr>
                <td>Category:</td>
                <td>
                <select class="w3-input w3-border" name="category" id="category" style="width: 400px">
      
      <!-- php query which returns the names of categories in the equipment table to put into the select box in form -->
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
                <span class = "error">* <?php echo $categoryErr;?></span>
                </td>
                </tr> 
            <tr>
                <td>Rental Price excl VAT:</td>
                <td>
                <input type="number" min="0.00" max="1000.00" step="0.01" name="rental_price" id = "rental_price" value="<?php echo $rental_price;?>" />
                    <span class = "error">* <?php echo $rentalErr;?></span>
                </td>
            </tr>  
            <tr>
                <td>Setup Price excl VAT (please leave as 0 if set up is not an option):</td>
                <td>
                    <input type="number" min="0.00" max="10000.00" step="0.01" name="setup_price" value="0.00" id="setup_price"/>
                    <span class = "error"> <?php echo $setupErr;?></span>
                </td>
            </tr>
            <tr>
                <td>Quantity:</td>
                <td>
                    <input type="number" min="0" max="10000" step="1" value="<?php echo $quantity;?>" name="quantity" id="quantity"/>
                    <span class = "error">* <?php echo $quantErr;?></span>
                </td>
            </tr>
            </table>
        <p>
        <br>
        <p><input type="submit" value="Submit"> or <input type="reset" value="Reset"></p>
    </form>

</body>
</html>