
<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";
    $sql = "SELECT * FROM sales";
    $result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<!-- section of bulk purchases -->
<section class="salesBooth">
    <h1>Entry of Purchases</h1>
    <form id="salesForm" method="POST">
        <table id="purchasesTable">
            <tr>
                <th><label for="product_id" >Product Id</label></th>
                <th><label for="product_name">Product Name</label></th>
                <th><label for="category">Category</label></th>
                <th><label for="sales_rate">Sales rate</label></th>
                <th><label for="quantity">Quantity</label></th>
                <th><label for="purchases_rate">Purchases rate</label></th>
                <th><label for="item_total">Total</label></th>
            </tr>
            <tr>
                <td><input type="text" id="show_product_id" onblur="fetchProduct()"> </td>
                <td><input type="text" id="show_product_name"></td>
                <td><input type="text" id="show_product_category"></td>
                <td><input type="text" id="show_sales_rate"></td>
                <td><input type="text" id="show_quantity"></td>
                <td><input type="text" id="show_purchases_rate"></td>
                <td><input type="total" id="show_total"  onfocus="calculate()"></td>
            </tr>       
            <tr>
                <td colspan="3">
                    <button type="button" onclick="addTableRow()">More items</button>
                </td>
                <td colspan="3">
                    <button type="button" onfocus="totalpayment()">Calculate</button>
                </td>
                <td><input type="text" id="show_grand_total" onfocus="totalpayment()"></td>
            </tr>    
        </table>

        <table>
            <td>
                <!-- Suppliers info table -->
                <table class="subtable">
                    <tr>
                        <th colspan="2" style="align-items: center;">Supplier Info</th>
                    </tr>
                    <tr>
                        <td>Mobile Number:</td>
                        <td><input type="text" id="supplier_id" name="supplier_id" onblur="fetch_supplier()"></td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" id="supplier_name" name="supplier_name"></td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><input type="text" id="supplier_address" name="supplier_address"></td>
                    </tr>
                </table>
            </td>
            <td>
                <!-- Total calucation table -->
                <table class="subtable">
                    <tr>
                        <td>Expenses:</td>
                        <td><input type="text" id="expenses" name="expenses"></td>
                    </tr>
                    <tr>
                        <td><b>Bill Amount:</b></td>
                        <td><input type="text" id="bill_amount" name="bill_amount" onfocus="totalpayment()" style="font-weight: bold;"></td>
                    </tr>
                    <tr>
                        <td>Paid:</td>
                        <td><input type="text" id="paid" name="paid"></td>
                    </tr>
                    <tr>
                        <td>Due:</td>
                        <td><input type="text" id="payable" name="payable" onfocus="calculateDeu()" ></td>
                    </tr>
                </table>
            </td>
        </table>
        
        <div><button type="button" id="sales_update" onclick="supplier_info(); sendDataToServer();">Complete</button></div>

    </form>
</section>
<!-- purchases record section end -->


<button id="scrollButton">Scroll Down</button>


<!-- ============== show Purchases records ========================== -->
<main class="tableRec">
    <section class="table__header">
        <h1>Purchases History</h1>

        <div class="input-group">
            <input type="search" placeholder="Search here">
            <ion-icon name="search-outline"></ion-icon>
        </div>
    </section>
    <section class="table__body">
        <table id="salesDataTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>purchases_id</th>
                    <th>Products</th>
                    <th>Total_amount</th>
                    <th>Expenses</th>
                    <th>Paid</th>
                    <th>Payable</th>
                    <th>Supplier ID</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // Fetch data from purchases_header and purchases_items tables
                $query = "SELECT ph.purchases_date, ph.purchases_id, GROUP_CONCAT(pi.product_name SEPARATOR ', ') as products,
                    ph.total_amount, ph.expenses, ph.paid, ph.payable, ph.supplier_id
                    FROM purchases_header ph
                    LEFT JOIN purchases_items pi ON ph.purchases_id = pi.purchases_id
                    GROUP BY ph.purchases_id
                    ORDER BY ph.purchases_id DESC";

                $result = mysqli_query($conn, $query);

                if ($result === false) {
                    die("Error in SQL query: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['purchases_date'] . "</td>";
                        echo "<td>" . $row['purchases_id'] . "</td>";
                        echo "<td>" . $row['products'] . "</td>";
                        echo "<td>" . $row['total_amount'] . "</td>";
                        echo "<td>" . $row['expenses'] . "</td>";
                        echo "<td>" . $row['paid'] . "</td>";
                        echo "<td>" . $row['payable'] . "</td>";
                        echo "<td>" . $row['supplier_id'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No purchases data available</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
