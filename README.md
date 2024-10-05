# Rent-It
<form method="post" action="send-otp.php">
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Send OTP</button>
  </form>
  

  <?php
// Generate OTP
$otp = rand(100000, 999999);

// Save OTP to database
$conn = new mysqli("localhost", "root", "", "mydatabase");
$stmt = $conn->prepare("INSERT INTO otp_verification (email, otp) VALUES (?, ?)");
$stmt->bind_param("si", $_POST['email'], $otp);
$stmt->execute();
$conn->close();

// Send OTP to user's email address
$to = $_POST['email'];
$subject = "Your OTP for email verification";
$message = "Your OTP is: " . $otp;
$headers = "From: yourname@example.com\r\n";
$headers .= "Reply-To: yourname@example.com\r\n";
$headers .= "Content-type: text/html\r\n";
mail($to, $subject, $message, $headers);

// Redirect user to OTP verification page
header("Location: otpverification.html?email=" . $_POST['email']);
exit;
?>


<form method="post" action="otp-verification.php">
    <label>OTP:</label>
    <input type="text" name="otp" required>
    <button type="submit">Verify OTP</button>
  </form>


  <?php
// Verify OTP
$conn = new mysqli("localhost", "root", "", "mydatabase");
$stmt = $conn->prepare("SELECT otp FROM otp_verification WHERE email = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("s", $_GET['email']);
$stmt->execute();
$stmt->bind_result($otp_saved);
$stmt->fetch();
$conn->close();

if (isset($_POST['otp']) && $_POST['otp'] == $otp_saved) {
  // OTP is correct, redirect user to login page or whatever you want to do
  header("Location: login.php");
  exit;
} else {
  // OTP is incorrect, display error message
  echo "Invalid OTP.";
}
?># Rent-It_Web
