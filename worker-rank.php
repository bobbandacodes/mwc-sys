<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mwc");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch workers ranked by their average rating with department included
$sql = "SELECT w.firstname, w.surname, w.department, AVG(r.average_rating) AS avg_rating 
        FROM workers w
        INNER JOIN worker_ratings r ON w.id = r.worker_id
        GROUP BY w.id, w.department
        ORDER BY avg_rating DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worker Rankings</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/worker-rank.css">
</head>
<body>
    <h2>Worker Performance Rankings</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="ranking-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                while ($row = $result->fetch_assoc()): 
                    // Format department name to be human-readable
                    $department = ucwords(str_replace("_", " ", $row['department']));
                ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td><?php echo htmlspecialchars($row['firstname'] . " " . $row['surname']); ?></td>
                        <td><?php echo htmlspecialchars($department); ?></td>
                        <td><?php echo number_format($row['avg_rating'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No rankings available at the moment.</p>
    <?php endif; ?>
    <div class="button-container">
        <a href="worker-list-hr.php" class="btn">Back to Worker List</a>
        <a href="hr-dashboard.php" class="btn">Back to Dashboard</a>
        <a href="worker-report.php" class="btn">View Reports</a>
    </div>
</body>
</html>
