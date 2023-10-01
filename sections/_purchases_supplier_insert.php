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

    // Insert the supplier's information into suppliers_table
    $sql = "INSERT INTO `suppliers_table` (`supplier_id`, `supplier_name`, `supplier_address`, `total_payable`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "dssd", $supplierId, $supplierName, $supplierAddress, $payable);
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
    echo json_encode(array('success' => false));
}
