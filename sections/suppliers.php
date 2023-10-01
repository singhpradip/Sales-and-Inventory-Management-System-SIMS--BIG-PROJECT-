<?php
    // Session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }

    include "../admin/connect.php";

    // query to fetch data from customers_table in descending order of sn
    $query = "SELECT sn, supplier_id, supplier_name, supplier_address, total_payable
    FROM suppliers_table 
    ORDER BY sn DESC";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if (!$result) {
        die("Database query failed.");
    }
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
                    <h1>Amount Payable</h1>
                    <div class="input-group">
                        <input type="search" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </div>
                </section>
            <section class="table__body">
                <table id="salesDataTable">
                    <thead>
                        <tr>
                            <th>Serial Number</th>
                            <th>Supplier ID</th>
                            <th>Supplier Name</th>
                            <th>Supplier Address</th>
                            <th>Total Payable</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Loop through the query result and display each row
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['sn'] . "</td>";
                                echo "<td>" . $row['supplier_id'] . "</td>";
                                echo "<td>" . $row['supplier_name'] . "</td>";
                                echo "<td>" . $row['supplier_address'] . "</td>";
                                echo "<td>" . $row['total_payable'] . "</td>";
                                echo "<td><button onclick='openModal(" . $row['supplier_id'] . ", " . $row['total_payable'] . ")'>Payment</button></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>

        <!-- ===========POPUP BOX============================ -->
        
        <!-- Modal for Payment Settlement -->
        <div id="paymentModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Payment Settlement</h2><br>
                <!-- Hidden input fields to store customerId and totalReceivable -->
                <input type="hidden" id="supplierId" name="supplierId">
                <input type="hidden" id="payable" name="payable">

                <label for="totalPayableDisplay">Total Payable Amount:</label>
                <input type="text" id="totalPayableDisplay" name="totalPayableDisplay" readonly><br>

                <label for="paidAmount">Amount Paid:</label>
                <input type="text" id="paidAmount" name="paidAmount" oninput="validateInput(this)"><br>

                <button type="button" onclick="submitPayment()">Submit</button>
            </div>
        </div>

    </body>
</html>

<?php
mysqli_close($conn);
?>
