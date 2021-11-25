<?php

session_start();

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
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Search Results</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light navbar-static-top">
    <a class="navbar-brand" href="#"><img src="../../../img/nav-logo.png"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search results
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="./home.php">Home</a>
                <a class="dropdown-item" href="./profile.php">Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../php/logout.php">Logout</a>
            </div>
            </li>
        </ul>
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

      <?php
        include '../php/connectDB.php';
        include '../php/validateText.php';

        $connection = connectToDB();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

          $search = validate($_GET['search']);
          $search = '%'.$search.'%';
          
          $sql = "SELECT A.uname, post_date, post_pic, P.cat_title, post_body, p_likes, p_dislikes, A.pfp 
                  FROM POST P
                  INNER JOIN Account A ON A.uname=P.uname
                  INNER JOIN Category C ON P.cat_title=C.cat_title
                  WHERE post_body LIKE '$search' OR A.uname LIKE '$search'
                  ORDER BY post_date ASC;";

          $results = mysqli_query($connection, $sql);
          $row_cnt = mysqli_num_rows($results);

          if ($row_cnt !=0) {
            while ($row = $results->fetch_assoc())
            {
              echo '<div class="popular-post">
                      <div class="post-status">
                        <img src='.$row["pfp"].' alt="../../../img/pfp-placeholder.jpeg" class="pfp-small">
                        <a href="#" class="username">'.$row["uname"].'</a>
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
                          <label class="comment-counter"></label>

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
          }
          else {
            echo "<p>No posts to display!</p>";
          }
           
        }

      ?>

    </div>
  </div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>