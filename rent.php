
<style>
.success-message {
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    text-align: center;
}
</style>


<?php
session_start();

// check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: login.html?redirect=true");
    exit();
}

// retrieve user ID from session
$user_id = $_SESSION["user_id"];

if (isset($_POST["rent_now"])) {
    // retrieve the selected rental item ID from the form
    $rental_id = $_POST["rental_id"];

    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "rentit");

    // check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // insert the selected rental item into the database for the current user
    $sql = "INSERT INTO rented_items (user_id, rental_id) VALUES ('$user_id', '$rental_id')";
    if (mysqli_query($conn, $sql)) {
        echo '<div class="success-message">Rental item saved successfully.</div>';
        echo '<script>setTimeout(function() { window.location.href = "cart.php"; }, 1000);</script>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    

    // close connection
    mysqli_close($conn);
}
?>
