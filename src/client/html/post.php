<?php
/*
This page is navigatable without being signed in
This page is displayed when a user clicks on the comment icon of a certain post.
That certain post is isolated by it's post id and displayed on its own, along with a
comment box and all comments left by users. 
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
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/post.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="../js/likeSystem.js"></script>
  <script src="../js/bookmarkSystem.js"></script>
  <title>Post</title>
</head>

<body>
  <?php
  include '../php/navBar.php';
  echo displayNavBar($connection, $_SESSION['signedin'] ?? null, null);
  ?>

  <div class="container-1">
    <div class="content-box">

      <?php
      include '../php/connectDB.php';
      include '../php/handleImg.php';
      include '../php/retrieveLikes.php';

      $connection = connectToDB();

      $uname = $_SESSION['signedin'] ?? null;

      $pid = $_GET['pids'];

      $sql = "SELECT P.pid, fname, lname, A.uname, post_date, P.imageID, P.cat_title, post_body, A.imageID AS pfp
                  FROM POST P
                  INNER JOIN Account A ON A.uname=P.uname
                  INNER JOIN Category C ON P.cat_title=C.cat_title
                  LEFT OUTER JOIN Images I ON I.imageID=P.imageID
                  WHERE P.pid = '$pid'
                  ORDER BY post_date DESC";

      $result = mysqli_query($connection, $sql);

      while ($row = mysqli_fetch_array($result)) {
        echo displayPost2($connection, $row['pid'], $_SESSION['signedin'] ?? null, true);

        // Assign database post values to their own variables for ease of use
        $pid = $row['pid'];
        $uname = $row['uname'];
        $postDate = $row['post_date'];
        $cat = $row['cat_title'];
        $pBody = $row['post_body'];
        // Replace our placeholder (~) with the user submitted apostrophes
        $pBody = str_replace("~", "'", $pBody);
        // Grab number of likes, dislikes and comments for each post
        $numLikes = getNumLikes($connection, $pid);
        $numDislikes = getNumDislikes($connection, $pid);
        $numComments = getNumComments($connection, $pid);
        // Determine if each post has already been liked by the signed in user
        $liked = alreadyLiked($connection, $pid, $_SESSION['signedin'] ?? null);
        $disliked = alreadyDisliked($connection, $pid, $_SESSION['signedin'] ?? null);
        $bookmarked = alreadyBookmarked($connection, $pid, $_SESSION['signedin'] ?? null);
        //Access the posting user's profile picture
        $pfp = accessImgFromDB($connection, $row['pfp'], 'post');
      ?>

          <!-- Comment form -->
          <form class="create-post" name="form" method="post" action="../php/createComment.php">
            <div class="user-comment">
              <?php echo '<img src=' . $pfp . ' alt="../../../img/pfp-placeholder.jpeg" class="pfp-smaller">'; ?>
              <input type="text" name="comment" placeholder="Leave a comment" aria-label="Search">
              <?php echo '<input type="hidden" id="pid" name="pid" value=' . $pid . '> ' ?>
            </div>
          </form>

          <?php
          // Query comments for each post
          $sql2 = "SELECT * FROM Comment WHERE pid = '$pid'  ORDER BY comment_date DESC";
          $result2 = mysqli_query($connection, $sql2);

          while ($row2 = mysqli_fetch_array($result2)) {
            $cUname = $row2['uname'];
            $cDate = $row2['comment_date'];
            $cBody = $row2['comment_body'];
            // Replace our placeholder (~) with the user submitted apostrophes
            $cBody = str_replace("~", "'", $cBody);

          ?>
            <!-- Display comments -->
            <div class="comments">
              <div class="user-info">
                <img src="../../../img/pfp-placeholder.jpeg" alt="../../../img/pfp-placeholder.jpeg" class="pfp-smaller">
                <?php echo '<a href="./profile.php?username=' . $cUname . '">' . $cUname . '</a>'; ?>
                <?php echo '<p>' . $cDate . '</p>'; ?>
              </div>
              <div class="user-content">
                <?php echo '<p>' . $cBody . '</p>'; ?>
                <div class="menu-bar-comment"></div>
              </div>
            </div>
        <?php
          } // End of while loop displaying comments
        } // End of while loop displaying post 
        ?>
        </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>