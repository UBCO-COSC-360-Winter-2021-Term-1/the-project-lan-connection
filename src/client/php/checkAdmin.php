<?php 

  // Return true if signed in user is an admin
  function checkForAdmin($connection, $signedIn) {

    if (!isset($signedIn)) {
      return false;
    }

    $sql = "SELECT * FROM Account WHERE (uname='$signedIn' AND administrator=true);";
    $results = mysqli_query($connection, $sql);

    if (mysqli_num_rows($results)) {
      return true;
    }
    else {
      return false;
    }

  }

?>