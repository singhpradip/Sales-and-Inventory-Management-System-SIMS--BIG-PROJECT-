<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../admin/connect.php');

    // Read the JSON data sent from the client
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data);

    // Handle data for purchases_header
    $totalAmount = $data->totalAmount;
    $expenses = $data->expenses;
    $paid = $data->paid;
    $payable = $data->payable;
    $supplier_id = $data->supplier_id;

    // Query to fetch the current maximum purchases_id
    $sql = "SELECT MAX(purchases_id) AS max_purchases_id FROM purchases_header";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $max_purchases_id = $row['max_purchases_id'];

        if ($max_purchases_id !== null) {
            $new_purchases_id = $max_purchases_id + 1;
        } else {
            // If there are no records in the table, set purchases_id to 1
            $new_purchases_id = 1;
        }
    } else {
        echo "Error fetching max_purchases_id: " . mysqli_error($conn);
    }

    // Create and execute the SQL query for purchases_header
    $sql1 = "INSERT INTO `purchases_header` (`purchases_id`, `total_amount`, `expenses`, `paid`, `payable`, `supplier_id`, `purchases_date`) VALUES (?, ?, ?, ?, ?, ?, current_timestamp())";
    $stmt1 = mysqli_prepare($conn, $sql1);

    if ($stmt1) {
        mysqli_stmt_bind_param($stmt1, "iddddi", $new_purchases_id, $totalAmount, $expenses, $paid, $payable, $supplier_id);

        mysqli_stmt_execute($stmt1);

        mysqli_stmt_close($stmt1);

        // Handle data for purchases_items
        $purchasesItemsArray = $data->purchasesItemsArray;

        foreach ($purchasesItemsArray as $item) {
            $show_product_id = $item->show_product_id;
            $show_product_name = $item->show_product_name;
            $show_product_category = $item->show_product_category;
            $show_sales_rate = $item->show_sales_rate;
            $show_quantity = $item->show_quantity;
            $show_purchases_rate = $item->show_purchases_rate;
            $show_total = $item->show_total;

            // Create and execute the SQL query for each purchases item
            $sql2 = "INSERT INTO `purchases_items` (`product_id`, `purchases_id`, `product_name`, `category`, `purchases_rate`, `sales_rate`, `quantity`, `item_total_amount`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($conn, $sql2);

            if ($stmt2) {
                mysqli_stmt_bind_param($stmt2, "iissdddd", $show_product_id, $new_purchases_id, $show_product_name, $show_product_category, $show_purchases_rate, $show_sales_rate, $show_quantity, $show_total);

                mysqli_stmt_execute($stmt2);

                mysqli_stmt_close($stmt2);

                            // Update the inventory in the purchases table
                            $sqlUpdateInventory = "UPDATE `inventory_table` SET `quantity_available` = `quantity_available` + ?, 
                            `sales_rate` = ?, 
                            `purchases_rate` = ?
                          WHERE `product_id` = ?";
                            $stmtUpdateInventory = mysqli_prepare($conn, $sqlUpdateInventory);

                            if ($stmtUpdateInventory) {
                                mysqli_stmt_bind_param($stmtUpdateInventory, "dddi", $show_quantity, $show_sales_rate, $show_purchases_rate, $show_product_id);

                                mysqli_stmt_execute($stmtUpdateInventory);

                                mysqli_stmt_close($stmtUpdateInventory);
                            } else {
                                echo "Error in prepared statement for updating inventory: " . mysqli_error($conn);
                            }
                
            } else {
                echo "Error in prepared statement for purchases_items: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error in prepared statement for purchases_header: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request method";
}
