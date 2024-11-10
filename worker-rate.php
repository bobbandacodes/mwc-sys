<?php
session_start();
$supervisor_id = $_SESSION['supervisor_id'] ?? null;
$worker_name = "";
$success_message = "";

// Verify supervisor is logged in
if ($supervisor_id === null) {
    die("Supervisor ID not set. Please log in.");
}

// Check if `worker_id` is provided in the URL
if (isset($_GET['worker_id'])) {
    $worker_id = intval($_GET['worker_id']);
    $conn = new mysqli("localhost", "root", "", "mwc");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
} else {
    die("Worker ID not provided.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $punctuality = intval($_POST['punctuality']);
    $technical_knowledge = intval($_POST['technical_knowledge']);
    $appearance = intval($_POST['appearance']);
    $teamwork = intval($_POST['teamwork']);
    $learning_ability = intval($_POST['learning_ability']);
    $punctuality_comment = $_POST['punctuality_comment'] ?? "";
    $technical_knowledge_comment = $_POST['technical_knowledge_comment'] ?? "";
    $appearance_comment = $_POST['appearance_comment'] ?? "";
    $teamwork_comment = $_POST['teamwork_comment'] ?? "";
    $learning_ability_comment = $_POST['learning_ability_comment'] ?? "";

    // Calculate average rating
    $average_rating = ($punctuality + $technical_knowledge + $appearance + $teamwork + $learning_ability) / 5;

    // Insert rating and comments into the database
    $stmt = $conn->prepare("INSERT INTO worker_ratings (worker_id, supervisor_id, punctuality, technical_knowledge, appearance, teamwork, learning_ability, punctuality_comment, technical_knowledge_comment, appearance_comment, teamwork_comment, learning_ability_comment, average_rating, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiiiiiisssssd", $worker_id, $supervisor_id, $punctuality, $technical_knowledge, $appearance, $teamwork, $learning_ability, $punctuality_comment, $technical_knowledge_comment, $appearance_comment, $teamwork_comment, $learning_ability_comment, $average_rating);

    if ($stmt->execute()) {
        $success_message = "Rating submitted successfully!";
        echo "<script>
            setTimeout(function() {
                document.getElementById('success-modal').style.display = 'block';
            }, 500);
            setTimeout(function() {
                window.location.href = 'worker-list.php';
            }, 3000);
        </script>";
    } else {
        $success_message = "Failed to submit rating.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Worker</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/worker-rate.css">
</head>
<body>
    <h2>Rate Worker: <?php echo htmlspecialchars($worker_name); ?></h2>
    <?php if (!empty($success_message)): ?>
        <div id="success-modal">
            <h3><?php echo htmlspecialchars($worker_name); ?></h3>
            <p>Average Rating: <?php echo number_format($average_rating, 2); ?></p>
            <p><?php echo htmlspecialchars($success_message); ?></p>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <!-- Rating Inputs with Comments -->
        <?php 
        $metrics = [
            'punctuality' => 'Punctuality',
            'technical_knowledge' => 'Skill Level',
            'appearance' => 'Appearance',
            'teamwork' => 'Teamwork',
            'learning_ability' => 'Quickness of Learning'
        ];
        foreach ($metrics as $name => $label): ?>
            <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
            <input type="range" name="<?php echo $name; ?>" min="1" max="5" value="3">
            <textarea name="<?php echo $name; ?>_comment" rows="2" placeholder="Comments on <?php echo strtolower($label); ?>..."></textarea>
        <?php endforeach; ?>
        
        <button type="submit">Submit Rating</button>
    </form>
</body>
</html>
