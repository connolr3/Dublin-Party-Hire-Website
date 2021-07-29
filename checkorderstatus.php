<!-- 
checkorderstatus.php
Purpose: form to allow customer to view logged times made by staff (when they have loaded van,and left)
accessed from: pastorder.php
-->
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
    min-width: 400px;
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

<!-- Navigation Bar code from WS3 Schools-->
<div class="topnav">
<div class="w3-bar w3-black w3-card">
    <a href="customeraccount.php">Customers Home</a>
    <a href="newevent.php">Register Event</a>    
    <a href="pastorders.php">View Past Orders</a>
    <a href="logout.php">Log Out</a>
</div>
  </div>
  
 

  <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    
    <h4 class="w3-wide w3-center">Check status</h4>
    <p class="w3-opacity w3-center"><i>Please see updates below<br>
  

<?php
#query event_hours to get relevant details to display to customer
        session_start();
        include ("detail.php");
        $q= "SELECT * from event_hours INNER JOIN staff ON staff.employee_id=event_hours.staff_id where event_id= ".$_POST["event"];
        $result = $db->query($q);
        $num_results = mysqli_num_rows ($result);
        #the shift has not been made

          if($num_results==0){
             echo 'Event not underway - check back soon!';
          }
          else
          {
          for($i=0; $i <$num_results; $i++)
              {
                    $row = mysqli_fetch_assoc($result);
                    echo "<table class='styled-table'>";
                    echo "Staff member: ".$row['full_name']." (you're in good hands!)";
                      
                    
                    #display van loading time
                    if(!is_null($row['vans_loading']))
                          {
                          echo " <tr>
                          <td><b>Van loaded at: </b></td>
                          <td>". $row['vans_loading']."</td>
                          </tr>";
                          }           
                          else {
                            echo " <tr>
                            <td><b>Van has not been loaded yet </b></td>
                            <td>Check back soon!</td>
                            </tr>";               
                          }      

                          #display vans enroute time
                          if(!is_null($row['van_enroute']))
                          {
                          echo " <tr>
                          <td><b>Van left at: </b></td>
                          <td>". $row['van_enroute']."</td>
                          </tr>";
                          }           
                          else {
                            echo " <tr>
                            <td><b>Van hasn't left yet </b></td>
                            <td>Check back soon!</td>
                            </tr>";               
                          }      
                    
              }
          }         
?> 















</div>
</body>
</html>