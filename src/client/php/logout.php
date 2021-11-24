<?php

// enable sessions
session_start();

// check if session logged in
if (isset($_SESSION['uname'])) {
    // log out logged in user
    $_SESSION['uname'] = null;
    header("Location: ".$_SERVER['HTTP_REFERER']);
}
else {
    // redirect no session user to home page
    header("Location: ../html/home.php");
}

?>