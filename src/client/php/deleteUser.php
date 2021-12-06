<?php

  session_start();

  include './connectDB.php';

  $connection = connectToDB();

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uname'])) {
    $uname = $_POST['uname'];
  }
  else if ($_SERVER["REQUEST_METHOD"] =="GET" && isset($_GET['uname'])) {
    $uname = $_GET['uname'];
  }
  else {
  die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Ensure input parameters are set correctly</p>
        <a href="javascript:history.back()">Return to login screen</a>
       </div>');
  }

  $sql = "DELETE FROM Account WHERE uname = '$uname'";

  if ($_SERVER["REQUEST_METHOD"] == "POST" && mysqli_query($connection, $sql)) {
    return "<p>Successful user deletion</p>";
  } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    return "<p>Something went wrong, post was not deleted</p>";
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header('Location: ../html/admin-portal.php');
  }

?>