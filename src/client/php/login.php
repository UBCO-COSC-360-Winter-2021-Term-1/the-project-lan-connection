<?php

include 'connectDB.php';
include 'validateText.php';

$connection = connectToDB();

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $email = validate($_POST['email']);
  $pword = validate($_POST['password']);
  $pword = md5($pword);

  $sql = "SELECT uname, email, pword FROM Account;";
  $results = mysqli_query($connection, $sql);

  $match = false;
  while ($row = mysqli_fetch_assoc($results))
  {
    // Check for matching username and password
    if(($row['email'] == $email) && ($row['pword'] == $pword)) {
      echo '<div style="display:flex; justify-content:center; align-items:center;"><p>The user <b>'.$row['uname'].'</b> has a valid account</p>';
      $match = true;
    }
    else {
      continue;
    }
  }
  // Display a message to user on unsuccessful login attempt
  if ($match == false) {
    echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <h1>Oh no!</h1>
            <p>Username and/or password are invalid</p>
            <a href="javascript:history.back()">Return to login screen</a>
          </div>';
  }

}
// Handle GET requests
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>Ensure request method is POST, not GET</p>
          <a href="javascript:history.back()">Return to login screen</a>
        </div>';
}

mysqli_close($connection);
mysqli_free_result($results);

?>