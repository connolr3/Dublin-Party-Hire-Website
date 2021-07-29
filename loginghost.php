<!-- 
    loginghost.php
    Purpose: allow customers who are registered already, but chose to create an event befire logging in to log in
    Accessed from: checkcustomer.php
    sends user to: invoice.php

    Note: could potentially merge this into login.php, and use if statements to determine where to send customer


-->


<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPH Equipment</title>

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

        <!-- styled table-->
        <style>
        .styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 600px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

.styled-table thead tr {
    background-color: #18e76e;
    color: #ffffff;
    text-align: left;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

.styled-table tbody tr.active-row {
    font-weight: bold;
    color: #009879;
}
</style>
</head>

<body>
<!-- Navigation Bar -->
<?php
  // Only display for customer side
  if (isset($_SESSION['user_email'])) {
    echo '<div class="topnav">
      <a href="customeraccount.php">Customer Home</a>
      <a href="newevent.php">Register Event</a>
      <a href="pastinvoices.php">View Past Orders</a>
      <a href="logout.php">Log Out</a>
    </div>';
  } else {

    echo '<div class="topnav">
    <a href="index.php"> Home</a>
  </div>';



  }

?>

  <h4>Login to complete your order</h4>

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

        //Validations Complete -> Sent to invoice Page
        if ($emailErr == "" && $passwordErr == "" && $matchingErr == "" && $email != "" && $user_password != ""){

            $_SESSION['user_email'] = $email;




             #update events, set the email to the newly registed customers email.
             $updatequery = "UPDATE events SET cust_email = '".$email."'
             WHERE event_id = ".$_SESSION['event_id'];
            $result = $db->query($updatequery);


            echo '<script language="javascript">	

            document.location.replace("invoice.php");
        
            </script>';

        }

    ?>
<div class="w3-container w3-content w3-padding-11" style="max-width:2000px">
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