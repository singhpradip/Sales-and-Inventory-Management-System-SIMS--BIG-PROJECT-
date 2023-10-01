<?php
    $servername = "localhost"; // Default XAMPP server name
    $username = "root"; // Default XAMPP username
    $password = ""; // Default XAMPP password
    $dbname = "admin_db";

    $conn = new mysqli($servername, $username, $password, $dbname);   // Create connection
    // echo "db connected";

    // Check connection
    if ($conn->connect_error) {
        die("<br>Database Connection failed: " . $conn->connect_error);
    }
    