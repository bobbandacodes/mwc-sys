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

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form data for updating worker
    $worker_id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $nrc = $_POST['nrc'];
    $place = $_POST['place'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $nextofkin = $_POST['nextofkin'];
    $nextofkin_phone = $_POST['nextofkin_phone'];
    $dob = $_POST['dob'];
    $department = $_POST['department'];
    $health = $_POST['health'];
    $academic_level = $_POST['academic_level'];
    $licenses = implode(",", $_POST['licenses']); // Convert licenses array to comma-separated string

    // Update the worker's information in the database
    $query = "UPDATE workers SET firstname=?, surname=?, nrc=?, place=?, city=?, phone=?, nextofkin=?, nextofkin_phone=?, dob=?, department=?, health=?, academic_level=?, licenses=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssissi", $firstname, $surname, $nrc, $place, $city, $phone, $nextofkin, $nextofkin_phone, $dob, $department, $health, $academic_level, $licenses, $worker_id);

    if ($stmt->execute()) {
        // Redirect to worker-list.php after a successful update
        header("Location: worker-list.php?status=success");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Get the worker ID from the URL for displaying the edit form
    $worker_id = $_GET['id'];

    // Fetch the worker details from the database
    $query = "SELECT * FROM workers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $worker = $result->fetch_assoc();

    if (!$worker) {
        echo "Worker not found.";
        exit;
    }

    $firstname = htmlspecialchars($worker['firstname']);
    $surname = htmlspecialchars($worker['surname']);
    $licenses = explode(",", $worker['licenses']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Worker - MWC RECORD-KEEPING SYSTEM</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/worker-edit.css">
</head>
<body>

    <!-- Header with Navbar -->
    <header>
        <nav class="navbar">
            <a href="index.php"><img src="img/mwc-logo.png" alt="Logo" class="logo"></a>
            <ul class="nav-links">
                <li><a href="supervisor-dashboard.php">Dashboard</a></li>
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

    <!-- Main Content for Edit Worker Form -->
    <main class="background-gradient">
        <div class="form-container">
            <h1>Edit Worker: <?php echo $firstname . " " . $surname; ?></h1>
            <form action="worker-edit.php" method="POST" class="worker-form">
                <input type="hidden" name="id" value="<?php echo $worker_id; ?>">

                <!-- Worker Information Fields -->
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required>
                </div>
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" value="<?php echo $surname; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nrc">NRC Number</label>
                    <input type="text" id="nrc" name="nrc" value="<?php echo htmlspecialchars($worker['nrc']); ?>" required pattern="^\d{6}/\d{2}/\d$" title="Format: xxxxxx/xx/x">
                </div>

                <!-- Other fields (place, city, phone, etc.) -->
                <div class="form-group">
                    <label for="place">Place</label>
                    <input type="text" id="place" name="place" value="<?php echo htmlspecialchars($worker['place']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($worker['city']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($worker['phone']); ?>" required>
                </div>

                <!-- Next of Kin and other fields -->
                <div class="form-group">
                    <label for="nextofkin">Next of Kin</label>
                    <input type="text" id="nextofkin" name="nextofkin" value="<?php echo htmlspecialchars($worker['nextofkin']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nextofkin_phone">Next of Kin Phone Number</label>
                    <input type="text" id="nextofkin_phone" name="nextofkin_phone" value="<?php echo htmlspecialchars($worker['nextofkin_phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($worker['dob']); ?>" required>
                </div>

                <!-- Department, Health Condition, Academic Level, and Licenses -->
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="landscaping" <?php echo ($worker['department'] == 'landscaping') ? 'selected' : ''; ?>>Landscaping</option>
                        <option value="window_cleaning" <?php echo ($worker['department'] == 'window_cleaning') ? 'selected' : ''; ?>>Window Cleaning</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="health">Health Condition</label>
                    <select id="health" name="health" required>
                        <option value="able_bodied" <?php echo ($worker['health'] == 'able_bodied') ? 'selected' : ''; ?>>Able-bodied</option>
                        <option value="challenged" <?php echo ($worker['health'] == 'challenged') ? 'selected' : ''; ?>>Challenged</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="academic_level">Academic Level</label>
                    <select id="academic_level" name="academic_level" required>
                        <option value="primary" <?php echo ($worker['academic_level'] == 'primary') ? 'selected' : ''; ?>>Primary</option>
                        <option value="secondary" <?php echo ($worker['academic_level'] == 'secondary') ? 'selected' : ''; ?>>Secondary</option>
                        <option value="tertiary" <?php echo ($worker['academic_level'] == 'tertiary') ? 'selected' : ''; ?>>Tertiary</option>
                    </select>
                </div>

                <!-- Licenses Section -->
                <div class="form-group">
                    <label>Licenses</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="licenses[]" value="drivers_license" <?php echo (in_array('drivers_license', $licenses)) ? 'checked' : ''; ?>> Driver's License</label>
                        <label><input type="checkbox" name="licenses[]" value="vocational_training" <?php echo (in_array('vocational_training', $licenses)) ? 'checked' : ''; ?>> Vocational Training</label>
                        <label><input type="checkbox" name="licenses[]" value="first_aid" <?php echo (in_array('first_aid', $licenses)) ? 'checked' : ''; ?>> First Aid Certificate</label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit">Update Worker</button>
            </form>
        </div>
    </main>

</body>
</html>
