<?php

  session_start();

  include './connectDB.php';

  $connection = connectToDB();

  $pid = $_POST['pid'];

  $sql = "DELETE FROM Post WHERE pid = '$pid'";
  if (mysqli_query($connection, $sql)) {
    return "<p>Successful post deletion</p>";
  }
  else {
    return "<p>Something went wrong, post was not deleted</p>";
  }

?>