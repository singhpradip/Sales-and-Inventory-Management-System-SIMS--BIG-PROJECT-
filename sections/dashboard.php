<?php
// Session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";
?>

<?php
// Calculating values for all 4 cart boxes

    $sqlLatestDate = "SELECT MAX(sales_date) AS latest_date FROM sales_header";
    $resultLatestDate = $conn->query($sqlLatestDate);

    if ($resultLatestDate->num_rows > 0) {
        $rowLatestDate = $resultLatestDate->fetch_assoc();
        $latestDate = $rowLatestDate['latest_date'];

        if ($latestDate === null) {
            echo "No sales data found.";
            exit;
        }
    } else {
        echo "No sales data found.";
        exit;
    }

        // Fetch the sum of bill_amount for the latest date
        $sqlSumAmount = "SELECT SUM(bill_amount) AS total_amount FROM sales_header WHERE sales_date = ?";
        $stmt = $conn->prepare($sqlSumAmount);
        $stmt->bind_param("s", $latestDate);
        $stmt->execute();
        $resultSumAmount = $stmt->get_result();
        $stmt->close();

        if ($resultSumAmount->num_rows > 0) {
            $rowSumAmount = $resultSumAmount->fetch_assoc();
            $totalAmount = $rowSumAmount['total_amount'];
            
            if ($totalAmount === null) {
                $totalAmount = 0;
            }
        } else {
            echo "No sales data found for the latest date.";
            exit;
        }

    // Fetch the sum of total_receivable from the customers_table
    $sqlSumReceivable = "SELECT SUM(total_receivable) AS total_receivable_sum FROM customers_table";
    $resultSumReceivable = $conn->query($sqlSumReceivable);

    if ($resultSumReceivable->num_rows > 0) {
        $rowSumReceivable = $resultSumReceivable->fetch_assoc();
        $totalReceivableSum = $rowSumReceivable['total_receivable_sum'];
        
        if ($totalReceivableSum === null) {
            $totalReceivableSum = 0;
        }
    } else {
        echo "No data found in the customers_table.";
        exit;
    } 


    // Fetch the sum of payable from the suppliers_table
    $sqlSumPayable = "SELECT SUM(total_payable) AS payable_sum FROM suppliers_table";
    $resultSumPayable = $conn->query($sqlSumPayable);

    if ($resultSumPayable->num_rows > 0) {
        $rowSumPayable = $resultSumPayable->fetch_assoc();
        $payableSum = $rowSumPayable['payable_sum'];
        
        if ($payableSum === null) {
            $payableSum = 0;
        }
    } else {
        echo "No data found in the suppliers_table.";
        exit;
    }

    // SQL query to fetch data and calculate profit
    $sql = "SELECT sn, product_id, sales_rate, purchases_rate, quantity FROM sales_items2";
    $result = mysqli_query($conn, $sql);

    $totalProfit = 0; // Initialize the total profit variable

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $salesRate = $row['sales_rate'];
            $purchasesRate = $row['purchases_rate'];
            $quantity = $row['quantity'];

            // Calculate profit for the current row
            $profit = ($salesRate - $purchasesRate) * $quantity;

            // Add the profit to the total profit
            $totalProfit += $profit;
        }
    }


?>


<!DOCTYPE html>
<html>
<head>
    <!-- <link rel="stylesheet" href="../css/dashboard.css"> -->
</head>
<body>
    <div class="cardBox">
        <div class="card">
            <div>
                <div class="numbers">Rs.<?php echo $totalAmount?></div>
                <div class="cardName">Daily Sales</div>
            </div>

            <div class="iconBx">
                <ion-icon name="pulse-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="numbers">Rs.<?php echo $totalReceivableSum?></div>
                <div class="cardName">Total Receivable</div>
            </div>

            <div class="iconBx">
                <ion-icon name="person-add-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="numbers">Rs.<?php echo $payableSum?></div>
                <div class="cardName">Total Payable </div>
            </div>

            <div class="iconBx">
                <ion-icon name="person-remove-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="numbers">Rs.<?php echo $totalProfit?></div>
                <div class="cardName">Total Profit</div>
            </div>

            <div class="iconBx">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
        </div>
    </div>
    <!-- ================ Add Charts ================= -->
    <div class="chartsBx">
        <div class="chart"> <canvas id="chart-1"></canvas> </div>
        <div class="chart"> <canvas id="chart-2"></canvas> </div>
    </div>


    <!-- ================ Order Details List [TODO] ================= -->

</body>
</html>

