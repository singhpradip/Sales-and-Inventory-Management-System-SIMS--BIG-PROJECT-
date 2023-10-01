<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";


// Get the values from the POST request
$supplierId = $_POST['supplierId'];
$paidAmount = $_POST['paidAmount'];

// Validate if customerId and receivedAmount are provided
if (empty($supplierId) || empty($paidAmount)) {
    echo "Missing customerId or receivedAmount";
    exit;
}

// Convert receivedAmount to a float
$paidAmount = floatval($paidAmount);

// Construct the SQL query to deduct the received amount from total_receivable
$sql = "UPDATE suppliers_table SET total_payable = total_payable - ? WHERE supplier_id = ?";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "di", $paidAmount, $supplierId);

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