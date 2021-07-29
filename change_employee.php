<!-- This form changes an employee's details who is a driver

This form is accessed through employeeactions.php by clicking the change employee button
This page redirects to admin.php, employeeactions.php and logout.php through the nav bar

-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Employee Details</title>
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

<!-- Import CDN link for Select 2 used for drop downs etc -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Select2 CSS --> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

<!-- jQuery --> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

<!-- Select2 JS --> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

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
    <a  href="employeeaction.php">Back to Employee Actions</a>
    <a  href="logout.php">Logout</a>
  </div>

  <h4>Use the Form Below to Change a Staff Member's Details</h4>
  <h5> Leave a field empty if you do not want to change it </h5>

<!-- Php Script to update a employee -->

<?php

    session_start();
    include("detail.php");
    $admin_homelink = "admin.php";
    $emplpagelink = "employeeaction.php";

    //test input
    function test_input($data) {
        $data = trim($data); 
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    //declare variables
    $empl_id = $phone_number = $position = "";
    $empl_id_err = $phoneErr = $positionErr = "";
 


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //retrieve id
        $empl_id = test_input($_POST['employee']);

        //check phone number
        if(!empty($_POST['phone_number'])){
            $phone_number = test_input($_POST['phone_number']);
            //remove whitespace:

            $phone_number = str_replace(' ', '', $phone_number);

            //check format is correct
        if(!preg_match("/^[0-9]{9,11}$/",$phone_number)) {
            $phoneErr = "Phone number has to be 9 to 11 numbers.";
        }

            //make sure the phone isn't the same as before
            //check phone number isn't already there
            $q1 = "SELECT * FROM staff where phone_number = '$phone_number'";
             $result1 = $db->query($q1);
                $num_rows1= mysqli_num_rows($result1);
                if($num_rows1 >= 1)
                {
                    $phoneErr = "This contact number already exists. Make sure you are inputting a new number.";
                }

        } 


            if(empty($_POST['position']))
            {
                $position = "";
            }
            else{

                $position = test_input($_POST['position']);
            }
    //input data:
    // Update data one by one
     if (!empty($phone_number) && $phoneErr == "" && $positionErr == "") {
         
            $q  = "UPDATE staff SET phone_number = '$phone_number' WHERE employee_id = '$empl_id'";
            $result = $db->query($q);
     } 

     // Update data one by one
     if ($position != "" && $positionErr == "" && $phoneErr == "") {
         
        $q3  = "UPDATE staff SET position = '$position' WHERE employee_id = '$empl_id'";
        $result3 = $db->query($q3);
     }  

     //Retrieve updated data if validations are correct
     if($positionErr == "" && $phoneErr == ""){
        $q4  = "SELECT * FROM staff WHERE employee_id = '$empl_id'";
           $result4 = $db->query($q4);
           $row = mysqli_fetch_assoc($result4);
   
           $empl_name = $row['full_name'];
           $phone_number = $row['phone_number'];
           $position = $row['position'];
   
           echo "<p><b>Confirmed: </b>" .$empl_name. "'s details have been updated.</p>";
           echo "<p><b>Their Role is: </b>" .$position."</p>";
           echo "<p><b>Their Phone Number is: </b>" .$phone_number."</p>";
           
           //clear form and print redirect links
           $empl_id = $phone_number = $position = "";
           echo '<p><a href="'.$admin_homelink.'">Take me back to admin.</a></p>';
           echo '<p><a href="'.$emplpagelink.'">Take me back to employee actions.</a></p>';
   
        
        

}   
    }

?>

  <!-- Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="empl_id" id="empl_id">
        <table>
            <tr>
                <td>Select the Employee:</td>
                <td>
                <select class="w3-input w3-border" name="employee" id="employee" style="width: 400px">
                <!--<input id="search_event" name="search_event" type="text" placeholder="Type here"> -->
                <?php
                    include ("detail.php"); 
			              $q2 = "SELECT full_name, employee_id FROM staff WHERE position = 'driver'";
			              $result2 = $db->query($q2);
                          $num_results = mysqli_num_rows($result2);
			              for($i=0; $i <$num_results; $i++)
			        {
                        $row = mysqli_fetch_assoc($result2);
				        echo '<option value="'.$row['employee_id'].'">'.$row['full_name'].'</option>'; 					
			        }
			        mysqli_close ($db);
                ?>
            <tr>
                <td>Phone Number:</td>
                <td>
                    <input type="text" name="phone_number" size="15">
                    <span class = "error"><?php echo $phoneErr;?></span>
                </td>
            </tr> 
            <tr>
                <td>Position:</td>
                <td>
                <select class="w3-input w3-border" name="position" style="width: 500px">
                <span class = "error"><?php echo $positionErr;?></span>
      
                <!-- php query which returns the different employee positions available -->
                <?php
                include ("detail.php"); 
			          $q1 = "SELECT DISTINCT position FROM staff";
			          $result1 = $db->query($q1);
                      $num_results = mysqli_num_rows ($result1);
		          	for($i=0; $i <$num_results; $i++)
		          	{
                  $row = mysqli_fetch_assoc($result1);
                  echo '<option value="'.$row['position'].'"'.(strcmp($row['position'],$_POST['position'])==0?' selected="selected"':'').'>'.$row['position'].'</option>'; 			
			          }
			          mysqli_close ($db);
                ?>		
                </select> 
                </td>
            </table>
        <p>
        <br>         		
        <p>
        <br>
<!-- Javascript to confirm whether we want to delete the employee or not -->
<script>
function ConfirmDelete()
{
  var x = confirm("Are you sure you want to change this employee's details?");
  if (x)
      return true;
  else
    return false;
}
</script>
<!-- Confirm if you want the empployee deleted or not -->
<button class="w3-button w3-black" onclick="return ConfirmDelete();" type="submit" value="Change Employee" name="actionchange" >Change Employee</button>
</form>
</body>
</html>