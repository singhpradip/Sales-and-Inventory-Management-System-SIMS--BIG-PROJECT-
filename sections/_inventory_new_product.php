
<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";


// Get values from the form
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$category = $_POST['category'];
$sales_price = $_POST['sales_price'];

// SQL query to insert the product information into inventory_table
$sql = "INSERT INTO `inventory_table` (`product_id`, `product_name`, `category`, `sales_rate`) VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssd", $product_id, $product_name, $category, $sales_price);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('../main.php#inventory');
    
    // echo "Product added successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
