<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../admin/connect.php');

    // Read the JSON data sent from the client
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data);

    // Extract customer ID
    $customerId = $data->customerId;

    // Query to check if the customer ID exists
    $sql = "SELECT COUNT(*) AS customerCount FROM customers_table WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "d", $customerId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $customerCount);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        $response = array('exists' => $customerCount > 0);
        echo json_encode($response);
    } else {
        // Return false in case of an error
        echo json_encode(array('exists' => false)); 
    }

    mysqli_close($conn);
} else {
    // when Invalid request method
    echo json_encode(array('exists' => false)); 
}
