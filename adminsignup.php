<?php
session_start();
require 'scripts/connect.inc.php';  // Ensure this file sets up a $conn variable with MySQLi connection

$signup_message = '';  // Initialize a variable to store the signup message.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['admin_name'], $_POST['admin_password'])) {
        $admin_name = trim($_POST['admin_name']);
        $admin_password = trim($_POST['admin_password']);

        if (!empty($admin_name) && !empty($admin_password)) {
            // Check if the admin name already exists in the database
            $stmt = $conn->prepare("SELECT id FROM admin WHERE admin_name = ?");
            $stmt->bind_param("s", $admin_name);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Admin name already exists
                $signup_message = "Admin name already taken.";
            } else {
                // Hash the password before storing
                $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

                // Insert new admin into the database
                $stmt_insert = $conn->prepare("INSERT INTO admin (admin_name, admin_password) VALUES (?, ?)");
                $stmt_insert->bind_param("ss", $admin_name, $admin_password);

                if ($stmt_insert->execute()) {
                    $signup_message = "Admin account successfully created. <a href='adminlogin.php'>Login Now</a>";
                } else {
                    $signup_message = "Failed to create admin account. Please try again.";
                }

                $stmt_insert->close();
            }

            $stmt->close();
        } else {
            $signup_message = "Please fill all the fields.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Sign Up</title>
    <link rel="stylesheet" type="text/css" href="stylesheet/adminsignup.css" />
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
                <li><a href="adminlogin.php">Sign In</a></li>
            </ul>
        </nav>
    </header>
    <hr>

    <div class="heading">
        <h1>Admin Sign Up</h1>
    </div>

    <?php if (!empty($signup_message)): ?>
        <div class="notify">
            <div class="notifytext"><?php echo $signup_message; ?></div>
            <div class="cross">X</div>
        </div>
    <?php endif; ?>

    <form action="adminsignup.php" method="POST">
        <table border="0">
            <tr>
                <td>Admin Username</td>
                <td><input type="text" name="admin_name" class="FormElement" required></td>
            </tr>
            <tr>
                <td>Admin Password</td>
                <td><input type="password" name="admin_password" class="FormElement" required></td>
            </tr>
            <tr>
                <td><input type="submit" value="Sign Up" class="button"></td>
                <td></td>
            </tr>
        </table>
    </form>

    <script type="text/javascript">
        $(document).ready(function(){
            // Show notification if there is a message
            if ($(".notifytext").html() != "") {
                $(".notify").slideDown(1000);  // Show notification with slide down effect
            }

            // Close notification when the "X" is clicked
            $(".cross").click(function(e) {
                $(".notify").slideUp(1000);  // Hide notification with slide up effect
            });
        });
    </script>

</body>
</html>
