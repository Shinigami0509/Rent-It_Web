<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  $response = array(
    'success' => false,
    'message' => 'User not logged in.'
  );
  echo json_encode($response);
  exit();
}

$user_id = $_SESSION["user_id"];
$fullname = '';
$phone = '';
$address = $_POST['address'];
$voucher = $_POST['voucher'];
$rentalIds = explode(',', $_POST['rental_ids']); // Get rental IDs from form data

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "rentit");

if (!$conn) {
  $response = array(
    'success' => false,
    'message' => 'Database connection error.'
  );
  echo json_encode($response);
  exit();
}

// Retrieve user data from the `users` table
$sql = "SELECT fullname, phone FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $fullname = $row["fullname"];
    $phone = $row["phone"];
} else {
    $response = array(
      'success' => false,
      'message' => 'User not found.'
    );
    echo json_encode($response);
    exit();
}

// Update the user's address
$updateQuery = "UPDATE users SET address = '$address' WHERE id = '$user_id'";
if (!mysqli_query($conn, $updateQuery)) {
  $response = array(
    'success' => false,
    'message' => 'Failed to update user address.'
  );
  echo json_encode($response);
  exit();
}

// Retrieve the user's rented items (with rental_id)
$getRentedItemsQuery = "
  SELECT rental_id, rental_items.name, rental_items.price 
  FROM rented_items 
  INNER JOIN rental_items ON rented_items.rental_id = rental_items.id 
  WHERE rented_items.user_id = '$user_id'";
$rentedItemsResult = mysqli_query($conn, $getRentedItemsQuery);

if (mysqli_num_rows($rentedItemsResult) > 0) {
  // Loop through each rented item and insert into orders table
  while ($item = mysqli_fetch_assoc($rentedItemsResult)) {
    $rental_id = $item['rental_id']; // Rental ID for the item
    $name = $item['name'];
    $price = $item['price'];
    
    // Save the order with the rental ID into the `orders` table
    $saveOrderQuery = "
      INSERT INTO orders (user_id, fullname, address, phone, voucher, rental_id, product_name, price) 
      VALUES ('$user_id', '$fullname', '$address', '$phone', '$voucher', '$rental_id', '$name', '$price')";

    if (!mysqli_query($conn, $saveOrderQuery)) {
      $response = array(
        'success' => false,
        'message' => 'Failed to save order for rental_id: ' . $rental_id
      );
      echo json_encode($response);
      exit();
    }
  }

  // After inserting all orders, delete the rented items for this user
  $deleteRentedItemsQuery = "DELETE FROM rented_items WHERE user_id = '$user_id'";
  mysqli_query($conn, $deleteRentedItemsQuery);

  $response = array(
    'success' => true,
    'message' => 'Order placed successfully for all rented items.'
  );
} else {
  $response = array(
    'success' => false,
    'message' => 'No rented items found.'
  );
}

// Close the database connection
mysqli_close($conn);

echo json_encode($response);
?>
