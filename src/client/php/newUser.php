<?php

include 'connectDB.php';
include 'validateText.php';
include 'handleImg.php';

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

// ensure required input fields all set
if (!isset($_POST['username']) || !isset($_POST['first-name']) || !isset($_POST['last-name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
  // not all inputs set
  die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Ensure all fields (excluding Last Name) are entered</p>
        <a href="javascript:history.back()">Return to login screen</a>
       </div>');
}

// get trimmed values of input fields
$uname = validate($_POST['username']);
$fname = validate($_POST['first-name']);
$lname = validate($_POST['last-name']);
$email = validate($_POST['email']);
$pword = validate($_POST['password']);
$pword = md5($pword);

// connect to DB
$connection = connectToDB();

// make SQL request for checking if username/email already in DB
$sql1 = "SELECT * FROM Account WHERE (uname = '$uname' OR email = '$email');";
$results = mysqli_query($connection, $sql1);

// dont add new user if username/email already in DB
if ($row = mysqli_fetch_assoc($results)) {
  // close connection to DB
  mysqli_free_result($results);
  mysqli_close($connection);
  // display error message
  die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>A user already exists with that username or email</p>
        <a href="javascript:history.back()">Return to login screen</a>
       </div>');
}

// release results 
mysqli_free_result($results);

// insert new user into DB
$sql2 = "INSERT INTO Account (uname, fname, lname, email, pword) 
         VALUES ('$uname', '$fname', '$lname', '$email', '$pword')";
mysqli_query($connection, $sql2);

// upload profile picture to DB
uploadImgToDB($connection, $_FILES["ppic"]["name"], $uname, "profile");

// close connection to DB
mysqli_close($connection);

// update signin state variable
$_SESSION['signedin'] = $uname;

// redirect signed in user to home page
header('Location: ../html/home.php');

?>