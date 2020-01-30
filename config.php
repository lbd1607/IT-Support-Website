<?php
/*
 * Laura Davis
 * Database connection configuration
 */

 ob_start();
 session_start();

 $timezone = date_default_timezone_set("America/Chicago");

 $conn = mysqli_connect("localhost", "lbd", "password", "support_tickets");

 if(mysqli_connect_errno()) {
   echo "Failed to connect: " . mysqli_connect_errno();
 }

?>
