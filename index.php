<?php
// start session and create an array to hold session variables
$_SESSION = array();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DublinParty Hire</title>

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
</head>


<body>

<!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
<div class="w3-bar w3-black w3-card">
    <a class="active" href="#home">Home</a>
    <a href="#customer">Customers</a>
    <a href="stafflogin.php">Staff</a>
    <?php 
      if (isset($_SESSION['user_email'])) {
        echo '<a href="logout.php">Log Out</a>';
      } else {
        echo '<a href="login.php">Log In</a>
        <a href="registration.php">Register</a>';
      }
    ?>
  </div>
  </div>

  <!-- Background image -->
<div style="background-image: url('party-header.jpg');
    background-size: cover; height:200px; padding-top:20px;">
</div>


    <!-- Section One : Customer Log in-->
   <div class="w3-container w3-content w3-padding-11" style="max-width:2000px" id="customer">
   <h1 class="w3-wide w3-center">Welcome to Dublin Party Hire<br><img src = "dph.jpg" ' width="150" height="150"></h1>
   
   <h2 class="w3-wide w3-center"><i>The party starts here!</i></h2>
 
  
   <div class="w3-container w3-content w3-center w3-padding-34" style="max-width:800px" id="customer">


   <a href = "newevent.php" target = "_self"> Register an event</a> today - or  <a href = "equipment.php" target = "blank"> browse our catalog!</a><br><br>
  <!--https://www.tentsandmarquees.com/our-marquees/traditional-pole-marquees/ -->
  <a href="equipment.php" target="blank">
  <img src = "marquee.jpg" ' width="500" height="300"><br>
  </a>
 


    <h2 class="w3-wide w3-center">Customers</h2>
    <p class=" w3-center"><i>Please Select to either Login or Register as a New Customer</i></p>
    <p class=" w3-center"><i>Our customers can book and view their events, and rent the necessary equipment from our catalogue</i></p>
    <button class="w3-button w3-black" onclick="window.location.href='login.php'">Login</button>
    <button class="w3-button w3-black" onclick="window.location.href='registration.php'">Sign up</button><br>
    <br> 
   
    
    <div class="w3-row w3-padding-32">
    
    
    


    <br>
    <br>
      <!-- SALE ITEMS DISPLAY img source: https://www.pinterest.ie/pin/411727590912228278/-->
      <img src = "sale.png" width="70" height="70" ><br>
    <?PHP
        include ("detail.php"); 
        session_start();
        $querysale  = "SELECT * from equipment where sale > 0";
      #  echo $querysale;
        $resultsale = $db->query($querysale);
        $num_results = mysqli_num_rows ($resultsale);
        for ($i=1; $i <$num_results+1; $i++)
        {
          $row = mysqli_fetch_assoc($resultsale);
          echo ($row[sale]*100)."%";
          echo " off our ".$row[product_name]." in ";
          echo $row[category]."!<br>";
         }
         ?>


</body>
</html>
