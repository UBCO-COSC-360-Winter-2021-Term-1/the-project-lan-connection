
/*TODO: The username of person making the post needs to be configured */
/*TODO: Must know post id so handleImg.php knows what tuple to insert the post pic into */

<?php

include 'connectDB.php';
include 'handleImg';

$connection = connectToDB();

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $postBody = $_POST['post_body'];
  $postCat = $_POST['post_category'];
  $postImg = $_FILES['file']['name'];

  // Insert post information into db
  $sql = 'INSERT INTO Post (post_body, uname, cat_title, post_date, post_time) VALUES (?, ?, ?, CURDATE(), CURTIME());';
  $stmt = $connection->prepare($sql);
  $stmt->bind_param('sss', $postBody, $uname, $postCat);
  $stmt->execute();

  // Insert post img to db
  uploadImgToDB($connection, $postImg, $uname, true);

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