<?php

include 'connectDB.php';
include 'validateText.php';

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
if (isset($_POST['oldpw']) && isset($_POST['pw1']) && isset($_POST['pw2'])) {
    $old = md5($_POST['oldpw']);
    $pw = md5($_POST['pw1']);
    $confirm = md5($_POST['pw2']);
}
// else die early
else {
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Input Parameters not specified</p>
        <a href="javascript:history.back()">Return to Profile</a>
        </div>');
}

// check if new passwords don't match
if ($pw != $confirm) {
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <h1>Oh no!</h1>
            <p>New Password does not match confirmation</p>
            <a href="javascript:history.back()">Return to Profile</a>
            </div>');
}

// check if old password matches that in DB
$sql = "SELECT * FROM Account WHERE uname='$uname' AND pword='$old';";
$result = mysqli_query($connection, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    // password matches
    $sql = "UPDATE Account SET pword='$pw' WHERE uname='$uname';";
    mysqli_query($connection, $sql);
}
mysqli_free_result($result);

// close connection to DB
mysqli_close($connection);

// redirect user
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>