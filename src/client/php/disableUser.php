<?php

include 'connectDB.php';

session_start();

// ensure using GET method
if (!$_SERVER["REQUEST_METHOD"] == "GET") {
    // not using POST
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Ensure request method is GET, not POST</p>
        <a href="javascript:history.back()">Return to Admin Portal</a>
       </div>');
}

// check if input fields set
if (!isset($_GET['uname'])) {
    // not all inputs set
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <h1>Oh no!</h1>
        <p>Ensure a user is specified</p>
        <a href="javascript:history.back()">Return to Admin Portal</a>
       </div>');
}

// get desired uname
$uname = $_GET['uname'];

// connect to DB
$connection = connectToDB();

// determine if user enabled or disabled
$sql = "SELECT * FROM Account WHERE (uname='$uname');";
$results = mysqli_query($connection, $sql);

if ($row = mysqli_fetch_assoc($results)) {
    $enabled = $row['user_enabled'];
}
mysqli_free_result($results);

// toggle user_enabled field in account table
if ($enabled == TRUE) {
    $sql = "UPDATE Account SET user_enabled=0 WHERE uname='$uname'";
}
else {
    $sql = "UPDATE Account SET user_enabled=1 WHERE uname='$uname'";
}
mysqli_query($connection, $sql);

// close connection to DB
mysqli_close($connection);

// redirect user back to admin page
header('Location: ' . $_SERVER['HTTP_REFERER']);
// header('Location: ../html/admin-portal.php');

?>