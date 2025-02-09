<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = ""; // No password for root user
$dbname = "finance"; // Replace with your database name

// Create a connection using mysqli with error handling
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the form
$transaction_date = $_POST['transaction_date'];
$transaction_type = $_POST['transaction_type'];
$amount_spent = $_POST['amount_spent'];
$other_description = isset($_POST['other_description']) ? $_POST['other_description'] : ''; // Only set if "others" is selected

// Prepare SQL statement to insert data into the 'transactions' table
$sql = "INSERT INTO transactions (transaction_date, transaction_type, amount_spent, other_description) 
        VALUES (?, ?, ?, ?)";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $transaction_date, $transaction_type, $amount_spent, $other_description);

// Execute query and check for success
if ($stmt->execute()) {
    echo "Transaction saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
