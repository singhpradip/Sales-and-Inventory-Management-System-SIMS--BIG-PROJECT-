<?php
    //session start and keep loggedin or redirect to login page
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
<h2>Add User</h2>
<?php
  include 'connect.php';
  if(isset($_POST['submit'])){
      $username = $_POST['username'];
      $password= sha1($_POST['password']);
      $cpassword= sha1($_POST['cpassword']);

    if($password === $cpassword && $password !== NULL && $cpassword !== NULL && $username !== '') {
          if (preg_match('/[a-zA-Z]/', $password) && preg_match('/[0-9]/', $password) && strlen($password) >= 6){
                  if (!preg_match('/\s/', $username)) {     // validation for username
                        // Check if the username is unique
                        $sql_check_username = "SELECT * FROM `users_table` WHERE `username` = '$username'";
                        $result_check_username = mysqli_query($conn, $sql_check_username);
                        if (mysqli_num_rows($result_check_username) > 0) {
                            echo '<p style="color: red; font-weight: bold;">Username already exists.</p>';
                        
                        } else {
                          $sql = "INSERT INTO `users_table` (`username`, `password`) VALUES ('$username', '$password')";
                          $result = mysqli_query($conn, $sql);
                          echo '<p style="color: blue; font-weight: bold;">New User: ' . $username . ' is added!</p>';
                        }
                        
                  } else {
                      echo '<p style="color: red; font-weight: bold;">Username must not contain any space.</p>';
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
        <input type="text" id="username" name="username" required autocomplete="off" value="<?php global $failed; if($failed!=1){echo $username;}?>"><br><br>
        <label for="new_password">Password:</label>
        <input type="password"  name="password" required autocomplete="off"><br><br>
        <label for="new_password">Conform Password:</label>
        <input type="password" id="conform_password"  name="cpassword" required autocomplete="off"><br><br>

        <button type="submit" name="submit">Conform</button>
      </form>
      <div class="input-group">
              <a href="admin_users.php"><button id="goback">Go Back to Admin Pannel</button><a>  
      </div>
      
</body>
</html>



