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

// Query to get all supervisors and the number of workers under them
$query = "SELECT s.id, s.first_name, s.department, COUNT(w.id) AS num_workers 
          FROM supervisors s 
          LEFT JOIN workers w ON s.id = w.supervisor_id
          GROUP BY s.id";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisors - MWC RECORD-KEEPING SYSTEM</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/supervisors.css">
</head>
<body>

    <!-- Header with Navbar -->
    <header>
        <nav class="navbar">
            <a href="index.php"><img src="img/mwc-logo.png" alt="Logo" class="logo"></a>
            <ul class="nav-links">
                <li><a href="hr-dashboard.php">Dashboard</a></li>
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

    <!-- Main Content for Supervisor Table -->
    <main class="content">
        <h1>Supervisors List</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Number of Workers</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['department'] . "</td>";
                        echo "<td>" . $row['num_workers'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No supervisors found</td></tr>";
                }
                ?>
            </tbody>
        </table>
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

<?php
// Close the database connection
$conn->close();
?>
