import mysql.connector
import matplotlib.pyplot as plt
import os

# Ensure the folder exists
output_folder = "analysis_images"
os.makedirs(output_folder, exist_ok=True)

# Connect to MySQL database (finance)
try:
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # No password
        database="finance"
    )
    cursor = conn.cursor()

    # Fetch transaction data grouped by transaction type
    cursor.execute("SELECT transaction_type, SUM(amount_spent) FROM transactions GROUP BY transaction_type")
    transaction_data = cursor.fetchall()

    if not transaction_data:
        print("No data found in the transactions table.")
    else:
        # Process data for the bar chart
        transaction_types = [row[0] for row in transaction_data]
        amount_spent = [float(row[1]) for row in transaction_data]

        # Generate Bar Chart
        plt.figure(figsize=(8, 5))
        plt.bar(transaction_types, amount_spent, color='skyblue')
        plt.xlabel("Transaction Type")
        plt.ylabel("Total Amount Spent")
        plt.title("Expenditure by Transaction Type")
        plt.xticks(rotation=45)
        plt.savefig(os.path.join(output_folder, "bar_chart.png"))
        plt.close()

    # Fetch daily expenditure data
    cursor.execute("SELECT DATE(transaction_date), SUM(amount_spent) FROM transactions GROUP BY DATE(transaction_date)")
    daily_data = cursor.fetchall()

    if not daily_data:
        print("No daily data found.")
    else:
        # Process data for the line chart
        days = [row[0].strftime('%Y-%m-%d') for row in daily_data]
        daily_total = [float(row[1]) for row in daily_data]

        # Generate Line Chart
        plt.figure(figsize=(8, 5))
        plt.plot(days, daily_total, marker='o', color='red', linestyle='-')
        plt.xlabel("Date")
        plt.ylabel("Total Amount Spent")
        plt.title("Daily Expenditure Trend")
        plt.xticks(rotation=45)
        plt.savefig(os.path.join(output_folder, "line_chart.png"))
        plt.close()

    # Close database connection
    cursor.close()
    conn.close()
    print("Charts saved in 'analysis_images/' successfully!")

except mysql.connector.Error as err:
    print(f"Error: {err}")
