<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: login.html?redirect=true");
    exit();
}

// Retrieve user ID from session
$user_id = $_SESSION["user_id"];

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "rentit");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Remove all items from the rented_items table for the current user
$sql = "DELETE FROM rented_items WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "All items removed successfully.";
} else {
    echo "Error removing items: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
exit();
?>
