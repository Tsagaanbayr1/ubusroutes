<?php
$hostname = 'localhost';
$username = 'iurtuuc1_ubus';
$password = 'iUrtuu224442';
$dbname = 'iurtuuc1_ubus';
$conn = mysqli_connect($hostname, $username, $password, $dbname) or
  die("Could not connect: " . mysqli_error($conn));
if ($conn->connect_error) {
  die("Database connection failed: " . $dbconnect->connect_error);
}
/* change character set to utf8 */
if (!$conn->set_charset("utf8")) {
  die("Could not use utf8: ");
} else {
  printf("Current character set: %s\n", $mysqli->character_set_name());
}
