<!-- 
    forgotpassword.php
    Purpose: allow customers to send an otp to reset their passwords
    Accessed from: login.php or loginghost.php
    sends user to: chnagepassword.php

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
        $email = $otp= "";
        $emailErr =$otpErr= "";
        if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
          
        if (isset($_POST['submit-order'])){
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

       // If Validation Successful, send one time password
       if ($emailErr == "") {
        echo '<div style = "position:absolute; left:550px; top:330px; ">';
        echo 'A one time password has been sent to your email address';
        echo '</div>';
        #generate random OTP (one time password)
        $six_digit_random_number = mt_rand(100000, 999999);
        $_SESSION['otp']=$six_digit_random_number;
        $_SESSION['emailtochange']= $email;
        #send welcome message to new member 
        $message = "We have received a request to change your password - You have been registered as a new customer! 
        If you did not request this forgotten password email, no action 
        is needed, your password will not be reset. However, you may want to log into 
        your account and change your security password as someone may have guessed it.";
        $message.='Your one time password is '. $six_digit_random_number;
     
        $headers = "From:DPH";
        $mailsend = mail($email, 'Reset Password at DPH!',$message,$headers);  
    }

 


        }
    

        if (isset($_POST['submit-otp'])){
          if (empty($_POST["otp"])){
            $otpErr = "Required";
        } elseif($_POST["otp"]!=$_SESSION['otp']) {
          $otpErr = "Does not match - Please check again";
          echo $_SESSION['otp'];
          echo'hi';
          echo $_POST['otp'];
        }
        else{
          echo '<script language="javascript">	
          document.location.replace("changepassword.php");
          </script>';
        }
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
    <h4>Reset Password</h4>
    <!-- Member Form -->
    <p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="customer_registration" id="customer_registration">
     Please enter the email address you used to sign up with Dublin Party Hire.<br>
     A one time password will be sent to your email address.<br>
                    <input type="text" name="email" value="<?php echo $email;?>" size="40">
                    <span class = "error">* <?php echo $emailErr;?></span>
                </td>
         
        <p><input type="submit" value="Send Link" name = "submit-order">
         <input type="reset" value="Reset"></p>
    </form>

    <br> 
    <br>
    </div>





    
<div class="w3-container w3-content w3-padding-11" style="max-width:900px" id="otp">
    <h4>Enter One time password</h4>
    <!-- Member Form -->
    <p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="customer_registration" id="customer_registration">
     Enter the password sent to you via email.<br>
                    <input type="text" name="otp" value="<?php echo $otp;?>" size="40">
                    <span class = "error">* <?php echo $otpErr;?></span>
                </td>
         
        <p><input type="submit" value="Submit" name = "submit-otp">
         <input type="reset" value="Reset"></p>
    </form>
    <br> 
    <br>
    </div>


</body>
</html>