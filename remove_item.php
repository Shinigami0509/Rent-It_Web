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

// Check if the POST data is set
if (isset($_POST["remove_item_id"])) {
    $removeItemId = $_POST["remove_item_id"];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "rentit");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Remove the item from the rented_items table
    $sql = "DELETE FROM rented_items WHERE rental_id = '$removeItemId' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "Item removed successfully.";
    } else {
        echo "Error removing item: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    exit();
} else {
    echo "Invalid request.";
}
?>
