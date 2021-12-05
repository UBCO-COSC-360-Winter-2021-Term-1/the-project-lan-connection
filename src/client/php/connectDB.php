<?php
/*
This function is called when a php file needs to connect to the database.
*/

function connectToDB() { 
  //DB config info
  $host = "localhost";
  $database = "db_10817245";
  $user = "10817245";
  $password = "10817245";

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