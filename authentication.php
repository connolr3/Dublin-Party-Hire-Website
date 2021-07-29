<!-- This is the authentication.php page where the admin is redirected to before they can change drivers' passwords

They are redirected here from employeeaction.php when clicking on the change password button
They can go to admin.php, employeeaction.php, logout.php, index.php and if sucessfully inputted
the correct password, they can go to change_password.php
-->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
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

  <h4><?php echo "Please enter your password ".$_SESSION['full_name']." to access this form";?></h4>
  <p><h4>Passwords are case sensitive</h4></p>

<!-- Php Script to determine if the correct password is chosen -->

<?php

    
    session_start();
    include("detail.php");

    $admin_password =  "";
    $passwordErr= "";
    $currpassword = "";
    $id = $_SESSION['staff_id'];

    //function to test input
    // function to test and filter data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // make sure the password is not empty
        if(empty($_POST['psw'])){

            $passwordErr = "Please enter your password";

        }else{
            $admin_password = test_input($_POST['psw']);

            //get the current manager's admin's password
            $q  = "SELECT password FROM staff WHERE employee_id = '$id'";
            $result = $db->query($q);
            $row = mysqli_fetch_assoc($result);
            $currpassword = $row['password'];

            //if password is correct, redirect to change_password.php
            if($admin_password == $currpassword)
            {
                //redirect
                header("Location: change_password.php");
            }
            else{
                $passwordErr = "Password is incorrect";
            }
            

    }
}


?>
  <!-- Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="password" id="password">
        <table>
            <tr>
                <td>Your Password:</td>
                <td>
                <input type="password" id="psw" name="psw"/>
                    <span class = "error">* <?php echo $passwordErr;?></span>
                </td>
                </tr>
            		
 </tr>
 </table>
        <p>
        <br>
<button class="w3-button w3-black" type="submit" value="Submit"  >Submit</button>
</form>
</body>
</html>