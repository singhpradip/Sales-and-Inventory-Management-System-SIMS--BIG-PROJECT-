<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
    include "../admin/connect.php";

    // Query to fetch the user's username from the users_table
    $sql = "SELECT username FROM users_table WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->bind_result($username);
        $stmt->fetch();
        $stmt->close();
    } else {
        // Handle the query error
        die("Error fetching username: " . $stmt->error);
    }

    // Handle form submission to update the password
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = $_POST["new_password"];
        $conform_password = $_POST["conform_password"];


        // Validate and update the password in the database
        $sql = "UPDATE users_table SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            // Password updated successfully
            header("Location: /../index.php");
            exit();
        } else {
            // Handle the update error
            $update_error = "Error updating password: " . $stmt->error;
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <style>

        .allitem {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 10vh;
        }

        form {
            background-color: var(--gray);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); 
            text-align: center;
            width: 60%;
        }

        h2 {
            font-size: 24px;
            color: var(--white);
            margin: 0;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            background-color: var(--blue);
            width: 60%; 

        }

        label {
            font-size: 18px;
            color: var(--black1);
            display: inline-block;
            width: 30%;
            text-align: right;
            padding-right: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 60%;
            padding: 10px;
            border: 1px solid var(--gray2);
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }

        input[type="submit"] {
            background-color: var(--blue);
            color: var(--white);
            border: none;
            border-radius: 5px;
            padding: 10px 20px; 
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <h2>Update Password</h2>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly><br><br>
        
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required><br><br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="new_password">Conform Password:</label>
        <input type="password" id="conform_password" name="conform_password" required><br><br>

        <?php if (isset($update_error)) { ?>
            <p style="color: red;"><?php echo $update_error; ?></p>
        <?php } ?>

        <input type="submit" value="Update Password">
    </form>
</body>
</html>
