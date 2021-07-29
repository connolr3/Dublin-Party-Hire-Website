<!-- This is the customer login page, they are redirected here from index.php
If they are an existing customer, they will be redirected to customeraccount.php-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Log In</title>
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
    <!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
    <a class="active" href="index.php">Home</a>
    <a href="index.php">Customers</a>
    <a href="registration.php">Register</a>
    <a href="stafflogin.php">Staff</a>
    
    
    
  </div>
<!-- PhP script to process form data and redirect customer to customer account -->
    <?php

        session_start();

        include("detail.php");

        $email = $password = "";
        $emailErr = $passwordErr = $matchingErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST["email"])){
                $emailErr = "Email is Required";
            } else {
                $email = test_input($_POST["email"]);
                // Remove Apostropes & lower case
                $email = str_replace('\'', '', $email);
                $email = strtolower($email);
    
                $q = "SELECT * FROM customers WHERE email = '$email'";
                $result = $db->query($q);
                $rows = mysqli_num_rows($result);
    
                // check if e-mail address is formatted correctly
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
                //check if account exists for input email
                elseif(!($rows == 1)){
                    $emailErr = "No record for this email";
                }
            }

            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
            } else {
                $user_password = test_input($_POST["password"]);

                if (strpos($user_password, "'") !== FALSE) {
                    $passwordErr = "NO APOSTROPHES, sorry";
                }
            }

            //Verify Input with MySQL if other validations are clear
            $q = "SELECT user_password FROM customers WHERE email = '$email'";
            $result = $db->query($q);
            $row = mysqli_fetch_assoc($result);

            if(($row['user_password'] != $user_password) && $emailErr == "" && $passwordErr == ""){
                $matchingErr = "Matching Error. Name / ID Invalid. Try Again";
            }

        }


        function test_input($data) {
            $data = trim($data); 
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //Validations Complete -> Sent to Account Page
        if ($emailErr == "" && $passwordErr == "" && $matchingErr == "" && $email != "" && $user_password != ""){

            $_SESSION['user_email'] = $email;



            echo '<script language="javascript">	

            document.location.replace("customeraccount.php");
        
            </script>';

        }

    ?>


<!-- Form to input their login details -->
<div class="w3-container w3-content w3-padding-11" style="max-width:500px" id="cust_login">
<h4> If you are an existing customer, login below, otherwise you can register a new account <h4>
    <p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="verify_customer">
        <p>
            Email:
            <input type="text" name="email" value="<?php echo $email;?>" size="40">
            <span class="error">* <?php echo $emailErr;?></span><br><br>
        </p>
        <p>
            Password:
            <input type="password" name="password" value="<?php echo $user_password;?>">
            <span class="error">* <?php echo $passwordErr;?></span>
            <span class="error"><?php echo $matchingErr;?></span>
        </p>
        <input type="submit" value="Submit"> 
        <a href="forgotpassword.php" target="_blank">Forgot password?</a><br>
    </form>

    <br> 
    <br>

    </div>
</body>
</html>