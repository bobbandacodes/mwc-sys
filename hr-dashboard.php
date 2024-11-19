<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mwc";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch HR name from session
$hr_name = $_SESSION['hr_name'] ?? 'HR';

// Fetch worker count
$worker_count_query = "SELECT COUNT(*) as count FROM workers";
$worker_count_result = $conn->query($worker_count_query);
$worker_count = $worker_count_result->fetch_assoc()['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR Dashboard - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/hr-dashboard.css">
</head>
<body>

  <!-- Header with Navbar -->
  <header>
    <nav class="navbar">
      <a href="index.php"><img src="img/mwc-logo.png" alt="Logo" class="logo"></a>
      <ul class="nav-links">
        <li><a href="supervisor-dashboard.php">Dashboard</a></li>
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

  <!-- Main Content -->
  <main class="background-gradient">
    <div class="dashboard-container">
      <h1>Welcome <?php echo htmlspecialchars($hr_name); ?> to the dashboard</h1>

      <div class="card-grid">
        <!-- Worker Card -->
        <div class="dashboard-card">
          <h2>Workers</h2>
          <p>Total workers: <?php echo $worker_count; ?></p>
          <button onclick="window.location.href='worker-list.php'">View Workers</button>
        </div>

        <!-- Supervisors Card -->
        <div class="dashboard-card">
          <h2>Supervisors</h2>
          <p>Manage and review supervisors in the system.</p>
          <button onclick="window.location.href='supervisors.php'">View Supervisors</button>
        </div>

        <!-- Track Performance Card -->
        <div class="dashboard-card">
          <h2>Track Performance</h2>
          <p>Monitor worker performance metrics and ratings.</p>
          <button onclick="window.location.href='track-performance.php'">Track Performance</button>
        </div>

        <!-- Reports Card -->
        <div class="dashboard-card">
          <h2>Reports</h2>
          <p>Generate and view reports on worker activities.</p>
          <button onclick="window.location.href='reports.php'">View Reports</button>
        </div>

        </div>
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
