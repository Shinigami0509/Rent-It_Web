<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page
    header("Location: login.html?redirect=true");
    exit();
}

// Check if the rental item ID is stored in the session
if (!isset($_SESSION["rental_item_id"])) {
    // Redirect the user to the rentals page or display an error message
    header("Location: rentals.php");
    exit();
}

// Retrieve the rental item ID from the session
$rentalItemId = $_SESSION["rental_item_id"];

// Perform any necessary validation or verification for the rental item ID
if (!isValidRentalItemId($rentalItemId)) {
    // Invalid rental item ID, redirect the user or display an error message
    header("Location: rentals.php");
    exit();
}

// Function to validate the rental item ID
function isValidRentalItemId($rentalItemId) {
    // Perform your validation logic here
    // Example: Check if the rental item ID exists in the database or meets specific criteria

    // Return true if the rental item ID is valid, false otherwise
    return ($rentalItemId >= 1 && $rentalItemId <= 100); // Example validation criteria
}

// Process the rental form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the rental form data
    $rentalStartDate = $_POST["start_date"];
    $rentalEndDate = $_POST["end_date"];
    $rentalQuantity = $_POST["quantity"];

    // Perform any necessary validation or processing of the rental form data
    // ...

    // Example: Store the rental form data in the database
    $conn = mysqli_connect("localhost", "root", "", "rentit");
    // Adjust the table and column names according to your database structure
    $rentalStartDate = mysqli_real_escape_string($conn, $rentalStartDate);
    $rentalEndDate = mysqli_real_escape_string($conn, $rentalEndDate);
    $rentalQuantity = mysqli_real_escape_string($conn, $rentalQuantity);
    $userId = $_SESSION["user_id"];
    $rentalItemId = $_SESSION["rental_item_id"];

    $sql = "INSERT INTO rentals (user_id, item_id, start_date, end_date, quantity)
            VALUES ('$userId', '$rentalItemId', '$rentalStartDate', '$rentalEndDate', '$rentalQuantity')";
    mysqli_query($conn, $sql);
    mysqli_close($conn);

    // Redirect the user to a confirmation page or display a success message
    header("Location: rental_confirmation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <!-- Header content -->
    </header>

    <section id="rental-form">
        <div class="container">
            <h2>Rental Form</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <div class="form-group">
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" name="end_date" required>
</div>
<div class="form-group">
<label for="quantity">Quantity:</label>
<input type="number" id="quantity" name="quantity" min="1" required>
</div>
<button type="submit">Submit</button>
</form>
</div>
</section>
<footer>
    <!-- Footer content -->
    <div class="container">
        <p>&copy; 2023 Rent It</p>
    </div>
</footer>
</body>
</html>
