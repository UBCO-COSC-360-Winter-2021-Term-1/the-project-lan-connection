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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../js/activity.js"></script>
    <title>Post</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-static-top">
        <a class="navbar-brand" href="./home.php"><img src="../../../img/nav-logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto"></ul>
            <form class="form-inline nav-search my-2 my-lg-0" method="get" action="./searchResults.php">
                <input class="search-bar" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
            </form>
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

    <div class="container-1">
        <div class="content-box">

        <?php
          include '../php/connectDB.php';
      
          $connection = connectToDB();

          $uname = $_SESSION['signedin'];
          $pid = $_GET['pids'];

          $sql = "SELECT P.pid, fname, lname, A.uname, post_date, post_pic, P.cat_title, post_body, p_likes, p_dislikes, A.pfp 
                  FROM POST P
                  INNER JOIN Account A ON A.uname=P.uname
                  INNER JOIN Category C ON P.cat_title=C.cat_title
                  WHERE P.pid = '$pid'
                  ORDER BY post_date DESC";
                  
          $result = mysqli_query($connection, $sql);

          //$result = $results->fetch_assoc();
          
          // DEBUG
          function debug_to_console($data) {
            $output = $data;
            if (is_array($output))
                $output = implode(',', $output);
        
            echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
          }
          debug_to_console($pid);          

          while ($row = mysqli_fetch_array($result)) { 
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
                  </div>';
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
                    </div>';
            }

          }

        ?> 

            <!--THIS IS A POST CONTAINING COMMENTS
            <div class="popular-post">
                <div class="post-status">
                    <img src="../../../img/pfp-placeholder.jpeg" class="pfp-small">
                    <a href="#" class="username">noahward</a>
                    <p>Dec 15, 2020</p>
                </div>
                <div class="category">
                    <p>Posted to <a href="#" class="post-category">snowboarding</a></p>
                </div>
                <div class="post-text">
                    <p>Powder day at Big White today! Dumped 30cm overnight.</p>
                </div>

                <!--Delete the line below using php if no media is uploaded to a post
                <div class="post-img">
                    <img class="hide-img" src="">
                </div>

                <div class="menu-bar">
                    <button class="like"><i class="fas fa-heart"></i></button>
                    <label class="like-counter">7</label>

                    <button class="dislike"><i class="far fa-heart-broken"></i></button>
                    <label class="dislike-counter">2</label>

                    <button class="comment"><i class="fas fa-comment"></i></button>
                    <label class="comment-counter">2</label>

                    <button class="bookmark"><i class="fa fa-bookmark"></i></button>
                </div>

                <!--LEAVE A COMMENT ON A POST
                <form class="create-post">
                    
                    <div class="user-comment">
                        <img src="../../../img/pfp-placeholder.jpeg" class="pfp-smaller">
                        <input type="search" placeholder="Leave a comment" aria-label="Search">
                    </div>

                    <!--Only echo the following code if a post has comments
                    <div class="comments">
                        <div class="user-info">
                            <img src="../../../img/pfp-placeholder.jpeg" class="pfp-smaller">
                            <a href="#">coleytweed</a>
                            <p>Dec 16, 2020</p>
                        </div>
                        <div class="user-content">
                            <p>Silverstar gets less snow than Big White</p>
                            <div class="menu-bar">
                                <button class="like"><i class="fas fa-heart"></i></button>
                                <label class="like-counter">14</label>

                                <button class="dislike"><i class="far fa-heart-broken"></i></button>
                                <label class="dislike-counter">1</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="comments">
                        <div class="user-info">
                            <img src="../../../img/pfp-placeholder.jpeg" class="pfp-smaller">
                            <a href="#">coleytweed</a>
                            <p>Dec 16, 2020</p>
                        </div>
                        <div class="user-content">
                            <p>This is another comment placeholder, which takes up multiple lines for testing purposes, etc
                            </p>
                            <div class="menu-bar">
                                <button class="like"><i class="fas fa-heart"></i></button>
                                <label class="like-counter">2</label>

                                <button class="dislike"><i class="far fa-heart-broken"></i></button>
                                <label class="dislike-counter">0</label>
                            </div>
                        </div>
                    </div>
        -->
                </form>
            </div>
        </div>
    </div>
</body>
</html>