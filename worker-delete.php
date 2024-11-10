<?php
// Start session to ensure the user is authenticated
session_start();

// Check if the worker ID is provided in the URL
if (isset($_GET['id'])) {
    $workerId = $_GET['id'];

    // Database connection
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "mwc";

    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the delete query
    $sql = "DELETE FROM workers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $workerId);

    if ($stmt->execute()) {
        // Redirect back to the workers list with a success message
        $_SESSION['message'] = "Worker deleted successfully.";
    } else {
        // Redirect back with an error message
        $_SESSION['message'] = "Failed to delete worker.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to workers list page
    header("Location: workers-list.php");
    exit();
} else {
    // If no ID is provided, redirect back to workers list
    header("Location: worker-list.php");
    exit();
}
