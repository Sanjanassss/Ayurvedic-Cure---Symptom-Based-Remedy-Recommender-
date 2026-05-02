<?php
require 'scripts/connect.inc.php';
require 'scripts/core.inc2.php';
$admin_name = strtoupper(getfieldadmin('admin_name'));
if (loggedin()) {

	?>


	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Admin Panel</title>
		<link rel="stylesheet" type="text/css" href="stylesheet/adminpanel.css" />
		<script type="text/javascript" src="scripts/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="scripts/effects.js"></script>
		<script type="text/javascript" src="scripts/photo.js"></script>
		<script type="text/javascript" src="scripts/search.js"></script>
	</head>

	<body>
		<div class="full">
			<div class="ask">
				<div class="ask_notify"></div>
				<div class="yes">YES</div>
				<div class="no">NO</div>
				<div class="close">CLOSE</div>
			</div>
		</div>
		<header>
			<div class="HeaderTitle">
				<img src="images/logo.png" width="70" height="70" border="0" />
			</div>
			<nav>
				<ul>
					<li><a href="adminpanel.php">Admin Panel</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</nav>
		</header>
		<hr>
		<div class="full_status">
			<div class="status">
				<div class="status_image"><img src="images/admin.png" width="40" height="40"></div>
				<div class="status_name">Welcome Admin <?php echo $admin_name; ?></div>
			</div>
		</div>
		<div class="heading">
			<h1>Admin Panel</h1>
		</div>
		<div class="notify">
			<div class="notifytext"></div>
			<div class="cross">X</div>
		</div>
		<div class="menu">
			<div class="bar">
				<ul>
					<li>Add a Doctor</li>
					<li>All Users</li>
					<li>User Management</li>
					<li>All Doctors</li>
					<li>Doctor Management</li>
					<li>User's Response</li>
				</ul>
			</div>
			<div class="query">
				<div class="move">
					<div class="doctor">
						<?php
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							if (
								isset($_POST['Doc_name'], $_POST['type'], $_POST['info'], $_POST['city'], $_POST['Doc_email'], $_POST['doc_contact'])
							) {
								// Sanitize and validate inputs
								$Doc_name = htmlspecialchars(trim($_POST['Doc_name']));
								$type = htmlspecialchars(trim($_POST['type']));
								$info = htmlspecialchars(trim($_POST['info']));
								$city = htmlspecialchars(trim($_POST['city']));
								$Doc_email = filter_var(trim($_POST['Doc_email']), FILTER_VALIDATE_EMAIL);
								$doc_contact = htmlspecialchars(trim($_POST['doc_contact']));

								// Collect tags dynamically
								$tags = [];
								for ($i = 1; $i <= 33; $i++) {
									if (isset($_POST['tags' . $i])) {
										$tags[] = htmlspecialchars(trim($_POST['tags' . $i]));
									}
								}
								$array_string = implode(' ', $tags);

								// File upload handling
								$max_size = 2097152; // 2 MB
								$allowed_extensions = ['jpg', 'jpeg'];
								$upload_dir = 'doc/';
								$file = $_FILES['file'];

								if (!empty($Doc_name) && !empty($type) && !empty($info) && !empty($city) && $Doc_email && !empty($doc_contact)) {
									if ($file['size'] <= $max_size) {
										$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

										if (in_array($file_ext, $allowed_extensions)) {
											$new_filename = $Doc_name . '.' . $file_ext;
											$file_path = $upload_dir . $new_filename;

											// Check if file exists and rename if necessary
											if (file_exists($file_path)) {
												$new_filename = $Doc_name . rand(1000, 9999) . '.' . $file_ext;
												$file_path = $upload_dir . $new_filename;
											}

											// Move the uploaded file
											if (move_uploaded_file($file['tmp_name'], $file_path)) {
												// Database connection
												$conn = new mysqli('localhost', 'username', 'password', 'mp');
												if ($conn->connect_error) {
													die('Database connection failed: ' . $conn->connect_error);
												}

												// Insert into database
												$stmt = $conn->prepare(
													"INSERT INTO doctor (Doc_name, type, info, tags, city, Doc_email, doc_contact, doc_photo) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
												);
												$stmt->bind_param(
													'ssssssss',
													$Doc_name,
													$type,
													$info,
													$array_string,
													$city,
													$Doc_email,
													$doc_contact,
													$file_path
												);

												if ($stmt->execute()) {
													echo '<script>
                                $("document").ready(function() {
                                    slidedown();
                                    $(".notify > .notifytext").html("Successfully uploaded and saved.");
                                });
                            </script>';
												} else {
													echo '<script>
                                $("document").ready(function() {
                                    slidedown();
                                    $(".notify > .notifytext").html("Failed to save data. Please try again.");
                                });
                            </script>';
												}

												$stmt->close();
												$conn->close();
											} else {
												echo '<script>
                            $("document").ready(function() {
                                slidedown();
                                $(".notify > .notifytext").html("Failed to upload the file.");
                            });
                        </script>';
											}
										} else {
											echo '<script>
                        $("document").ready(function() {
                            slidedown();
                            $(".notify > .notifytext").html("Invalid file type. Only JPG/JPEG allowed.");
                        });
                    </script>';
										}
									} else {
										echo '<script>
                    $("document").ready(function() {
                        slidedown();
                        $(".notify > .notifytext").html("File size exceeds 2 MB.");
                    });
                </script>';
									}
								} else {
									echo '<script>
                $("document").ready(function() {
                    slidedown();
                    $(".notify > .notifytext").html("Please fill all the required fields correctly.");
                });
            </script>';
								}
							} else {
								echo '<script>
            $("document").ready(function() {
                slidedown();
                $(".notify > .notifytext").html("Tip: Be careful in changing the things.");
            });
        </script>';
							}
						}
						?>

						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" enctype="multipart/form-data">
							<table border="0">
								<tr>
									<td>Doctor's Name</td>
									<td><input type="text" name="Doc_name" class="FormElement" required></td>
								</tr>
								<tr>
									<td>Doctor's photo</td>
									<td><input type="file" name="file" class="file_upload" onClick="imageDown();"
											onChange="readURL(this,'img_prev_Photo');" required></td>

								</tr>
								<tr>
									<td></td>
									<td>
										<div id="imgDisplayPhoto" align='center'
											style="border: 1px solid;height:160px;width:120px;">
											<img id="img_prev_Photo" src="images/profile.png" alt="select a image"
												border="0" style="height:160px;width:120px;">
										</div>
									</td>

								</tr>
								<tr>
									<td>Type</td>
									<td><select name="type" required>
											<option value="dentist" selected>Dentist</option>
											<option value="ent">ENT</option>
											<option value="gynecologist">Gynecologist</option>
											<option value="neurologist">Neurologist</option>
											<option value="orthopedic">Orthopedic</option>
											<option value="physician">Physician</option>
											<option value="surgeon">Surgeon</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Doctor's Info</td>
									<td><textarea type="text" name="info" class="FormElementtext" maxlength="500"
											required></textarea></td>
								</tr>
								<tr>
									<td>Please select the tags</td>
									<td>
										<input type="checkbox" name="tags1" value="abdomen" class="checkbox">abdomen
										<input type="checkbox" name="tags2" value="assaulted" class="checkbox">assaulted
										<input type="checkbox" name="tags3" value="back" class="checkbox">back
										<input type="checkbox" name="tags4" value="blindness" class="checkbox">blindness
										<input type="checkbox" name="tags5" value="breathe" class="checkbox">breathe
										<input type="checkbox" name="tags6" value="chest" class="checkbox">chest
										<input type="checkbox" name="tags7" value="cold/fever" class="checkbox">cold/fever
										<input type="checkbox" name="tags8" value="fracture" class="checkbox">fracture
										<input type="checkbox" name="tags9" value="head" class="checkbox">head
										<input type="checkbox" name="tags10" value="head-headche"
											class="checkbox">head-headche
										<input type="checkbox" name="tags11" value="hear" class="checkbox">hear
										<input type="checkbox" name="tags12" value="in a traffic accident"
											class="checkbox">in a traffic accident
										<input type="checkbox" name="tags13" value="limb" class="checkbox">limb
										<input type="checkbox" name="tags14" value="nausea" class="checkbox">nausea
										<input type="checkbox" name="tags15" value="neck" class="checkbox">neck
										<input type="checkbox" name="tags16" value="nosebleed" class="checkbox">nosebleed
										<input type="checkbox" name="tags17" value="passurine" class="checkbox">passurine
										<input type="checkbox" name="tags18" value="remember" class="checkbox">remember
										<input type="checkbox" name="tags19" value="shot" class="checkbox">shot
										<input type="checkbox" name="tags20" value="sleep" class="checkbox">sleep
										<input type="checkbox" name="tags21" value="smell things" class="checkbox">smell
										things
										<input type="checkbox" name="tags22" value="sprain" class="checkbox">sprain
										<input type="checkbox" name="tags23" value="stabbed" class="checkbox">stabbed
										<input type="checkbox" name="tags24" value="stomach" class="checkbox">stomach
										<input type="checkbox" name="tags25" value="taste" class="checkbox">taste
										<input type="checkbox" name="tags26" value="teeth/tooth"
											class="checkbox">teeth/tooth
										<input type="checkbox" name="tags27" value="tired" class="checkbox">tired
										<input type="checkbox" name="tags28" value="torn cartilage" class="checkbox">torn
										cartilage
										<input type="checkbox" name="tags29" value="vagina" class="checkbox">vagina
										<input type="checkbox" name="tags30" value="vomit" class="checkbox">vomit
										<input type="checkbox" name="tags31" value="walk" class="checkbox">walk
										<input type="checkbox" name="tags32" value="weak" class="checkbox">weak
										<input type="checkbox" name="tags33" value="write" class="checkbox">write
									</td>
								</tr>
								<tr>
									<!---	<td>Selected Tags</td>
				<td><textarea type="text" name="tags" class="FormElementtext" maxlength="500" required></textarea></td>
			</tr>
		   <script type="text/javascript">
			var i=0;
			$('document').ready(function(e) {
				$('.checkbox').click(function(e) {
			   i = $('.FormElementtext').html(this.value);
				 i++;
				});
			});
			
			
			</script>--->
							<tr>
								<td>City</td>
								<td><select name="city" required>
										<option value="Davangere">Davangere</option>
										<option value="Bengaluru" selected>Bengaluru</option>
										<option value="Mandya">Mandya</option>
										<option value="Mysore">Mysore</option>
									</select>
								</td>
							</tr>


							<tr>
								<td>E-Mail</td>
								<td><input type="email" name="Doc_email" class="FormElement" required></td>
							</tr>

							<tr>
								<td>Contact No.</td>
								<td><input type="text" name="doc_contact" class="FormElement" required></td>
							</tr>

							<tr>
								<td>
									<input type="submit" value="submit" class="button">
								</td>
							</tr>
						</table>
					</form>
				</div>

				<div class="user">
					<?php



					// Fetch users from the database
					$query_users = "SELECT * FROM signup";
					$result = $conn->query($query_users);

					if ($result && $result->num_rows > 0) {
						while ($user = $result->fetch_assoc()) {
							echo '<div class="result">
            <div class="result_left"><img src="' . htmlspecialchars($user['photo']) . '" alt="User Photo"></div>
            <div class="result_right">
                <b>' . htmlspecialchars($user['id']) . '. ' . htmlspecialchars($user['name']) . '</b><br>
                <b>Username: </b>' . htmlspecialchars($user['username']) . '<br>
                <b>E-mail: </b>' . htmlspecialchars($user['email']) . '<br>
                <b>Sex: </b>' . htmlspecialchars($user['sex']) . '<br>
                <b>Age: </b>' . htmlspecialchars($user['age']) . '<br>
                <b>City: </b>' . htmlspecialchars($user['city']) . '<br>
                <b>Secret Que: </b>' . htmlspecialchars($user['sec_que']) . '<br>
                <b>Secret Answer: </b>' . htmlspecialchars($user['sec_ans']) . '<br>
            </div>
        </div><br>';
						}
					} else {
						echo '<p>No users found.</p>';
					}

					// Close the connection
					$conn->close();
					?>

				</div>
				<div class="delete">
					<?php
					if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && !empty(trim($_POST['delete']))) {
						$delete = intval($_POST['delete']); // Ensure `id` is an integer for security
				
						// Database connection
						// $host = 'localhost'; // Replace with your host
						// $user = 'username'; // Replace with your username
						// $password = 'password'; // Replace with your password
						// $database = 'database_name'; // Replace with your database name
						$host = '127.0.0.1';
						$user = 'root';
						$password = '';
						$database = 'mp';

						$conn = new mysqli($host, $user, $password, $database);

						// Check connection
						if ($conn->connect_error) {
							die('Connection failed: ' . $conn->connect_error);
						}

						// Fetch the photo path for the user to delete
						$stmt = $conn->prepare("SELECT photo FROM signup WHERE id = ?");
						$stmt->bind_param("i", $delete);
						$stmt->execute();
						$result = $stmt->get_result();

						if ($result->num_rows > 0) {
							$photo = $result->fetch_assoc()['photo'];

							// Delete the photo file if it exists
							if (!empty($photo) && file_exists($photo)) {
								unlink($photo);
							}

							// Delete the user record
							$delete_stmt = $conn->prepare("DELETE FROM signup WHERE id = ?");
							$delete_stmt->bind_param("i", $delete);

							if ($delete_stmt->execute()) {
								echo '<script type="text/javascript">
                $("document").ready(function(){
                    slidedown();
                    $(".notify > .notifytext").html("Successfully deleted the user with id ' . $delete . '");
                });
            </script>';
							} else {
								echo '<script type="text/javascript">
                $("document").ready(function(){
                    slidedown();
                    $(".notify > .notifytext").html("Failed to delete the user. Please try again.");
                });
            </script>';
							}

							$delete_stmt->close();
						} else {
							echo '<script type="text/javascript">
            $("document").ready(function(){
                slidedown();
                $(".notify > .notifytext").html("User with id ' . $delete . ' not found.");
            });
        </script>';
						}

						$stmt->close();
						$conn->close();
					} else {
						echo '<script type="text/javascript">
        $("document").ready(function(){
            slidedown();
            $(".notify > .notifytext").html("Invalid request. Please provide a valid user ID.");
        });
    </script>';
					}
					?>

					<form action="adminpanel.php" method="post" id="delete_doc1">
						<table>
							<tr>
								<td>ID of the user</td>
								<td>
									<input type="text" name="delete" id="ask_permission_value1" class="FormElement"
										required>

								</td>
								<td>
									<input type="button" value="Delete User" id="ask_permission1" class="button">
								</td>
							</tr>
					</form>
					<form>
						<tr>


							<td>Search a user</td>
							<td>
								<input type="text" id="search" name="search" class="FormElement"
									placeholder="start to type">

							</td>
							<td>

							</td>
						</tr>
						</table>
					</form>

					<div class="search_result"></div>
				</div>
				<div class="delete2">
					<?php

					$host = '127.0.0.1';
					$user = 'root';
					$password = '';
					$database = 'mp';

					$conn = new mysqli($host, $user, $password, $database);
					// Fetch doctors from the database
					$query_doctors = "SELECT * FROM doctor";
					$result = $conn->query($query_doctors);


					if ($result && $result->num_rows > 0) {
						while ($doctor = $result->fetch_assoc()) {
							echo '<div class="result">
            <div class="result_left">
                <img src="' . htmlspecialchars($doctor['doc_photo']) . '" alt="Doctor Photo">
            </div>
            <div class="result_right">
                <b>' . htmlspecialchars($doctor['id']) . '. ' . htmlspecialchars($doctor['Doc_name']) . '</b><br>
                <b>Type: </b>' . htmlspecialchars($doctor['type']) . '<br>
                <b>E-mail: </b>' . htmlspecialchars($doctor['Doc_email']) . '<br>
                <b>City: </b>' . htmlspecialchars($doctor['city']) . '<br>
                <b>Tags: </b>' . htmlspecialchars($doctor['tags']) . '<br>
				<b>indo: </b>' . htmlspecialchars($doctor['info']) . '<br>
				<b>doc_contact: </b>' . htmlspecialchars($doctor['doc_contact']) . '<br>
            </div>
        </div><br>';
						}
					} else {
						echo '<p>No doctors found.</p>';
					}


					?>

				</div>
				<div class="delete3">
					<?php
					if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_doc']) && !empty(trim($_POST['delete_doc']))) {
						$delete_doc = intval($_POST['delete_doc']); // Ensure the `id` is treated as an integer
				
						// Database connection
						$host = '127.0.0.1';
						$user = 'root';
						$password = '';
						$database = 'mp';
				
						$conn = new mysqli($host, $user, $password, $database);

						// Check connection
						if ($conn->connect_error) {
							die('Connection failed: ' . $conn->connect_error);
						}

						// Fetch the doctor's photo
						$stmt = $conn->prepare("SELECT doc_photo FROM doctor WHERE id = ?");
						$stmt->bind_param("i", $delete_doc);
						$stmt->execute();
						$result = $stmt->get_result();

						if ($result->num_rows > 0) {
							$doctor = $result->fetch_assoc();
							$photo_path = $doctor['doc_photo'];

							// Delete the photo file if it exists
							if (!empty($photo_path) && file_exists($photo_path)) {
								unlink($photo_path);
							}

							// Delete the doctor record
							$delete_stmt = $conn->prepare("DELETE FROM doctor WHERE id = ?");
							$delete_stmt->bind_param("i", $delete_doc);

							if ($delete_stmt->execute()) {
								echo '<script type="text/javascript">
                $("document").ready(function(){
                    slidedown();
                    $(".notify > .notifytext").html("Successfully deleted the Doctor with id ' . $delete_doc . '");
                });
            </script>';
							} else {
								echo '<script type="text/javascript">
                $("document").ready(function(){
                    slidedown();
                    $(".notify > .notifytext").html("Error occurred while deleting the doctor. Please try again.");
                });
            </script>';
							}

							$delete_stmt->close();
						} else {
							echo '<script type="text/javascript">
            $("document").ready(function(){
                slidedown();
                $(".notify > .notifytext").html("Doctor with id ' . $delete_doc . ' not found.");
            });
        </script>';
						}

						$stmt->close();
						$conn->close();
					} else {
						echo '<script type="text/javascript">
        $("document").ready(function(){
            slidedown();
            $(".notify > .notifytext").html("Invalid request. Please provide a valid doctor ID.");
        });
    </script>';
					}
					?>

					<form action="adminpanel.php" method="post" id="delete_doc">
						<table>
							<tr>
								<td>ID of the doctor</td>
								<td>
									<input type="text" name="delete_doc" id="ask_permission_value" class="FormElement"
										required>

								</td>
								<td>
									<input type="button" value="Delete Doctor" id="ask_permission" class="button">

								</td>
							</tr>
					</form>
					<form>
						<tr>


							<td>Search a Doctor</td>
							<td>
								<input type="text" id="search_doc" name="search_doc" class="FormElement"
									placeholder="start to type">

							</td>
							<td>

							</td>
						</tr>
						</table>
					</form>

					<div class="search_result_admin"></div>
				</div>
				<div class="user_connect">
					<?php

					$query_user_connect = "SELECT * FROM connect";
					$query_run_user_connect = $conn->query($query_user_connect);

					while ($query_result_user_connect = $query_run_user_connect->fetch_assoc()) {
						echo '<div class="user_connect_result">
            <b>ID</b>: ' . $query_result_user_connect['id'] . '<br>
            <b>Name</b>: ' . $query_result_user_connect['name'] . '<br>
            <b>Subject</b>: ' . $query_result_user_connect['subject'] . '<br>
            <b>Email</b>: ' . $query_result_user_connect['email'] . '<br>
            <b>Message</b>: ' . $query_result_user_connect['message'] . '<br><hr>
        </div>';
					}

					// Close the connection
					$conn->close();
					?>



				</div>
			</div>

		</div>
	</div>

</body>

</html>
<?php
} else {
	header('Location: adminlogin.php');
}
?>