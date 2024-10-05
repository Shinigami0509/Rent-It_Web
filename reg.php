<?php
// establish connection to database
$conn = mysqli_connect("localhost", "root", "", "rentit");

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form data
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $nid = $_POST["nid"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];

    // get file paths
    $profile_picture_path = "";
    if(isset($_FILES["profile-picture"]) && $_FILES["profile-picture"]["error"] == 0){
        $target_dir = "uploads/profile_pictures/";
        $profile_picture_path = $target_dir . basename($_FILES["profile-picture"]["name"]);
        if (move_uploaded_file($_FILES["profile-picture"]["tmp_name"], $profile_picture_path)) {
            // file uploaded successfully
        } else {
            // error uploading file
            $profile_picture_path = "";
        }
    }

    $nid_photo_path = "";
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
        $target_dir = "uploads/nid_photos/";
        $nid_photo_path = $target_dir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $nid_photo_path)) {
            // file uploaded successfully
        } else {
            // error uploading file
            $nid_photo_path = "";
        }
    }

    // prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, nid, phone, password, profile_picture_path, nid_photo_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fullname, $email, $nid, $phone, $password, $profile_picture_path, $nid_photo_path);

    // execute statement
    if ($stmt->execute()) {
        // success
        echo "<div style=\"background-color: #f2f2f2; padding: 20px; border: 1px solid #ccc; border-radius: 5px; text-align: center;\">";
        echo "<p style=\"font-size: 24px; color: #333;\">Registration Complete.</p>";
        echo "<a href=\"login.html\" style=\"display: inline-block; background-color: #333; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 16px;\">Click here to go to the login page</a>";
        echo "</div>";
        exit();
    } else {
        // error
        echo "Error: " . $stmt->error;
    }

    // close statement and connection
    $stmt->close();
    mysqli_close($conn);
}
?>

