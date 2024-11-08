<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
</head>
<body>

  <!-- Navigation Bar -->
  <header>
    <nav class="navbar">
      <img src="img/mwc-logo.png" alt="Logo" class="logo">
      <ul class="nav-links">
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
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

  <!-- Hero Section -->
  <main class="hero-section">
    <div class="hero-content">
      <h1>MWC Record-Keeping System</h1>
      <p>Easy for Supervisor, nice for HR</p>
      <a href="#" class="btn-get-started">Get Started</a>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2024 MWC Record-Keeping System. All rights reserved.</p>
  </footer>

  <script>
    // Function to toggle the 'active' class for mobile navigation
    function toggleMenu() {
      console.log('Hamburger menu clicked');  // Debugging: Check if function is fired
      const navLinks = document.querySelector('.nav-links');
      navLinks.classList.toggle('active');
    }
  </script>
</body>
</html>
