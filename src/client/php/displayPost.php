<?php

function displayPost2($connection, $pid, $currentUname, $showComments) {
  // include '../php/checkAdmin.php';

  $isAdmins = checkForAdmin($connection, $currentUname);
  // make sql query for post info needed
  $sql = "SELECT P.pid, fname, lname, A.uname, post_date, P.imageID AS pimg, P.cat_title, post_body, A.imageID AS pfp
                  FROM POST P
                  INNER JOIN Account A ON A.uname=P.uname
                  INNER JOIN Category C ON P.cat_title=C.cat_title
                  LEFT OUTER JOIN Images I ON I.imageID=P.imageID
                  WHERE P.pid = '$pid'
                  ORDER BY post_date DESC";
  $result = mysqli_query($connection, $sql);
  if ($row = mysqli_fetch_array($result)) {
    // assign sql results to php variables
    $uname = $row['uname'];
    $postDate = $row['post_date'];
    $cat = $row['cat_title'];
    $pBody = str_replace("~", "'", $row['post_body']);
    // Grab number of likes, dislikes and comments for post
    $numLikes = getNumLikes($connection, $pid);
    $numDislikes = getNumDislikes($connection, $pid);
    $numComments = getNumComments($connection, $pid);
    // determine if signed in has already liked post
    $liked = alreadyLiked($connection, $pid, $currentUname ?? null);
    $disliked = alreadyDisliked($connection, $pid, $currentUname ?? null);
    $bookmarked = alreadyBookmarked($connection, $pid, $currentUname ?? null);
    // Access profile pic + post image
    $pfp = accessImgFromDB($connection, $row['pfp'], 'image');
    // $pimg = accessImgFromDB($connection, $row['pimg'], 'image');
    
    $catLower = strtolower($cat);
    // build html of post
    $html = '<div class="popular-post"><div class="post-status">';
    $html = $html.'<img src='.$pfp.' class="pfp-small">';
    $html = $html.'<a href="./profile.php?username='.$uname.'" class="username">'.$uname.' </a>';
    $html = $html.'<p>'.$postDate.'</p></div>';
    $html = $html. '<div class="category"><p>Posted to <a href="./category-page.php?page='.$cat.'" class="post-category">'.$catLower.'</a></p></div>';
    $html = $html. '<div class="post-text"><p>'. $pBody.'</p></div>';
    // likes
    $html = $html.'<div class="menu-bar">';
    $html = $html.'<button type="submit" onclick="clickedLike(this)" class="like" data-value="'.$pid.'" value="liked">';
    if ($liked) {
      $html = $html.'<i class="fas fa-thumbs-up"></i>';
    }
    else {
      $html = $html . '<i class="far fa-thumbs-up"></i>';
    }
    $html = $html.'</button>';
    $html = $html.'<label class="like-counter">'.$numLikes.'</label>';

    $html = $html.'<button type="submit" onclick="clickedDislike(this)" class="dislike" data-value="'.$pid.'" value="disliked">';
    if ($disliked) {
      $html = $html.'<i class="fas fa-thumbs-down"></i>';
    }
    else {
      $html = $html.'<i class="far fa-thumbs-down"></i>';
    }
    $html = $html.'</button>';
    $html = $html.'<label class="dislike-counter">'.$numDislikes.'</label>';
    $html = $html.'<a href="post.php?pids='.$pid.'" class="comment"><i class="far fa-comment"></i></a>';
    $html = $html.'<label class="comment-counter">'.$numComments.'</label>';
    $html = $html.'<button type="submit" onclick="clickedBookmark(this)" class="bookmark" data-value="'.$pid.'" value="liked">';
    if ($bookmarked) {
      $html = $html.'<i class="fas fa-bookmark"></i>';
    }
    else {
      $html = $html.'<i class="far fa-bookmark"></i>';
    }
    $html = $html.'</button>';
    if ($isAdmins) {
      $html = $html.'<div class="dropdown">
        <button onclick="dropdown()" class="dropbtn"><i class="fas fa-ellipsis-h"></i></button>
        <div class="myDropdown" class="dropdown-content">
          <button>Delete post</a>
          <button>Delete user</a>
        </div>
      </div>';
    }
    $html = $html.'</div></div>';
  }
  mysqli_free_result($result);
  // output comments if that is wanted
  if ($showComments == true) {
    // check if user logged in
    if ($currentUname != null) {
      // get pfp of logged in user
      $sql = 'SELECT imageID FROM Account WHERE uname="'. $currentUname . '"';
      $result = mysqli_query($connection, $sql);
      if ($row = mysqli_fetch_array($result)) {
        // get photo
        $pfp = accessImgFromDB($connection, $row['imageID'], 'image');
      }
      mysqli_free_result($result);
      // display add comment box
      $html = $html . '<form class="create-post" name="form" method="post" action="../php/createComment.php"><div class="user-comment">';
      $html = $html . '<img src=' . $pfp . ' class="pfp-smaller">';
      $html = $html . '<input type="text" name="comment" placeholder="Leave a comment" aria-label="Search">';
      $html = $html . '<input type="hidden" id="pid" name="pid" value=' . $pid . '>';
      $html = $html . '</div></form>';
    } 
    // get comments
    $sql = "SELECT * FROM Comment WHERE pid=$pid ORDER BY comment_date DESC;";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_array($result)) {
      // get comment info
      $cUname = $row['uname'];
      $cDate = $row['comment_date'];
      $cBody = str_replace("~", "'", $row['comment_body']);
      // get commenter pfp
      $sql1 = 'SELECT imageID FROM Account WHERE uname="' . $cUname . '"';
      $result1 = mysqli_query($connection, $sql1);
      if ($row1 = mysqli_fetch_array($result1)) {
        $pfp = accessImgFromDB($connection, $row1['imageID'], "image");
      }
      mysqli_free_result($result1);
      // add comments to output
      $html = $html . '<div class="comments"><div class="user-info">';
      $html = $html . '<img src=' . $pfp . ' class="pfp-small">';
      $html = $html . '<a href="./profile.php?username=' . $cUname . '">' . $cUname . '</a>';
      $html = $html . '<p>' . $cDate . '</p></div>';
      $html = $html . '<div class="user-content">';
      $html = $html . '<p>' . $cBody . '</p>';
      $html = $html . '<div class="menu-bar-comment"></div></div></div>';
    }
    mysqli_free_result($result);

  }
  // return output html as string
  if ($html != null) {
    return $html; // return output html as string ready to echo
  }
  else {
    return '<p>No posts to display!</p>'; // return empty string if no post
  }



}

?>