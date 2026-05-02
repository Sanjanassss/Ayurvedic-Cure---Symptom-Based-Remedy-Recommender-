<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="stylesheet/adminlogin.css" />
	<script type="text/javascript" src="scripts/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="scripts/effects.js"></script>
</head>

<body>

	<header>
		<div class="HeaderTitle">
			<img src="images/logo.png" width="70" height="70">
		</div>
		<nav>
			<ul>
				<li><a href="home.php">Home</a></li>
				<li><a href="userlogin.php">Sign In</a></li>
				<li><a href="adminsignup.php">Sign Up</a></li>
			</ul>
		</nav>

	</header>
	<hr>

	<div class="heading">
		<h1>Admin Log In</h1>
	</div>
	<div class="notify">
		<div class="notifytext"></div>
		<div class="cross">X</div>
	</div>
	<?php
	require 'scripts/connect.inc.php'; // Ensure this file sets up a $conn variable with MySQLi connection
	require 'scripts/core.inc2.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['admin_name'], $_POST['admin_password'])) {
			$admin_name = trim($_POST['admin_name']);
			$admin_password = trim($_POST['admin_password']);

			if (!empty($admin_name) && !empty($admin_password)) {
				// Prepare the SQL statement to prevent SQL injection
				$stmt = $conn->prepare("SELECT id, admin_password FROM admin WHERE admin_name = ?");
				$stmt->bind_param("s", $admin_name);
				$stmt->execute();
				$stmt->store_result();

				if ($stmt->num_rows === 0) {
					echo '<script type="text/javascript">
                        $(".notify").slideDown(1000);
                        $(".cross").click(function(e) {
                            $(".notify").slideUp(1000);
                        });
                        $(".notify > .notifytext").html("Invalid Username/Password Combination.");
                      </script>';
				} else {
					$stmt->bind_result($user_id, $stored_password);
					$stmt->fetch();

					// Verify password directly
					if ($stored_password === $admin_password) {
						$_SESSION['username'] = $user_id; // Start session and store user ID
						header('Location: adminpanel.php');
						exit;
					} else {
						echo '<script type="text/javascript">
                            $(".notify").slideDown(1000);
                            $(".cross").click(function(e) {
                                $(".notify").slideUp(1000);
                            });
                            $(".notify > .notifytext").html("Invalid Username/Password Combination.");
                          </script>';
					}
				}

				$stmt->close();
			} else {
				echo '<script type="text/javascript">
                    $(".notify").slideDown(1000);
                    $(".cross").click(function(e) {
                        $(".notify").slideUp(1000);
                    });
                    $(".notify > .notifytext").html("Please fill all the fields.");
                  </script>';
			}
		}
	}
	?>



	<form action="adminlogin.php" method="POST">
		<table border="0">


			<tr>
				<td>Admin Username</td>
				<td><input type="text" name="admin_name" class="FormElement" r></td>
			</tr>


			<tr>
				<td>Admin Password</td>
				<td><input type="password" name="admin_password" class="FormElement"></td>
			</tr>


			<tr>
				<td>
					<input type="submit" value="Sign In" class="button">
				</td>
				<td>
				</td>

			</tr>
		</table>
	</form>

</body>

</html>