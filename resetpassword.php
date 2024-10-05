<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f5f5f5;
		}

		.container {
			margin: 0 auto;
			padding: 20px;
			width: 400px;
			background-color: #fff;
			border-radius: 5px;
			box-shadow: 0px 0px 10px #888888;
		}

		h2 {
			text-align: center;
			margin-top: 0;
		}

		label {
			display: block;
			margin-bottom: 10px;
		}

		input[type="password"] {
			width: 100%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			margin-bottom: 20px;
			box-sizing: border-box;
		}

		input[type="submit"] {
			background-color: #4CAF50;
			color: #fff;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			font-size: 16px;
			margin-bottom: 20px;
		}

		input[type="submit"]:hover {
			background-color: #3e8e41;
		}

		.success {
			color: #4CAF50;
			text-align: center;
			margin-bottom: 20px;
		}
	</style>
</head>
<body>
	<?php
	session_start();

	// Check if the form has been submitted for resetting the password
	if (isset($_POST["reset"])) {
		// Retrieve the new password and email from the form data
		$new_password = $_POST["new_password"];
		$email = $_SESSION["email"];

		// Check if the password and confirm password fields match
		if ($_POST["new_password"] != $_POST["confirm_password"]) {
			echo "<div style=\"background-color: #f2f2f2; padding: 20px; border: 1px solid #ccc; border-radius: 5px; text-align: center;\">";
			echo "<p style=\"font-size: 24px; color: #333;\">Passwords do not match. Please try again.</p>";

			  
			echo "</div>";
			
		} else {
			// Update the password in the database
			$conn = mysqli_connect("localhost", "root", "", "rentit");
			$sql = "UPDATE users SET password ='$new_password' WHERE email='$email'";
			$result = mysqli_query($conn, $sql);

			if (isset($_SESSION["email"]))  {
				echo "<div style=\"background-color: #f2f2f2; padding: 20px; border: 1px solid #ccc; border-radius: 5px; text-align: center;\">";
				echo "<p style=\"font-size: 24px; color: #333;\">Your password has been reset successfully.</p>";
				echo "<a href=\"login.html?redirect=true\" style=\"display: inline-block; background-color: #333; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 16px;\">Click here to go to the login page</a>";
			  
				echo "</div>";

				// Unset all of the session variables
				$_SESSION = array();

				// Destroy the session
				session_destroy();
				  
				  exit();
			  } else {
				// Password reset failed. Show error message
				header("location: login.html");
			}
		}
	}	
	?>
	<div class="container">
		<h2>Reset Password</h2>
		<form action="" method="post">
			<label for="new_password">New Password:</label>
			<input type="password" id="new_password" name="new_password" required>
			<label for="confirm_password">Confirm Password:</label>
			<input type="password" id="confirm_password" name="confirm_password" required>
			<input type="submit" name="reset" value="Reset Password">
		</form>
	</div>
</body>
</html>
