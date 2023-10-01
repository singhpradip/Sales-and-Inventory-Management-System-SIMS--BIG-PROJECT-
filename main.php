<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION ['user_id'];
    if ($user_id == false) {
        header('location:index.php');
    }
    include 'admin/connect.php';

    $sql = "SELECT username FROM `users_table` WHERE `users_table`.`user_id` = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS</title>
    <link rel="stylesheet" href="css/style-global.css">
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="stylesheet" href="css/_table.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <!-- Top bar -->
    <div id="top-nav">
        <div id="system-name">
            <span class="icon">
                <ion-icon name="bag-handle"></ion-icon>
            </span>
            SIMS
        </div>

        <div id="date-time">
            <span id="current-date-time"></span>
        </div>      

        <div id="user-welcome" class="user-welcome">
            <span><?php echo "Welcome, ".$username ?></span>
            <div class="user">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQquq9tRUa7tByP7rHLzcP3PK3O2KBInCaSpWGMtEtPZOaOZy30XXwJKgUm-OlefAZ-Itc&usqp=CAU" alt="user Img">
            </div>
        </div>
    </div>
 

    <!-- Sidebar ra content container -->
    <div id="container">
        <nav id="left-nav">
            <ul>
                <li>
                    <a href="#dashboard" id="dashboard-link"  class="active-link">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#Sales" id="sales-link" class="active-link">
                        <span class="icon">
                            <ion-icon name="stats-chart-outline"></ion-icon>
                        </span>
                        Sales 
                    </a>
                </li>
                <li>
                    <a href="#purchases" id="purchases-link" class="active-link">
                        <span class="icon">
                            <ion-icon name="cart-outline"></ion-icon>
                        </span>
                        Purchases
                    </a>
                </li>
                <li>
                    <a href="#prices"id="prices-link" class="active-link">
                        <span class="icon">
                            <ion-icon name="card-outline"></ion-icon>
                        </span>
                        Prices 
                    </a>
                </li>
                <li>
                    <a href="#customers" id="customers-link" class="active-link">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        Customers
                    </a>
                </li>
                <li>
                    <a href="#suppliers" id="suppliers-link" class="active-link">
                        <span class="icon">
                            <ion-icon name="briefcase-outline"></ion-icon>
                        </span>
                        Suppliers
                    </a>
                </li>
                <li>
                    <a href="#expenses" id="expenses-link" class="active-link">
                        <span class="icon">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </span>
                        Expenses
                    </a>
                </li>
                <li>
                    <a href="#inventory" id="inventory-link">
                        <span class="icon">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                        </span>
                        Inventory
                    </a>
                </li>
                <li>
                    <a href="#password" id="password-link">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        Password 
                    </a>
                </li>
                <li>
                    <a href="#logout" id="logout-link">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        Sign Out 
                    </a>
                </li>
            </ul>
        </nav>
        
        <main id="content">
            <!-- main content yeha load hunx dybamically -->
        </main>
    </div>

    <!-- Including all js -->
    <script src="js/main.js"></script>
    <script src="js/_table.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!------------icon packs (https://ionic.io/ionicons)--------------- -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
