<?php

include 'connectDB.php';
include 'validateText.php';
include 'handleImg.php';

session_start();

// get currently signed in user
$uname = $_SESSION['signedin'] ?? null;

// connect to DB
$connection = connectToDB();

// ensure using POST method
if (!$_SERVER["REQUEST_METHOD"] == "POST") {
    // not using POST
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Ensure request method is POST, not GET</p>
        <a href="javascript:history.back()">Return to Profile</a>
       </div>');
}

// validate data existence for change password
if (isset($_POST['pw'])) {
    $pw = md5($_POST['pw']);
}
else {
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Password not specified</p>
        <a href="javascript:history.back()">Return to Profile</a>
        </div>');
}

// check if password matches that in DB
$sql = "SELECT * FROM Account WHERE uname='$uname' AND pword='$pw';";
$result = mysqli_query($connection, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    // password matches
    uploadImgToDB($connection, $_FILES["ppic"]["name"], $uname, "profile");
}
else {
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Password not valid</p>
        <a href="javascript:history.back()">Return to Profile</a>
        </div>');
}
mysqli_free_result($result);

// close connection to DB
mysqli_close($connection);

// redirect user
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>