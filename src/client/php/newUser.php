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
$sql1 = "SELECT * FROM users WHERE username = '$uname' OR email = '$email';";
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

// insert new user into DB
$sql2 = "INSERT INTO users (username, firstName, lastName, email, password) VALUES ('$uname', '$fname', '$lname', '$email', '$pword')";
mysqli_query($connection, $sql2);

/* // get profile photo
if (isset($_FILES["ppic"]["name"])) {
  // initialize image variables
  $uploadOk = 1;
  // Check file size
  if ($_FILES["ppic"]["size"] > 100000) {
    $uploadOk = 0;
  }
  // Check file type
  $imageFileType = strtolower(pathinfo(basename($_FILES["ppic"]["name"]), PATHINFO_EXTENSION));
  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif") {
    $uploadOk = 0;
  }
  // Check if upload ok 
  if ($uploadOk != 0) {
    $ppic = basename($_FILES["userImage"]["name"]);
  }
}
// check if image upload didn't work
if ($uploadOk == 0) {
  // use generic picture as profile pic instead
  $ppic = "../../../img/default_profile_picture";
}

// upload image
$imagedata = file_get_contents($ppic);
$sql3 = "UPDATE account SET (pfp = ?) WHERE (username = '$uname');";
$stmt = mysqli_stmt_init($connection);
mysqli_stmt_prepare($stmt, $sql3);
$null = NULL;
mysqli_stmt_bind_param($stmt, "isb", $userID, $imageFileType, $null);
mysqli_stmt_send_long_data($stmt, 2, $imagedata);
$result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
mysqli_stmt_close($stmt); */


// close connection to DB
mysqli_free_result($results);
mysqli_close($connection);

// update signin state variable
$_SESSION['signedin'] = $uname;

// redirect signed in user to home page
header('Location: ../html/home.php');




// // Handle POST requests
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   $uname = validate($_POST['username']);
//   $fname = validate($_POST['first-name']);
//   $lname = validate($_POST['last-name']);
//   $email = validate($_POST['email']);
//   $pword = validate($_POST['password']);
//   // $fileName = $_FILES['file']['name'];

//   $sql = "SELECT uname, fname, lname, email, pword FROM Account;";
//   $results = mysqli_query($connection, $sql);

//   $unameResults = mysqli_query($connection, "SELECT uname FROM Account WHERE uname = '$uname';");
//   $emailResults = mysqli_query($connection, "SELECT email FROM Account WHERE email = '$email';");

//   // If username or email exists, do not create user
//   if ((mysqli_num_rows($emailResults)>0) || (mysqli_num_rows($unameResults)>0)) {
//     echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
//             <h1>Oh no!</h1>
//             <p>User already exists with this name and/or email</p>
//             <a href="javascript:history.back()">Return to user signup screen</a>
//           </div>';
//   }
//   // If username or email does not exist, add user info to database
//   else {
//     $pword = md5($pword);
//     $sql1 = 'INSERT INTO Account (uname, email, fname, lname, pword) VALUES (?, ?, ?, ?, ?);';
//     $stmt1 = $connection->prepare($sql1);
//     $stmt1->bind_param('sssss', $uname, $email, $fname, $lname, $pword);
//     $stmt1->execute();

//     // Image upload to database
//     //uploadImgToDB($connection, $fileName, $uname);

//     $_SESSION['signedin'] = $uname;
//     header('Location: ../html/home.php');
//   }
  
// }
// // Handle GET requests
// else if ($_SERVER["REQUEST_METHOD"] == "GET") {
//   echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
//           <h1>Oh no!</h1>
//           <p>Ensure request method is POST, not GET</p>
//           <a href="javascript:history.back()">Return to user signup screen</a>
//         </div>';
// }

// mysqli_close($connection);
// mysqli_free_result($unameResults);
// mysqli_free_result($emailResults);

?>