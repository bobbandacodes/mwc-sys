<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/supervisor-login.css">
</head>
<body>

  <header>
    <nav class="navbar">
    <a href="index.php"><img src="img/mwc-logo.png" alt="Logo" class="logo"></a>
      <ul class="nav-links">
        <li><a href="register.php">Register</a></li>
        <li><a href="policy.php">Policy</a></li>
        <li><a href="about.php">About</a></li>
      </ul>
      <div class="hamburger-menu" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
  </header>

  <main class="login-section">
    <div class="login-content">
      <h1>Supervisor Login</h1>
      <form action="" method="POST" class="login-form">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login_supervisor" class="btn-submit">Login</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2024 MWC Record-Keeping System. All rights reserved.</p>
  </footer>

  <?php
  // Database connection details
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "mwc";

  // Check for form submission
  if (isset($_POST['login_supervisor'])) {
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Sanitize inputs to prevent XSS
      $email = htmlspecialchars($_POST['email']);
      $password = $_POST['password'];

      // Verify email and password
      $stmt = $conn->prepare("SELECT id, password FROM supervisors WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($supervisor_id, $hashed_password);

      if ($stmt->fetch() && password_verify($password, $hashed_password)) {
          // Successful login
          session_start();
          $_SESSION['supervisor_id'] = $supervisor_id;
          header("Location: supervisor-dashboard.php");
          exit();
      } else {
          echo "<p>Invalid email or password.</p>";
      }

      // Close connection
      $stmt->close();
      $conn->close();
  }
  ?>

  <script>
    function toggleMenu() {
      const navLinks = document.querySelector('.nav-links');
      navLinks.classList.toggle('active');
    }
  </script>

</body>
</html>
