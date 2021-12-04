<?php
/*
This function is called when a php file needs to connect to the database.
*/

function connectToDB() { 
  //DB config info
  $host = "localhost";
  $database = "lan-connection";
  $user = "LANuser";
  $password = "sk11ng@b1gwh1t3";

  $connection = mysqli_connect($host, $user, $password, $database);
  $error = mysqli_connect_error();

  if($error != null) {
    $output = "<p>Unable to connect to database!</p>";
    exit($output);
  }
  else {
    return $connection;
  }
  
}


?>