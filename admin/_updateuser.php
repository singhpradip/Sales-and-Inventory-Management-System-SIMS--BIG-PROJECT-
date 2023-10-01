<?php
    //session start and keep loggedin or redirect to login page. it provide security
    session_start();
    $userprofile=$_SESSION['usersession'];
    if($userprofile==false){
        header('location:admin_login.php');
    }

?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/style-global.css">
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
        form, h2 {
          margin-left: 20%;


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

        button {
            background-color: var(--blue);
            color: var(--white);
            border: none;
            border-radius: 5px;
            padding: 10px 20px; 
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button :hover{
            background-color: #0056b3;
        }
        #goback{
          margin-left: 66%;
          align-items: center;
        }

    </style>
</head>
<body>
<h2>Update Password</h2>
  <?php
    include 'connect.php';
    $id=$_GET['updateid'];
    $sql = "SELECT * FROM `users_table` WHERE `users_table`.`user_id` = $id";
    $result=mysqli_query($conn, $sql);
    $row=mysqli_fetch_assoc($result);
    $username=$row['username'];
    $password=$row['password'];


    if(isset($_POST['update'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $cpassword= $_POST['cpassword'];
    
        if($password === $cpassword && $password !== NULL && $cpassword !== NULL && $username !== '') {
          if (preg_match('/[a-zA-Z]/', $password) && preg_match('/[0-9]/', $password) && strlen($password) >= 6) {
            $password= sha1($password);
              if (!preg_match('/\s/', $username)) {    // Additional validation for username
                  $sql = "UPDATE `users_table` SET `username` = '$username', `password` = '$password' WHERE `users_table`.`user_id` = $id";
                  $result = mysqli_query($conn, $sql);
                  header('location:admin_users.php');
              } else {
                  echo '<p style="color: red; font-weight: bold;">! Username must not contain any space</p>';
              }
          } else {
              echo '<p style="color: red; font-weight: bold;">Password must contain both characters and numbers, and be at least 6 characters long.</p>';
          }
      } else {
          echo '<p style="color: red; font-weight: bold;">Password does not match!</p>';
      }
    }
      
  ?>  

    <form method="post" autocomplete="off">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required autocomplete="off" value="<?php echo $username?>"><br><br>
        <label for="new_password">New Password:</label>
        <input type="password"  name="password" required autocomplete="off"><br><br>
        <label for="new_password">Conform Password:</label>
        <input type="password" id="conform_password"  name="cpassword" required autocomplete="off"><br><br>

        <?php if (isset($update_error)) { ?>
            <p style="color: red;"><?php echo $update_error; ?></p>
        <?php } ?>

        <button type="submit" name="update">Update Password</button>
      </form>
      <div class="input-group">
              <a href="admin_users.php"><button id="goback">Go Back to Admin Pannel</button><a>  
      </div>
</body>
</html>



