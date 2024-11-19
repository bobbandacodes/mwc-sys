<?php
require('fpdf/fpdf.php');

session_start();

$filter_department = $_POST['filter_department'] ?? 'all';
$current_supervisor_id = $_SESSION['supervisor_id'] ?? null;

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "mwc";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch supervisor name
$supervisor_query = "SELECT first_name, last_name FROM supervisors WHERE id = ?";
$stmt = $conn->prepare($supervisor_query);
$stmt->bind_param("i", $current_supervisor_id);
$stmt->execute();
$supervisor_result = $stmt->get_result();
$supervisor = $supervisor_result->fetch_assoc();
$supervisor_name = $supervisor ? $supervisor['first_name'] . ' ' . $supervisor['last_name'] : '';

// Fetch worker data
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

// Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, 'Worker Report', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Supervisor: ' . $supervisor_name, 0, 1);
$pdf->Cell(0, 10, 'Department: ' . ($filter_department === 'all' ? 'All' : ucfirst(str_replace('_', ' ', $filter_department))), 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Name', 1);
$pdf->Cell(50, 10, 'Phone', 1);
$pdf->Cell(50, 10, 'Department', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $department = ucfirst(str_replace('_', ' ', $row['department']));
    $pdf->Cell(10, 10, $row['id'], 1);
    $pdf->Cell(50, 10, $row['firstname'] . " " . $row['surname'], 1);
    $pdf->Cell(50, 10, $row['phone'], 1);
    $pdf->Cell(50, 10, $department, 1);
    $pdf->Ln();
}

$pdf->Output('D', 'worker_report.pdf');
?>
