
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
  $curDate = date("Y-m-d H:i:s");

  // Insert post information into db
  $sql = "INSERT INTO Post (post_body, uname, cat_title, post_date) 
          VALUES (?, ?, ?, ?);";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("ssss", $postBody, $uname, $postCat, $curDate);

  $stmt->execute();

  // Insert post img to db
  //uploadImgToDB($connection, $postImg, $uname, true);
  header('Location: ../html/home.php');
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