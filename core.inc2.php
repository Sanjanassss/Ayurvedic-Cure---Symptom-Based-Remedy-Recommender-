<?php
ob_start();
session_start();

// Store current file name
$current_file = $_SERVER['SCRIPT_NAME'];

// Store HTTP referer if it exists
$http_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// Check if the user is logged in
function loggedin()
{
  return isset($_SESSION['username']) && !empty($_SESSION['username']);
}

// Fetch a specific field for the logged-in admin user
function getfieldadmin($field)
{
  global $conn; // Ensure $conn is accessible here
  if (isset($_SESSION['username'])) {
    $user_id = $_SESSION['username'];

    // Use a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT `$field` FROM `admin` WHERE `id` = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
      $stmt->bind_result($result);
      if ($stmt->fetch()) {
        return $result;
      }
    }
    $stmt->close();
  }
  return null; // Return null if the field or session doesn't exist
}
?>