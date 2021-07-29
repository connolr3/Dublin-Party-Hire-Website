<?php

$host = "localhost";
$database = "stu33001_2021_group_3_db";
$user = "group_3";
$password = "ii6hi4Ho";

@ $db = mysqli_connect($host, $user, $password, $database);

$db->select_db($database);
if (mysqli_connect_errno())
{
echo 'Error connecting to the db.';
exit;
}


?>


