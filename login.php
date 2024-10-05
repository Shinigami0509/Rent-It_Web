<?php
// check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "rentit");

    // check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // prepare SQL statement
    $sql = "SELECT id FROM users WHERE email = '$email' AND password = '$password'";

    // execute SQL statement
    $result = mysqli_query($conn, $sql);

    // check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        // login successful
        $row = mysqli_fetch_assoc($result);
        $user_id = $row["id"];
        session_start();
        $_SESSION["user_id"] = $user_id;

        header("Location: dashboard.php");
        exit();
    } else {
        // login failed
        echo "<div style=\"background-color: #f2f2f2; padding: 20px; border: 1px solid #ccc; border-radius: 5px; text-align: center;\">";
        echo "<p style=\"font-size: 24px; color: #333;\">Invalid email or password.</p>";
        echo "<a href=\"login.html\" style=\"display: inline-block; background-color: #333; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 16px;\">Try Again</a>";
        echo "</div>";
    }

    // close connection
    mysqli_close($conn);
}
?>
