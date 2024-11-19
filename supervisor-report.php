<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "mwc";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_supervisor_id = $_SESSION['supervisor_id'] ?? null; // Get supervisor ID from session

// Fetch the supervisor's name
$supervisor_query = "SELECT first_name, last_name FROM supervisors WHERE id = ?";
$stmt = $conn->prepare($supervisor_query);
$stmt->bind_param("i", $current_supervisor_id);
$stmt->execute();
$supervisor_result = $stmt->get_result();
$supervisor = $supervisor_result->fetch_assoc();
$supervisor_name = $supervisor ? $supervisor['first_name'] . ' ' . $supervisor['last_name'] : '';

// Filter workers by department
$filter_department = $_POST['filter_department'] ?? 'all';
$sql = "SELECT id, firstname, surname, phone, department FROM workers WHERE supervisor_id = ?";
if ($filter_department !== 'all') {
    $sql .= " AND department = ?";
}

$stmt = $conn->prepare($sql);
if ($filter_department === 'all') {
    $stmt->bind_param("i", $current_supervisor_id);
} else {
    $stmt->bind_param("is", $current_supervisor_id, $filter_department);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Report</title>
    <link rel="stylesheet" href="css/generate-report.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Include your existing sidebar here -->
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Generate Report</h2>

        <form method="POST" action="">
            <label for="filter_department">Filter by Department:</label>
            <select name="filter_department">
                <option value="all" <?= $filter_department === 'all' ? 'selected' : '' ?>>All Departments</option>
                <option value="landscaping" <?= $filter_department === 'landscaping' ? 'selected' : '' ?>>Landscaping</option>
                <option value="window_cleaning" <?= $filter_department === 'window_cleaning' ? 'selected' : '' ?>>Window Cleaning</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <h3>Supervisor: <?= htmlspecialchars($supervisor_name) ?></h3>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $department = ucfirst(str_replace('_', ' ', $row['department']));
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($department) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No workers found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <form method="POST" action="supervisor-download-report.php">
            <input type="hidden" name="filter_department" value="<?= htmlspecialchars($filter_department) ?>">
            <button type="submit">Download PDF</button>
        </form>
    </div>
</body>
</html>
