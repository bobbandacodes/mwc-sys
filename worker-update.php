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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the worker's information from the form
    $worker_id = $_POST['id'];
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $nrc = $conn->real_escape_string($_POST['nrc']);
    $place = $conn->real_escape_string($_POST['place']);
    $city = $conn->real_escape_string($_POST['city']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $nextofkin = $conn->real_escape_string($_POST['nextofkin']);
    $nextofkin_phone = $conn->real_escape_string($_POST['nextofkin_phone']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $department = $conn->real_escape_string($_POST['department']);
    $health = $conn->real_escape_string($_POST['health']);
    $academic_level = $conn->real_escape_string($_POST['academic_level']);
    $licenses = isset($_POST['licenses']) ? implode(",", $_POST['licenses']) : '';

    // Update worker details in the database
    $query = "UPDATE workers SET 
              firstname = ?, 
              surname = ?, 
              nrc = ?, 
              place = ?, 
              city = ?, 
              phone = ?, 
              nextofkin = ?, 
              nextofkin_phone = ?, 
              dob = ?, 
              department = ?, 
              health = ?, 
              academic_level = ?, 
              licenses = ?
              WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssi", $firstname, $surname, $nrc, $place, $city, $phone, $nextofkin, $nextofkin_phone, $dob, $department, $health, $academic_level, $licenses, $worker_id);

    if ($stmt->execute()) {
        // Redirect to workers list page after a successful update
        header("Location: workers-list.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
