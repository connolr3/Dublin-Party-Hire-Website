<!-- 
newevent.php
Purpose: form to create a new event
Accessed from:  index.php/home  (in which case the email for the event is set to null until they login or register) 
                or 
                customeraccount.php (in which case the email is set already in a  session variable)
Sends user to: customers are sent to orderequipment.php, where they select equipment for the event just created

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






  <?php


   include ("detail.php");
   session_start();
$emptyerror = FALSE;#this will be made true if an error occurs, sql data is only submitted if error is false
$event_name = $location = $county=$start_date=$end_date=$start_time=$end_time="";
$event_nameER = $locationER = $countyER=$deliveryER=$start_dateER=$end_dateER=$start_timeER=$end_timeER="";












function test_input($data) {
  $data = trim($data); 
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
  }







  #test all the inputs
  if (empty($_POST["event_name"])) {
    $emptyerror = True;
    $event_nameER = "Field is Required";
  } else {
    $event_name = test_input($_POST["event_name"]);
  }

  if (empty($_POST["location"])){
    $emptyerror = true;
    $locationER = "Field is Required";
  }else{
    $location=test_input($_POST["location"]);
  }

  if (empty($_POST["county"])){
    $emptyerror = true;
    $countyER = "Field is Required";
  }else{
    $county=test_input($_POST["county"]);
  }



  if (empty($_POST["start_date"])){
    $emptyerror = true;
    $start_dateER = "Field is Required";
  }else{
    $start_date=test_input($_POST["start_date"]);
    $due_date =test_input($_POST["start_date"]);
  }

  if (empty($_POST["start_time"])){
    $emptyerror = true;
    $start_timeER = "Field is Required";
  }else{
    $start_time=test_input($_POST["start_time"]);
  }

  if (empty($_POST["end_date"])){
    $emptyerror = true;
    $end_dateER = "Field is Required";
  }
  else{
    $end_date=test_input($_POST["end_date"]);
  }

  if (empty($_POST["end_time"])){
    $emptyerror = true;
    $end_timeER = "Field is Required";
  }
  else{
    $end_time=test_input($_POST["end_time"]);
  }
if ($emptyerror==true){
  $deliveryER = "Field has reset";
}
  if ($emptyerror==FALSE){

  #it doesn't matter for the dates and times whether the customer has chosen delivery or collection
  #the dates and times will mean the the dates and times a customer will collect tand drop off their equipment if they choose collection.
   #the dates and times will mean the the dates and times a customer will have their equipment dropped off and collected by DPH  if they choose delivery.
   #the dates and times are all stored as start_date, start_time, end_date,end_time 
 #check start date is before end date
 if ($start_date>$end_date){
  $othererror=TRUE;
  $end_dateER="The party can't start before it begins! Please choose a date after ".$start_date;
}


#compute due date
$due_date = (new DateTime((new DateTime())->modify($start_date)->format('Y-m-d')))->modify('+5 days')->format('Y-m-d');
#if the start date is after the end date

if ($start_date>$end_date){
  $othererror=TRUE;
  $end_dateER="The party can't start before it begins! Please choose a date after ".$start_date;
}
#if end date is after the due date
if ($due_date<$end_date){
  $othererror=TRUE;
  $end_dateER="DPH allows up to 5 days for equipment to be returned";
}


if (($start_date=$end_date)and $end_time<$start_time){
  $othererror=TRUE;
  $end_timeER = "The party can't start before it begins! Please choose a time after ".$start_time;
}
if ($othererror==true){
  $deliveryER = "Field has reset";
}
#no other errors -> insert event into table
if ($othererror==FALSE){
  $event_name = str_replace( "'","\'",$event_name);
  $location = str_replace( "'","\'",$location);
  $delivery=test_input($_POST["delivery"]);
 
  $delivery = ($delivery == 'delivery') ? 1 : 0;#change delivery from string to boolean, as in sql tables
  #assign user email variable from session variable - may be null if customer is a ghost (not logged in/not existing customer)

  $cust_email = $_SESSION['user_email']; 
  
  if(is_null($_SESSION['user_email'])){
  $q  = "INSERT INTO events (";
        $q .= "event_name,cust_email,location,county,start_date,end_date,start_time,end_time,delivery_status";
        $q .= ") VALUES (";
        $q .= "'$event_name', null, '$location','$county','$start_date','$end_date','$start_time', '$end_time','$delivery')";
  }
    else {#else email is not null, so insert email in too
      $q  = "INSERT INTO events (";
        $q .= "event_name,cust_email,location,county,start_date,end_date,start_time,end_time,delivery_status";
        $q .= ") VALUES (";
        $q .= "'$event_name', '$cust_email', '$location','$county','$start_date','$end_date','$start_time', '$end_time','$delivery')";
      }

      $result = $db->query($q); 
       #Fetch the id of the event just created, and assign it to a new session variable 
        #for use of assigning equipment
        $eventidquery = "SELECT * FROM events WHERE event_id = (SELECT MAX(event_id) FROM events) and event_name = '".$event_name."'";
        $eventidresults = $db->query($eventidquery); 
        $row = mysqli_fetch_assoc($eventidresults);
        #store the following as session variables
        $_SESSION['event_id'] =$row['event_id'];
        $_SESSION['delivery'] =$row['delivery_status'];
        $_SESSION['start_date'] =$row['start_date'];
        $_SESSION['end_date'] =$row['end_date'];


 

       #>>>> send user to get equipmet for event just created
     header( 'Location: https://stu33001.scss.tcd.ie/group_3/orderequipment.php' ) ;
} 
  }
