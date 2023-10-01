<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";

if (isset($_POST['purchases_id']) && isset($_POST['new_expenses'])) {
    // Sanitize and store the data
    $purchasesId = mysqli_real_escape_string($conn, $_POST['purchases_id']);
    $newExpenses = mysqli_real_escape_string($conn, $_POST['new_expenses']);

    // SQL query to update the expenses
    $sql = "UPDATE purchases_header SET expenses = '$newExpenses' WHERE purchases_id = '$purchasesId'";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}

mysqli_close($conn);
