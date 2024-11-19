<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supervisor Dashboard - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/supervisor-dashboard.css"> 
</head>
<body>

  <header>
    <nav class="navbar">
      <a href="index.php"><img src="img/mwc-logo.png" alt="Logo" class="logo"></a>
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

  <!-- Main content with cards -->
  <main class="dashboard">
    <div class="content">
      <h1>Welcome to the Supervisor Dashboard</h1>
      <p>Select an option below to manage workers.</p>
    </div>

    <div class="card-container">
      <div class="card">
        <h3>Add Worker</h3>
        <p>Register new temporary workers.</p>
        <button onclick="window.location.href='add-worker.php'">Add</button>
      </div>
      <div class="card">
        <h3>View Workers</h3>
        <p>Modify and evalute workers' performance.</p>
        <button onclick="window.location.href='worker-list.php'">View</button>
      </div>
      <div class="card">
        <h3>Generate Reports</h3>
        <p>View reports on worker performance.</p>
        <button onclick="window.location.href='generate-reports.php'">Generate</button>
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
