<?php
/*
This php file adds a users comment information to the database, and handles any exceptions along the way.
*/

  session_start();

  include 'connectDB.php';
  include 'handleImg.php';

  $connection = connectToDB();

  // Handle POST requests
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $commentText = $_POST['comment'];
    $uname = $_SESSION['signedin'];
    $pid = $_POST['pid'];

    if ($commentText == null) {
      echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
              <h1>Oh no!</h1>
              <p>Ensure you have entered text in order to submit a comment</p>
              <a href="javascript:history.back()">Return to previous screen</a>
            </div>';
    }
    else if (!isset($_SESSION['signedin'])) {
      echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
              <h1>Oh no!</h1>
              <p>You must be signed in to comment</p>
              <a href="../html/login.html">Login</a>
              <a href="javascript:history.back()">Return to previous screen</a>
            </div>';
    }
    else {
      date_default_timezone_set('America/Vancouver');
      $curDate = date('Y-m-d H:i:s');

      $sql = "INSERT INTO Comment (pid, comment_body, uname, comment_date, c_likes, c_dislikes) 
            VALUES (?, ?, ?, ?, 0, 0);";
      $stmt = $connection->prepare($sql);
      $stmt->bind_param("ssss", $pid, $commentText, $uname, $curDate);

      if ($stmt->execute()) {
        header('Location: ../html/home.php');
      }
      else {
        echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <h1>Oh no!</h1>
                <p>Something went wrong with your comment</p>
                <a href="javascript:history.back()">Return to previous screen</a>
              </div>';
      }
    }
  }
  else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <h1>Oh no!</h1>
            <p>Ensure request method is POST, not GET</p>
            <a href="javascript:history.back()">Return to login screen</a>
          </div>';
  }
  
?>