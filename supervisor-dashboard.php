<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supervisor Dashboard - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/dashboard.css"> <!-- Custom styles for the dashboard -->
</head>
<body>

  <!-- Header with Navbar -->
  <header>
    <nav class="navbar">
      <img src="logo.png" alt="Logo" class="logo">
      <ul class="nav-links">
        <li><a href="login.php">Logout</a></li>
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

  <!-- Main content with cards aligned to the right -->
  <main class="dashboard">
    <div class="content">
      <h1>Welcome to the Supervisor Dashboard</h1>
      <p>Select an option below to manage workers.</p>
    </div>

    <div class="card-container">
      <div class="card" onclick="window.location.href='add-worker.php'">
        <h3>Add Worker</h3>
        <p>Register new workers into the system.</p>
      </div>
      <div class="card" onclick="window.location.href='rate-worker.php'">
        <h3>Rate Worker</h3>
        <p>Evaluate and rate workers' performance.</p>
      </div>
      <div class="card" onclick="window.location.href='generate-reports.php'">
        <h3>Generate Reports</h3>
        <p>View reports on worker performance and other metrics.</p>
      </div>
    </div>
  </main>

  <!-- Footer -->
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
