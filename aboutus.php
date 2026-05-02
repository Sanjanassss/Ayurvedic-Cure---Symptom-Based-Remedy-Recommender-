<?php
require 'scripts/core.inc.php';
require 'scripts/connect.inc.php';

if (loggedin()) {
	$visitor_name = strtoupper(getfield('name'));
	$visitor_photo = getfield('photo');
	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>About Us</title>
		<link rel="stylesheet" type="text/css" href="stylesheet/aboutus.css" />
		<script type="text/javascript" src="scripts/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="scripts/effects.js"></script>
	</head>

	<body>

		<header>
			<div class="HeaderTitle">
				<img src="images/logo.png" width="70" height="70" border="0" />
			</div>
			<nav>
				<ul>
					<li><a href="home.php">Home</a></li>
					<li><a href="aboutus.php">About Us</a></li>
					<li><a href="findadoc.php">Find a Doctor</a></li>
					<li><a href="profile.php">Profile</a></li>
					<li><a href="connect.php">Connect</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</nav>
		</header>
		<hr>
		<div class="full_status">
			<div class="status">
				<div class="status_image"><img src="<?php echo $visitor_photo; ?>" width="40" height="40"></div>
				<div class="status_name">Welcome <?php echo $visitor_name; ?></div>
			</div>
		</div>
		<div class="heading">
			<h1>About Us</h1>
		</div>
		<div class="menu">
		<h2><i>A System to Recommend Ayurvedic Cure for Health Issues</i></h2>
  <p>
    <i>A System to Recommend Ayurvedic Cure for Health Issues</i> is an undergraduate project aimed at developing a comprehensive system for recommending Ayurvedic remedies based on a user's health symptoms. This project is being conducted under the expert guidance of faculty members at BIET. The focus is to create a user-friendly platform that leverages traditional Ayurvedic knowledge to suggest personalized treatments. The project combines the ancient principles of Ayurveda with modern technology, offering a holistic approach to healthcare. Through this initiative, we aim to bridge the gap between ancient healing practices and contemporary health needs, providing users with reliable and accessible Ayurvedic solutions.
  </p>		</div>

	</body>

	</html>
	<?php
} else {
	header('Location: userlogin.php');
}

?>