<?php

  error_reporting(0);
  session_start();

  include './connectDB.php';

  $connection = connectToDB();

  $uname = $_SESSION['signedin'];
  $pid = $_POST['pid'];

  if (!isset($uname)) {
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>You must sign in to use this feature</p>
          <a href="../html/login.html">Login here</a><br>
          <a href="javascript:history.back()">Return to previous screen</a>
        </div>');
  }

  if (isset($_POST['action'])) {
    switch ($_POST['action']) {

      // Add bookmark to database
      case 'bookmarked':
        $sql = "INSERT INTO Bookmarks (uname, pid) VALUES ('$uname', '$pid')";
        mysqli_query($connection, $sql);
        mysqli_close($connection);  
        break;

      // Remove bookmark from database
      case 'unbookmarked':
        $sql = "DELETE FROM Bookmarks WHERE (uname = '$uname' AND pid = '$pid')";
        mysqli_query($connection, $sql);
        mysqli_close($connection); 
        break;
    }
  }

?>