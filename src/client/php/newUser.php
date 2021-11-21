<?php

include 'connectDB.php';

$connection = connectToDB();

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $uname = $_POST['username'];
  $fname = $_POST['first-name'];
  $lname = $_POST['last-name'];
  $email = $_POST['email'];
  $pword = $_POST['password'];
  $pfp = $_POST['profile-pic'];

  $sql = "SELECT uname, fname, lname, email, pword FROM Account;";
  $results = mysqli_query($connection, $sql);

  $unameResults = mysqli_query($connection, "SELECT uname FROM Account WHERE uname = '$uname';");
  $emailResults = mysqli_query($connection, "SELECT email FROM Account WHERE email = '$email';");

  // If username or email exists, do not create user
  if ((mysqli_num_rows($emailResults)>0) || (mysqli_num_rows($unameResults)>0)) {
    echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <h1>Oh no!</h1>
            <p>User already exists with this name and/or email</p>
            <a href="javascript:history.back()">Return to user signup screen</a>
          </div>';
  }
  // If username or email does not exist, add user info to database
  else {
    $pword = md5($pword);
    $sql = 'INSERT INTO Account (uname, email, fname, lname, pword, pfp) VALUES (?, ?, ?, ?, ?, ?);';
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('sssssb', $uname, $email, $fname, $lname, $pword, $pfp);
    $stmt->execute();

    //mysqli_query($connection, "INSERT INTO `Account` (`uname`, `email`, `fname`, `lname`, `pword`,`administrator`, `pfp`) VALUES ('".$uname."', '".$email."', '".$fname."', '".$lname."', '".md5($pword)."', `false`, '".$pfp."');");
    echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <p>An account for the user <b>'.$uname.'</b> has been created</p>
          </div>';
  }
  
}
// Handle GET requests
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>Ensure request method is POST, not GET</p>
          <a href="javascript:history.back()">Return to user signup screen</a>
        </div>';
}

mysqli_close($connection);
//mysqli_free_result($unameResults);
//mysqli_free_result($emailResults);

?>