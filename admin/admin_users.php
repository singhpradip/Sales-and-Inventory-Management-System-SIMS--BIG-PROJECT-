<?php
    //session start and keep loggedin or redirect to login page.
    session_start();
    $userprofile=$_SESSION['usersession'];
    if($userprofile==false){
        header('location:admin_login.php');
    }
  //db connection and selecting all data to display bellow in the table
  include"connect.php";
  $sql = "SELECT * FROM users_table";
  $result=mysqli_query($conn,$sql);
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Zone</title>
    <link rel="stylesheet" type="text/css" href="../css/style-global.css">
    <link rel="stylesheet" href="../css/_table.css">

    <style>

        .btn{
          margin-top: 20px;
          margin-left: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 220px;
            margin-top: 60px;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #FFFFFF; 
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #DDDDDD;
            width: 40%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); 
            
            margin-left: 20%; 
        }
        .close {
            float: right;
            cursor: pointer;
            color: #FF0000; 
            font-size: 20px; 
            font-weight: bold; 
            padding: 5px; 
            border-radius: 5px;
            background-color: #FFFFFF; 
        }
        .close:hover {
            background-color: #FF0000; 
            color: #FFFFFF;
        }
        /* styling for the input fields and button */
        input[type="text"] {
            width: 90%;
            color:#FF0000 ;
            padding: 10px;
            margin-bottom: 10px;
            border: 0.5px solid #DDDDDD; 
            border-radius: 5px;
            font-weight: bold;
        }
        button {
            background-color: #007BFF; 
            color: #FFFFFF; 
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3; 
        }

    </style>
  </head>
<body>
  <thead>

        <!-- add new user and logout button -->
          <a href="_adduser.php"><button type="button"class="btn">Add New User</button></a>
          <a href="admin_logout.php"> <button type="button" class="btn">Logout</button></a>


<main class="tableRec">
        <section class="table__header">
            <h1>Authorized Users:</h1>

            <div class="input-group">
                <input type="search" placeholder="Search here">
                <ion-icon name="search-outline"></ion-icon>
            </div>
        </section>
    <section class="table__body">
        <table id="salesDataTable">
          <thead>
          <!-- creating table header -->
            <tr>
              <th scope="col">User_id</th>
              <th scope="col">Username</th>
              <th scope="col">Password</th>
              <th scope="col">Operation:</th>
            </tr>
          </thead>
          <tbody>
            <?php
              // Display data in table rows
              if ($result) {
                  // Fetch data in descending order by key
                  $sql = "SELECT * FROM `users_table` ORDER BY `user_id` DESC";
                  $result = mysqli_query($conn, $sql);

                  while ($row = mysqli_fetch_assoc($result)) {
                      $key = $row['user_id'];
                      $name = $row['username'];
                      $password = $row['password'];
                      echo '<tbody>
                      <tr>
                          <th scope="row">' . $key . '</th>
                          <td>' . $name . '</td>
                          <td>' . $password . '</td>
                          <td>
                              <a href="_updateuser.php?updateid=' . $key . '"><button type="button" class="btn btn-primary">Update</button></a>
                              <a href="_deleteuser.php?deleteid=' . $key . '"><button type="button" class="btn btn-danger">Delete</button></a>             
                          </td>
                      </tr>';
                  }
              } else {
                  echo "<tr><td colspan='3'>No data available</td></tr>";
              }
            ?>
          </tbody>
        </table>
    </section>
</main>

</body>
</html>