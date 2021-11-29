<?php

  // Return number of likes on a post
  function getNumLikes($connection, $pid) {
    $sql = "SELECT * FROM Ratings WHERE (pid = $pid AND action='liked')";
    $likesResult = mysqli_query($connection, $sql);
    $numLikes = mysqli_num_rows($likesResult);
    return $numLikes;
  }

  // Return number of dislikes on a post
  function getNumDislikes($connection, $pid) {
    $sql = "SELECT * FROM Ratings WHERE (pid = $pid AND action='disliked')";
    $dislikesResult = mysqli_query($connection, $sql);
    $numDislikes = mysqli_num_rows($dislikesResult);
    return $numDislikes;
  }

  // Return number of comments on a post
  function getNumComments($connection, $pid) {
    $sql = "SELECT * FROM Comment WHERE pid=$pid";
    $commentsResult = mysqli_query($connection, $sql);
    $numComments = mysqli_num_rows($commentsResult);
    return $numComments;
  }

  // Check if a user has already liked a post
  // If they have, return true
  function alreadyLiked($connection, $pid, $uname) {
    if (!isset($uname)) {
      return false;
    }

    $sql = "SELECT * FROM Ratings WHERE (pid = $pid AND action='liked')";
    $result = mysqli_query($connection, $sql);
    $exists = 0;

    while ($row = $result->fetch_assoc()) {
      if($row['uname'] == $uname) {
        $exists++;
      }
      else {
        continue;
      }
    }

    if ($exists!=0) {
      return true;
    }
    else {
      return false;
    }
  }

  // Check if a user has already disliked a post
  // If they have, return true
  function alreadyDisliked($connection, $pid, $uname) {
    if (!isset($uname)) {
      return false;
    }

    $sql = "SELECT * FROM Ratings WHERE (pid = $pid AND action='disliked')";
    $result = mysqli_query($connection, $sql);
    $exists = 0;

    while ($row = $result->fetch_assoc()) {
      if($row['uname'] == $uname) {
        $exists++;
      }
      else {
        continue;
      }
    }

    if ($exists!=0) {
      return true;
    }
    else {
      return false;
    }
  }

  // Check if a user has already bookmarked a post
  // If they have, return true
  function alreadyBookmarked($connection, $pid, $uname) {
    if (!isset($uname)) {
      return false;
    }

    $sql = "SELECT * FROM Bookmarks WHERE pid = $pid";
    $result = mysqli_query($connection, $sql);
    $exists = 0;

    while ($row = $result->fetch_assoc()) {
      if($row['uname'] == $uname) {
        $exists++;
      }
      else {
        continue;
      }
    }

    if ($exists!=0) {
      return true;
    }
    else {
      return false;
    }
  }

?>