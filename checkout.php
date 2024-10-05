<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  // User not logged in
  $response = array(
    'success' => false,
    'message' => 'User not logged in.'
  );  
  exit();
}

$user_id = $_SESSION["user_id"];
$fullname = '';
$phone = '';

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "rentit");

// Check connection
if (!$conn) {
  $response = array(
    'success' => false,
    'message' => 'Database connection error.'
  );  
  exit();
}

// Get fullname and phone from the users table
$getUserDataQuery = "SELECT fullname, phone FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $getUserDataQuery);
if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $fullname = $row['fullname'];
  $phone = $row['phone'];
} else {
  $response = array(
    'success' => false,
    'message' => 'Failed to retrieve user data from the users table.'
  );  
  exit();
}


// Close the database connection
mysqli_close($conn);


?>





<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: lightslategrey;
    }

    h1 {
      font-size: 3em;
      margin-top: 0;
      text-align: center;
    }

    #order-summary,
    #user-details {
      width: 50%;
      margin: 0 auto;
      background-color: white;
      padding: 20px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
    }

    th,
    td {
      padding: 10px;
      text-align: left;
    }

    tfoot td {
      font-weight: bold;
    }

    #checkout-message {
      text-align: center;
      color: red;
      font-weight: bold;
      margin-top: 20px;
    }

    #user-details label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    #user-details input[type="text"],
    #user-details input[type="submit"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    #user-details input[type="submit"] {
      background-color: lightslategrey;
      color: white;
      cursor: pointer;
    }

    #user-details input[type="submit"]:hover {
      background-color: gray;
    }

    #user-details input[type="submit"]:focus {
      outline: none;
    }
  </style>
</head>
<body>
  <h1>Checkout</h1>

  <div id="order-summary">
    <h2>Order Summary</h2>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody id="order-items">
        <!-- Order items will be dynamically added here -->
      </tbody>
      <tfoot>
        <tr>
          <td>Total:</td>
          <td id="total-price"></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div id="user-details">
    <h2>User Details</h2>
    <form id="checkout-form" action="retrieve_data.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $fullname; ?>" readonly>

  <label for="address">Address:</label>
  <input type="text" id="address" name="address" required>

  <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" readonly>

  <label for="voucher">Voucher Code:</label>
  <input type="text" id="voucher" name="voucher">

  <input type="submit" value="Place Order">
</form>

  </div>

  <div id="checkout-message"></div>

  <script src="checkout.js"></script>
</body>
</html>
