<!-- This is the staff login page. 
    Staff are redirected here from index.php
    Once logged in, staff can be redirected to staff.php
    or if it's Aiden (the admin) logging in, then they're
    redirected to admin.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Log In</title>
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

    <style>
/* Style all input fields */
input {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 12px;
}

/* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 10px;
  margin-top: 5px;
}

#message p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
}

/* Add a red text color when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
}

</style>
</head>
<body>
    <!-- Php Script to process information -->
    <?php

        session_start();

        include("detail.php");

        $id = $user_password = "";
        $idErr = $passwordErr = $matchingErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST["id"])){
                $idErr = "ID is Required";
            } else {
                $id = test_input($_POST["id"]);
                
                 // check if id only contains numbers 0-9
                if (!preg_match("/^(['.$custom.'0-9_]*)$/i",$id)) {
                    $idErr = "Only Digits 0-9 can be input";
                }

            }

            //make sure password is not empty
            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
            } else {
                $user_password = test_input($_POST["password"]);

                if (strpos($user_password, "'") !== FALSE) {
                    $passwordErr = "NO APOSTROPHES, sorry";
                }
            }

            //Verify Input with MySQL if other validations are clear
            $q = "SELECT password FROM staff WHERE employee_id = '$id'";
            $result = $db->query($q);
            $row = mysqli_fetch_assoc($result);

            if(($row['password'] != $user_password) && $idErr == "" && $passwordErr == ""){
                $matchingErr = "Matching Error. Name / ID Invalid. Try Again";
            }

        }


        //function to test inputs
        function test_input($data) {
            $data = trim($data); 
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //Validations Complete -> Sent to Account Page
        if ($idErr == "" && $passwordErr == "" && $matchingErr == "" && $id != "" && $user_password != ""){

            $_SESSION['staff_id'] = $id;

            $q = "SELECT * FROM staff WHERE employee_id = '$id'";
            $query = $db->query($q);
            $row = mysqli_fetch_assoc($query);

            //Admin page if it's admin/manager and staff page if it's staff
            if ($row['position'] == 'manager') {
                echo '<script language="javascript">	
                
                document.location.replace("admin.php");
    
                </script>';
            } else {

                echo '<script language="javascript">	

                document.location.replace("staff.php");
            
                </script>';

            }

        }

    ?>

<!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
    <a href="index.php">Home</a>
    <a class="active" href="stafflogin.php">Staff Log In</a>

  </div>

<!-- Form for inputting data to login -->
<div class="w3-container w3-content w3-padding-11" style="max-width:800px" id="staff_login">
<h4>Welcome! Fill in your details below to log into your staff account</h4>
    <p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="verify_customer">
        <p>
            Staff ID:
            <input type="text" name="id" value="<?php echo $id;?>" size="40">
            <span class="error">* <?php echo $idErr;?></span><br><br>
        </p>
        <p>
            Password:
            <input type="password" name="password" value="<?php echo $user_password;?>">
            <span class="error">* <?php echo $passwordErr;?></span>
            <span class="error"><?php echo $matchingErr;?></span>
        </p>
        <input type="submit" value="Log in"> 
    </form>
</div>

</body>
</html>