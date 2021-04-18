<?php
  $hostname = 'localhost';
  $username = 'iurtuuc1_ubus';
  $password = 'iUrtuu224442';
  $dbname = 'iurtuuc1_ubus';
  $conn = mysqli_connect($hostname,$username,$password,$dbname) or
    die("Could not connect: " . mysql_error());
  if ($conn->connect_error) {
    die("Database connection failed: " . $dbconnect->connect_error);
  }
?>
