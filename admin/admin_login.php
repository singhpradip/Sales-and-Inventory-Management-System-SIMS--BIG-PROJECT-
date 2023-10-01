<!-- <?php
  session_start();
  ?> -->
  <!DOCTYPE html>
  <html>
  <head>
    <title>Sales & Inventory Management System</title>
    <link rel="stylesheet" type="text/css" href="../css/index_new.css">
  </head>
  <body>
      <section>
          <div class="form-box">
              <div class="form-value">
                  <form method="post" autocomplete="off">
                      <!-- <h1>Sales & Inventory Management System</h1> -->
                      <h2>Login as Administrator</h2>
                        <?php
                          // // Check if the form is submitted
                          if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $username = $_POST["username"];
                            $password = $_POST["password"];
                          
                            // Check if the username and password match
                            if ($username === "admin" && $password === "admin") {
                              $_SESSION['usersession']=$username;
                              header("Location: admin_users.php"); //loads admin_user.php
                              exit;
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
                          <P><a href="../index.php">GO BACK TO LOGIN ?</a></P>
                      </div>
                  </form>
              </div>
          </div>
      </section>
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
      <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  </body>
  </html>