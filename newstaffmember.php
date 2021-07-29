<!-- This is the form to add a new staff member

Admin is redirected here from employeeaction.php when clicking on the add new member button

It redirects to the admin homepage, logout and employeeaction.php from the bav bar

-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Staff Member</title>
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
        <a class="active" href="admin.php">Admin Home</a>
        <a href="employeeaction.php">Back to Employee Actions</a>
        <a href="logout.php">Log Out</a> 
    </div>


<!-- Php Script to add a new employee -->
<?php

    session_start();
    include("detail.php");

    $admin_homelink = "admin.php";
    $emplpagelink = "employeeaction.php";

    $empl_name = $phone_number = $position = $user_password = $confirm_password = $on_shift ="";
    $nameErr = $phoneErr = $positionErr = $passwordErr = $confirm_passwordErr =  "";

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
        if(empty($_POST['empl_name'])){

            $nameErr = "Name cannot be empty";

        }else{
            $empl_name = test_input($_POST['empl_name']); 

       
         //make sure name contains only white space, apostrophes and letters
         //using the appropriate regex
         if(!preg_match("/^([a-zA-Z ']*)$/", $empl_name))
        {
            $nameErr = "Only letters, white space and apostrophes can be used";
        }
    }

    // make sure phone number is not empty
    if (empty($_POST["phone_number"])) {

        $phoneErr = "Telephone Contact is required";

    } else {
             $phone_number = test_input($_POST["phone_number"]);
             $phone_number = str_replace(' ', '', $phone_number);
             $phonelength = strlen($phone_number);

        // check if phone only contains numbers
        if (!preg_match("/^(['.$custom.'0-9_]*)$/i",$phone_number)) {
            $phoneErr = "Only Digits 0-9 can be input";
        } 

        elseif ($phonelength < 7) {
            $phoneErr = "Enter Valid Phone Number";
        }

        //check phone number isn't already there
        $q1 = "SELECT * FROM staff where phone_number = '$phone_number'";
            $result1 = $db->query($q1);
            $num_rows1= mysqli_num_rows($result1);
            if($num_rows1 >= 1)
            {
                $phoneErr = "There already exists an employee with this contact number.";
            }

        }

        //make sure position isn't empty
        if(empty($_POST['position'])){

            $positionErr = "Position cannot be empty";

        }else{
            $position = test_input($_POST['position']); 
            $position = strtolower($position);

         //make sure name contains only white space, apostrophes and letters
         //using the appropriate regex
         /* Old code from when admin could type in the position
         if(!preg_match("/^([a-zA-Z ']*)$/", $position))
        {
            $positionErr = "Only letters, white space and apostrophes can be used";
        }
        */
    }

    //deal with the password
    if (empty($_POST["psw"])) {
        $passwordErr = "Password is required (No Apostrophes)";
    } else {
        $user_password = test_input($_POST["psw"]);
        $confirm_password = test_input($_POST["confirm_password"]);

        if (strpos($user_password, "'") !== FALSE) {
            $passwordErr = "NO APOSTROPHES, sorry";
        } elseif (!($user_password == $confirm_password)) {
            $confirm_passwordErr = "Does not match password";
        }
    }

    //escape string to allow for apostrophes
    $empl_name = $db->real_escape_string($empl_name);

    // insert data into the table
     // If Validation Successful, Run MySQL query
     if ($nameErr == "" && $phoneErr == ""  && $passwordErr == "" && $confirm_passwordErr == "" && $empl_name != "") {
         
        $on_shift = "False";
        $q  = "INSERT INTO staff (";
        $q .= "full_name, phone_number, position, password, on_shift ";
        $q .= ") VALUES (";
        $q .= " '$empl_name', '$phone_number', '$position', '$user_password', '$on_shift')";
    
        $result = $db->query($q);


        echo "<p><b>Confirmed: </b>" .$empl_name. "  has been added as an employee.</p>";
        echo "<p><b>Their Role is: </b>" .$position."</p>";
        echo "<p><b>Their Phone Number is: </b>" .$phone_number."</p>";

        $empl_name = $phone_number = $position = $user_password = $confirm_password = "";
        $nameErr = $phoneErr = $positionErr = $passwordErr = $confirm_passwordErr =  "";
        echo '<p><a href="'.$admin_homelink.'">Take me back to admin.</a></p>';
        echo '<p><a href="'.$emplpagelink.'">Take me back to employee actions.</a></p>';
        
     }   

     
}


?>

  <!-- Form -->
<div class="w3-container w3-content w3-padding-11" style="max-width:800px" id="new_staff">
<h4>Use the Form Below to Add a New Staff Member</h4>
<p><span class="error">* required field</span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="new_employee" id="new_employee">
        <table>
            <tr>
                <td>Full Name: </td>
                <td>
                    <input type="text" name="empl_name" value="<?php echo $empl_name;?>" size="40">
                    <span class = "error">* <?php echo $nameErr;?></span>
                </td>
            </tr>
                <td>Phone Number:</td>
                <td>
                    <input type="text" name="phone_number" value="<?php echo $phone_number;?>" size="15">
                    <span class = "error">* <?php echo $phoneErr;?></span>
                </td>
            </tr> 
            <tr>
                <td>Position:</td>
                <td>
                <select class="w3-input w3-border" name="position" style="width: 500px">
                <span class = "error">* <?php echo $positionErr;?></span>
      
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
        <div id="message">
            <h3>Password must contain the following:</h3>
            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
             <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
            <p id="number" class="invalid">A <b>number</b></p>
            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>
        <p>
        <br>
        <p><input type="submit" value="Submit"> 
        <input type="reset" value="Reset"></p>
    </form>

    <div>

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



</body>
</html>