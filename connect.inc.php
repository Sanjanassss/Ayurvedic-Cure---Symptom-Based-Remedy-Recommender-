<?php
$host = '127.0.0.1';
$user = 'root';
$password = '';
$mysql_db = 'mp';
$conn_err = 'Could not connect';

// Create a connection using mysqli
$conn = new mysqli($host, $user, $password, $mysql_db);

// Check connection
if ($conn->connect_error) {
    die($conn_err . ": " . $conn->connect_error);
}

?>