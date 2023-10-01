
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
<!-- section of sales booth -->
<section class="salesBooth">
    <h1>Sales Section</h1>
    <form id="salesForm" method="POST">
        <table id="salesTable">
            <tr>
                <th><label for="product_id" >Product Id</label></th>
                <th><label for="product_name">Product Name</label></th>
                <th><label for="sales_rate">Price</label></th>
                <th><label for="quantity">Quantity</label></th>
                <th><label for="item_total">Total</label></th>
            </tr>
            <tr>
                <td><input type="text" id="show_product_id" oninput="validateNeg(this)" onblur="fetchProduct()" required> </td>
                <td><input type="text" id="show_product_name" readonly></td>
                <td><input type="text" id="show_product_sp" readonly></td>
                <td><input type="text" id="show_quantity" oninput="validateNeg(this)" oninput="validateInput(this)" required></td>
                <td><input type="total" id="show_total"  onfocus="calculate()" readonly></td>
            </tr>       
            <tr>
                <td colspan="2">
                    <button type="button" onclick="addTableRow()">Add Row</button>
                </td>
                <td colspan="2">
                    <button type="button" onfocus="totalpayment()">Calculate</button>
                </td>
                <td><input type="text" id="show_grand_total" onfocus="totalpayment()" readonly required></td>
            </tr>    
        </table>

        <table>
            <td>
                <!-- Customers table -->
                <table class="subtable">
                    <tr>
                        <th colspan="2" style="align-items: center;">Customer Info</th>
                    </tr>
                    <tr>
                        <td>Mobile Number:</td>
                        <td><input type="number" id="customer_id" name="customer_id" oninput="validateNeg(this)" onblur="fetch_customer()" ></td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" id="customer_name" name="customer_name"></td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><input type="text" id="customer_address" name="customer_address"></td>
                    </tr>
                </table>
            </td>

            <td>
                <!-- Total amounts table -->
                <table class="subtable">
                    <tr>
                        <td>Discount:</td>
                        <td><input type="text" id="discount" name="discount" oninput="validateInput(this); validateNeg(this);"></td>
                    </tr>
                    <tr>
                        <td><b>Bill Amount:</b></td>
                        <td><input type="text" id="bill_amount" name="bill_amount" onfocus="calculateBillAmount()" oninput="validateNeg(this)" readonly required></td>
                    </tr>
                    <tr>
                        <td>*Received:</td>
                        <td><input type="text" id="received" name="received" oninput="validateInput(this); validateNeg(this);" required></td>
                    </tr>
                    <tr>
                        <td>Receivable:</td>
                        <td><input type="text" id="receivable" name="receivable" onfocus="calculateReceivable()" oninput="validateNeg(this)" readonly required></td>
                    </tr>
                </table>
            </td>
        </table>
        
        <div><button type="button" id="sales_update" onclick="customer_info(); sendDataToServer();">Complete</button></div>

    </form>
</section>
<!-- sales record section end -->


<button id="scrollButton">Scroll Down</button>


<!-- ============== show sales records ========================== -->
<main class="tableRec">
        <section class="table__header">
            <h1>Sales Data</h1>

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
                    <th>sales_id</th>
                    <th>Products</th>
                    <th>Discount</th>
                    <th>Total_amount</th>
                    <th>Received</th>
                    <th>Receivable</th>
                    <th>Customer ID</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // Fetch data from sales_header and sales_items tables
                $query = "SELECT sh.sales_date, sh.sales_id, GROUP_CONCAT(si.product_name SEPARATOR ', ') as products, 
                    sh.discount, sh.total_amount, sh.received, sh.receivable, sh.customer_id
                    FROM sales_header sh
                    LEFT JOIN sales_items2 si ON sh.sales_id = si.sales_id
                    GROUP BY sh.sales_id
                    ORDER BY sh.sales_id DESC";

                $result = mysqli_query($conn, $query);

                if ($result === false) {
                    die("Error in SQL query: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['sales_date'] . "</td>";
                        echo "<td>" . $row['sales_id'] . "</td>";
                        echo "<td>" . $row['products'] . "</td>";
                        echo "<td>" . $row['discount'] . "</td>";
                        echo "<td>" . $row['total_amount'] . "</td>";
                        echo "<td>" . $row['received'] . "</td>";
                        echo "<td><b>" . $row['receivable'] . "</b></td>";
                        echo "<td>" . $row['customer_id'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No sales data available</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
