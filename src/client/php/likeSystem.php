<?php

  session_start();

  include './connectDB.php';

  $connection = connectToDB();

  $uname = $_SESSION['signedin'];
  $pid = $_POST['pid'];
  $action = $_POST['action'];

  if (isset($_POST['action'])) {
    switch ($_POST['action']) {

      case 'liked':
        // Check for dislikes before adding the like
        $sql1 = "SELECT * FROM Ratings WHERE (uname = '$uname' AND pid = '$pid' AND action = 'disliked')";
        $result = mysqli_query($connection, $sql1);
        $rows = mysqli_num_rows($result);
        // If there the post is already disliked, delete the dislike
        if ($rows>0) {
          $sql2 = "DELETE FROM Ratings WHERE (uname='$uname' AND pid='$pid')";
          mysqli_query($connection, $sql2);
        }
        // Add the like
        $sql = "INSERT INTO Ratings (uname, pid, action) VALUES ('$uname', '$pid', '$action')";
        mysqli_query($connection, $sql);

        // Check number of dislikes and likes on post
        $sqlLikes = "SELECT * FROM Ratings WHERE (pid = $pid AND action='liked')";
        $sqlDislikes = "SELECT * FROM Ratings WHERE (pid = $pid AND action='disliked')";
        $likesResult = mysqli_query($connection, $sqlLikes);
        $dislikesResult = mysqli_query($connection, $sqlDislikes);

        $numLikes = mysqli_num_rows($likesResult);
        $numDislikes = mysqli_num_rows($dislikesResult);

        mysqli_close($connection);     
        echo json_encode(array($numLikes, $numDislikes));
        break;

      case 'unliked':
        $sql = "DELETE FROM Ratings
                WHERE (uname = '$uname' AND pid = '$pid' AND action = 'liked')";
        mysqli_query($connection, $sql);
        mysqli_close($connection);
        echo 'Unlike was a success'; 
        break;

      case 'disliked':
        // Check for likes before adding the dislike
        $sql1 = "SELECT * FROM Ratings WHERE (uname = '$uname' AND pid = '$pid' AND action = 'liked')";
        $result = mysqli_query($connection, $sql1);
        $rows = mysqli_num_rows($result);
        // If there the post is already liked, delete the like
        if ($rows>0) {
          $sql2 = "DELETE FROM Ratings WHERE (uname='$uname' AND pid='$pid')";
          mysqli_query($connection, $sql2);
        }
        // Add the dislike
        $sql = "INSERT INTO Ratings (uname, pid, action)
                VALUES ('$uname', '$pid', '$action')";
        mysqli_query($connection, $sql);

        $sqlLikes = "SELECT * FROM Ratings WHERE (pid = $pid AND action='liked')";
        $sqlDislikes = "SELECT * FROM Ratings WHERE (pid = $pid AND action='disliked')";
        $likesResult = mysqli_query($connection, $sqlLikes);
        $dislikesResult = mysqli_query($connection, $sqlDislikes);

        $numLikes = mysqli_num_rows($likesResult);
        $numDislikes = mysqli_num_rows($dislikesResult);

        mysqli_close($connection);     
        echo json_encode(array($numLikes, $numDislikes));
        break;

      case 'undisliked':
        $sql = "DELETE FROM Ratings
                WHERE (uname = '$uname' AND pid = '$pid' AND action = 'disliked')";
        mysqli_query($connection, $sql);
        mysqli_close($connection);
        echo 'Undislike was a success';
        break;

    }
  }

?>