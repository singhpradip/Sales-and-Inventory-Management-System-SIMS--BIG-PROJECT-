
<?php
    //session and login holder
    session_start();
    $user_id = $_SESSION['user_id'];
    if ($user_id == false) {
        header('location:..\index.php');
    }
   
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .allitem{
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 10vh;
            /* align-items: center; */
            align-items: flex-start;
        }

        .logoutbox {
            background-color: #f4f4f4;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-left: 20px;
            
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--black1);
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
            color: var(--black2);
        }

        .clickable-container {
            display: flex;
            justify-content: center;
        }

        .clickable {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            text-align: center;
            text-decoration: none;
            border: none;
            background-color: var(--blue);
            color: var(--white); 
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .clickable:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="allitem">
        <div class="logoutbox">
            <h1>Are you sure, you want to Sign out?</h1>
            <a href="sections/_logout.php" class="clickable">Logout</a>
        </div>
    </div>
</body>
</html>