?>

















<h2 class="w3-wide w3-center">Register Event</h2>

<!-- Form for customer to register event-->
<p class="w3-opacity w3-center">Enter details below to register your event at DPH <br>Your event will <b> not be saved</b> until you confirm your invoice.<br></p>
   
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <br>
    <div style = "position:absolute; left:700px; top:180px; " class="styled-table">
    <span class="error">* Required</span><br><br>
    <label for="student_name">Name of Event:</label>
       <input type = "text" name = "event_name" id = "event_name" size = 30 value="<?php echo $event_name;?>">
       <span style="color:red" class="error">* <?php echo $event_nameER;?></span>
       <br><br>
       <label for="location">Address:</label>
       <input type = "text" name = "location" id = "location" size =20  value="<?php echo $location;?>">
       <span style="color:red" class="error"> *<?php echo $locationER;?></span>
       <br><br>
        <?PHP  
        include ("detail.php");
        session_start();
        #drop down select option for the counties
        echo '<label for="county">County: <b>'.$category.' </b></label>';
        echo "<select name='county',id='county'  >";
        $q  = "SELECT * FROM `delivery_charges` where chargetype ='delivery'";
        $result = $db->query($q);
        $num_results = mysqli_num_rows ($result);
        for ($i=1; $i <$num_results+1; $i++)
        {
          $row = mysqli_fetch_assoc($result);
          #make sure the selected option is the one last selected if an error occurs
          if ($row['county']==$county)
            echo "<option value='".$row['county']."'selected> ".$row['county']."</option>"; 
         else
             echo "<option value='".$row['county']."'> ".$row['county']."</option>"; 
        
        } echo '</select>';
        ?>
        <span style="color:red" class="error"> <?php echo $countyER;?></span>
        <br><br><br><br>
        <center>Dublin Party Hire offers a delivery service for events. <br>
        Please see our
        <a href="deliverycharges.php" target="_blank">delivery charges</a><br>
        Would you like to your equipment for your event delivered, or collect it yourself?
        <br><i>Please note setup is available for equipment that is delivered only <br></i>
        <?php
        if ($emptyerror=true or $othererror = true){
          echo "<span style='color:red' class='error'> Field has reset!</span><br>";
        }
        ?>
        <select name='delivery',id='delivery'>
        <?php
          #make sure the selected option is the one last selected if an error occurs
        if ($delivery == 'delivery' or $delivery == '1'or $delivery==true or $delivery == 1){
          echo "<option value='delivery'selected >Delivery</option>"; 
          echo "<option value='collection'>Collection</option>";
        }
        else{
          echo "<option value='delivery'>Delivery</option>"; 
          echo "<option value='collection'selected >Collection</option>";
        }
      

        ?>
      <?php
      $today = date("Y-m-d");
      ?>
      
        <span style="color:red" class="error"><?php echo $deliveryER;?></span>
        </select></center>
        <br><br><br>
        Please choose the date and time you would like to collect/have you equipment delivered.<br>
        Collection/Delivery<br>
        <input id="start_date" name="start_date" type="date" min="<?php echo $today;?>" max="2026-12-28" value="<?php echo $start_date;?>">
        <span style="color:red" class="error"> <?php echo $start_dateER;?></span><br>
        <input type = "time" name = "start_time" id = "start_time" size =20 value="<?php echo $start_time;?>">
        <span style="color:red" class="error"> <?php echo $start_timeER;?></span><br><br>
        Please choose the date and time you would like to return/have you equipment collected.<br>
        Drop-off/pick-up<br>
        <input id="end_date" name="end_date" type="date" min="<?php echo $today;?>"  max="2026-12-28" value="<?php echo $end_date;?>">
        <span style="color:red" class="error"><?php echo $end_dateER;?></span><br>
        <input type = "time" name = "end_time" id = "end_time" size =20 value="<?php echo $end_time;?>">
        <span style="color:red" class="error"><?php echo $end_timeER;?></span>
        <br><br><br><br>
        <input type = "submit" value = "Submit">
        <input type="reset" value = "Reset"> 
        </div>
    </form>
</body>



</html>


