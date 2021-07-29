<!-- This is the page that allows the admin to change drivers' passwords

They are redirected here from authentication.php when the correct admin password is inserted
They can go to admin.php, employeeaction.php, logout.php, index.php and if sucessfully inputted
the correct password, they can go to confirm_pass.php
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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
    <a class="active" href="admin.php">Admin Home</a>
    <a  href="employeeaction.php">Back to Employee Actions</a>
    <a  href="logout.php">Logout</a>
  </div>

  <h4>Select an employee to change their password</h4>
<!-- Php Script to determine if the correct password is chosen -->

<?php

    session_start();
    include("detail.php");

    $employee_id =  "";
    $password= "";
    $confirm_password = "";
    $passwordErr = "";
    $confirm_passwordErr ="";

    //function to test input
    // function to test and filter data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $employee_id = test_input($_POST['employee']);

    //deal with the password
    if (empty($_POST["psw"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["psw"]);
        $confirm_password = test_input($_POST["confirm_password"]);

        //make sure it matches the right format
        if (strpos($password, "'") !== FALSE) {
            $passwordErr = "NO APOSTROPHES, sorry";
            //make sure the confirm password field matches
        } elseif (!($password == $confirm_password)) {
            $confirm_passwordErr = "Does not match password";
        }

        //make sure the new password is not the same as the old one
        $q1 = "SELECT password FROM staff where employee_id = '$employee_id' AND password = '$password'";
            $result1 = $db->query($q1);
            $num_rows1= mysqli_num_rows($result1);
            if($num_rows1 >= 1)
            {
                $passwordErr = "The passwords are the same.";
            }
    }

    //update the password if validations are correct
    if($passwordErr =="" && $confirm_passwordErr =="")
    {
        $q2 = "UPDATE staff SET password = '$password' WHERE employee_id ='$employee_id'";
        $result1 = $db->query($q2);

        //redirect
        header("Location: confirm_pass.php");
    }

}


?>

<!-- Form -->
<div class="w3-container w3-content w3-padding-11" style="max-width:800px" id="new_password">
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="empl_id" id="empl_id">
        <table>
            <tr>
                <td>Employee:</td>
                <td>
                <select class="w3-input w3-border" name="employee" id="employee" style="width: 400px">
                <!--<php script to return all employees> -->
                <?php
                    include ("detail.php"); 
			              $q2 = "SELECT full_name, employee_id FROM staff WHERE position='driver'";
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
                <tr>
                <td>Password:</td>
                <td>
                    <input type="password" id="psw" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                    <span class = "error">* <?php echo $passwordErr;?></span>
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
            <!-- Display on page password message -->
        <div id="message">
            <h3>Password must contain the following:</h3>
            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
             <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
            <p id="number" class="invalid">A <b>number</b></p>
            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>	
        <p>
        <br>
<!-- Javascript to validate password as user writes them in -->
<script>
var myInput = document.getElementById("psw");
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



<!-- Javascript to confirm the form action -->
<script>
function ConfirmDelete()
{
  var x = confirm("Are you sure you want to change passwords?");
  if (x)
      return true;
  else
    return false;
}
</script>
<!-- Confirm if you want to change the passwords -->
<button class="w3-button w3-black" onclick="return ConfirmDelete();" type="submit" value="Change Password" name="actionchange" >Change Password</button>
</form>
</div>
</body>
</html>