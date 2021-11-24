<?php

// enable sessions
session_start();

// check if session logged in
if (isset($_SESSION['signedin'])) {
    // log out logged in user
    $_SESSION['signedin'] = null;
    header("Location: ".$_SERVER['HTTP_REFERER']);
}
else {
    // redirect no session user to home page
    header("Location: ../html/home.php");
}

?>