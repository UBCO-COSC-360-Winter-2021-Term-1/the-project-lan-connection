<?php

  function displayPost() {

    include '../php/connectDB.php';
    include '../php/handleImg.php';
    include '../php/retrieveLikes.php';

    $pids = $row['pid'];
    // Grab number of likes, dislikes and comments for each post
    $numLikes = getNumLikes($connection, $pids);
    $numDislikes = getNumDislikes($connection, $pids);
    $numComments = getNumComments($connection, $pids);
    // Determine if each post has already been liked by the signed in user
    $liked = alreadyLiked($connection, $pids, $_SESSION['signedin'] ?? null);
    $disliked = alreadyDisliked($connection, $pids, $_SESSION['signedin'] ?? null);
    $bookmarked = alreadyBookmarked($connection, $pids, $_SESSION['signedin'] ?? null);
    //Access the posting user's profile picture
    $pfp = accessImgFromDB($connection, $row['pfp'], 'image');
    
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
                <button type="submit" onclick="clickedLike(this)" class="like" data-value="'.$row['pid'].'" value="liked">';
                if($liked) {
                  echo '<i class="fas fa-thumbs-up"></i>';
                } else {
                  echo '<i class="far fa-thumbs-up"></i>';
                }
                echo '</button>
                <label class="like-counter">'.$numLikes.'</label>

                <button type="submit" onclick="clickedDislike(this)" class="dislike" data-value="'.$row['pid'].'" value="disliked">';         
                if($disliked) {
                  echo '<i class="fas fa-thumbs-down"></i>';
                }
                else {
                  echo '<i class="far fa-thumbs-down"></i>';
                }
                echo '</button>
                <label class="dislike-counter">'.$numDislikes.'</label>

                <a href="post.php?pids='.$row['pid'].'" class="comment"><i class="far fa-comment"></i></a>
                <label class="comment-counter">'.$numComments.'</label>

                <button type="submit" onclick="clickedBookmark(this)" class="bookmark" data-value="'.$row['pid'].'" value="liked">';
                if ($bookmarked) {
                  echo '<i class="fas fa-bookmark"></i>';
                }
                else {
                  echo '<i class="far fa-bookmark"></i>';
                }
                echo '</button>
              </div>
            </div>';
    }
    else {
      echo '<div class="post-img">
                <img class="" src="'.$row["imageID"].'">
              </div>
              <div class="menu-bar">
                <button type="submit" onclick="clickedLike(this)" class="like" data-value="'.$row['pid'].'" value="liked">';
                if($liked) {
                  echo '<i class="fas fa-thumbs-up"></i>';
                } else {
                  echo '<i class="far fa-thumbs-up"></i>';
                }
                echo '</button>
                <label class="like-counter">'.$numLikes.'</label>

                <button type="submit" onclick="clickedDislike(this)" class="dislike" data-value="'.$row['pid'].'" value="disliked">';
                if($disliked) {
                  echo '<i class="fas fa-thumbs-down"></i>';
                }
                else {
                  echo '<i class="far fa-thumbs-down"></i>';
                }
                echo '</button>
                <label class="dislike-counter">'.$numDislikes.'</label>

                <a href="post.php?pids='.$row['pid'].'" class="comment"><i class="far fa-comment"></i></a>
                <label class="comment-counter">'.$numComments.'</label>

                <button type="submit" onclick="clickedBookmark(this)" class="bookmark" data-value="'.$row['pid'].'" value="liked">';
                if ($bookmarked) {
                  echo '<i class="fas fa-bookmark"></i>';
                }
                else {
                  echo '<i class="far fa-bookmark"></i>';
                }
                echo '</button>                                    
                </div>                                
            </div>';
    }
  }

function displayPost2($connection, $pid, $currentUname) {
  // include other functions
  // include '../php/handleImg.php';
  // include '../php/retrieveLikes.php';
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
    // Access proster profile pic + post image
    $pfp = accessImgFromDB($connection, $row['pfp'], 'image');
    $pimg = accessImgFromDB($connection, $row['pimg'], 'image');
    // build html of post
    $html = '<div class="popular-post"><div class="post-status">';
    $html = $html.'<img src='.$pfp.' class="pfp-small">';
    $html = $html.'<a href="./profile.php?username='.$uname.'" class="username">'.$uname.' </a>';
    $html = $html.'<p>'.$postDate.'</p></div>';
    $html = $html. '<div class="category"><p>Posted to<a href="./category-page.php?page='.$cat.'" class="post-category">'.$cat.'</a></p></div>';
    $html = $html. '<div class="post-text"><p>'. $pBody.'</p></div>';
    if ($pimg != null) {
      $html = $html . '<div class="post-img"><img class="" src=' . $pimg . '></div>';
    }
    $html = $html. '<div class="menu-bar"><button type="submit" onclick="clickedLike(this)" class="like" data-value="'.$pid.' value="liked">';
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
    $html = $html.'</butto></div></div>';
  }
  mysqli_free_result($result);
  // return output html as string
  if ($html != null) {
    return $html; // return output html as string ready to echo
  }
  else {
    return ''; // return empty string if no post
  }



}

?>