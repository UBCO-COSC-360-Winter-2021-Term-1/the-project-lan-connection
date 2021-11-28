<?php

  function getNumLikes($connection, $pid) {
    $sql = "SELECT * FROM Ratings WHERE (pid = $pid AND action='liked')";
    $likesResult = mysqli_query($connection, $sql);
    $numLikes = mysqli_num_rows($likesResult);
    return $numLikes;
  }

  function getNumDislikes($connection, $pid) {
    $sql = "SELECT * FROM Ratings WHERE (pid = $pid AND action='disliked')";
    $dislikesResult = mysqli_query($connection, $sql);
    $numDislikes = mysqli_num_rows($dislikesResult);
    return $numDislikes;
  }

  function getNumComments($connection, $pid) {
    $sql = "SELECT * FROM Comment WHERE pid=$pid";
    $commentsResult = mysqli_query($connection, $sql);
    $numComments = mysqli_num_rows($commentsResult);
    return $numComments;
  }

?>