<?php
function displayNavBar($connection, $signedin, $pagecat) {
    include '../php/checkAdmin.php';

    $isAdmin = checkForAdmin($connection, $signedin);

    $html = '<nav class="navbar navbar-expand-lg navbar-light navbar-static-top">';
    $html = $html . '<a class="navbar-brand" href="../html/home.php"><img src="../../../img/nav-logo.png"></a>';
    $html = $html . '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">';
    $html = $html . '<span class="navbar-toggler-icon"></span>';
    $html = $html . '</button>';
    $html = $html . '<div class="collapse navbar-collapse" id="navbarSupportedContent">';
    // for category page
    if ($pagecat != null) {
        $html = $html . '<ul class="navbar-nav mr-auto">';
        $html = $html . '<li class="nav-item dropdown">';
        $html = $html . '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $html = $html . $pagecat. '</a><div class="dropdown-menu" aria-labelledby="navbarDropdown">';
        $categories = array('Mountain Biking', 'Hiking', 'Climbing', 'Snowboarding', 'Golf', 'Hockey');
        if (($key = array_search($pagecat, $categories)) !== false) {
            $categories[$key] == null;
        }
        for ($x = 0; $x < count($categories); $x++) {
            $html = $html . '<a class="dropdown-item" href="./category-page.php?page=' . $categories[$x] . '">' . $categories[$x] . '</a>';
        }
        $html = $html . '</div></li></ul>';
    }
    else {
        $html = $html . '<ul class="navbar-nav mr-auto"></ul>';
    }
    $html = $html . '<form class="form-inline nav-search my-2 my-lg-0" method="get" action="./searchResults.php">';
    $html = $html . '<input class="search-bar" type="search" name="search" placeholder="Search" aria-label="Search">';
    $html = $html . '<button class="search-button" type="submit"><i class="fa fa-search"></i></button>';
    $html = $html . '</form>';
    // Login/Signup link / Profile/Logout links / Admin link
    if ($isAdmin == true) {
      $html = $html . '<a href="./admin-portal.php" class="form-login">Admin Portal</a>';
    }
    if ($signedin != null) {
        $html = $html . "<a href='./profile.php?username=".$signedin."' class='form-login'>My Profile</a>";
        $html = $html . "<a href='../php/logout.php' class='form-login'>Logout</a>";
    } else {
        $html = $html . "<a href='login.html' class='form-login'>Login / Sign Up</a>";
    }
    $html = $html. '</div></nav>';
    return $html;
}
?>