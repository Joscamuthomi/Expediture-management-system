<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Analysis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .chart img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Transaction Analysis</h1>
    <p>This page provides an analysis of your financial transactions.</p>

    <div class="chart">
        <h2>Expenditure by Transaction Type</h2>
        <img src="analysis_images/bar_chart.png?<?php echo time(); ?>" alt="Bar Chart">
    </div>

    <div class="chart">
        <h2>Daily Expenditure Trend</h2>
        <img src="analysis_images/line_chart.png?<?php echo time(); ?>" alt="Line Chart">
    </div>
</div>

</body>
</html>
