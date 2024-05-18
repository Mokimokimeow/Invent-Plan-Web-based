<?php
include 'items-func.php';
include 'header.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventplan";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    foreach ($data as $monthName => $rowData) {
        $placeholders = rtrim(str_repeat('?, ', count($rowData)), ', ');

        $stmt = $conn->prepare("INSERT INTO `weekly_earnings` (month, week1, week2, week3, week4, week5) VALUES (?, $placeholders)");

        if ($stmt) {
            $values = array_values($rowData);
            $types = str_repeat('i', count($values));
            $stmt->bind_param('s' . $types, $monthName, ...$values);

            if ($stmt->execute()) {
                echo "Data inserted successfully for month: $monthName<br>";
            } else {
                echo "Error inserting data for month: $monthName - " . $stmt->error . "<br>";
            }

            $stmt->close();
        } else {
            echo "Error preparing statement for month: $monthName - " . $conn->error . "<br>";
        }
    }
}
$conn->close();
?>
<title>Dashboard</title>
<link rel="stylesheet" href="../asset/css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <h2>Dashboard</h2>
    </div>
    <div class="cards">
        <div class="card">
            <h3>Total Sales in Month's</h3>
            <p>Month's total sales: ₱<span id="totalSales">0</span></p>
        </div>
        <div class="card">
            <h3>Items in Stock</h3>
            <p>Total items in stock: <span id="itemsStock"><?php echo $total_items; ?></span></p>
        </div>
    </div>
    <div class="container">
        <h2>Weekly Total Earnings for January to December</h2>
        <form id="earningsForm" method="post">
            <table id="weeklyEarningsTable">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Week 1</th>
                        <th>Week 2</th>
                        <th>Week 3</th>
                        <th>Week 4</th>
                        <th>Week 5</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $months = [
                        'January', 'February', 'March', 'April',
                        'May', 'June', 'July', 'August',
                        'September', 'October', 'November', 'December'
                    ];
                    foreach ($months as $monthName) {
                        echo "<tr>";
                        echo "<td>$monthName</td>";
                        for ($week = 1; $week <= 5; $week++) {
                            echo "<td contenteditable='true' class='editable-cell'><input type='number'></td>";
                        }
                        echo "<td class='total-cell'></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <div class="chart-container">
        <span>Earnings every Month</span>
        <canvas id="barChart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include 'footer.php'; ?>