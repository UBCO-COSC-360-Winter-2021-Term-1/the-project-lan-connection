<?php
/*
This file logs in a user and handles a range of scenarios inclduing invalid get requests,
non matching passwords, password doesn't match the username, etc.
*/

include 'connectDB.php';
include 'validateText.php';

// enable sessions
session_start();

// ensure using POST method
if (!$_SERVER["REQUEST_METHOD"] == "POST") {
  // not using POST
  die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Ensure request method is POST, not GET</p>
        <a href="javascript:history.back()">Return to login screen</a>
       </div>');
}

// check if input fields set
if (!isset($_POST['identifier']) || !isset($_POST['password'])) {
  // not all inputs set
  die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Ensure both email and password are entered</p>
        <a href="javascript:history.back()">Return to login screen</a>
       </div>');
}

// get input values
$id = validate($_POST['identifier']);
$pword = validate($_POST['password']);
$pword = md5($pword);

// connect to DB
$connection = connectToDB();

// make SQL request
$sql = "SELECT * FROM Account WHERE ((email='$id' OR uname='$id') AND pword='$pword' AND user_enabled=TRUE);";
$results = mysqli_query($connection, $sql);

// check if any results returned (ie. user exists in DB with that password)
if ($row = mysqli_fetch_assoc($results)) {
  // update session
  $_SESSION['signedin'] = $row['uname'];
  $_SESSION['LAST_ACTIVITY'] = time();
  // close connection
  mysqli_close($connection);
  mysqli_free_result($results);
  // redirect logged in user to home page
  header("Location: ../html/home.php");
}
else {
  // close connection
  mysqli_close($connection);
  mysqli_free_result($results);
  // notify user of unsuccessful login attempt
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>Username and/or password are invalid</p>
          <a href="javascript:history.back()">Return to login screen</a>
        </div>';
}

?>
