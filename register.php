<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/register.css"> 
</head>
<body>

  <!-- Navigation Bar -->
  <header>
    <nav class="navbar">
    <a href="index.php"><img src="img/mwc-logo.png" alt="Logo" class="logo"></a>
      <ul class="nav-links">
        <li><a href="login.php">Login</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>

      </ul>
      <div class="hamburger-menu" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
  </header>

  <!-- Login Section -->
  <main class="login-section">
    <div class="login-content">
      <h1>Register As</h1>
      <div class="login-buttons">
        <a href="hr-register.php" class="btn-login hr">HR</a>
        <a href="supervisor-register.php" class="btn-login supervisor">Supervisor</a>
      </div>
    </div>
  </main>

  <!-- Footer  -->
  <footer>
    <p>&copy; 2024 MWC Record-Keeping System. All rights reserved.</p>
  </footer>

  <script>
    function toggleMenu() {
      const navLinks = document.querySelector('.nav-links');
      navLinks.classList.toggle('active');
    }
  </script>

</body>
</html>
