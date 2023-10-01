<?php
include('../admin/connect.php');

// Check if the customer_id is provided in the POST request
if (isset($_POST['customer_id'])) {
    // Sanitize and get the customer_id from the POST data
    $customerId = intval($_POST['customer_id']);

    //fetch customer information based on the customer_id
    $sql = "SELECT customer_name, customer_address FROM customers_table WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind the customer_id parameter
        mysqli_stmt_bind_param($stmt, "i", $customerId);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Bind the result variables
        mysqli_stmt_bind_result($stmt, $customerName, $customerAddress);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Check if the customer exists
        if ($customerName !== null && $customerAddress !== null) {
            $response = array(
                'exists' => true,
                'customerName' => $customerName,
                'customerAddress' => $customerAddress
            );
        } else {
            $response = array('exists' => false);
        }

        // Close the statement
        mysqli_stmt_close($stmt);

        // Close the database connection
        mysqli_close($conn);

        // Send the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo 'Error in prepared statement: ' . mysqli_error($conn);
    }
} else {
    echo 'Customer ID not provided.';
}
