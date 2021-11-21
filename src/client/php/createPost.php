<?php

include 'connectDB.php';

$connection = connectToDB();

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $postBody = $_POST['post_body'];
  $postCat = $_POST['post_category'];
  $postImg = $_FILES['file']['name'];


}
// Handle GET requests
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
  echo '<div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <h1>Oh no!</h1>
          <p>Ensure request method is POST, not GET</p>
          <a href="javascript:history.back()">Return to login screen</a>
        </div>';
}

?>