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

        $name = $address = $eircode = $phone = $email = $business = $user_password = $confirm_password = $agree = "";
        $nameErr = $addressErr = $eircodeErr = $phoneErr = $emailErr = $passwordErr = $confirm_passwordErr = $agreeErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["is_business"])){
                $business = FALSE;
            } else {
                $business = TRUE;
            }
            
            if (empty($_POST["name"])){
                $nameErr = "Your Name is Required:";
            } else {
                $name = test_input($_POST["name"]);
                $name = str_replace('\'', "", $name);
                // check if name only letters and whitespaces
                if ($business == FALSE) {
                    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                        $nameErr = "Only letters and white space allowed";
                    }
                }          
            }

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
                elseif($rows == 1){
                    $emailErr = "Already have a record for that E-mail";
                }
            }

            if (empty($_POST["address"])) {
                $addressErr = "Address is Required";
            } else {
                $address = test_input($_POST["address"]);
            }

            if (empty($_POST["eircode"])) {
                $eircodeErr = "Eircode is Required";
            } else {
                $eircode = test_input($_POST["eircode"]);
                $eircodelength = strlen($eircode);
                $eircode = strtoupper($eircode);

                if (!preg_match("/([A-Za-z0-9]+)/",$eircode)) {
                    $eircodeErr = "Letters and Number only";
                }

                elseif (!($eircodelength == 7)) {
                    $eircodeErr = "Eircode must be 7 Digits";
                }
            }
    
            if (empty($_POST["phone"])) {
                $phoneErr = "Telephone Contact is required";
            } else {
                $phone = test_input($_POST["phone"]);
                $phone = str_replace(' ', '', $phone);
                $phonelength = strlen($phone);

                // check if id only contains numbers
                if (!preg_match("/^(['.$custom.'0-9_]*)$/i",$phone)) {
                    $phoneErr = "Only Digits 0-9 can be input";
                } 

                elseif ($phonelength < 7) {
                    $phoneErr = "Enter Valid Phone Number";
                }
            }

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

            if (empty($_POST["agree"])){
                $agreeErr = "Required";
            } else {
                $agree = test_input($_POST["agree"]);
            }

        }

        function test_input($data) {
            $data = trim($data); 
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // If Validation Successful, Run MySQL query
        if ($nameErr == "" && $addressErr == "" && $eircodeErr == "" &&
        $phoneErr == "" && $emailErr == "" && $passwordErr == "" && 
        $confirm_passwordErr == "" && $agreeErr == "" && $name != "") {
            

           


      


            #add new customer
            $q = "INSERT INTO customers (name, address, eircode, phone, email,
            is_business, user_password) VALUES ('$name','$address', '$eircode',
            '$phone', '$email', '$business', '$user_password')";
            $_SESSION['user_email'] = $email;
            $result = $db->query($q);

                

                   $result = $db->query($q);
                   $row = mysqli_fetch_assoc($result);
                   $id = $row['id'];
                   $username .= $id;
                   $username = strtolower($username);
                   $_SESSION['username'] = $username;


               

            #send welcome message to new member 
            $message = "Welcome to the DPH family ".$name. " - You have been registered as a new customer! ";
            $headers = "From:DPH";
            $mailsend = mail($email, 'Welcome DPH!',$message,$headers);


            echo '<script language="javascript">	

            document.location.replace("customeraccount.php");
        
            </script>';
        } 
    ?> 

<!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
    <a href="index.php">Home</a>
    <a href="newevent.php">Register Event</a> 
    <a href="login.php">Log In</a> 
    <a class="active" href="register.php">Register</a>  
  </div>

<div class="w3-container w3-content w3-padding-11" style="max-width:900px" id="new_member">
    <h4>Customer Registration</h4>
    Please fill in your details below:
   
    <!-- Member Form -->
    <p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="customer_registration" id="customer_registration">
        <table>
            <tr>
                <td>Name: </td>
                <td>
                    <input type="text" name="name" value="<?php echo $name;?>" size="40">
                    <span class = "error">* <?php echo $nameErr;?></span>
                </td>
            </tr>
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
            <tr>
                <td>E-mail:</td>
                <td>
                    <input type="text" name="email" value="<?php echo $email;?>" size="40">
                    <span class = "error">* <?php echo $emailErr;?></span>
                </td>
            </tr>
            <tr>
                <td>Address: </td>
                <td>
                    <textarea name="address" rows="5" cols="40"><?php echo $address;?></textarea>
                    <span class = "error">* <?php echo $addressErr;?></span>
                </td>
            </tr>
            <tr>
                <td>Eircode:</td>
                <td>
                    <input type="text" name="eircode" value="<?php echo $eircode;?>" size="40">
                    <span class = "error">* <?php echo $eircodeErr;?></span>
                </td>
            </tr> 
            <tr>
                <td>Phone:</td>
                <td>
                    <input type="text" name="phone" value="<?php echo $phone;?>" size="40">
                    <span class = "error">* <?php echo $phoneErr;?></span>
                </td>
            </tr> 
            <tr>
                <td>You are a Business:</td>
                <td>
                    <input type="checkbox" name="is_business" value="<?php echo $business;?>">
                    <span class = "error">*</span>
                </td>
            </tr> 
        </table> 
        <p>
            <!-- Terms & Conditions -->
            <input type="checkbox" name="agree"
                <?php if(isset($_POST['agree']) && $_POST['agree']!="") echo "checked";?>
                >  Agree to our 
                <a href="https://www.wikihow.com/Plan-a-Party" target="_blank">
                    Term and Conditions
                </a>
            <span class = "error">* <?php echo $agreeErr;?></span>
        </p> 
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