document.addEventListener('DOMContentLoaded', () => {
  const orderItems = document.getElementById('order-items');
  const totalPriceElement = document.getElementById('total-price');
  const checkoutForm = document.getElementById('checkout-form');
  const checkoutMessage = document.getElementById('checkout-message');

  let rentalIds = []; // To store rental IDs

  // Retrieve the order items from the database
  const xhr = new XMLHttpRequest();
  xhr.open('GET', 'get_order_items.php', true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.success) {
        // Build the HTML for the order items and collect rental_ids
        const orderItemsHTML = response.orderItems.map(item => {
          rentalIds.push(item.rental_id); // Collect rental_id from each item
          return `
            <tr>
              <td>${item.name}</td>
              <td>Tk ${item.price}</td>
            </tr>
          `;
        }).join('');

        orderItems.innerHTML = orderItemsHTML;
        totalPriceElement.textContent = 'Tk ' + response.totalPrice.toFixed(2);
      } else {
        checkoutMessage.textContent = response.message;
      }
    } else {
      console.error(xhr.responseText);
    }
  };
  xhr.send();

  // Handle the form submission
  checkoutForm.addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(checkoutForm); // Create a FormData object from the form

    // Include rental_ids in the form data (convert the array to a comma-separated string)
    formData.append('rental_ids', rentalIds.join(','));

    const xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
    xhr.open('POST', 'place_order.php', true); // Configure it: POST-request to 'place_order.php'

    // Define what happens on successful data submission
    xhr.onload = function () {
      if (xhr.status === 200) { // If the request was successful
        const response = JSON.parse(xhr.responseText); // Parse the JSON response
        if (response.success) {
          // Order placed successfully, redirect to success page
          window.location.href = 'order_success.html'; // Redirect to order success page
        } else {
          checkoutMessage.textContent = response.message; // Display the error message
        }
      } else {
        console.error(xhr.responseText); // Log any other errors
      }
    };

    xhr.send(formData); // Send the form data
  });
});
