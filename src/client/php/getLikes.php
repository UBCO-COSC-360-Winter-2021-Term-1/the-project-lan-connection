<?php

function getLikes($connection, $uname, $pid) {

  $sql = "SELECT uname, pid, action FROM Ratings WHERE uname=?;";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("s", $uname);
  $stmt->execute();
  $stmt->store_result();
  $rows = $stmt->num_rows;

  if ($rows) {
    echo true;
  }
  else {
    echo false;
  }

}

function getDislikes() {

}

function getBookmarks() {

}

?>