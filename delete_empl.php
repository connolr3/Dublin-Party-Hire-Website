<!-- This form deletes an employee who is a driver

This form is accessed through employeeactions.php by clicking the delete employee button
This page redirects to admin.php, employeeactions.php and logout.php through the nav bar

-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Staff Member</title>
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

  <h4>Use the Form Below to Remove a Staff Member</h4>

<!-- Php Script to delete a new employee -->

<?php

    session_start();
    include("detail.php");

    $empl_id =  "";
    $empl_name= "";
    $empl_id_err =  "";
    $deletemessage = "";

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
        if(empty($_POST['employee'])){

            $empl_id = "Name cannot be empty";

        }else{
            $empl_id = test_input($_POST['employee']);

            //get employee name
            $q1 = "SELECT full_name FROM staff WHERE employee_id = '$empl_id'";
            $result1 = $db->query($q1);
            $row = mysqli_fetch_assoc($result1);
            $empl_name = $row['full_name'];
    }

   

    }


     // If Validation Successful, Run MySQL query
     if ($empl_id_err == "" && $empl_id != "") {
         
        $q  = "DELETE FROM staff WHERE employee_id = '$empl_id'";
        $deletemessage = "The employee has been deleted";
        $result = $db->query($q);

        //Delete future shifts for fired employees
        $q4 = "DELETE FROM staff_shifts WHERE staff_id = '$empl_id' AND shift_date > CURDATE()";
        $result4 = $db->query($q4);

        unset($_SESSION['deleted']);
        $_SESSION["deleted_empl"] = "The following employee has been removed: ".$empl_name."";

        //redirect
        header("Location: confirm_delempl.php");
     }   



?>

  <!-- Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="empl_id" id="empl_id">
        <table>
            <tr>
                <td>Employee:</td>
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
            		
 </tr>
 </table>
        <p>
        <br>

<!-- Javascript to confirm whether we want to delete the employee or not -->
<script>
function ConfirmDelete()
{
  var x = confirm("Are you sure you want to delete this employee?");
  if (x)
      return true;
  else
    return false;
}
</script>
<!-- Confirm if you want the empployee deleted or not -->
<button class="w3-button w3-black" onclick="return ConfirmDelete();" type="submit" value="Delete Employee" name="actiondelete" >Delete Employee</button>
</form>
</body>
</html>