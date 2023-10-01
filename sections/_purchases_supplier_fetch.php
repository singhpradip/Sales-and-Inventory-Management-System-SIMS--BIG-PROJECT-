<?php
include('../admin/connect.php');

if (isset($_POST['supplier_id'])) {
    // Sanitize and get the supplier_id from the POST data
    $supplierId = intval($_POST['supplier_id']);

    // Query to fetch supplier information based on the supplier_id
    $sql = "SELECT supplier_name, supplier_address FROM suppliers_table WHERE supplier_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $supplierId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $supplierName, $supplierAddress);

        mysqli_stmt_fetch($stmt);

        // Check if the supplier exists
        if ($supplierName !== null && $supplierAddress !== null) {
            $response = array(
                'exists' => true,
                'supplierName' => $supplierName,
                'supplierAddress' => $supplierAddress
            );
        } else {
            $response = array('exists' => false);
        }

        mysqli_stmt_close($stmt);

        mysqli_close($conn);

        // Send the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo 'Error in prepared statement: ' . mysqli_error($conn);
    }
} else {
    echo 'Supplier ID not provided.';
}
