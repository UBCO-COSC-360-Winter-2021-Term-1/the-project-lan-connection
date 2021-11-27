<?php

  $user_id = $_SESSION['signedin'];
  $post_id = $_POST['pid'];
  $action = $_POST['action'];

  if (isset($action)) {
    switch ($action) {
      case 'like':
        $sql="INSERT INTO Ratings (uname, pid, action) 
              VALUES ($user_id, $post_id, 'like') 
              ON DUPLICATE KEY UPDATE rating_action='like'";
        break;

      case 'dislike':
        $sql="INSERT INTO Ratings (uname, pid, action) 
              VALUES ($user_id, $post_id, 'dislike') 
              ON DUPLICATE KEY UPDATE rating_action='dislike'";
        break;

      case 'unlike':
        $sql="DELETE FROM Ratings WHERE uname=$user_id AND pid=$post_id";
        break;

      case 'undislike':
        $sql="DELETE FROM Ratings WHERE uname=$user_id AND pid=$post_id";
        break;

      default:
        break;
    }

    mysqli_query($connection, $sql);
    echo getRating($post_id);
    exit(0);

  }

  function getLikes($id) {
    global $connection;
    $sql = "SELECT COUNT(*) FROM Ratings
            WHERE pid = $id AND action='like'";
    $rs = mysqli_query($connection, $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
  }

  // Get total number of dislikes for a particular post
  function getDislikes($id) {
    global $connection;
    $sql = "SELECT COUNT(*) FROM Ratings
            WHERE pid = $id AND action='dislike'";
    $rs = mysqli_query($connection, $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
  }

  function getRating($id) {
    global $connection;
    $rating = array();
    $likes_query = "SELECT COUNT(*) FROM Ratings WHERE pid = $id AND action='like'";
    $dislikes_query = "SELECT COUNT(*) FROM Ratings
              WHERE pid = $id AND action='dislike'";
    $likes_rs = mysqli_query($connection, $likes_query);
    $dislikes_rs = mysqli_query($connection, $dislikes_query);
    $likes = mysqli_fetch_array($likes_rs);
    $dislikes = mysqli_fetch_array($dislikes_rs);
    $rating = [
      'likes' => $likes[0],
      'dislikes' => $dislikes[0]
    ];
    return json_encode($rating);
  }

  function userLiked($post_id) {
    global $connection;
    global $user_id;
    $sql = "SELECT * FROM ratings WHERE uname=$user_id 
          AND pid=$post_id AND action='like'";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) > 0) {
      return true;
    }
    else {
      return false;
    }
  }

  function userDisliked($post_id) {
    global $connection;
    global $user_id;
    $sql = "SELECT * FROM Ratings WHERE uname=$user_id 
          AND pid=$post_id AND action='dislike'";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) > 0) {
      return true;
    }
    else {
      return false;
    }
  }

  $sql = "SELECT * FROM Post";
  $result = mysqli_query($connection, $sql);
  // fetch all posts from database
  // return them as an associative array called $posts
  $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>