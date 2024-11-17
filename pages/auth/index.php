<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
}

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = $_POST['identity']; // From form input
    $password = $_POST['password']; // From form input

    // API URL
    $apiUrl = 'localhost/api/collections/admins/auth-with-password';

    // Data to send in POST request
    $data = json_encode([
        'identity' => $identity,
        'password' => $password
    ]);

    // Initialize cURL
    $ch = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Execute cURL request
    $response = curl_exec($ch);

    // Handle errors
    if ($response === false) {
        die('Error: ' . curl_error($ch));
    }

    // Close cURL session
    curl_close($ch);

    // Parse API response
    $responseData = json_decode($response, true);

    // Check if login was successful (token is present in response)
    if (isset($responseData['token'])) {
        // Save the token and other user info in session
        $_SESSION['token'] = $responseData['token'];
        $_SESSION['username'] = $responseData['record']['username'];
        $_SESSION['name'] = $responseData['record']['name'];
        # change this to fly.io server to
        $_SESSION['domain'] = "localhost";      
        // Redirect to dashboard
        header('Location: /pages/dashboard.php');
        exit();
    } else {
        // Login failed
        $error = 'Invalid login credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../../assets/css/index.css">

</head>
<body>
<div class="form-container">

   <form action="" method="post">
      <h3>InstaBid Admin</h3>
 
     <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

      <input type="text" name="identity" required placeholder="enter your username">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="submit" name="submit" value="login now" class="form-btn">

   </form>
</div>
</body>
</html>