<?php

function sanitize($mystring){
    $mystring = stripslashes($mystring);
    $mystring = trim($mystring);
    $mystring = htmlspecialchars($mystring);
    return $mystring;
}