<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Sign Up</title>
	<link rel="stylesheet" type="text/css" href="stylesheet/signup.css" />
	<script type="text/javascript" src="scripts/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="scripts/effects.js"></script>
	<script type="text/javascript" src="scripts/photo.js"></script>
	<script type="text/javascript" src="scripts/search.js"></script>
</head>

<body>
	<div class="help"></div>
	<header>
		<div class="HeaderTitle">
			<img src="images/logo.png" width="70" height="70">
		</div>
		<nav>
			<ul>
				<li><a href="userlogin.php">Sign In</a></li>
			</ul>
		</nav>

	</header>
	<hr>
	<div class="notify">
		<div class="notifytext"></div>
		<div class="cross">X</div>
	</div>
	<div class="heading">
		<h1>User Registration</h1>
	</div>
	<?php
	require 'scripts/connect.inc.php'; // Ensure this file sets up a $conn variable with MySQLi connection
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = strtolower(htmlentities($_POST['name']));
		$username = htmlentities($_POST['username']);
		$age = strtolower(htmlentities($_POST['age']));
		$sex = strtolower(htmlentities($_POST['sex']));
		$city = strtolower(htmlentities($_POST['city']));
		$email = htmlentities($_POST['email']);
		$password_raw = htmlentities($_POST['password']); // Keep the raw password
		$confirmpassword = htmlentities($_POST['confirmpassword']);
		$sec_que = strtolower(htmlentities($_POST['sec_que']));
		$answer = strtolower(htmlentities($_POST['answer']));
		$filename = htmlentities($_FILES['file']['name']);
		$size = $_FILES['file']['size'];
		$type = $_FILES['file']['type'];
		$tmp_name = $_FILES['file']['tmp_name'];
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$max_size = 2097152;
		$location = 'profiles/';

		if (!empty($name) && !empty($username) && !empty($filename) && !empty($age) && !empty($sex) && !empty($city) && !empty($email) && !empty($password_raw) && !empty($confirmpassword) && !empty($sec_que) && !empty($answer)) {

			// Check username availability
			$stmt_username = $conn->prepare("SELECT id FROM signup WHERE username = ?");
			$stmt_username->bind_param("s", $username);
			$stmt_username->execute();
			$stmt_username->store_result();

			// Check email availability
			$stmt_email = $conn->prepare("SELECT id FROM signup WHERE email = ?");
			$stmt_email->bind_param("s", $email);
			$stmt_email->execute();
			$stmt_email->store_result();

			if ($stmt_username->num_rows > 0) {
				echo '<script type="text/javascript">
                        $(".notify").slideDown(1000);
                        $(".cross").click(function(e) {
                            $(".notify").slideUp(1000);
                        });
                        $(".notify > .notifytext").html("Username already chosen");
                      </script>';
			} elseif ($stmt_email->num_rows > 0) {
				echo '<script type="text/javascript">
                        $(".notify").slideDown(1000);
                        $(".cross").click(function(e) {
                            $(".notify").slideUp(1000);
                        });
                        $(".notify > .notifytext").html("Email Id already registered.");
                      </script>';
			} elseif ($password_raw !== $confirmpassword) {
				echo '<script type="text/javascript">
                        $(".notify").slideDown(1000);
                        $(".cross").click(function(e) {
                            $(".notify").slideUp(1000);
                        });
                        $(".notify > .notifytext").html("Password combination does not match.");
                      </script>';
			} elseif (($extension == 'jpg' || $extension == 'jpeg') && $size <= $max_size && ($type == 'image/jpg' || $type == 'image/jpeg')) {
				// File upload logic
				move_uploaded_file($tmp_name, $location . $filename);
				$file_path = $location . $username . '.' . $extension;
				rename($location . $filename, $file_path);

				// Insert user into database
				$stmt_insert = $conn->prepare("INSERT INTO signup (username, password, name, email, age, sex, city, sec_que, sec_ans, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt_insert->bind_param("ssssisssss", $username, $password_raw, $name, $email, $age, $sex, $city, $sec_que, $answer, $file_path);

				if ($stmt_insert->execute()) {
					echo '<script type="text/javascript">
                            $(".notify").slideDown(1000);
                            $("form").slideUp(1000);
                            $(".cross").click(function(e) {
                                $(".notify").slideUp(1000);
                            });
                            $(".notify > .notifytext").html("You\'ve successfully signed up. Click to go on <a href=\'userlogin.php\'>login page</a>");
                          </script>';
				} else {
					echo '<script type="text/javascript">
                            $(".notify").slideDown(1000);
                            $(".cross").click(function(e) {
                                $(".notify").slideUp(1000);
                            });
                            $(".notify > .notifytext").html("Failed to sign up. Please try again.");
                          </script>';
				}
				$stmt_insert->close();
			} else {
				echo '<script type="text/javascript">
                        $(".notify").slideDown(1000);
                        $(".cross").click(function(e) {
                            $(".notify").slideUp(1000);
                        });
                        $(".notify > .notifytext").html("File should be less than 2 MB and JPEG/JPG.");
                      </script>';
			}
			$stmt_username->close();
			$stmt_email->close();
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
	?>


	<form action="signup.php" method="POST" enctype="multipart/form-data">
		<table border="0">
			<tr>
				<td>Your Name</td>
				<td><input type="text" name="name" id="name" value="" class="FormElement" required></td>
			</tr>
			<tr>
				<td>Choose a Username</td>
				<td><input type="text" name="username" id="searchusername" class="FormElement" required></td>
			</tr>
			<tr>
				<td>Upload Your photo</td>
				<td><input type="file" name="file" id="photo" class="file_upload" onClick="imageDown();"
						onChange="readURL(this,'img_prev_Photo');" required></td>

			</tr>
			<tr>
				<td></td>
				<td>
					<div id="imgDisplayPhoto" align='center' style="border: 1px solid;height:160px;width:120px;">
						<img id="img_prev_Photo" src="images/profile.png" alt="select a image" border="0"
							style="height:160px;width:120px;">
					</div>
				</td>

			</tr>

			<tr>
				<td>Age</td>
				<td><input type="text" name="age" id="age" value="" class="FormElementAge" maxlength="3" required></td>
			</tr>

			<tr>
				<td>Sex</td>
				<td>
					<select name="sex" id="sex" required>
						<option value="Gender" selected>Gender</option>
						<option value="male">Male</option>
						<option value="female">Female</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>City</td>
				<td><select name="city" id="city" required>
						<option value="davanagere" selected>Davanagere</option>
						<option value="harihar">Harihar</option>
						<option value="channagiri">Channagiri</option>
						<option value="chitradurga">Chitradurga</option>
					</select>
				</td>
			</tr>



			<tr>
				<td>E-Mail</td>
				<td><input type="email" name="email" id="email" value="" class="FormElement" required></td>
			</tr>

			<tr>
				<td>Enter New Password</td>
				<td><input type="password" name="password" id="password" class="FormElement" maxlength="45" required>
				</td>
			</tr>

			<tr>
				<td>Confirm Password</td>
				<td><input type="password" name="confirmpassword" class="FormElement" id="confirmpassword"
						maxlength="45" required></td>
			</tr>

			<tr>
				<td>Secret Question</td>
				<td><select name="sec_que" id="sec_que" required>
						<option value="Which is your first school ?">Which is your first school ?</option>
						<option value="Who is your favourite teacher ?" selected>Who is your favourite teacher ?
						</option>
						<option value="What is your pet name ?">What is your pet name ?</option>
						<option value="Where is your bith-place ?">Where is your bith-place ?</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>Answer</td>
				<td><input type="text" name="answer" class="FormElement" id="sec_ans" required></td>
			</tr>
			<tr>
				<td>
					<input type="submit" value="submit" class="button">
				</td>
			</tr>
		</table>
	</form>


</body>

</html>