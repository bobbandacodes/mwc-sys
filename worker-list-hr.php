<?php

session_start();

$current_supervisor_id = $_SESSION['supervisor_id'] ?? null; // Get supervisor_id if set in session

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "mwc";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the query to fetch workers
$sql = "SELECT id, firstname, surname, phone, department, supervisor_id FROM workers";
$filter_department = "";

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT id, firstname, surname, phone, department, supervisor_id FROM workers 
            WHERE firstname LIKE '%$search%' OR surname LIKE '%$search%'";
} elseif (isset($_POST['filter_department'])) {
    $filter_department = $_POST['filter_department'];
    if ($filter_department !== "all") {
        $sql = "SELECT id, firstname, surname, phone, department, supervisor_id FROM workers 
                WHERE department = '$filter_department'";
    }
}

$result = $conn->query($sql);

if (isset($_SESSION['message'])) {
    echo "<div class='message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

$filter_city = isset($_POST['filter_city']) ? $_POST['filter_city'] : "all";

$sql = "SELECT id, firstname, surname, phone, department, city, nrc, nextofkin, health, dob, supervisor_id FROM workers";

$conditions = []; // Collect filter conditions

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $conditions[] = "(firstname LIKE '%$search%' OR surname LIKE '%$search%')";
}
if ($filter_department !== "all") {
    $conditions[] = "department = '$filter_department'";
}
if ($filter_city !== "all") {
    $conditions[] = "city = '$filter_city'";
}

// Combine conditions with SQL WHERE clause
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workers List</title>
    <link rel="stylesheet" href="index.php">
    <link rel="stylesheet" href="css/worker-list-hr.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- For Icons -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="index.php"><h2><img src="img/mwc-logo.png" alt="Maroon White Logo" style="width: 100px;"></h2></a>
        <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>
        <ul>
            <li><a href="dashboard-supervisor.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-users"></i>Check Rankings</a></li>
            <li><a href="request-reports.php"><i class="fas fa-file-alt"></i> Whaaaat</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h2>Workers List</h2>
        </header>

        <div class="filter-bar">
    <form method="POST" action="">
        <select name="filter_department">
            <option value="all" <?= $filter_department === "all" ? "selected" : "" ?>>All Departments</option>
            <option value="landscaping" <?= $filter_department === "landscaping" ? "selected" : "" ?>>Landscaping</option>
            <option value="window_cleaning" <?= $filter_department === "window_cleaning" ? "selected" : "" ?>>Window Cleaning</option>
        </select>

        <select name="filter_city">
            <option value="all" <?= $filter_city === "all" ? "selected" : "" ?>>All Cities</option>
            <option value="Ndola" <?= $filter_city === "Ndola" ? "selected" : "" ?>>Ndola</option>
            <option value="Kitwe" <?= $filter_city === "Kitwe" ? "selected" : "" ?>>Kitwe</option>
            <option value="Luanshya" <?= $filter_city === "Luanshya" ? "selected" : "" ?>>Luanshya</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <form method="POST" action="" style="margin-left: 10px;">
        <input type="text" name="search" placeholder="Search workers by name...">
        <button type="submit">Search</button>
    </form>
</div>

        <!-- Workers Table -->
        
        <div class="workers-table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>City</th>
                <th>NRC</th>
                <th>Next of Kin</th>
                <th>Health</th>
                <th>Date of Birth</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $workerName = htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['surname']);
                        $department = ucfirst(str_replace('_', ' ', $row['department'])); // Transform department for display
                        $health = ucfirst(str_replace('_', ' ', $row['health'])); // Transform health condition for display

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>"; // Worker ID
                        echo "<td>" . $workerName . "</td>"; // Full name
                        echo "<td>" . htmlspecialchars($department) . "</td>"; // Department
                        echo "<td>" . htmlspecialchars($row['city']) . "</td>"; // City
                        echo "<td>" . htmlspecialchars($row['nrc']) . "</td>"; // NRC
                        echo "<td>" . htmlspecialchars($row['nextofkin']) . "</td>"; // Next of Kin
                        echo "<td>" . htmlspecialchars($health) . "</td>"; // Health
                        echo "<td>" . htmlspecialchars($row['dob']) . "</td>"; // Date of Birth
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>"; // Phone
                        echo "<td class='action-buttons'>";
                        echo "<a href='worker-track.php?worker_id=" . $row['id'] . "' class='btn rate-btn'>Track</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No workers found</td></tr>";
                }
            ?>
        </tbody>
    </table>
</div>

    </div>

    <?php $conn->close(); ?>
</body>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
    }


    // Automatically remove the message div after animation ends
    setTimeout(() => {
        const messageDiv = document.querySelector('.message');
        if (messageDiv) {
            messageDiv.style.display = 'none';
        }
    }, 5000); // 5 seconds (same as animation duration)
</script>

</html>
