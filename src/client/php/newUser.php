<?php

session_start();

include 'connectDB.php';
include 'validateText.php';
include 'handleImg.php';

$connection = connectToDB();

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $uname = validate($_POST['username']);
  $fname = validate($_POST['first-name']);
  $lname = validate($_POST['last-name']);
  $email = validate($_POST['email']);
  $pword = validate($_POST['password']);
  // $fileName = $_FILES['file']['name'];

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
    $sql1 = 'INSERT INTO Account (uname, email, fname, lname, pword) VALUES (?, ?, ?, ?, ?);';
    $stmt1 = $connection->prepare($sql1);
    $stmt1->bind_param('sssss', $uname, $email, $fname, $lname, $pword);
    $stmt1->execute();

    // Image upload to database
    //uploadImgToDB($connection, $fileName, $uname);

    $_SESSION['signedin'] = $uname;
    header('Location: ../html/home.php');
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
mysqli_free_result($unameResults);
mysqli_free_result($emailResults);

?>