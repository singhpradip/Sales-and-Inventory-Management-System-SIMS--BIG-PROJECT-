<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../admin/connect.php');

    // Read the JSON data sent from the client
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data);

    // Extract supplier ID
    $supplierId = $data->supplierId;

    // Query to check if the supplier ID exists
    $sql = "SELECT COUNT(*) AS supplierCount FROM suppliers_table WHERE supplier_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "d", $supplierId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $supplierCount);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);

        $response = array('exists' => $supplierCount > 0);
        echo json_encode($response);
    } else {
        echo json_encode(array('exists' => false));
    }

    mysqli_close($conn);
} else {
    echo json_encode(array('exists' => false)); // Invalid request method
}
