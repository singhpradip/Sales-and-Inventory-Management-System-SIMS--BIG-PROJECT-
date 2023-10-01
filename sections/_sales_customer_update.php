<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../admin/connect.php');

    // Read the JSON data sent from the client
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data);

    // Extract customer data
    $customerId = $data->customerId;
    $customerName = $data->customerName;
    $customerAddress = $data->customerAddress;
    $receivable = $data->receivable;


    // Query to update customer information
    $sql = "UPDATE customers_table SET customer_name = ?, customer_address = ?, total_receivable = total_receivable + ? WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters for updating receivable
        mysqli_stmt_bind_param($stmt, "ssdi", $customerName, $customerAddress, $receivable, $customerId);

        // Execute the prepared statement to update receivable
        mysqli_stmt_execute($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);

    } else {
        echo "Error in prepared statement for updating receivable: " . mysqli_error($conn);
    }
}