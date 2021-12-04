<?php

// Handle account creation image processing or post image processing
/**
 * $connection : created DB connection
 * $filename : image file taken from $_FILES (Ex: $_FILES["ppic"]["name"])
 * $id : username of user uploading image
 * $purpose : either "profile" or "post"
 */
function uploadImgToDB ($connection, $fileName, $id, $purpose) {
  // get inputted picture file
  if (isset($fileName)) {
    // initialize image variables
    $target_file = "../uploads/" . basename($fileName);
    $uploadOk = 1; // switch to zero if anything wrong
/*     // Check file size
    if ($_FILES["ppic"]["size"] > 1000000000000) {
      $uploadOk = 0;
    } */
    // Check file type
    $imageFileType = strtolower(pathinfo(basename($fileName), PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
      $uploadOk = 0;
    }
    // Move image to server folder
    if ($uploadOk != 0 && move_uploaded_file($_FILES["ppic"]["tmp_name"], $target_file)) {
      // image successfully moved into server folder
    } else {
      // error using user's image -> don't upload
      $upoadOk = 0;
      return false;
    }
  }

  // check if uploading image
  if ($uploadOk != 0) {
    // get image data from file
    $imagedata = file_get_contents($target_file);
    // write insert sql
    $sql = "INSERT INTO Images (contentType, image) VALUES (?, ?);"; // removed lab 10 user parameter
    // build sql prepared statement
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $sql);
    $null = NULL;
    mysqli_stmt_bind_param($stmt, "sb", $imageFileType, $null); // not adding user parameter like in lab 10
    mysqli_stmt_send_long_data($stmt, 1, $imagedata); // blob is at idx 1
    // execute insert
    $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
    // get auto increment id of inserted row
    $insertID = mysqli_insert_id($connection);
    // close prepared statement
    mysqli_stmt_close($stmt);

    // create SQL INSERT stmt
    switch ($purpose) {
      case "profile":
        // insert image id into user's imageID field in account
        $sql = "UPDATE Account SET imageID=$insertID WHERE uname='$id';";
        break;
      case "post":
        $sql = "UPDATE Post SET imageID=$insertID WHERE pid='$id';";
        break;
    }
    
    // execute UPDATE
    mysqli_query($connection, $sql);
    return true;
  }
}

/**
 * $connection: established connection to DB
 * $id : id of image in images table
 * $purpose: "profile" for id=uname, "post" for id=pid, "image" for id=imageID
 * return : the src string for the image encapsulated in double quotes
 */
function accessImgFromDB($connection, $id, $purpose) {
  // query account/post table for imageID
  switch ($purpose) {
    case "profile":
      $sql = "SELECT imageID FROM Account WHERE uname = $id";
      if ($results = mysqli_query($connection, $sql)) {
        if ($row = mysqli_fetch_assoc($results)) {
          $imageID = $row['imageID'];
        }
      }
      mysqli_free_result($results);
      break;
    case "post":
      $sql = "SELECT imageID FROM post WHERE pid = $id";
      if ($results = mysqli_query($connection, $sql)) {
        if ($row = mysqli_fetch_assoc($results)) {
          $imageID = $row['imageID'];
        }
      }
    default:
      $imageID = $id;
  }

  // query images table
  $sql = "SELECT contentType, image FROM images WHERE imageID = ?";
  $stmt = mysqli_stmt_init($connection);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
  mysqli_stmt_bind_result($stmt, $type, $image);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  
  // formulate output
  if ($image != null) {
    return "'data:image/" . $type . ";base64," . base64_encode($image) . "'";
  }
  else {
    return "'../../../img/default_profile_picture.jpg'";
  }
  
}

?>