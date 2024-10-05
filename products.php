<!DOCTYPE html>
<html>
<head>
  <title>Products</title>
  <style>
    /* CSS styles for product listing */
    .product-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    
    .product-card {
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 10px;
      text-align: center;
    }
    
    .product-image {
      max-width: 100%;
      height: auto;
    }
  </style>
</head>
<body>
  <h1>Products</h1>
  
  <div class="product-list">
  <?php
  // Connect to the database (replace host, username, password, and database name with your own)
  $connection = mysqli_connect('localhost', 'root', '', 'rentit');

  // Check if the connection was successful
  if (!$connection) {
    die('Error connecting to the database');
  }

  // Prepare the SQL query to retrieve product information from the uploads table
  $sql = "SELECT * FROM uploads";

  // Execute the SQL query
  $result = mysqli_query($connection, $sql);

  // Check if any products were found
  if (mysqli_num_rows($result) > 0) {
    // Loop through each product and display its information
    while ($row = mysqli_fetch_assoc($result)) {
      $productName = $row["name"];
      $productCategory = $row["category"];
      $productPrice = $row["price"];
      $productDescription = $row["description"];
      $productImage = $row["image"];

      // Display the product listing
      echo '<div class="product-item">';
      echo '<img src="uploads/for_rent/' . $productImage . '" alt="' . $productName . '" class="product-image">';
      echo '<div class="product-details">';
      echo '<h3 class="product-name">' . $productName . '</h3>';
      echo '<p class="product-category">' . $productCategory . '</p>';
      echo '<p class="product-price">$' . $productPrice . '</p>';
      echo '<p class="product-description">' . $productDescription . '</p>';
      echo '</div>';
      echo '</div>';
    }
  } else {
    // No products found
    echo '<p>No products available at the moment.</p>';
  }

  // Close the database connection
  mysqli_close($connection);
  ?>
</div>

</body>
</html>
