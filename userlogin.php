<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>User Login</title>
	<link rel="stylesheet" type="text/css" href="stylesheet/userlogin.css" />
	<script type="text/javascript" src="scripts/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="scripts/effects.js"></script>
</head>

<body>

	<header>
		<div class="HeaderTitle">
			<img src="images/logo.png">

		</div>
		<nav>
			AYURVEDIC CURE
		</nav>

	</header>
	<hr>
	<div class="notify">
		<div class="notifytext"></div>
		<div class="cross">X</div>
	</div>



	<?php
	require 'scripts/connect.inc.php'; // Ensure this sets up a $conn variable for MySQLi connection
	require 'scripts/core.inc.php';

	date_default_timezone_set('Asia/Calcutta');
	$date = date('Y/m/d H:i:s');

	// Get client IP address
	$ip = $_SERVER['HTTP_CLIENT_IP'] ??
		$_SERVER['HTTP_X_FORWARDED_FOR'] ??
		$_SERVER['REMOTE_ADDR'];

	// Detect browser using `get_browser`
	$browser = strtolower(get_browser(null, true)['browser'] ?? 'unknown');

	// Insert IP, browser, and access time into the database
	$query_security = "INSERT INTO `security` (`ip`, `browser`, `time`) VALUES (?, ?, ?)";
	$stmt_security = $conn->prepare($query_security);
	$stmt_security->bind_param("sss", $ip, $browser, $date);
	$stmt_security->execute();
	$stmt_security->close();

	// Handle login form submission
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (
			isset($_POST['username'], $_POST['password']) &&
			!empty($_POST['username']) &&
			!empty($_POST['password'])
		) {
			$username = trim($_POST['username']);
			$password = trim($_POST['password']); // Directly use the plaintext password
	
			// Use prepared statement for user authentication
			$query = "SELECT `id` FROM `signup` WHERE `username` = ? AND `password` = ?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ss", $username, $password); // Compare plaintext passwords
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows === 0) {
				echo '<script type="text/javascript">
                    $(".notify").slideDown(1000);
                    $(".cross").click(function() {
                        $(".notify").slideUp(1000);
                    });
                    $(".notify > .notifytext").html("Invalid Username/Password Combination.");
                  </script>';
			} else {
				$stmt->bind_result($user_id);
				$stmt->fetch();
				$_SESSION['user_id'] = $user_id; // Store user ID in the session
				header('Location: home.php');
				exit;
			}

			$stmt->close();
		} else {
			echo '<script type="text/javascript">
                $(".notify").slideDown(1000);
                $(".cross").click(function() {
                    $(".notify").slideUp(1000);
                });
                $(".notify > .notifytext").html("Please fill all the fields.");
              </script>';
		}
	}
	?>



	<form action="<?php echo $current_file; ?>" method="POST">
		<table border="0">


			<tr>
				<td>Username</td>
				<td><input type="text" name="username" class="FormElement" maxlength="45" required></td>
			</tr>


			<tr>
				<td>Password</td>
				<td><input type="password" name="password" class="FormElement" maxlength="45" required></td>
			</tr>


			<tr>
				<td>
				</td>
				<td>
					<input type="submit" value="Log IN" class="button">
				</td>
			</tr>
		</table>
	</form>
	<div class="WantSignUp"><a href="adminlogin.php">forgot password</a> ?
		<br><br>
		Click to <a href="signup.php">Sign Up</a>
		<br>
		<br>
		Go to <a href="adminlogin.php">Admin Panel</a>
	</div>


</body>

</html>