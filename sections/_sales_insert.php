<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('../admin/connect.php');

    // Read the JSON data sent from the client
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data);

    // Handle data for customers_table
    $customerId = $data->customerId;


    // Handle data for sales_header
    $totalAmount = $data->totalAmount;
    $discount = $data->discount;
    $billAmount = $data->billAmount;
    $received = $data->received;
    $receivable = $data->receivable;

    // Query to fetch the current maximum sales_id
    $sql = "SELECT MAX(sales_id) AS max_sales_id FROM sales_header";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $max_sales_id = $row['max_sales_id'];

        if ($max_sales_id !== null) {
            $new_sales_id = $max_sales_id + 1;
        } else {
            // If there are no records in the table, set sales_id to 1
            $new_sales_id = 1;
        }
    }

    // Create and execute the SQL query for sales_header
    $sql1 = "INSERT INTO `sales_header` (`sales_id`, `total_amount`, `discount`, `bill_amount`, `received`, `receivable`, `customer_id`, `sales_date`) VALUES (?, ?, ?, ?, ?, ?, ?, current_timestamp())";
    $stmt1 = mysqli_prepare($conn, $sql1);

    if ($stmt1) {
        // Bind parameters for sales_header
        mysqli_stmt_bind_param($stmt1, "idddddi", $new_sales_id, $totalAmount, $discount, $billAmount, $received, $receivable, $customerId);

        // Execute the prepared statement for sales_header
        mysqli_stmt_execute($stmt1);

        // Close the statement
        mysqli_stmt_close($stmt1);



            // sales Item info ------------------------------------------------------

            $salesItems = $data->salesItems;

            foreach ($salesItems as $item) {
                $product_id = $item->product_id;
                $product_name = $item->product_name;
                $sales_rate = $item->sales_rate;
                $quantity = $item->quantity;
                $item_total = $item->item_total;

                // Fetch the purchases_rate from the purchases table
                $sqlFetchPurchasesRate = "SELECT `purchases_rate` FROM `purchases` WHERE `product_id` = ?";
                $stmtFetchPurchasesRate = mysqli_prepare($conn, $sqlFetchPurchasesRate);

                if ($stmtFetchPurchasesRate) {
                    // Bind parameter for fetching purchases_rate
                    mysqli_stmt_bind_param($stmtFetchPurchasesRate, "i", $product_id);

                    mysqli_stmt_execute($stmtFetchPurchasesRate);

                    mysqli_stmt_bind_result($stmtFetchPurchasesRate, $purchases_rate);

                    mysqli_stmt_fetch($stmtFetchPurchasesRate);

                    mysqli_stmt_close($stmtFetchPurchasesRate);
                } else {
                    echo "Error in prepared statement for fetching purchases_rate: " . mysqli_error($conn);
                    continue; // Skip this iteration and continue with the next item
                }

                // Create and execute the SQL query for each sales item
                $sql3 = "INSERT INTO `sales_items2` (`product_id`, `sales_id`, `product_name`, `purchases_rate`, `sales_rate`, `quantity`, `item_total_amount`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt3 = mysqli_prepare($conn, $sql3);

                if ($stmt3) {
                    // Bind parameters for each sales item
                    mysqli_stmt_bind_param($stmt3, "iisdddd", $product_id, $new_sales_id, $product_name, $purchases_rate, $sales_rate, $quantity, $item_total);

                    mysqli_stmt_execute($stmt3);

                    mysqli_stmt_close($stmt3);

                    // Deduct sold quantity from inventory_table
                    $sqlUpdateInventory = "UPDATE `inventory_table` SET `quantity_available` = `quantity_available` - ? WHERE `product_id` = ?";
                    $stmtUpdateInventory = mysqli_prepare($conn, $sqlUpdateInventory);

                    if ($stmtUpdateInventory) {
                        mysqli_stmt_bind_param($stmtUpdateInventory, "di", $quantity, $product_id);

                        mysqli_stmt_execute($stmtUpdateInventory);

                        mysqli_stmt_close($stmtUpdateInventory);
                    } else {
                        echo "Error in prepared statement for updating inventory: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error in prepared statement for sales_items2: " . mysqli_error($conn);
                }
            }

    } else {
        echo "Error in prepared statement for sales_header: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request method";
}
