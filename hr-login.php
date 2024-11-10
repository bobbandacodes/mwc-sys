
  
  <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR Login - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/hr-login.css">
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
      <h1>Login as HR</h1>
      <form action="" method="POST" class="login-form">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login_hr" class="btn-submit">Login</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2024 MWC Record-Keeping System. All rights reserved.</p>
  </footer>

  <?php
  // Database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "mwc";

  if (isset($_POST['login_hr'])) {
      $conn = new mysqli($servername, $username, $password, $dbname);

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $email = htmlspecialchars($_POST['email']);
      $password = $_POST['password'];

      $sql = "SELECT * FROM hr WHERE email = '$email'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          if (password_verify($password, $row['password'])) {
              session_start();
              $_SESSION['hr_id'] = $row['id'];
              header("Location: hr-dashboard.php");
              exit();
          } else {
              echo "<p>Invalid credentials.</p>";
          }
      } else {
          echo "<p>Email not found.</p>";
      }

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
