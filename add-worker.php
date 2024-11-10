<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Worker - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/add-worker.css"> <!-- Custom styles for the form -->
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

  <!-- Main Content for Add Worker Form -->
  <main class="main-content">
    <div class="form-container">
      <h1>Add Worker</h1>
      <form action="submit-worker.php" method="POST" class="worker-form">

        <!-- Worker Information Fields -->
        <div class="form-group">
          <label for="firstname">First Name</label>
          <input type="text" id="firstname" name="firstname" required>
        </div>
        <div class="form-group">
          <label for="surname">Surname</label>
          <input type="text" id="surname" name="surname" required>
        </div>
        <div class="form-group">
          <label for="nrc">NRC Number</label>
          <input type="text" id="nrc" name="nrc" required pattern="^\d{6}/\d{2}/\d$" title="Format: xxxxxx/xx/x (e.g., 123456/78/9)">
        </div>

        <!-- Address Fields -->
        <div class="form-group">
          <label for="place">Place</label>
          <input type="text" id="place" name="place" required>
        </div>
        <div class="form-group">
          <label for="city">City</label>
          <input type="text" id="city" name="city" required>
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" required>
        </div>

        <!-- Next of Kin Fields -->
        <div class="form-group">
          <label for="nextofkin">Next of Kin</label>
          <input type="text" id="nextofkin" name="nextofkin" required>
        </div>
        <div class="form-group">
          <label for="nextofkin_phone">Next of Kin Phone Number</label>
          <input type="text" id="nextofkin_phone" name="nextofkin_phone" required>
        </div>
        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <input type="date" id="dob" name="dob" required>
        </div>

        <!-- Additional Information Fields -->
        <div class="form-group">
          <label for="department">Department</label>
          <select id="department" name="department" required>
            <option value="landscaping">Landscaping</option>
            <option value="window_cleaning">Window Cleaning</option>
          </select>
        </div>
        <div class="form-group">
          <label for="health">Health Condition</label>
          <select id="health" name="health" required>
            <option value="able_bodied">Able-bodied</option>
            <option value="challenged">Challenged</option>
          </select>
        </div>
        <div class="form-group">
          <label for="academic_level">Academic Level</label>
          <select id="academic_level" name="academic_level" required>
            <option value="primary">Primary</option>
            <option value="secondary">Secondary</option>
            <option value="tertiary">Tertiary</option>
          </select>
        </div>

        <!-- Licenses Section -->
        <div class="form-group">
          <label>Licenses</label>
          <div class="checkbox-group">
            <label><input type="checkbox" id="drivers_license" name="licenses[]" value="drivers_license"> Driver's License</label>
            <label><input type="checkbox" id="vocational_training" name="licenses[]" value="vocational_training"> Vocational Training</label>
            <label><input type="checkbox" id="first_aid" name="licenses[]" value="first_aid"> First Aid</label>
          </div>
        </div>

        <button type="submit" class="btn-submit">Add Worker</button>
      </form>
    </div>
  </main>

  <!-- Footer -->
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
