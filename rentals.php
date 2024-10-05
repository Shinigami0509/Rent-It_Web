<?php
session_start();

// connect to the database
$conn = mysqli_connect("localhost", "root", "", "rentit");

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// retrieve user ID from session
if (isset($_SESSION["user_id"])) {
    // User is logged in
    // retrieve user ID from session
    $user_id = $_SESSION["user_id"];
    // prepare SQL statement to retrieve user data including profile picture path
$sql = "SELECT * FROM users WHERE id = $user_id";

// execute SQL statement
$result = mysqli_query($conn, $sql);

// check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // retrieve user data
    $row = mysqli_fetch_assoc($result);    
    $profile_picture_path = $row["profile_picture_path"];
} else {
    // user not found
    echo "User not found.";
    exit();
}
}




// search functionality
if (isset($_GET["search"])) {
    $search = $_GET["search"];

    // retrieve rental items from the database based on search keyword
    $sql = "SELECT * FROM rental_items WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);
} else {
    // retrieve all rental items from the database
    $sql = "SELECT * FROM rental_items";
    $result = mysqli_query($conn, $sql);
}

// close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rentals - Rent It</title>
    <style>
        /* Main styles */

body {
    font-family: Arial, sans-serif;
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    border-radius: 50%;
    margin: 0 auto;
    object-position: center;
}

header {
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 999;
}

header nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: flex-end;
}

header nav ul li {
    margin-left: 20px;
}

header nav ul li:first-child {
    margin-left: 0;
}

header nav ul li a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
    transition: color 0.3s ease;
}

header nav ul li a:hover {
    color: #f00;
}

.profile-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-left: 10px;
    margin-top: 10px;
    float: right;
  }
  
  

section#hero {
    background-image: url('https://picsum.photos/id/1018/2000/1000');
    background-size: cover;
    background-position: center;
    height: 600px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

section#hero h2 {
    font-size: 60px;
    font-weight: bold;
    color: #fff;
    margin-bottom: 30px;
}

section#hero p {
    font-size: 24px;
    color: #fff;
    margin-bottom: 50px;
}

.btn {
    display: inline-block;
    background-color: #f00;
    color: #fff;
    font-weight: bold;
    padding: 12px 30px;
    border-radius: 30px;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #fff;
    color: #f00;
    border: 2px solid #f00;
}

section#features {
    background-color: #f9f9f9;
    padding: 100px 0;
}

section#features h3 {
    font-size: 36px;
    font-weight: bold;
    color: #333;
    margin-bottom: 30px;
}

section#features p {
    font-size: 18px;
    color: #333;
    margin-bottom: 50px;
}

.feature-box {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    margin: 0 20px;
}

.feature-box i {
    font-size: 60px;
    color: #f00;
    margin-bottom: 30px;
}

section#rentals {
    padding: 100px 0;
}

section#rentals h2 {
    font-size: 36px;
    font-weight: bold;
    color: #333;
    margin-bottom: 30px;
}

.rental-item {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    margin: 0}
    .search-form {
        margin-bottom: 20px;
        text-align: center;
    }

    .search-input {
        padding: 10px;
        width: 300px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .search-button {
        padding: 10px 20px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .search-button:hover {
        background-color: #555;
    }

    section#rentals h2 {
    font-size: 36px;
    font-weight: bold;
    color: #333;
    margin-top: 50px; /* Add margin-top property */
    text-align: center; /* Add text-align property */
}

    </style>
</head>
<body>
<header>
    <div class="container">
        <h1><a href="dashboard.php">Rent It</a></h1>
            <nav>
            <ul>
  <li><a href="dashboard.php"><img src="photos/demo-home.webp" alt="Home" class="profile-icon" title="Home"> </a></li>
  <li><a href="contact.php"><img src="photos/demo-contact.jpg" alt="Contact Us" class="profile-icon" title="Contact Us"></a></li>
  <?php
  if (isset($_SESSION["user_id"])) {
    // User is logged in, show the profile picture and cart
    echo '<li><a href="cart.php"><img src="photos/demo-cart.webp" alt="My Cart" class="profile-icon" title="My Cart"></a></li>';
    echo '<li><a href="myprofile.php"><img src="' . $profile_picture_path . '" alt="My Profile" class="profile-icon" title="My Profile"></a></li>';
  } else {
    // User is not logged in, show login option and notification
    echo '<li><a href="cart.php" onclick="showLoginNotification()"><img src="photos/demo-cart.webp" alt="My Cart" class="profile-icon" title="My Cart"></a></li>';
    echo '<li><a href="login.html" onclick="showLoginNotification()"><img src="photos/login.jpg" alt="Log In" class="profile-icon" title="Log In"></a></li>';
  }
  ?>
</ul>
            </nav>
        </div>
    </header>

    <section id="rentals">
    
        <h2>Available Rentals</h2>
        <div class="container">
        <form class="search-form" method="GET" action="rentals.php">
                <input class="search-input" type="text" name="search" placeholder="Search rentals">
                <input class="search-button" type="submit" value="Search">
        </form>
<?php

// Check for query execution errors
if (!$result) {
    echo "Error executing the query: " . mysqli_error($conn);
    exit();
}

// Check if any rental items were found
if (mysqli_num_rows($result) > 0) {
    // Loop through each rental item
    while ($row = mysqli_fetch_assoc($result)) {
        $rental_id = $row['id'];
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $image = $row['image'];

        // Display the rental item
        echo '<div class="rental-item">';
        echo '<img src="' . $image . '" alt="' . $name . '" style="max-width: 100%; max-height: 200px;">';
        echo '<h3>' . $name . '</h3>';
        echo '<p>' . $description . '</p>';
        echo '<p>' . $price . ' Tk</p>';

        // Create the form for renting the item
        echo '<form method="post" action="rent.php">';
        echo '<input type="hidden" name="rental_id" value="' . $rental_id . '">';
        echo '<input type="submit" name="rent_now" value="Rent Now" class="btn"></br></br>';
        echo '</form>';

        echo '</div>';
    }
} else {
    // No rental items found
    echo '<p>No rental items available.</p>';
}

?>
    </div>
</section>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Rent It</p>
        </div>
    </footer>
</body>
</html>
<script>
  function showLoginNotification() {
    alert("You are not logged in. Please click OK to go to the login page.");
    window.location.href = "login.html";
  }
</script>