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


    // Insert the customer's information into customers_table2
    $sql = "INSERT INTO `customers_table` (`customer_id`, `customer_name`, `customer_address`, `total_receivable`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "dssd", $customerId, $customerName, $customerAddress, $receivable);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($result) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false));
        }
    } else {
        echo json_encode(array('success' => false));
    }

    mysqli_close($conn);
} else {
    echo json_encode(array('success' => false)); // Invalid request method
}
