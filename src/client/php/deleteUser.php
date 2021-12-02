<?php

  session_start();

  include './connectDB.php';

  $connection = connectToDB();

  $uname = $_POST['uname'];

  $sql = "DELETE FROM Account WHERE uname = '$uname'";

?>