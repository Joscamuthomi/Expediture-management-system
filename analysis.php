<?php
// Start the session (optional, for user authentication)
session_start();

// Define image paths
$barChart = "static/images/bar_chart.png";
$lineChart = "static/images/line_chart.png";

// Check if images exist
$barChartExists = file_exists($barChart);
$lineChartExists = file_exists($lineChart);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Analysis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .chart {
            text-align: center;
            margin-bottom: 40px;
        }
        .chart img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .run-analysis {
            text-align: center;
            margin: 20px 0;
        }
        .run-analysis button {
            padding: 10px 15px;
            font-size: 16px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .run-analysis button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transaction Analysis</h1>
        <p>This page provides an analysis of your financial transactions.</p>

        <!-- Button to Run Python Analysis -->
        <div class="run-analysis">
            <form action="run_analysis.php" method="post">
                <button type="submit">Run Data Analysis</button>
            </form>
        </div>

        <!-- Display Charts -->
        <div class="chart">
            <h2>Expenditure by Transaction Type</h2>
            <?php if ($barChartExists): ?>
                <img src="<?php echo $barChart; ?>" alt="Bar Chart">
            <?php else: ?>
                <p>Bar chart is not available. Run analysis to generate it.</p>
            <?php endif; ?>
        </div>

        <div class="chart">
            <h2>Daily Expenditure Trend</h2>
            <?php if ($lineChartExists): ?>
                <img src="<?php echo $lineChart; ?>" alt="Line Chart">
            <?php else: ?>
                <p>Line chart is not available. Run analysis to generate it.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
