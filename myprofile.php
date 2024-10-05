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

// prepare SQL statement to retrieve user data including profile picture path
$sql = "SELECT * FROM users WHERE id = $user_id";

// execute SQL statement
$result = mysqli_query($conn, $sql);

// check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // retrieve user data
    $row = mysqli_fetch_assoc($result);
    $name = $row["fullname"];
    $email = $row["email"];
    $nid = $row["nid"];
    $phone = $row["phone"];    
    $profile_picture_path = $row["profile_picture_path"];
} else {
    // user not found
    echo "User not found.";
    exit();
}

// close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<style>
/* Style for the container */
.container {
    position: relative;
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f7f7f7;
  border: 2px solid #ddd;
  border-radius: 10px;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
}

/* Style for the header */
.header {
  font-size: 36px;
  color: #fff;
  background-color: #ff8c00; /* set a bright orange background color */
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-top: 20px;
  padding: 10px 0;
  border-bottom: 1px solid #ccc;
  box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2); /* add a subtle drop shadow */
}


/* Style for the profile picture */
.profile-picture {
  display: block;
  height: auto;
  margin: 0 auto;  
  width: 200px;
  height: 200px;
  object-fit: cover;
  object-position: center;
}

/* Style for the profile details */
.profile-details {
  text-align: center;
  margin-top: 20px;
}

/* Style for the name */
.name {
  font-size: 24px;
  font-weight: bold;
  margin-top: 10px;
}

/* Style for the email */
.email {
  font-size: 20px;
  margin-top: 10px;
}

/* Style for the NID */
.nid {
  font-size: 20px;
  margin-top: 10px;
}

/* Style for the phone */
.phone {
  font-size: 20px;
  margin-top: 10px;
}

/* Style for the background */
body {
  background-color: #f1f1f1;
  font-family: Arial, sans-serif;
}

.edit-profile-btn {
  background-color: #ff9800;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  text-decoration: none;
  margin-top: 10px;
  align-self: flex-start;
}

.edit-profile-btn:hover {
  background-color: #ffa726;
  cursor: pointer;
}

.rent {
  background-color: #007bff;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  text-decoration: none;
  margin-top: 10px;
  align-self: flex-start;
}

.rent:hover {
  background-color: #0069d9;
  cursor: pointer;
}


ul.logout {
    list-style: none;
    margin: 0;
    padding: 0;
}
    ul.logout li {
    display: inline-block;
}


li a[href="logout.php"] {
  position: absolute;
  bottom: 0;
  right: 0;
  color: #fff;
  background-color: #c9302c;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
}

li a[href="logout.php"]:hover {
  background-color: #a94442;
}

.ordered-items-btn {
  position: absolute;
  bottom: 0;
  left: 0;
  color: #fff;
  background-color:#007bff ;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
}

.ordered-items-btn:hover {
  background-color: #0069d9;
  cursor: pointer;
}





</style>

<head>
    <title>My Profile</title>
</head>
<body>

<div class="container">
    <h1 class="header">My Profile</h1>
    <div class="profile">
        <img class="profile-picture" src="<?php echo $profile_picture_path; ?>" alt="Profile Picture">
        <div class="profile-details">
            <p class="name">Name: <?php echo $name; ?></p>
            <p class="email">Email: <?php echo $email; ?></p>
            <p class="nid">NID: <?php echo $nid; ?></p>
            <p class="phone">Phone: <?php echo $phone; ?></p>          
            
            <a href="addproduct.php" class="rent">Add product</a></br></br></br>           

            <a href="editprofile.php" class="edit-profile-btn">Edit Profile</a>            
        </div>
        
        <a href="ordered_items.php" class="ordered-items-btn">Ordered Items</a>

        <ul class="logout">
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</div>

</body>
</html>
