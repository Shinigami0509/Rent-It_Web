<!DOCTYPE html>
<html>
  <head>
    <title>Forgot Password</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
      }
      h2 {
        text-align: center;
        color: #555;
      }
      form {
        max-width: 400px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
      }
      label {
        display: block;
        margin-bottom: 10px;
        color: #555;
      }
      input[type="email"] {
        display: block;
        width: 93%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 20px;
      }
      input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
      }
      input[type="submit"]:hover {
        background-color: #45a049;
      }
      .otp-section {
        display: none;
      }
    </style>
  </head>
  <body>
  
    <h2>Forgot Password</h2>
    <form class="" action="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="" required><br>     
      <input type="submit" name="send" value="Send OTP">
    </form>     
  

    <?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'phpmailer/src/Exception.php';
  require 'phpmailer/src/PHPMailer.php';
  require 'phpmailer/src/SMTP.php';

  // Check if the form has been submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the user's email address from the form data
    $email = $_POST["email"];

    // Check if email is registered in the database
    $conn = mysqli_connect("localhost", "root", "", "rentit");
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
      header("Location: error.php");
      exit();
    }

    // Email is registered, generate OTP and store it in database
    $otp = rand(100000, 999999);
    $sql = "UPDATE users SET otp='$otp' WHERE email='$email'";
    mysqli_query($conn, $sql);
    mysqli_close($conn);

    // Store the email in a session variable for use in otp.php
    session_start();
    $_SESSION["email"] = $email;

    // Send an email to the user with the OTP using PHPMailer
    try {
      $mail = new PHPMailer(true);

      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com'; // SMTP server
      $mail->SMTPAuth   = true;
      $mail->Username   = 'walidbin.kamal64@gmail.com'; // SMTP username
      $mail->Password   = 'xpermlrnmcmikegt'; // SMTP password
      $mail->SMTPSecure = 'ssl';
      $mail->Port       = 465;

      $mail->setFrom('walidbin.kamal64@gmail.com', 'Rent It');
      $mail->addAddress($email);

      $mail->isHTML(true);
      $mail->Subject = 'OTP for password reset';
      $mail->Body    = "Your OTP is: $otp";

      $mail->send();
      header("Location: verifyotp.php");
    } catch (Exception $e) {
      echo "Error sending email: {$mail->ErrorInfo}";
    }
  }
?>

</body>

</html>
