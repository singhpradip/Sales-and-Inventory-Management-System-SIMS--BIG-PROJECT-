<?php
include '../admin/connect.php';

if (isset($_GET['product_id'])) {
  $productId = $_GET ['product_id'];

  // Perform a database query to fetch product details based on the product ID
  $sql = "SELECT product_name, sales_rate FROM inventory_table WHERE product_id = $productId";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    $productDetails = mysqli_fetch_assoc($result);
    echo $productDetails['product_name'] . ',' . $productDetails['sales_rate'];
  } else {
    echo 'No such Product !';
  }
}