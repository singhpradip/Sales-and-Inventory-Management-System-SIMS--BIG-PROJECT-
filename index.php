<!-- <?php
  session_start();
?> -->
<!DOCTYPE html>
<html>
<head>
  <title>Sales & Inventory Management System</title>
  <link rel="stylesheet" type="text/css" href="css/index_new.css">
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="post" autocomplete="off">
                    <div id="name">
                        Sales & Inventory Management System
                    </div> 
                    <h2>Login</h2>
                    <?php
                    include 'admin/connect.php';
          
                    if (isset($_POST['login'])) {
                        $username = $_POST['username'];
                        $password = sha1($_POST['password']);
                    
                        $sql = "SELECT user_id FROM `users_table` WHERE username='$username' and password='$password'";
                        $result = mysqli_query($conn, $sql);
                        $data = mysqli_fetch_assoc($result);
                    
                        if (!empty($data)) {
                            // Start a new session
                            session_start();
                    
                            // Store the user_id in the session
                            $_SESSION['user_id'] = $data['user_id'];
                    
                            header('location: main.php');
                            exit();
                        } else {
                            echo '<p style="color: red; font-weight: bold; text-align: center;">! Wrong username or password.</p>';
                        }
                    }
                    
                  ?>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" required>
                        <label for="">Username</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required>
                        <label for="">Password</label>
                    </div>

                    <button type="submit" name="login">Login</button>
                    <div class="register">
                        <P><a href="admin/admin_login.php">Are you an admin ?</a></P>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>