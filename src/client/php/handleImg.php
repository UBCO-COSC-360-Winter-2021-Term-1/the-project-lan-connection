<?php

// Handle account creation image processing
function uploadImgToDB ($connection, $fileName, $uname) {

  $targetDir = '../uploads/';
  $targetFilePath = $targetDir . $fileName;
  $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
  $allowTypes = array('jpg','png','jpeg','pdf');
  $file = $_FILES['file']['tmp_name'];

  if(in_array($fileType, $allowTypes)){
    // Upload file to server
    if(move_uploaded_file($file, $targetFilePath)){
      // Insert image file name into database
      $insert = $connection->query("UPDATE Account SET pfp = '".$fileName."' WHERE uname = '".$uname."'");
      
      if($insert){
        echo "The file ".$fileName. " has been uploaded successfully.";
      }else{
        echo "File upload failed, please try again.";
      } 
    }else{
      echo "Sorry, there was an error uploading your file.";
    }
  }else{
    echo 'Sorry, only JPG, JPEG, PNG, & PDF files are allowed to upload.';
  }
}

?>