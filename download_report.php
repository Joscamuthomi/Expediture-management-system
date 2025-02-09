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

        .insight-box {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffefe6;
            border-left: 5px solid #FF6347;
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }

        .insight-box strong {
            color: #FF4500;
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

    <?php if ($most_spent): ?>
        <div class="insight-box">
            <p><strong>💡 Most Spent Day:</strong> <?= $most_spent['date'] ?> | <strong>Type:</strong> <?= $most_spent['transaction_type'] ?> | <strong>Amount:</strong> $<?= number_format($most_spent['total'], 2) ?></p>
            <p><strong>📈 Financial Tip:</strong> Regularly track your expenses to identify areas where you can cut costs. Consider setting monthly limits for high-spending categories like <strong><?= $most_spent['transaction_type'] ?></strong>.</p>
            <p><strong>🌟 Insight:</strong> Planning ahead and avoiding impulsive purchases can help you save more for long-term goals!</p>
        </div>
    <?php endif; ?>

    <button onclick="generatePDF('daily-table', 'Daily Transactions')">Download Daily Report</button>

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

            

            const table = document.getElementById(tableId);
            const rows = Array.from(table.querySelectorAll("tr"));

            let yPosition = 60;
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
                if (mostSpent) {

                    pdf.setFontSize(13)
                    pdf.setTextColor("#008000"); // Standard Green color (Hex)
                pdf.text(` Most Spent Day: ${mostSpent.date} | Type: ${mostSpent.transaction_type} | Amount: $${parseFloat(mostSpent.total).toFixed(2)}`, 14,120 );
                pdf.setTextColor("#FF6347"); // Tomato Red
                pdf.text(` Financial Tip: Regularly track your expenses to identify areas where you can cut costs.`, 14, 130);
                pdf.setTextColor("#1E90FF"); // Dodger Blue

                pdf.text(` Insight: Planning ahead and avoiding impulsive purchases can help you save more!`, 14, 140);
            }
            });

            pdf.save(`${title}.pdf`);
        }
    </script>

</body>
</html>
