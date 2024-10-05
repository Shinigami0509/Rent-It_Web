<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f1f1f1;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            margin: 50px auto;
            padding: 20px;
            width: 400px;
        }

        h1 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            border-radius: 5px;
            border: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            padding: 10px;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            padding: 10px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>

<?php
// Start the session (assuming you have a session started for user authentication)
session_start();

// Check if the user is logged in and retrieve the user ID from the session
if (!isset($_SESSION["user_id"])) {
    echo "User not logged in.";
    exit;
}

$userID = $_SESSION["user_id"];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "rentit");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Update profile information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $nid = $_POST["nid"];
    $password = $_POST["password"];
    
    // Update the profile information in the users table
    $sql = "UPDATE users SET fullname='$name', email='$email' WHERE id = $userID";
    
    // Update only if the query runs successfully
    if (mysqli_query($conn, $sql)) {
        // If a new password is provided, update the password as well
        if (!empty($password)) {
            // No password hashing, store the password as plain text (not recommended)
            $passwordUpdateQuery = "UPDATE users SET password='$password' WHERE id = $userID";
            
            if (mysqli_query($conn, $passwordUpdateQuery)) {
                // Password updated successfully
                echo "Password updated successfully.<br>";
            } else {
                echo "Error updating password: " . mysqli_error($conn) . "<br>";
            }
        }

        // Redirect to the profile page after successful update
        header("Location: myprofile.php");
        exit;
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

// Fetch the current user's profile information
$sql = "SELECT * FROM users WHERE id = $userID";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row["fullname"];
    $email = $row["email"];
    $nid = $row["nid"];
} else {
    echo "User not found.";
}

// Close the database connection
mysqli_close($conn);
?>

<div class="container">
    <h1>Edit Profile</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        </div>
        <div class="form-group">
            <label for="nid">NID:</label>
            <input type="text" id="nid" name="nid" value="<?php echo $nid; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">
        </div>
        <div class="form-group">
            <input type="submit" value="Save Changes">
        </div>
    </form>
</div>

</body>
</html>
