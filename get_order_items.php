<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  // User not logged in
  $response = array(
    'success' => false,
    'message' => 'User not logged in.'
  );
  echo json_encode($response);
  exit();
}

$user_id = $_SESSION["user_id"];
$conn = mysqli_connect("localhost", "root", "", "rentit");

if (!$conn) {
  $response = array(
    'success' => false,
    'message' => 'Failed to connect to the database.'
  );
  echo json_encode($response);
  exit();
}

// Modify query to select rental_id along with item name and price
$sql = "SELECT rented_items.rental_id, rental_items.name, rental_items.price 
        FROM rented_items
        INNER JOIN rental_items ON rented_items.rental_id = rental_items.id
        WHERE rented_items.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);
$totalPrice = 0;
$orderItems = array();
$rentalIds = array(); // To store unique rental IDs

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $orderItems[] = array(
      'rental_id' => $row['rental_id'],
      'name' => $row['name'],
      'price' => $row['price']
    );
    $totalPrice += $row['price'];
    
    // Add rental_id to the list (in case you need a list of unique rental_ids)
    if (!in_array($row['rental_id'], $rentalIds)) {
      $rentalIds[] = $row['rental_id'];
    }
  }
  
  $response = array(
    'success' => true,
    'orderItems' => $orderItems,
    'totalPrice' => $totalPrice,
    'rentalIds' => $rentalIds // Return all unique rental_ids
  );
} else {
  $response = array(
    'success' => false,
    'message' => 'No items found in the cart.'
  );
}

mysqli_free_result($result);
mysqli_close($conn);

echo json_encode($response);
?>
