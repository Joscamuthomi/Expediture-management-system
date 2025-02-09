<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/webfontloader@1.6.28/webfontloader.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
        }

        button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h1>Transaction Reports</h1>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "finance";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Query for daily transactions
    $daily_sql = "SELECT DATE(transaction_date) as period, transaction_type, SUM(amount_spent) as total_amount 
                  FROM transactions 
                  GROUP BY DATE(transaction_date), transaction_type 
                  ORDER BY DATE(transaction_date)";
    $daily_result = $conn->query($daily_sql);

    // Query for weekly transactions
    $weekly_sql = "SELECT YEARWEEK(transaction_date) as period, transaction_type, SUM(amount_spent) as total_amount 
                   FROM transactions 
                   GROUP BY YEARWEEK(transaction_date), transaction_type 
                   ORDER BY YEARWEEK(transaction_date)";
    $weekly_result = $conn->query($weekly_sql);

    // Find the most spent day and transaction type
    $most_spent_sql = "SELECT transaction_type, DATE(transaction_date) as date, SUM(amount_spent) as total
                       FROM transactions
                       GROUP BY DATE(transaction_date), transaction_type
                       ORDER BY total DESC LIMIT 1";
    $most_spent_result = $conn->query($most_spent_sql);
    $most_spent = $most_spent_result->fetch_assoc();
    ?>

    <!-- Daily Transactions Table -->
    <h2>Daily Transactions</h2>
    <button onclick="generatePDF('daily-table', 'Daily Transactions')">Download Daily Report</button>
    <table id="daily-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction Type</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($daily_result->num_rows > 0) {
                while ($row = $daily_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['period']}</td>
                            <td>{$row['transaction_type']}</td>
                            <td>" . number_format($row['total_amount'], 2) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Weekly Transactions Table -->
    <h2>Weekly Transactions</h2>
    <button onclick="generatePDF('weekly-table', 'Weekly Transactions')">Download Weekly Report</button>
    <table id="weekly-table">
        <thead>
            <tr>
                <th>Week</th>
                <th>Transaction Type</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($weekly_result->num_rows > 0) {
                while ($row = $weekly_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['period']}</td>
                            <td>{$row['transaction_type']}</td>
                            <td>" . number_format($row['total_amount'], 2) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php $conn->close(); ?>

    <script>
        // Load custom handwriting font
        WebFont.load({
            google: {
                families: ['Pacifico']
            }
        });

        async function generatePDF(tableId, title) {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            const mostSpent = <?php echo json_encode($most_spent); ?>;

            pdf.setFont("Pacifico");
            pdf.setFontSize(18);
            pdf.setTextColor("#FF6347");
            pdf.text(title, 14, 16);

            pdf.setFontSize(12);
            pdf.setTextColor("#000");
            if (mostSpent) {
                pdf.text(`💡 Most Spent Day: ${mostSpent.date} | Type: ${mostSpent.transaction_type} | Amount: $${parseFloat(mostSpent.total).toFixed(2)}`, 14, 28);
                pdf.text(`📈 Insight: Monitor transactions for frequent expenses. Consider budgeting!`, 14, 36);
            }

            const table = document.getElementById(tableId);
            const rows = Array.from(table.querySelectorAll("tr"));

            let yPosition = 50;
            rows.forEach((row, index) => {
                const cells = Array.from(row.querySelectorAll(index === 0 ? "th" : "td"));
                let xPosition = 14;

                cells.forEach(cell => {
                    pdf.setFontSize(10);
                    pdf.setTextColor(index === 0 ? "#4CAF50" : "#000");
                    pdf.text(cell.textContent.trim(), xPosition, yPosition);
                    xPosition += 60;
                });

                yPosition += 10;
            });

            pdf.save(`${title}.pdf`);
        }
    </script>

</body>
</html>
