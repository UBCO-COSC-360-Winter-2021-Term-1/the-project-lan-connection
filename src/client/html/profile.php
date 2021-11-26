<?php

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../js/activity.js"></script>
    <title>My Profile</title>
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
    
    <div class="plain-background-2">
        <div class="container">
            <div class="row">

                <div class="left-col">
                    <div class="filter">
                        <h1>Filter by:</h1>
                        <div>
                            <button>Newest</button>
                            <button>Upvotes</button>
                            <button>Downvotes</button>
                        </div>
                    </div>

                    <div class="posts">
                        <?php
                          include '../php/connectDB.php';
                      
                          $connection = connectToDB();

                          $uname = $_SESSION['signedin'];
    
                          $sql = "SELECT P.pid, fname, lname, A.uname, post_date, post_pic, P.cat_title, post_body, p_likes, p_dislikes, A.pfp 
                                  FROM POST P
                                  INNER JOIN Account A ON A.uname=P.uname
                                  INNER JOIN Category C ON P.cat_title=C.cat_title
                                  WHERE A.uname = '$uname'
                                  ORDER BY post_date DESC";
                                  
                          $results = mysqli_query($connection, $sql);
                          $row_cnt = mysqli_num_rows($results);

                          $result = $results->fetch_assoc();
    
                          if ($row_cnt !=0) {

                            $fname = $result['fname'];
                            $lname = $result['lname'];

                            while ($row = $results->fetch_assoc()) {
                              echo '<div class="popular-post">
                                      <div class="post-status">
                                        <img src='.$row["pfp"].' alt="../../../img/pfp-placeholder.jpeg" class="pfp-small">
                                        <a href="./profile.php" class="username">'.$row["uname"].'</a>
                                        <p>'.$row["post_date"].'</p>
                                      </div>
                                      <div class="category">
                                        <p>Posted to <a href="#" class="post-category">'.$row["cat_title"].'</a></p>
                                      </div>
                                      <div class="post-text">
                                        <p>'.$row["post_body"].'</p>
                                      </div>';

                                      if ($row["post_pic"] == null) {
                                        echo '<div class="post-img">
                                                <img class="hide-img" src="">
                                              </div>
                                              <div class="menu-bar">
                                                <button class="like"><i class="fas fa-heart"></i></button>
                                                <label class="like-counter">'.$row["p_likes"].'</label>

                                                <button class="dislike"><i class="far fa-heart-broken"></i></button>
                                                <label class="dislike-counter">'.$row["p_dislikes"].'</label>

                                                <button class="comment"><i class="fas fa-comment"></i></button>
                                                <label class="comment-counter">3</label>

                                                <button class="bookmark"><i class="fa fa-bookmark"></i></button>
                                              </div>
                                            </div>
                                            <a class="pop-post-comment" href="post.php?pids='.$row['pid'].'">Comments</a>';
                                      }
                                      else {
                                        echo '<div class="post-img">
                                                  <img class="" src="'.$row["post_pic"].'">
                                                </div>
                                                <div class="menu-bar">
                                                  <button class="like"><i class="fas fa-heart"></i></button>
                                                  <label class="like-counter">'.$row["p_likes"].'</label>
            
                                                  <button class="dislike"><i class="far fa-heart-broken"></i></button>
                                                  <label class="dislike-counter">'.$row["p_dislikes"].'</label>
            
                                                  <button class="comment"><i class="fas fa-comment"></i></button>
                                                  <label class="comment-counter"></label>
            
                                                  <button class="bookmark"><i class="fa fa-bookmark"></i></button>
                                                </div>
                                              </div>
                                              <a class="pop-post-comment" href="post.php?pids='.$row['pid'].'">Comments</a>';
                                      }
                            }
                          }
                          else {
                            echo 'No posts to display!';
                          }

                        ?>
                  </div>
                </div>

                <div class="right-col">
                    <div class="sq1">
                        <div class="profile-header">
                            <img src="../../../img/pfp-placeholder.jpeg">
                            <div>
                                <?php
                                  if ($row_cnt !=0) {
                                    echo '<p class="user-name">'.$fname.' '.$lname.'</p>';
                                    echo '<p>'.$uname.'</p>';
                                  }
                                ?>
                            </div>
                        </div>
                        <?php
                          if (isset($_SESSION['signedin'])) {
                            echo '<button>Change profile picture</button>';
                          }
                        ?>
                        <div class="links">
                            <?php
                              if (isset($_SESSION['signedin'])) {      
                                echo '<a href="#"><i class="fa fa-bookmark"></i>Bookmarks</a>
                                      <a href="#"><i class="fa fa-chart-line"></i>Activity Monitor</a>
                                      <a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>';
                              }
                            ?>
                            
                        </div>
                    </div> 
                    <form class="create-post" name="form" method="post" action="../php/createPost.php" enctype="multipart/form-data">
                        <div class="post-text">
                            <input type="search" name="post_body" placeholder="Create a post" aria-label="Search">
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
                            <a href="#"><img src="../../../img/media.png"></a>
                            <a href="#"><img src="../../../img/plus.png"></a>
                            <input type="submit" class="form-post" value="Post">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>