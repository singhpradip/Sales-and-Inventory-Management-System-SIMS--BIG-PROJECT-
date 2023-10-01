<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";


// Calculate the date 30 days ago from today
$thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

// SQL query to fetch bill_amount for the last 30 days
$sql = "SELECT DATE(sales_date) as date, SUM(bill_amount) as total FROM sales_header WHERE sales_date >= '$thirtyDaysAgo' GROUP BY date";

$result = mysqli_query($conn, $sql);

$data = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
mysqli_close($conn);


