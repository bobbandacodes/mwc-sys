<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register as Supervisor - MWC RECORD-KEEPING SYSTEM</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/register.css"> <!-- Specific register page styles -->
</head>
<body>

  <header>
    <nav class="navbar">
      <a href="index.php"><img src="img/mwc-logo.png" alt="Logo" class="logo"></a>
      <ul class="nav-links">
          <li><a href="login.php">Login</a></li>
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

  <main class="register-section">
    <div class="register-content">
      <h1>Register as Supervisor</h1>
      <form action="" method="POST" class="register-form">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address.">
        <input type="password" name="password" placeholder="Password" required pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}" title="Must contain at least 8 characters, including letters and numbers.">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>

        <!-- Department radio buttons -->
        <div>
          <p>Select Department:</p>
          <label>
            <input type="radio" name="department" value="Landscaping" required>
            Landscaping
          </label>
          <label>
            <input type="radio" name="department" value="Window Cleaning">
            Window Cleaning
          </label>
        </div>

        <button type="submit" name="register_supervisor" class="btn-submit">Register</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2024 MWC Record-Keeping System. All rights reserved.</p>
  </footer>

  <?php
  // Database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "mwc";

  // Check for form submission
  if (isset($_POST['register_supervisor'])) {
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Sanitize inputs to prevent XSS attacks
      $first_name = htmlspecialchars($_POST['first_name']);
      $last_name = htmlspecialchars($_POST['last_name']);
      $email = htmlspecialchars($_POST['email']);
      $phone = htmlspecialchars($_POST['phone']);
      $department = htmlspecialchars($_POST['department']);

      // Ensure password and confirm password match
      if ($_POST['password'] !== $_POST['confirm_password']) {
          echo "<p>Passwords do not match.</p>";
      } else {
          // Hash the password for secure storage
          $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

          // Insert query
          $sql = "INSERT INTO supervisors (first_name, last_name, email, password, phone, department) VALUES ('$first_name', '$last_name', '$email', '$password', '$phone', '$department')";

          if ($conn->query($sql) === TRUE) {
              // Redirect to Supervisor dashboard on successful registration
              header("Location: supervisor-dashboard.php");
              exit();
          } else {
              echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
          }
      }

      // Close connection
      $conn->close();
  }
  ?>

  <script>
    function toggleMenu() {
      const navLinks = document.querySelector('.nav-links');
      navLinks.classList.toggle('active');
    }
  </script>

</body>
</html>
