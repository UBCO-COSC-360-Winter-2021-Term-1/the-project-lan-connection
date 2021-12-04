<?php
/*
This is the home page. This is navigatable by a non-signed in user, but the links to settings,
bookmarks and activity monitor will not be functional.
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
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/special.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> 
    <script src="../js/likeSystem.js"></script>
    <script src="../js/bookmarkSystem.js"></script>
    <script src="../js/dropdown.js"></script>
    <title>Home</title>
</head>
<body">
    <!--NAVIGATION BAR (done with bootstrap)-->
    <?php 
    include '../php/navBar.php';
    include '../php/connectDB.php';
    $connection = connectToDB();

    echo displayNavBar($connection, $_SESSION['signedin'] ?? null, null); 
    ?>

    <!--PAGE CONTENT-->
    <div class="plain-background">
        <div class="container">
            <div class="row">
                
                <!--LEFT COLUMN-->
                <div class="side-col-l">
                    <p class="header1">Categories</p>

                    <!--CATEGORY LINKS-->
                    <div class="grid-container">
                        <div class="post-category-l">
                            <a href="./category-page.php?page=Mountain Biking"><img src="../../../img/biking.jpg"></a>
                            <p>Mountain Biking</p>
                        </div>

                        <div class="post-category">
                            <a href="./category-page.php?page=Hiking"><img src="../../../img/hiking.jpg"></a>
                            <p>Hiking</p>
                        </div>
                    
                        <div class="post-category-l">
                            <a href="./category-page.php?page=Climbing"><img src="../../../img/climbing.jpeg"></a>
                            <p>Climbing</p>
                        </div>

                        <div class="post-category">
                            <a href="./category-page.php?page=Snowboarding"><img src="../../../img/snowboarding.jpg"></a>
                            <p>Snowboarding</p>
                        </div>
                    
                        <div class="post-category-l">
                            <a href="./category-page.php?page=Golf"><img src="../../../img/golf.png"></a>
                            <p>Golf</p>
                        </div>

                        <div class="post-category">
                            <a href="./category-page.php?page=Hockey"><img src="../../../img/goalie.jpg"></a>
                            <p>Hockey</p>
                        </div>
                    </div>
                </div>

                <!--MIDDLE COLUMN-->
                <div class="mid-col">
                  <p class="header1">Popular Posts</p>
                  <?php
                    include '../php/handleImg.php';
                    include '../php/retrieveLikes.php';
                    include '../php/displayPost.php';

                    // Query the posts that will be displayed on the home page
                    $sql = "SELECT pid, A.uname, post_date, P.imageID, P.cat_title, post_body, A.imageID AS pfp
                            FROM POST P
                            INNER JOIN Account A ON A.uname=P.uname
                            INNER JOIN Category C ON P.cat_title=C.cat_title
                            LEFT OUTER JOIN Images I ON I.imageID=P.imageID
                            ORDER BY post_date DESC
                            LIMIT 10;";
                            
                    $results = mysqli_query($connection, $sql);
                    $row_cnt = mysqli_num_rows($results);
                    
                    if ($row_cnt !=0) {
                      while ($row = $results->fetch_assoc())
                      {
                        echo displayPost2($connection, $row['pid'], $_SESSION['signedin'] ?? null, false);
                      } // End of while loop displaying posts
                    } // End of if statment ensuring posts!=0
                    else {
                      echo "<p>No posts to display!</p>";
                    }                     
                    ?>
                </div>

                <!--RIGHT COLUMN-->
                <div class="side-col-r">
                    <p class="header1">Personal</p>

                    <!--CREATE A POST-->
                    <form class="create-post" name="form" method="post" action="../php/createPost.php" enctype="multipart/form-data">
                      <div class="post-text">
                        <textarea name="post_body" placeholder="Create a post" rows="5"></textarea>
                        <br>
                        <input type="text" name="post_category" placeholder="Category" list="category-selection">
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

                    <!--PROFILE LINKS-->
                    <?php 
                      if (isset($_SESSION['signedin'])) {
                        // echo '<a href="./profile.php"><i class="fa fa-cog"></i>Settings</a>';
                        echo '<a href="./bookmarks.php?user='.$_SESSION['signedin'].'"><i class="fa fa-bookmark"></i>Bookmarks</a>';
                        // echo '<a href="#"><i class="fa fa-chart-line"></i>Activity Monitor</a>';
                        echo '<a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>';
                      }
                      else {
                        // echo '<a href="#"><i class="fa fa-cog"></i>Settings</a>';
                        // echo '<a href="#"><i class="fa fa-bookmark"></i>Bookmarks</a>';
                        // echo '<a href="#"><i class="fa fa-chart-line"></i>Activity Monitor</a>';
                      }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
