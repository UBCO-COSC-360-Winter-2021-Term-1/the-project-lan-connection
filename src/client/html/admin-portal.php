<?php

include '../php/navBar.php';
include '../php/connectDB.php';
include '../php/validateText.php';
include '../php/handleImg.php';
// include '../php/retrieveLikes.php';
// include '../php/displayPost.php';

session_start();

$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['discard_after'] = $now + 1800;

$admin = $_SESSION['signedin'];

// connect to DB
$connection = connectToDB();

// check if signed in user is an admin
$sql = "SELECT * FROM Account WHERE uname='$admin' AND administrator=true;";
if ($results = mysqli_query($connection, $sql)) {
    // signed in user is an admin -> continue displaying page
} else {
    // signed in user not admin, redirect to home
    die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
    <h1>Oh no!</h1>
    <p>Signed in user is not an administrator</p>
    <a href="home.php">Return to Home page</a></div>');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/brands.min.css" integrity="sha512-sVSECYdnRMezwuq5uAjKQJEcu2wybeAPjU4VJQ9pCRcCY4pIpIw4YMHIOQ0CypfwHRvdSPbH++dA3O4Hihm/LQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/fontawesome.min.css" integrity="sha512-P9vJUXK+LyvAzj8otTOKzdfF1F3UYVl13+F8Fof8/2QNb8Twd6Vb+VD52I7+87tex9UXxnzPgWA3rH96RExA7A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/admin-portal.css">
    <link rel="stylesheet" href="../css/nav.css">
    <title>Admin Portal</title>
</head>

<body>
    <?php
    echo displayNavBar($connection, $_SESSION['signedin'] ?? null, null);
    ?>
    <!-- Display page content -->
    <div class="plain-background">
        <div id="container">
            <div id="logo">
                <a href="./home.php"><img src="../../../img/login-logo.png"></a>
            </div>
            <div id="search-user-container" class="form">
                <!-- Form to search for users -->
                <form method="get" action="admin-portal.php">
                    <legend>Admin Portal</legend>
                    <hr>
                    <p>
                        <label>Search for User</label>
                        <br>
                        <input type="text" class="textbox" name="userSearch" placeholder="Username or email of desired user" required>
                        <input class="form-submit" type="submit" value="Search">
                    </p>
                </form>
                <!-- display results of search -->
                <?php
                // check if user already submitted user search form
                if (isset($_GET['userSearch'])) {
                    $keyword = validate($_GET['userSearch'] ?? null);
                    $keyword = '%' . $keyword . '%';
                    // display search keyword
                    echo '<p>Seeing results for: <b>' . $_GET['userSearch'] . '</b></p>';
                    // display top of table
                    echo '<table id="users-results"><tbody><th></th><th>Full Name</th><th>Username</th><th>Email</th><th>Profile Page</th></tr>';
                    // get results
                    $sql = "SELECT DISTINCT A.uname, A.fname, A.lname, A.email, A.user_enabled, A.imageID FROM Account AS A INNER JOIN Post AS P ON P.uname=A.uname WHERE (A.uname LIKE '$keyword' OR email LIKE '$keyword' OR post_body LIKE '$keyword');";
                    if ($result = mysqli_query($connection, $sql)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // display row of table
                            echo '<tr><td><img src=' . accessImgFromDB($connection, $row['imageID'], 'image') . ' class="pfp-admin"></td>';
                            echo '<td>' . $row['fname'] . ' ' . $row['lname'] . '</td>';
                            echo '<td>' . $row['uname'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo "<td><a href='./profile.php?username=" . $row['uname'] . "'>" . $row['fname'] . "'s Profile</a></td>";
                            echo '<td><a href="../php/disableUser.php?uname=' . $row['uname'] . '">' . (($row['user_enabled'] == TRUE) ? ('Disable') : ('Enable')) . ' ' . $row['fname'] . '</a></td></tr>';
                        }
                        mysqli_free_result($result);
                    }
                    // display bottom of table
                    echo '</tbody></table>';
                } else {
                    echo '<p>Search for users in search bar above</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

<?php
// close connection to DB
mysqli_close($connection);
?>