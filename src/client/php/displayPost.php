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

?>