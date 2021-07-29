<!-- This form deletes equipment

This form is accessed through equipaction.php by clicking the delete equipment button
This page redirects to admin.php, equipaction.php and logout.php and as a result index.php through the nav bar

-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Equipment</title>
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
    <a  href="equipaction.php">Back to Operations & Equipment</a>
    <a  href="logout.php">Logout</a>
  </div>

  <h4>Use the Form Below to Remove Equipment</h4>

<!-- Php Script to delete a new employee -->

<?php

    session_start();
    include("detail.php");

    $category ="";
    $category_err = "";
    $equip_id =  "";
    $equip_id_err =  "";
    $product_name = "";
    $deletemessage = "";
    $admin_homelink = "admin.php";
    $equippagelink = "equipaction.php";

    //function to test input
    // function to test and filter data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // make sure the category is not empty
        if(empty($_POST['category'])){

            $category_err = "Please select a category";

        }else{
            $category = test_input($_POST['category']);
    }

        // make sure product is is not empty
        //make sure product id isn't empty
        if(empty($_POST['product_id']))
        {
            $equip_id_err = "Please select a product";
        }
        else{
            $equip_id = test_input($_POST['product_id']);

            $q3 = "SELECT product_name FROM equipment WHERE id = '$equip_id'";
            $result3 = $db->query($q3);
            $row = mysqli_fetch_assoc($result3);

            $product_name = $row['product_name'];

        }
   

    }

     // If Validation Successful, Run MySQL query
     if ($equip_id_err == "" && $equip_id != "") {
         
        $q  = "DELETE FROM equipment WHERE id = '$equip_id'";
        $result = $db->query($q);
        $deletemessage = "The equipment ".$product_name." has been deleted";
        unset($_SESSION['deleted']);
        $_SESSION["deleted"] = $deletemessage;
     
        header("Location: confirmdelete.php");

        

     }   



?>

  <!-- Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
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
      <span class = "error"><?php echo $category_err;?></span>         		
 </tr>

 </td>
      </tr>
      <tr>
      <td>
      <p><button class="w3-button w3-black" type="submit">Select this Category</button></p>
      </td>
    </tr>

<tr>
<td>
<h5><b>Select your product</b></h5> 
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
      <span class = "error"><?php echo $equip_id_err;?></span>
      </td>
    </td>
 </table>
        <p>
        <br>

<!-- Javascript to confirm whether we want to delete the employee or not -->
<script>
function ConfirmDelete()
{
  var x = confirm("Are you sure you want to delete this equipment?");
  if (x)
      return true;
  else
    return false;
}
</script>
<!-- Confirm if you want the empployee deleted or not -->
<button class="w3-button w3-black" onclick="return ConfirmDelete();" type="submit" value="Delete Equipment" name="actiondelete" >Delete Equipment</button>
</form>
</body>
</html>