<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mwc");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if `worker_id` is provided
if (!isset($_GET['worker_id'])) {
    die("Worker ID not provided.");
}

$worker_id = intval($_GET['worker_id']);
$worker_name = "";

// Fetch worker's name
$stmt = $conn->prepare("SELECT firstname, surname FROM workers WHERE id = ?");
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$stmt->bind_result($firstname, $surname);
if ($stmt->fetch()) {
    $worker_name = $firstname . " " . $surname;
} else {
    die("Worker not found.");
}
$stmt->close();

// Fetch worker's ratings
$sql = "SELECT punctuality, technical_knowledge, appearance, teamwork, learning_ability, average_rating, date 
        FROM worker_ratings WHERE worker_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$result = $stmt->get_result();
$ratings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Performance</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/worker-track.css">
</head>
<body>
    <div class="performance-container">
        <div class="worker-header">
            <h2>Performance Report</h2>
            <h3><?php echo htmlspecialchars($worker_name); ?></h3>
        </div>

        <?php if (empty($ratings)): ?>
            <p>No performance data available for this worker.</p>
        <?php else: ?>
            <div class="summary-section">
                <p><strong>Total Ratings:</strong> <?php echo count($ratings); ?></p>
                <p><strong>Average Rating:</strong> <?php echo number_format(array_sum(array_column($ratings, 'average_rating')) / count($ratings), 2); ?></p>
            </div>

            <table class="performance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Punctuality</th>
                        <th>Skill Level</th>
                        <th>Appearance</th>
                        <th>Teamwork</th>
                        <th>Learning Ability</th>
                        <th>Average Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ratings as $rating): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rating['date']); ?></td>
                            <td><?php echo $rating['punctuality']; ?></td>
                            <td><?php echo $rating['technical_knowledge']; ?></td>
                            <td><?php echo $rating['appearance']; ?></td>
                            <td><?php echo $rating['teamwork']; ?></td>
                            <td><?php echo $rating['learning_ability']; ?></td>
                            <td><?php echo number_format($rating['average_rating'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="button-container">
            <a href="worker-list-hr.php" class="button">Return to Worker List</a>
            <a href="worker-rank.php?worker_id=<?php echo $worker_id; ?>" class="button">View Worker Rank</a>
        </div>
    </div>
</body>
</html>

