<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];

    // Performing deletion in database table
    $delete_query = "DELETE FROM `inventory_table` WHERE `product_id` = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);

    if ($delete_stmt) {
        mysqli_stmt_bind_param($delete_stmt, "s", $product_id);
        if (mysqli_stmt_execute($delete_stmt)) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
    mysqli_stmt_close($delete_stmt);
    mysqli_close($conn);
}
