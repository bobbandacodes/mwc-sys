<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mwc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch departments, supervisors, workers, and their performance
$query = "
    SELECT d.id AS department_id, d.name AS department_name, 
           CONCAT(s.first_name, ' ', s.last_name) AS supervisor_name,
           w.id AS worker_id, w.name AS worker_name, w.performance_score
    FROM departments d
    LEFT JOIN supervisors s ON d.supervisor_id = s.id
    LEFT JOIN workers w ON d.id = w.department_id
    ORDER BY d.name, s.first_name, w.name
";

$result = $conn->query($query);
$departments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[$row['department_id']]['name'] = $row['department_name'];
        $departments[$row['department_id']]['supervisor'] = $row['supervisor_name'];
        $departments[$row['department_id']]['workers'][] = [
            'id' => $row['worker_id'],
            'name' => $row['worker_name'],
            'performance_score' => $row['performance_score']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments - MWC RECORD-KEEPING SYSTEM</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/department.css">
</head>
<body>

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

    <main class="content">
        <h1>Departments Overview</h1>
        <?php if (!empty($departments)): ?>
            <?php foreach ($departments as $department): ?>
                <div class="department-card">
                    <h2>Department: <?php echo $department['name']; ?></h2>
                    <p><strong>Supervisor:</strong> <?php echo $department['supervisor']; ?></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Worker ID</th>
                                <th>Worker Name</th>
                                <th>Performance Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_score = 0;
                            $num_workers = count($department['workers']);
                            foreach ($department['workers'] as $worker): 
                                $total_score += $worker['performance_score'];
                            ?>
                                <tr>
                                    <td><?php echo $worker['id']; ?></td>
                                    <td><?php echo $worker['name']; ?></td>
                                    <td><?php echo $worker['performance_score']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if ($num_workers > 0): ?>
                        <p><strong>Overall Performance:</strong> <?php echo round($total_score / $num_workers, 2); ?></p>
                    <?php else: ?>
                        <p><strong>Overall Performance:</strong> N/A</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No departments found.</p>
        <?php endif; ?>
    </main>

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
