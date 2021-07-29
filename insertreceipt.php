
<!--
 insertreceipt.php
  purpose: logs the order on the date at which it was made, also sends a thank you email to customer
  accessed from: invoice.php
  sends user to: thankyouevent.php
  -->




<?php
 session_start();
 include ("detail.php");

#each event and it's total price are inputted into receipts with the date of order,
#for the purposes of this assignment, receipts included the order date, not purchase date
$receipt = "INSERT INTO receipts (event_id,	event_cost,	purchase_date,vat_charged,delivery_charged) VALUES (".$_SESSION['event_id'].",".$_SESSION['cost'].",'".date("Y-m-d")."',".$_SESSION['vat'].",".$_SESSION['delivery'].")";
$result = $db->query($receipt);

header( 'Location: https://stu33001.scss.tcd.ie/group_3/thankyouevent.php' ) ; 


$message = "Your event has been recorded at Dublin Party Hire!";
$headers = "From:DPH";

$mailsend = mail($_SESSION['user_email'], 'New Event at DPH!',$message,$headers);
?>