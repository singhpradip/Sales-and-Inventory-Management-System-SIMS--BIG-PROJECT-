<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";


// Get the values from the POST request
$customerId = $_POST['customerId'];
$receivedAmount = $_POST['receivedAmount'];

// Validate if customerId and receivedAmount are provided
if (empty($customerId) || empty($receivedAmount)) {
    echo "Missing customerId or receivedAmount";
    exit;
}

// Convert receivedAmount to a float
$receivedAmount = floatval($receivedAmount);

// SQL query to deduct the received amount from total_receivable
$sql = "UPDATE customers_table SET total_receivable = total_receivable - ? WHERE customer_id = ?";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "di", $receivedAmount, $customerId);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "Error updating the database: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing the statement: " . mysqli_error($conn);
}

mysqli_close($conn);