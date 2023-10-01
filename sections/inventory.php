
<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";

    // Query to retrieve data from the inventory_table
    $query = "SELECT * FROM inventory_table ORDER BY sn DESC";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
?>

<?php


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from the form
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $sales_price = $_POST['sales_price'];

    // Check if the product already exists in the database
    $check_query = "SELECT * FROM `inventory_table` WHERE `product_id` = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $product_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $message = "Product already exists in the database.";
    } else {
        // if Product does not exist, insert it into the database
        $insert_query = "INSERT INTO `inventory_table` (`product_id`, `product_name`, `category`, `sales_rate`) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);

        if ($insert_stmt) {
            mysqli_stmt_bind_param($insert_stmt, "sssd", $product_id, $product_name, $category, $sales_price);
            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);

            $message = "Product added successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 220px;
        margin-top: 60px;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .modal-content {
        background-color: #FFFFFF; 
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #DDDDDD; 
        width: 40%;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); 
        margin-left: 20%; 
    }
    .close {
        float: right;
        cursor: pointer;
        color: #FF0000;
        font-size: 20px; 
        font-weight: bold; 
        padding: 5px;
        border-radius: 5px;
        background-color: #FFFFFF;
    }
    .close:hover {
        background-color: #FF0000;
        color: #FFFFFF;
    }
    /* styling for the input fields and button */
    input[type="text"] {
        width: 90%;
        color:#FF0000 ;
        padding: 10px;
        margin-bottom: 10px;
        border: 0.5px solid #DDDDDD;
        border-radius: 5px;
        font-weight: bold;
    }
    button {
        background-color: #007BFF;
        color: #FFFFFF;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
    }
    button:hover {
        background-color: #0056b3;
    }

    .popup-form {
            display: none;
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f2f2f2;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px #888;
            z-index: 1;
        }

</style>
</head>
<body>
<button onclick="openPopupForm()">Create New Product</button>

    
<main class="tableRec">
                <section class="table__header">
                    <h1>Total Inventory</h1>
                    <div class="input-group">
                        <input type="search" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </div>
                </section>
<section class="table__body">
    <table id="salesDataTable">
        <tr>
            <th>SN</th>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Quantity Available</th>
            <th>Purchases Rate</th>
            <th>Sales Rate</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php
        // Loop through the result set and display data in the table
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['sn'] . "</td>";
            echo "<td>" . $row['product_id'] . "</td>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td><b>" . $row['quantity_available'] . "</b></td>";
            echo "<td>" . $row['purchases_rate'] . "</td>";
            echo "<td>" . $row['sales_rate'] . "</td>";
            echo "<td></td>";
            echo '<td><button class="delete-button" data-product-id="' . $row['product_id'] . '">Delete</button></td>';
            echo "</tr>";
        }
        ?>
    </table>




 <!-- The popup form -->
    <div id="popupForm" class="popup-form">
        <h2>Create New Product</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="handleFormSubmit(event)">
            <label for="product_id">Product ID:</label>
            <input type="text" id="product_id" name="product_id" required>

            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required><br><br>

            <label for="sales_price">Sales Price:</label>
            <input type="text" id="sales_price" name="sales_price" required><br><br>

            <input type="submit" value="Submit">
        </form>
        <p id="successMessage"></p>
    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>
