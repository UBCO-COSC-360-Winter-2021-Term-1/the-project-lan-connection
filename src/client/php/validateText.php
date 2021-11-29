<?php

  // Cleans user inputted text for consistency purposes
  function validate($text) {
    $text = trim($text);    
    $text = stripslashes($text);
    $text = htmlspecialchars($text);
    return $text;
  }
?>