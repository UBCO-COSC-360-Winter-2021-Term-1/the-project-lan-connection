<?php
/*
This page is naviagtable by a non signed in user.
This page may be displayed when a user clicks on their own profile link in the nav bar or the
settings link on the home page. On a users own profile, they may change their profile picture and several other actions.
It is also displayed when a user clicks on another users username on a post; in this case it will
navigate to that users profile and display all of their posts.
*/

session_start();

$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
  session_unset();
  session_destroy();
  session_start();
}

$_SESSION['discard_after'] = $now + 1800;

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
  <link rel="stylesheet" href="../css/profile.css">
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/home.css">
  <link rel="stylesheet" href="../css/post.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="../js/likeSystem.js"></script>
  <script src="../js/sortPosts.js"></script>
  <script src="../js/bookmarkSystem.js"></script>
  <title>My Profile</title>
</head>

<body>
  <!--NAVIGATION BAR (done with bootstrap)-->
  <?php
  include '../php/navBar.php';
  include '../php/connectDB.php';
  $connection = connectToDB();

  echo displayNavBar($connection, $_SESSION['signedin'] ?? null, null); 
  ?>

  <div class="plain-background-2">
    <div class="container">
      <div class="row">

        <div class="left-col">
          <div class="filter">
            <h1>Filter by:</h1>
            <div>
              <input type="submit" id="1" onclick="changeColor(this.id)" class="special-button classy" name="newest" value="Newest">
              <input type="submit" id="2" onclick="changeColor(this.id)" class="special-button" name="likes" value="Likes">
              <input type="submit" id="3" onclick="changeColor(this.id)" class="special-button" name="dislikes" value="Dislikes">
            </div>
          </div>

          <div class="posts">
            <?php
            include '../php/handleImg.php';
            include '../php/retrieveLikes.php';
            include '../php/displayPost.php';

            $uname = $_SESSION['signedin'] ?? null;
            $userProfile = $_GET['username'] ?? null;

            $newestQuery = $_GET['newest'] ?? null;
            $likesQuery = $_GET['likes'] ?? null;
            $dislikesQuery = $_GET['dislikes'] ?? null;


            if (isset($userProfile)) {

              $sql = "SELECT P.pid, fname, lname, A.uname, post_date, P.imageID, P.cat_title, post_body, A.imageID AS pfp
                                    FROM POST P
                                    INNER JOIN Account A ON A.uname=P.uname
                                    INNER JOIN Category C ON P.cat_title=C.cat_title
                                    LEFT OUTER JOIN Images I ON I.imageID=P.imageID
                                    WHERE A.uname = '$userProfile'
                                    ORDER BY post_date DESC";

              $result = mysqli_query($connection, $sql);
              $row_cnt = mysqli_num_rows($result);

              $query = mysqli_query($connection, "SELECT uname, fname, lname, A.imageId as pfp FROM Account A LEFT OUTER JOIN Images I ON I.imageID=A.imageID WHERE uname = '$userProfile'");
              $result2 = mysqli_fetch_array($query);

              $fname = $result2['fname'];
              $lname = $result2['lname'];
              $pfp2 = accessImgFromDB($connection, $result2['pfp'], 'account');
            } else {
              $sql = "SELECT P.pid, fname, lname, A.uname, post_date, P.imageID, P.cat_title, post_body, A.imageID AS pfp
                                    FROM POST P
                                    INNER JOIN Account A ON A.uname=P.uname
                                    INNER JOIN Category C ON P.cat_title=C.cat_title
                                    LEFT OUTER JOIN Images I ON I.imageID=P.imageID
                                    WHERE A.uname = '$uname'
                                    ORDER BY post_date DESC";

              $result = mysqli_query($connection, $sql);
              $row_cnt = mysqli_num_rows($result);

              $query = mysqli_query($connection, "SELECT uname, fname, lname, A.imageId as pfp FROM Account A LEFT OUTER JOIN Images I ON I.imageID=A.imageID WHERE uname = '$uname'");
              $result2 = mysqli_fetch_array($query);

              $fname2 = $result2['fname'];
              $lname2 = $result2['lname'];
              $pfp = accessImgFromDB($connection, $result2['pfp'], 'account');
            }

            if ($row_cnt = 0) {
              echo 'No posts to display!';
            }

            while ($row = mysqli_fetch_array($result)) {
              echo displayPost2($connection, $row['pid'], $_SESSION['signedin'] ?? null);
            } // End of while loop
            ?>
          </div>
        </div>

        <div class="right-col">
          <div class="sq1">
            <div class="profile-header">
              <?php
              if (isset($userProfile)) {
                echo '<img src="' . $pfp2 . '">';
              } else {
                echo '<img src="' . $pfp . '">';
              }
              ?>
              <div>
                <?php

                if (isset($userProfile)) {
                  echo '<p class="user-name">' . $fname . ' ' . $lname . '</p>';
                  echo '<p>' . $userProfile . '</p>';
                } else {
                  echo '<p class="user-name">' . $fname2 . ' ' . $lname2 . '</p>';
                  echo '<p>' . $uname . '</p>';
                }
                ?>
              </div>
            </div>
            <?php
            if (isset($_SESSION['signedin']) && !isset($userProfile)) {
              echo '<button>Change profile picture</button>';
            }
            ?>
            <div class="links">
              <?php
              if (isset($_SESSION['signedin']) && !isset($userProfile)) {
                echo '<a href="./bookmarks.php?user=' . $_SESSION['signedin'] . '"><i class="fa fa-bookmark"></i>Bookmarks</a>
                      <a href="#"><i class="fa fa-chart-line"></i>Activity Monitor</a>
                      <a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>';
              }
              ?>
            </div>
          </div>

          <!-- If navigated to the profile page by the "My Profile" link -->
          <?php if (!isset($userProfile)) : ?>
            <form class="create-post" name="form" method="post" action="../php/createPost.php" enctype="multipart/form-data">
              <div class="post-text">
                <textarea name="post_body" placeholder="Create a post" rows="5"></textarea>
                <br>
                <input type="text" name="post_category" placeholder="Category" list="category-selection" aria-label="Category">
                <datalist id="category-selection">
                  <option>Mountain Biking</option>
                  <option>Hiking</option>
                  <option>Climbing</option>
                  <option>Snowboarding</option>
                  <option>Golf</option>
                  <option>Hockey</option>
                </datalist>
              </div>
              <div class="menu-bar">
                <input type="submit" class="form-post" value="Post">
              </div>
            </form>
          <?php else : ?>
          <?php endif; ?>


        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>