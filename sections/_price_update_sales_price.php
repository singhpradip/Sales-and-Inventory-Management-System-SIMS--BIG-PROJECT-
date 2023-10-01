<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the product ID and new sales price from the POST data
    $product_id = $_POST["product_id"];
    $new_sales_price = $_POST["new_sales_price"];

    // Update the sales rate in the inventory_table
    $update_query = "UPDATE inventory_table SET sales_rate = ? WHERE product_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);

    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, "ds", $new_sales_price, $product_id);
        $result = mysqli_stmt_execute($update_stmt);
        
        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
        
        mysqli_stmt_close($update_stmt);
    } else {
        echo "error";
    }
} else {
    echo "error";
}

mysqli_close($conn);