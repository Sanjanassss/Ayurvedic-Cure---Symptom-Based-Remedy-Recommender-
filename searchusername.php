<?php
require 'connect.inc.php';

if (isset($_POST['username'])) {
	$username = $_POST['username'];
	$query = "SELECT * FROM `signup` WHERE `username` = '" . mysqli_real_escape_string($GLOBALS['conn'], $username) . "'";

	if ($query_run = mysqli_query($GLOBALS['conn'], $query)) {
		$mysqli_num_rows = mysqli_num_rows($query_run);

		if ($mysqli_num_rows >= 1) {
			echo 'Username already chosen';
		} else {
			if (strlen($username) == 0) {
				echo 'Type a Username';
			} else {
				echo 'Valid Username';
			}
		}
	} else {
		echo 'Query is not running';
	}
}
?>