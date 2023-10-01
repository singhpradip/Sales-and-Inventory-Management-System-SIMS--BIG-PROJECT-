<?php
// Session and login holder
session_start();
$user_id = $_SESSION['user_id'];
if ($user_id == false) {
    header('location:..\index.php');
}

include "../admin/connect.php";

// fetch data from purchases_header table in descending order of purchases_id
$query = "SELECT purchases_id, purchases_date, expenses 
FROM purchases_header 
ORDER BY purchases_id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
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
        .bold {
            font-weight: bold;
        }


    </style>
</head>
<body>
<main class="tableRec">
        <section class="table__header">
            <h1>Products Price</h1>

            <div class="input-group">
                <input type="search" placeholder="Search here">
                <ion-icon name="search-outline"></ion-icon>
            </div>
        </section>
    <section class="table__body">
        <table id="salesDataTable">
            <thead>
            <tr>
                <th>Purchase ID</th>
                <th>Purchase Date</th>
                <th>Expenses</th>
                <!-- <th>Action</th> -->
            </tr>
            </thead>
            <tbody>
            <?php
                // Loop through the query result and display each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['purchases_id'] . "</td>";
                    echo "<td>" . $row['purchases_date'] . "</td>";
                    echo "<td>" . $row['expenses'] . "</td>";
                    // echo "<td><button onclick='openEditForm(" . $row['expenses'] . ")'>Edit</button></td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total Expenses:</th>
                    <td id="totalExpenses">0.00</td>
                </tr>
            </tfoot>
        </table>
    </section>
</main>

    <!-- Popup form to update sales price -->
    <div id="updatePriceModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2>Edit Expenses</h2><br>
            <!-- Hidden input fields to store productId -->
            <input type="hidden" id="purchases_id" name="purchases_id">

            <!-- Display the current sales price -->
            <label for="expenses">Total Expenses:</label>
            <input type="text" id="expenses" name="expenses" readonly><br>

            <!-- Enter the new sales price -->
            <label for="newexpenses">Corrected Expenses:</label>
            <input type="text" id="newexpenses" name="newexpenses" oninput="validateInput(this)"><br>

            <button type="button" onclick="updateexpenses()">Update</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </div>
    </div>

</body>
</html>

<?php
mysqli_close($conn);
?>
