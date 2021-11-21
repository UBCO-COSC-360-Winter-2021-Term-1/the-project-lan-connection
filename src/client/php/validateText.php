<?php
    function validate($text) {
        $text = trim($text);    
        $text = stripslashes($text);
        $text = htmlspecialchars($text);
        return $text;
    }
?>