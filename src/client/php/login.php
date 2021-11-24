<?php

include 'connectDB.php';
include 'validateText.php';

// connect to DB
$connection = connectToDB();

// enable sessions
session_start();

// ensure using POST method
if (!$_SERVER["REQUEST_METHOD"] == "POST") {
  // not using POST
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>Ensure request method is POST, not GET</p>
          <a href="javascript:history.back()">Return to login screen</a>
        </div>';
  die();
}

// check if input fields set
if (!isset($_POST['email']) || !isset($_POST['password'])) {
  // not all inputs set
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>Ensure both email and password are inputted</p>
          <a href="javascript:history.back()">Return to login screen</a>
        </div>';
  die();
}

// get input values
$email = validate($_POST['email']);
$pword = validate($_POST['password']);
$pword = md5($pword);

// make SQL request
$sql = "SELECT * FROM Account WHERE (email='$email' AND pword='$pword');";
$results = mysqli_query($connection, $sql);

// check if any results returned (ie. user exists in DB with that password)
if ($row = mysqli_fetch_assoc($results)) {
  // update session
  $_SESSION['uname'] = $row['uname'];
  // redirect user to home page
  header("Location: ../html/home.php");
}
else {
  // notify user of unsuccessful login attempt
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
    <h1>Oh no!</h1>
    <p>Username and/or password are invalid</p>
    <a href="javascript:history.back()">Return to login screen</a>
    </div>';
}

// close connection
mysqli_close($connection);
mysqli_free_result($results);

?>
