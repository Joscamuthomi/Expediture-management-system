<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = ""; // No password for root user
$dbname = "finance"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to fetch transaction data
$sql = "SELECT transaction_date, transaction_type, amount_spent, other_description FROM transactions";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Overview</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        /* Table Styling */
        table {
            width: 80%;
            margin: 30px 0;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
        }

        td {
            font-size: 16px;
            color: #555;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Button Styling */
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin: 20px;
        }

        .btn-back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div>
        <h1>Transaction Overview</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th>Amount Spent</th>
                        <th>Other Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['transaction_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['transaction_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount_spent']); ?></td>
                            <td><?php echo htmlspecialchars($row['other_description']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px; color: #888;">No transactions found.</p>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="index.html" class="btn-back">Back to home</a>
        </div>
    </div>

</body>
</html>
