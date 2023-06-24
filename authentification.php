<?php
// Assume the form has been submitted and the input has been sanitized
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

// Query the database to check if the user exists
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $sql);

// If the user exists, check if the password is correct
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  if (password_verify($password, $row['password'])) {
    // Authentication successful, set session variables
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    // Redirect to the CMS dashboard
    header('Location: dashboard.php');
    exit();
  } else {
    // Authentication failed, show error message
    $error_message = 'Invalid username or password.';
  }
} else {
  // Authentication failed, show error message
  $error_message = 'Invalid username or password.';
}

// Close the database connection
mysqli_close($conn);


/////////////////////////////////////////////////////////////////////

// At the top of each protected CMS page, check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
  // User is not logged in, redirect to login page
  header('Location: login.php');
  exit();
}


?>