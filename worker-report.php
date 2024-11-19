<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mwc");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of workers
$total_workers_result = $conn->query("SELECT COUNT(*) AS total FROM workers");
$total_workers = $total_workers_result->fetch_assoc()['total'];

// Fetch worker count per department
$department_workers_result = $conn->query("SELECT department, COUNT(*) AS count FROM workers GROUP BY department");
$department_workers = [];
while ($row = $department_workers_result->fetch_assoc()) {
    $department_workers[] = [
        'department' => ucwords(str_replace("_", " ", $row['department'])),
        'count' => $row['count']
    ];
}

// Fetch ratings for categories
$ratings_result = $conn->query("
    SELECT 
        AVG(average_rating) AS avg_rating,
        SUM(CASE WHEN average_rating >= 4 THEN 1 ELSE 0 END) AS excellent,
        SUM(CASE WHEN average_rating BETWEEN 2.5 AND 3 THEN 1 ELSE 0 END) AS average,
        SUM(CASE WHEN average_rating < 2 THEN 1 ELSE 0 END) AS improvement
    FROM worker_ratings");
$ratings_data = $ratings_result->fetch_assoc();

// Fetch top 5 workers per department
$top_workers_result = $conn->query("
    SELECT w.firstname, w.surname, w.department, AVG(r.average_rating) AS avg_rating
    FROM workers w
    INNER JOIN worker_ratings r ON w.id = r.worker_id
    GROUP BY w.id, w.department
    ORDER BY w.department, avg_rating DESC
    LIMIT 5");
$top_workers = [];
while ($row = $top_workers_result->fetch_assoc()) {
    $top_workers[] = [
        'name' => $row['firstname'] . " " . $row['surname'],
        'department' => ucwords(str_replace("_", " ", $row['department'])),
        'rating' => number_format($row['avg_rating'], 2)
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Reports</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/worker-report.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard">
        <h2>Worker Reports</h2>

        <!-- Metrics Overview -->
        <div class="metrics">
            <div class="card">
                <h3>Total Workers</h3>
                <p><?php echo $total_workers; ?></p>
            </div>
            <div class="card">
                <h3>Departments</h3>
                <ul>
                    <?php foreach ($department_workers as $dept): ?>
                        <li><?php echo $dept['department'] . ": " . $dept['count']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts">
            <!-- Worker Ratings Breakdown -->
            <div class="chart-container">
                <canvas id="ratingsPieChart"></canvas>
            </div>

            <!-- Workers per Department -->
            <div class="chart-container">
                <canvas id="departmentBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Grid layout starts here -->
<div class="grid-container">
    <!-- First grid item: Pie chart -->
    <div class="grid-item">
        <h3>Worker Distribution</h3>
        <canvas id="workerDistributionChart"></canvas>
    </div>

    <!-- Second grid item: Bar chart -->
    <div class="grid-item">
        <h3>Department Ratings</h3>
        <canvas id="departmentRatingsChart"></canvas>
    </div>

    <!-- Third grid item: Average vs. Improvement -->
    <div class="grid-item">
        <h3>Performance Overview</h3>
        <p>Above 4.0: Excellent</p>
        <p>2.5 - 3.0: Average</p>
        <p>Below 2.0: Needs Improvement</p>
    </div>

    <!-- Fourth grid item -->
    <div class="grid-item">
        <h3>Top Performers</h3>
        <ul>
            <li>John Doe - Landscaping - 4.9</li>
            <li>Jane Smith - Window Cleaning - 4.8</li>
            <!-- Add more -->
        </ul>
    </div>

    <!-- Fifth grid item -->
    <div class="grid-item">
        <h3>HR Insights</h3>
        <p>Total Workers: 150</p>
        <p>Departments: 2</p>
        <p>Top Department: Landscaping</p>
    </div>

    <!-- Sixth grid item -->
    <div class="grid-item">
        <h3>Actionable Insights</h3>
        <p>10 workers need improvement</p>
        <p>15 workers average</p>
        <p>125 workers excellent</p>
    </div>
</div>

        </div>

        <!-- Navigation Buttons -->
        <div class="button-container">
            <a href="worker-list-hr.php" class="btn">Back to Worker List</a>
            <a href="hr-dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </div>

    <script>
// Ratings Pie Chart
const ratingsPieCtx = document.getElementById('ratingsPieChart').getContext('2d');
new Chart(ratingsPieCtx, {
    type: 'pie',
    data: {
        labels: ['Excellent (>= 4.0)', 'Average (2.5 - 3.0)', 'Needs Improvement (< 2.0)'],
        datasets: [{
            data: [<?php echo $ratings_data['excellent']; ?>, <?php echo $ratings_data['average']; ?>, <?php echo $ratings_data['improvement']; ?>],
            backgroundColor: ['#4CAF50', '#FFC107', '#F44336'],
            hoverBackgroundColor: ['#66BB6A', '#FFD54F', '#E57373']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});

// Workers per Department Bar Chart
const departmentBarCtx = document.getElementById('departmentBarChart').getContext('2d');
new Chart(departmentBarCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($department_workers, 'department')); ?>,
        datasets: [{
            label: 'Number of Workers',
            data: <?php echo json_encode(array_column($department_workers, 'count')); ?>,
            backgroundColor: '#3F51B5',
            hoverBackgroundColor: '#5C6BC0'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Departments',
                    color: '#333'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Number of Workers',
                    color: '#333'
                }
            }
        }
    }
});

    </script>
</body>
</html>
