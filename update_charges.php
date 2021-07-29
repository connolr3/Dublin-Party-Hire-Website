<!-- This is the update charge price page that lets the admin change the charge for each delivery and VAT

This page is accessed through equipaction.php when the user clicks the update charges button

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
    <title>Update Charges</title>

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
<!-- Navigation Bar --> 
<!-- Nav Bar -->
<div class="topnav">
        <a class="active" href="admin.php">Admin Home</a>
        <a href="equipaction.php">Back to Operations & Equipment</a>
        <a href="logout.php">Log Out</a> 
    </div>

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

 if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $admin_homelink = "admin.php";
    $equippagelink = "equipaction.php";
    $charge_input = $change_charge = $new_value = $currValue = "";
    $chargeErr = $new_valErr = "";

//make sure charge type isn't empty
if(empty($_POST['charge_input']))
{
    $chargeErr = "Please select the charge that you would like to change";
}else{
    $charge_input = test_input($_POST['charge_input']);
}

//make sure new value isn't empty
if(empty($_POST['new_value']))
{
    $new_valErr = "Please enter a value for the new charge rate";
}
else{
    $new_value = test_input($_POST['new_value']);
}

//logic for if the charge input is VAT
if($charge_input == "VAT")
{
    if($new_value > 1)
    {
        $new_valErr = "Please enter the VAT rate as a decimal. Eg. 0.75";
    }
}


//if validations are successful, update table
if($chargeErr == "" && $new_valErr == "" ){

    if($charge_input == "VAT")
    {
        $sql  = "UPDATE delivery_charges SET rate_per_km = '$new_value' WHERE chargetype = 'VAT'";
    
        $result_sql = $db->query($sql);
    
    }elseif($charge_input == "Flat Delivery Rate")
    {
        $sql2  = "UPDATE delivery_charges SET flat_rate = '$new_value' WHERE chargetype = 'delivery'";
    
        $result_sql2 = $db->query($sql2);
    }else{
    
        $sql3  = "UPDATE delivery_charges SET rate_per_km = '$new_value' WHERE chargetype = 'delivery'";
    
        $result_sql3 = $db->query($sql3);
    }
    
    
    $update_message = "Thank you, the charge has been updated.";
    $update_message2 = "The new charge for ".$charge_input." is: €".$new_value;
    
    //print success message
    echo '<b><span style="color">'.$update_message.'</b>';
    echo '<p><b><span style="color">'.$update_message2.'</b></p>';
    echo '<p><a href="'.$admin_homelink.'">Take me back to admin.</a></p>';
    echo '<p><a href="'.$equippagelink.'">Take me back to the operations and equipment page.</a></p>';

    //clear variables
    $charge_input = $change_charge = $new_value = $currValue = "";

    
    }
    

 }

 ?>


<div class="w3-container w3-content w3-padding-11" style="max-width:800px">
<h2>Use this form to change a specific charge:</h2>
<!-- Form -->
<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<label> Select the Charge you would like to change </label> <br>

              <input type="radio" name="charge_input" value="VAT" <?php if(isset($_POST['charge_input']) && $_POST['charge_input'] =='VAT' ){echo "checked";}?>> VAT
              <br>
              <input type="radio" name="charge_input" value="Flat Delivery Rate" <?php if(isset($_POST['charge_input']) && $_POST['charge_input'] =='Flat Delivery Rate' ){echo "checked";}?>> Flat Delivery Rate
              <br>
              <input type="radio" name="charge_input" value="Delivery Rate/Km" <?php if(isset($_POST['charge_input']) && $_POST['charge_input'] =='Delivery Rate/Km' ){echo "checked";}?> > Flat Delivery Rate/Km
              <br>
              <span class="error"> <?php echo '<font color = red>'; echo $chargeErr; echo '</font>';?></span>

<br>
<br>
<b>New Charge</b>
<input class= "w3-input w3-border" type="number" name = "new_value" max="100000.00" min="0" step="0.01" >
<span class = "error"><?php echo $new_valErr;?></span>
</td>
<!-- Java script to chance the charge -->
<script>
function ConfirmDelete()
{
  var x = confirm("Are you sure you want to update the charge?");
  if (x)
      return true;
  else
    return false;
}
</script>
<!-- Confirm if you want the charge changed or not -->
<p><button class="w3-button w3-black" onclick="return ConfirmDelete();" type="submit" value="Update Charge" name="actionupdate" >Update Charge</button></p>
<p><button class="w3-button w3-black" type="reset">Reset</button></p>
</form>

</form>
</div>

</body>
</html>