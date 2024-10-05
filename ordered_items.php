<?php
// start the session
session_start();

// check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "<div style=\"background-color: #f2f2f2; padding: 20px; border: 1px solid #ccc; border-radius: 5px; text-align: center;\">";
    echo "<p style=\"font-size: 24px; color: #333;\">You have not logged in.</p>";
    echo "<a href=\"login.html?redirect=true\" style=\"display: inline-block; background-color: #333; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 16px;\">Click here to go to the login page</a>";
    echo "</div>";

    exit();
}

// retrieve user ID from session
$user_id = $_SESSION["user_id"];

// connect to the database
$conn = mysqli_connect("localhost", "root", "", "rentit");

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Example correction based on hypothetical column names
$getRentedItemsQuery = "SELECT rental_items.name 
FROM rental_items 
INNER JOIN orders ON rental_items.id = orders.item_id 
WHERE orders.user_id = $user_id;
";

$rentedItemsResult = mysqli_query($conn, $getRentedItemsQuery);

// Check if the query was successful
if (!$rentedItemsResult) {
    die("Query failed: " . mysqli_error($conn)); // Show error if query fails
}

// Check if any rows were returned
if (mysqli_num_rows($rentedItemsResult) > 0) {
    echo "<h1>Ordered Rental Items</h1>";
    echo "<table style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f2f2f2;'><th style='border: 1px solid #ccc; padding: 8px;'>Product Name</th></tr>";

    // Iterate over the rental items
    while ($row = mysqli_fetch_assoc($rentedItemsResult)) {
        $product_name = htmlspecialchars($row["name"]); // Escape output to prevent XSS

        echo "<tr><td style='border: 1px solid #ccc; padding: 8px;'>$product_name</td></tr>";
    }

    echo "</table>";
} else {
    echo "<p>No ordered rental items found.</p>";
}

// close connection
mysqli_close($conn);
?>
