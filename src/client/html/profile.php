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
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>User Profile</title>
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
                    Home
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Logout</a>
                </div>
                </li>
            </ul>
            <form class="form-inline nav-search my-2 my-lg-0" method="get" action="">
                <input class="search-bar" type="search" placeholder="Search" aria-label="Search">
                <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
            </form>
            <?php 
                if (isset($_SESSION['signedin'])) {
                    echo "<a href='./profile.php' class='form-login'>".$_SESSION['signedin']."'s Profile</a>";
                    echo "<a href='../php/logout.php' class='form-login'>Logout</a>";
                }
                else {
                    echo "<a href='login.html' class='form-login'>Login / Sign Up</a>";
                }
            ?>
        </div>
    </nav>
    
    <div class="plain-background">
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
    
                          $sql = "SELECT A.uname, post_date, post_time, post_pic, P.cat_title, post_body, p_likes, p_dislikes, A.pfp 
                                  FROM POST P
                                  INNER JOIN Account A ON A.uname=P.uname
                                  INNER JOIN Category C ON P.cat_title=C.cat_title
                                  WHERE A.uname = '$uname'
                                  ORDER BY post_date DESC, post_time DESC;";
                                  
                          $results = mysqli_query($connection, $sql);
                          $row_cnt = mysqli_num_rows($results);
    
                          if ($row_cnt !=0) {
                            while ($row = $results->fetch_assoc()) {
                              echo '<div class="popular-post">
                                      <div class="post-status">
                                        <img src='.$row["pfp"].' class="pfp-small">
                                        <a href="./profile.php" class="username">'.$row["uname"].'</a>
                                        <p>'.$row["post_date"].$row["post_time"].'</p>
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
                                                  <label class="comment-counter">3</label>
            
                                                  <button class="bookmark"><i class="fa fa-bookmark"></i></button>
                                                </div>
                                              </div>';
                                      }
                            }
                          }
                          else {
                            echo 'No posts to display!';
                          }

                        ?>
                        <!--
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
                            <div class="post-img">
                                <img class="hide-img" src="">
                            </div>
                            <div class="menu-bar">
                                <button class="like"><i class="fas fa-heart"></i></button>
                                <label class="like-counter">7</label>
    
                                <button class="dislike"><i class="far fa-heart-broken"></i></button>
                                <label class="dislike-counter">2</label>
    
                                <button class="comment"><i class="fas fa-comment"></i></button>
                                <label class="comment-counter">3</label>
    
                                <button class="bookmark"><i class="fa fa-bookmark"></i></button>
                            </div>
                        </div>
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
                            <div class="post-img">
                                <img class="hide-img" src="">
                            </div>
                            <div class="menu-bar">
                                <button class="like"><i class="fas fa-heart"></i></button>
                                <label class="like-counter">7</label>
    
                                <button class="dislike"><i class="far fa-heart-broken"></i></button>
                                <label class="dislike-counter">2</label>
    
                                <button class="comment"><i class="fas fa-comment"></i></button>
                                <label class="comment-counter">3</label>
    
                                <button class="bookmark"><i class="fa fa-bookmark"></i></button>
                            </div>
                        </div>
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
                                <p>Cool picture of simon climbing.</p>
                            </div>
                            <div class="post-img">
                                <img class="" src="../../../img/sample-post-pic.jpg">
                            </div>
                            <div class="menu-bar">
                                <button class="like"><i class="fas fa-heart"></i></button>
                                <label class="like-counter">7</label>
    
                                <button class="dislike"><i class="far fa-heart-broken"></i></button>
                                <label class="dislike-counter">2</label>
    
                                <button class="comment"><i class="fas fa-comment"></i></button>
                                <label class="comment-counter">3</label>
    
                                <button class="bookmark"><i class="fa fa-bookmark"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                        -->

                <div class="right-col">
                    <div class="sq1">
                        <div class="profile-header">
                            <img src="../../../img/pfp-placeholder.jpeg">
                            <div>
                                <p class="user-name">Noah Ward</p>
                                <p>noahward</p>
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
                    <form class="create-post">
                        <div class="post-text">
                            <input type="search" placeholder="Create a post" aria-label="Search">
                            <br>
                            <input type="text" placeholder="Category" list="category-selection" aria-label="Category">
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
</body>
</html>