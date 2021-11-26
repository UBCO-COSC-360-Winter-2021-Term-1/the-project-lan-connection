
<?php

session_start();

include 'connectDB.php';
include 'handleImg.php';

$connection = connectToDB();

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $postBody = $_POST['post_body'];
  $postCat = $_POST['post_category'];
  $uname = $_SESSION['signedin'];

  if ($postBody == null || $postCat == null) {
    echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <h1>Oh no!</h1>
            <p>Ensure both the post body and category are filled out</p>
            <a href="javascript:history.back()">Return to previous screen</a>
          </div>';
  }
  else if (!isset($_SESSION['signedin'])) {
    echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
            <h1>Oh no!</h1>
            <p>You must be signed in to post</p>
            <a href="../html/login.html">Login</a>
            <a href="../html/home.php">Home</a>
          </div>';
  }
  else {
    date_default_timezone_set('America/Vancouver');
    $curDate = date('Y-m-d H:i:s');
  
    // Insert post information into db
    $sql = "INSERT INTO Post (post_body, uname, cat_title, post_date, p_likes, p_dislikes) 
            VALUES (?, ?, ?, ?, 0, 0);";

    // execute insert stmt
    if (mysqli_query($connection, $sql)) {
      // get post id
      $postID = mysqli_insert_id($connection);

      // Insert post img to db
      uploadImgToDB($connection, $postImg, $postID, "post");

      // redirect user back to home
      header('Location: ../html/home.php');
    }
    else {
      die('<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
              <h1>Oh no!</h1>
              <p>Something went wrong with your post</p>
              <a href="javascript:history.back()">Return to previous screen</a>
            </div>');
    }
  }

}
// Handle GET requests
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>Ensure request method is POST, not GET</p>
          <a href="javascript:history.back()">Return to login screen</a>
        </div>';
}

mysqli_close($connection);

?>