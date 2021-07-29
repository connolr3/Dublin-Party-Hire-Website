<!-- 
    changepassword.php
    Purpose: allow customers to send an otp to change their passwords, once validated from forgotpassword.php
    Accessed from: forgotpassword.php
    sends user to: passwordsuccess.php

-->




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <style>
        form {
            text-align: left;
        }
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
    <?php

        session_start();

        include("detail.php");

      $user_password = $confirm_password = "";
       $passwordErr = $confirm_passwordErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["password"])) {
                $passwordErr = "Password is required (No Apostrophes)";
            } else {
                $user_password = test_input($_POST["password"]);
                $confirm_password = test_input($_POST["confirm_password"]);

                if (strpos($user_password, "'") !== FALSE) {
                    $passwordErr = "NO APOSTROPHES, sorry";
                } elseif (!($user_password == $confirm_password)) {
                    $confirm_passwordErr = "Does not match password";
                }
            }

      
 
       // If Validation Successful, Run MySQL query
       if (($passwordErr == "") && ($confirm_passwordErr == "") ) {
        $updatequery = "UPDATE customers SET user_password = '".$user_password."' where email = '".$_SESSION['emailtochange']."' ";
        $result = $db->query($updatequery);
        #send password change message to
        $message = "Your password has been changed! If this was not you, unfortunetely your account has been hacked";
        $headers = "From:DPH";
        $mailsend = mail( $_SESSION['emailtochange'], 'Password has been changed!',$message,$headers);
       
        echo '<script language="javascript">	
       document.location.replace("passwordsuccess.php");
      </script>';
    
    } 


        }

        function test_input($data) {
            $data = trim($data); 
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }


    ?> 



<div class="w3-container w3-content w3-padding-11" style="max-width:900px" id="new_member">
    <h4>Your one time password is correct</h4>
    Please choose a new password below
   
    <!-- Member Form -->
    <p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="customer_registration" id="customer_registration">
        <table>
            <tr>
                <td>Password:</td>
                <td>
                    <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" value="<?php echo $user_password;?>" size="40">
                    <span class = "error">* <?php echo $passwordErr;?></span>
                    <!-- Password Message -->
                    <div id="message">
                    <h3>Password must contain the following:</h3>
                    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                    <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                    <p id="number" class="invalid">A <b>number</b></p>
                    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Confirm Password:</td>
                <td>
                    <input type="password" name="confirm_password" value="<?php echo $confirm_password;?>" size="40">
                    <span class = "error">* <?php echo $confirm_passwordErr;?></span>
                </td>
            </tr>
         
         
        </table> 
     
        <br>
        <p><input type="submit" value="Submit">
         <input type="reset" value="Reset"></p>
    </form>

    <br> 
    <br>
    </div>

<!-- Javascript to validate password -->
<script>
var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
}

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>


</body>
</html>