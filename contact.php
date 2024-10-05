<?php
session_start(); // Start the session

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com'; // SMTP server
$mail->SMTPAuth   = true;
$mail->Username   = 'walidbin.kamal64@gmail.com'; // SMTP username
$mail->Password   = 'xpermlrnmcmikegt'; // SMTP password
$mail->SMTPSecure = 'ssl';
$mail->Port       = 465;

// Database connection settings
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'rentit';

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
  // get form data
  $category = isset($_POST["select_category"]) ? $_POST["select_category"] : '';
  $name = isset($_POST["name"]) ? $_POST["name"] : '';
  $email = isset($_POST["email"]) ? $_POST["email"] : '';
  $phone = isset($_POST["phone"]) ? $_POST["phone"] : '';
  $message = isset($_POST["enter_message"]) ? $_POST["enter_message"] : '';

  // Retrieve the user_id from the session
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

  // Store form data in MySQL database
  try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Prepare the SQL statement
      $stmt = $conn->prepare("INSERT INTO contact_form (user_id, category, name, email, phone, message) VALUES (:user_id, :category, :name, :email, :phone, :message)");
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':category', $category);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':phone', $phone);
      $stmt->bindParam(':message', $message);
      $stmt->execute();

      // Send email to the authority
      $mail->setFrom($email, $name); // Set the sender's email and name
      $mail->addAddress('walidbin.kamal64@gmail.com'); // Set the recipient's email
      $mail->Subject = 'Message from Rent It'; // Include the sender's name in the subject
      $mail->Body = 'Sender Name: ' . $name . "\r\n" .
                    'Sender Email: ' . $email . "\r\n" .
                    'Sender Phone: ' . $phone . "\r\n" .
                    'Message: ' . $message; // Include sender's details in the email body

      $mail->send();    
// Redirect to a new HTML page if everything is successful
header('Location: success.html'); // Change 'success.html' to your desired page
exit(); // Make sure to call exit after a redirect
} catch (PDOException $e) {
echo 'Database Error: ' . $e->getMessage();
} catch (Exception $e) {
echo 'Email Error: ' . $e->getMessage();
} finally {
// Close the database connection
$conn = null;
}
}
?>





<!DOCTYPE html>
<html>
  <head>
    <title>Contact Us</title>  
    <style>
      body {
        background-color: #4c67b2;
      }
      
      .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #9caed5;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      }
      
      h1 {
        text-align: center;
        color: hsl(228, 50%, 12%);
      }
      
      label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: larger;
        color: #01010e;
      }
      
      input[type="text"],
      input[type="email"],
      input[type="number"],
      textarea {
        display: block;
        width: 96%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        margin-bottom: 20px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        font-size: 18px; 
      }
      
      input[type="submit"] {
        background-color: #05243f;
        color: #fff;
        border: none;
        padding: 15px 15px;
        border-radius: 10px;
        font-size: large;
        cursor: pointer;
      }
      
      input[type="submit"]:hover {
        background-color: #230a7d;
      }
      
      .contact-info {
        display: flex;
        align-items: center;
        justify-content: center;
      }
    
      .gmail-button, .phone-button {
        display: flex;
        align-items:normal;
        color: #fff;
        padding: 20px 20px;
        border-radius: 5px;
        text-decoration: none;
        margin: 0 10px;
      }
    
      .gmail-button img {
        height: 35px;
        margin-right: 10px;
        vertical-align: middle;
      }
      .phone-button img {
        height: 70px;
        margin-right: 0px;
        vertical-align: middle;
      }
    </style> 
  </head>
  <body>
    <div class="container">
      <h1>Contact Us</h1>
      <div class="contact-info">
        <a href="mailto:walidbin.kamal64@gmail.com" class="gmail-button">
          <img src="photos/gmail logo.png" alt="Gmail icon"> 
        </a>
        <p>walidbin.kamal64@gmail.com</p>
        <a href="tel:+8801825469654" class="phone-button">
          <img src="photos/phone logo.png" alt="Phone icon"> 
        </a>
        <p>+880-1825469654</p>
      </div>
      <form action="" method="post">
        <label for="select_category">Select Category:</label>
        <input type="text" id="select_category" name="select_category" ><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" ><br>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" ><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" ><br>

        <label for="enter_message">Enter Message:</label>
        <textarea id="enter_message" name="enter_message" ></textarea><br>

        <input type="submit" value="Submit">
      </form>
    </div>
  </body>

</html>