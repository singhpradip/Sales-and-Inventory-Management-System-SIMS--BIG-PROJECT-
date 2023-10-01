<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";

// SQL query to count products in each category
$sql = "SELECT category, COUNT(*) as count FROM inventory_table GROUP BY category";

$result = mysqli_query($conn, $sql);

$data = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
mysqli_close($conn);

