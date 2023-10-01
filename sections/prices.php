<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";
?>

<!DOCTYPE html>
<html>
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

    </style>
</head>
<body>
<main class="tableRec">
        <section class="table__header">
            <h1>Products Price</h1>

            <div class="input-group">
                <input type="search" placeholder="Search here">
                <ion-icon name="search-outline"></ion-icon>
            </div>
        </section>
    <section class="table__body">
        <table id="salesDataTable">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Purchase Rate</th>
                    <th>Sales Rate</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="priceTableBody">
                <?php

                // Query to fetch data from the inventory table
                $sql = "SELECT product_id, product_name, purchases_rate, sales_rate FROM inventory_table";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['product_id']}</td>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['purchases_rate']}</td>";
                        echo "<td><b>{$row['sales_rate']}</b></td>";
                        echo "<td>";
                        echo "<button onclick='openUpdateModal({$row['product_id']}, {$row['sales_rate']})'>Change Price</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </section>
</main>

    <!-- Popup form to update sales price -->
    <div id="updatePriceModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2>Update Sales Price</h2><br>
            <!-- Hidden input fields to store productId -->
            <input type="hidden" id="updateProductId" name="updateProductId">

            <!-- Display the current sales price -->
            <label for="currentSalesPrice">Current Sales Price:</label>
            <input type="text" id="currentSalesPrice" name="currentSalesPrice" readonly><br>

            <!-- Enter the new sales price -->
            <label for="newSalesPrice">New Sales Price:</label>
            <input type="text" id="newSalesPrice" name="newSalesPrice" oninput="validateInput(this)"><br>

            <button type="button" onclick="updateSalesPrice()">Update</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </div>
    </div>


</body>
</html>
