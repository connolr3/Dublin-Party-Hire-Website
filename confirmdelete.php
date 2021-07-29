<!-- This is the confirmation page for a deleted equipment

This form is accessed through deleteequip.php bon successful deletion
This page redirects to admin.php, equipaction.php, deleteequip.php and logout.php and as a result index.php through the nav bar

-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Deleted</title>
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
    <a  href="equipaction.php">Back to Operations & Equipment</a>
    <a  href="logout.php">Logout</a>
  </div>

  <h4>Deleted Equipment </h4>

<!-- Php Script to delete a new employee -->

<?php

    $equippagelink= "equipaction.php";
    $deleteequiplink = "deleteequip.php";
    session_start();
    echo $_SESSION["deleted"];

    echo '<p><a href="'.$equippagelink.'">Take me back operations and equipment actions.</a></p>';
    echo '<p><a href="'.$deleteequiplink.'">Delete more equipment.</a></p>';

?>
</body>
</html>