<?php

  session_start();

  $now = time();
  if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    session_unset();
    session_destroy();
    session_start();
  }

  $_SESSION['discard_after'] = $now + 1800;

  $user = $_GET['user'] ?? null;

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
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> 
    <script src="../js/likeSystem.js"></script>
    <script src="../js/bookmarkSystem.js"></script>
    <title>Bookmarks</title>
</head>
<body>
    <!--NAVIGATION BAR (done with bootstrap)-->
    <nav class="navbar navbar-expand-lg navbar-light navbar-static-top">
        <a class="navbar-brand" href="../html/home.php"><img src="../../../img/nav-logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto"></ul>
            <form class="form-inline nav-search my-2 my-lg-0" method="get" action="./searchResults.php">
                <input class="search-bar" type="search" name="search" placeholder="Search" aria-label="Search">
                <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
            </form>
            <!-- Login/Signup link / Profile/Logout links -->
            <?php 
                if (isset($_SESSION['signedin'])) {
                    echo "<a href='./profile.php' class='form-login'>My Profile</a>";
                    echo "<a href='../php/logout.php' class='form-login'>Logout</a>";
                }
                else {
                    echo "<a href='login.html' class='form-login'>Login / Sign Up</a>";
                }
            ?>
        </div>
    </nav>

  <div class="plain-background">
    <div class="container5">
      <p class="header1">Bookmarked Posts</p>

      <?php
        include '../php/connectDB.php';
        include '../php/validateText.php';
        include '../php/handleImg.php';
        include '../php/retrieveLikes.php';
        include '../php/bookmarkSystem.php';

        $connection = connectToDB();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
          
          $sql = "SELECT B.pid, A.uname, post_date, P.imageID, P.cat_title, post_body, A.imageID AS pfp
                  FROM POST P
                  INNER JOIN Account A ON A.uname=P.uname
                  INNER JOIN Category C ON P.cat_title=C.cat_title
                  INNER JOIN Bookmarks B ON B.pid = P.pid
                  LEFT OUTER JOIN Images I ON I.imageID=P.imageID
                  WHERE B.uname = '$user'
                  ORDER BY post_date ASC;";

          $results = mysqli_query($connection, $sql);
          $row_cnt = mysqli_num_rows($results);

          if ($row_cnt !=0) {
            while ($row = $results->fetch_assoc())
            {
              // Grab likes and dislikes for each post
              $pids = $row['pid'];
              $numLikes = getNumLikes($connection, $pids);
              $numDislikes = getNumDislikes($connection, $pids);
              $numComments = getNumComments($connection, $pids);

              $pfp = accessImgFromDB($connection, $row['pfp'], 'post');
              echo '<div class="popular-post">
                      <div class="post-status">
                        <img src='.$pfp.' alt="../../../img/pfp-placeholder.jpeg" class="pfp-small">
                        <a href="./profile.php?username='.$row['uname'].'" class="username">'.$row["uname"].'</a>
                        <p>'.$row["post_date"].'</p>
                      </div>
                      <div class="category">
                        <p>Posted to <a href="./category-page.php?page='.$row['cat_title'].'" class="post-category">'.$row["cat_title"].'</a></p>
                      </div>
                      <div class="post-text">
                        <p>'.$row["post_body"].'</p>
                      </div>';

              if ($row["imageID"] == null) {         
                echo '<div class="post-img">
                          <img class="hide-img" src="">
                        </div>
                        <div class="menu-bar">
                          <button type="submit" onclick="clickedLike(this)" class="like" data-value="'.$row['pid'].'" value="liked"><i class="far fa-thumbs-up"></i></button>
                          <label class="like-counter">'.$numLikes.'</label>

                          <button type="submit" onclick="clickedDislike(this)" class="dislike" data-value="'.$row['pid'].'" value="disliked"><i class="far fa-thumbs-down"></i></button>
                          <label class="dislike-counter">'.$numDislikes.'</label>

                          <a href="post.php?pids='.$row['pid'].'" class="comment"><i class="far fa-comment"></i></a>
                          <label class="comment-counter">'.$numComments.'</label>

                          <button type="submit" onclick="clickedBookmark(this)" class="bookmark" data-value="'.$row['pid'].'" value="liked"><i class="far fa-bookmark"></i></button>                          </div>
                      </div>';
              }
              else {
                echo '<div class="post-img">
                          <img class="" src="'.$row["imageID"].'">
                        </div>
                        <div class="menu-bar">
                          <button type="submit" onclick="clickedLike(this)" class="like" data-value="'.$row['pid'].'" value="liked"><i class="far fa-thumbs-up"></i></button>
                          <label class="like-counter">'.$numLikes.'</label>

                          <button type="submit" onclick="clickedDislike(this)" class="dislike" data-value="'.$row['pid'].'" value="disliked"><i class="far fa-thumbs-down"></i></button>
                          <label class="dislike-counter">'.$numDislikes.'</label>

                          <a href="post.php?pids='.$row['pid'].'" class="comment"><i class="far fa-comment"></i></a>
                          <label class="comment-counter">'.$numComments.'</label>

                          <button type="submit" onclick="clickedBookmark(this)" class="bookmark" data-value="'.$row['pid'].'" value="liked"><i class="far fa-bookmark"></i></button>                          </div>
                      </div>';
              }
            }
          }
          else {
            echo "<p>No posts to display!</p>";
          }
          
          
        }

      ?>

    </div>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>