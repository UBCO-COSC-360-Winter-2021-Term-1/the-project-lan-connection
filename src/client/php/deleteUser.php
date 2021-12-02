<?php

  session_start();

  include './connectDB.php';

  $connection = connectToDB();

  $uname = $_POST['uname'];

  $sql = "DELETE FROM Account WHERE uname = '$uname'";

  if (mysqli_query($connection, $sql)) {
    return "<p>Successful user deletion</p>";
  } else {
    return "<p>Something went wrong, post was not deleted</p>";
  }

?>