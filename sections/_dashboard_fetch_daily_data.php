<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION ['user_id'];
    if ($user_id == false) {
        header('location:index.php');
    }
    include 'admin/connect.php';

    echo "Debug: Before database query<br>";

    // Query to fetch daily sales data for the recent month
    $query = "SELECT DAY(sales_date) AS day, SUM(bill_amount) AS total_sales
              FROM sales_header
              WHERE MONTH(sales_date) = MONTH(CURRENT_DATE())
              GROUP BY DAY(sales_date)
              ORDER BY DAY(sales_date)";
    
    $result = $mysqli->query($query);
    echo "Debug: after database query<br>";
    
    // Prepare the data in an associative array
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = "Day " . $row['day'];
        $data['data'][] = $row['total_sales'];
    }
    
    $mysqli->close();
    
    // Send the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);

    mysqli_close($conn);
    