<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../admin/connect.php');

    // Read the JSON data sent from the client
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data);

    // Extract supplier data
    $supplierId = $data->supplierId;
    $supplierName = $data->supplierName;
    $supplierAddress = $data->supplierAddress;
    $payable = $data->payable;

    // Query to update supplier information
    $sql = "UPDATE suppliers_table SET supplier_name = ?, supplier_address = ?, total_payable = total_payable + ? WHERE supplier_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssdi", $supplierName, $supplierAddress, $payable, $supplierId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

    } else {
        echo "Error in prepared statement for updating payable: " . mysqli_error($conn);
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
