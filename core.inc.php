<?php
ob_start();
session_start();
$current_file = $_SERVER['SCRIPT_NAME'];

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
  $http_referer = $_SERVER['HTTP_REFERER'];
}

function loggedin()
{

  if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    return true;
  } else {
    return false;
  }
}

function getfield($field)
{
  // Use mysqli_query for querying
  $query = "SELECT `$field` FROM `signup` WHERE `id` = '" . mysqli_real_escape_string($GLOBALS['conn'], $_SESSION['user_id']) . "'";

  if ($query_run = mysqli_query($GLOBALS['conn'], $query)) {
    // Fetch the result using mysqli_fetch_assoc
    if ($query_result = mysqli_fetch_assoc($query_run)) {
      return $query_result[$field];
    }
  }

  return null; // Return null if something goes wrong
}

?>