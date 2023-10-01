// // JavaScript code for calculating and updating total expenses
// document.addEventListener('DOMContentLoaded', function () {
//     calculateTotalExpenses();
// });

// function calculateTotalExpenses() {
//     const expensesCells = document.querySelectorAll('tbody td:nth-child(3)');
//     let totalExpenses = 0;

//     expensesCells.forEach(cell => {
//         totalExpenses += parseFloat(cell.textContent);
//     });

//     // Update the total expenses in the table footer
//     // document.getElementById('totalExpenses').textContent = totalExpenses.toFixed(2);
//     // // Add a CSS class to make the total expenses cell bold
//     // totalExpensesCell.classList.add('bold');
// }


// // ==================================================


// // JavaScript to open the expenses edit form and pre-fill the data
// function openEditForm(purchasesId, expenses) {
//     var editFormModal = document.getElementById('editExpensesModal');
//     editFormModal.style.display = 'block';

//     // Pre-fill the purchases ID input (readonly) with the purchases ID
//     document.getElementById('editPurchasesIdDisplay').value = purchasesId;

//     // Store the purchases ID in a hidden input field for reference
//     document.getElementById('editPurchasesId').value = purchasesId;

//     // // Pre-fill the expenses input with the current expenses value
//     // document.getElementById('editExpenses').value = expenses;
// }

// // JavaScript to close the expenses edit form
// function closeEditForm() {
//     var editFormModal = document.getElementById('editExpensesModal');
//     editFormModal.style.display = 'none';
// }

// // JavaScript to update the expenses
// function updateExpenses() {
//         // Get the new sales price and product ID from the form
//         var editExpenses = document.getElementById("editExpenses").value;
//         var editPurchasesId = document.getElementById("editPurchasesId").value;

//         // Send an AJAX request to update the sales rate
//         var xhr = new XMLHttpRequest();
//         xhr.open("POST", "sections/update_expenses.php", true);
//         xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

//         // Define the callback function when the request is complete
//         xhr.onreadystatechange = function () {
//             if (xhr.readyState === 4) { // Request is complete
//                 if (xhr.status === 200) { // Request was successful
//                     var response = xhr.responseText;
//                     if (response === "success") {
//                         alert("Sales price updated successfully.");
//                         // Close the modal and refresh the table
//                         closeEditForm();
//                         window.location.reload();
//                     } else {
//                         alert("Error updating sales price.");
//                     }
//                 } else {
//                     alert("Error: " + xhr.statusText);
//                 }
//             }
//         };

//         // Send the request with the product ID and new sales price as data
//         xhr.send("editPurchasesId=" + editPurchasesId + "&editExpenses=" + editExpenses);
// }

// // JavaScript to validate input (allow only numbers and one decimal point)
// function validateInput(input) {
//     input.value = input.value.replace(/[^0-9.]/g, ''); // Remove non-numeric and non-dot characters
//     var decimalCount = (input.value.match(/\./g) || []).length;
//     if (decimalCount > 1) {
//         input.value = input.value.replace(/\.+$/, ''); // Remove extra dots
//     }
// }




// open the edit expenses modal and pre-fill the fields
function openEditForm(currentExpenses) {
    var modal = document.getElementById('updatePriceModal');
    modal.style.display = 'block';

    // Pre-fill the current expenses input with the current expenses
    document.getElementById('expenses').value = currentExpenses;

    // Store the purchase ID in a hidden input field for reference
    document.getElementById('purchases_id').value = currentExpenses;

    // Clear the new expenses input
    document.getElementById('newexpenses').value = '';
}

// JavaScript to validate input (allow only numbers and one decimal point)
function validateInput(input) {
    input.value = input.value.replace(/[^0-9.]/g, ''); // Remove non-numeric and non-dot characters
    var decimalCount = (input.value.match(/\./g) || []).length;
    if (decimalCount > 1) {
        input.value = input.value.replace(/\.+$/, ''); // Remove extra dots
    }
}

// Function to update expenses
function updateExpenses() {
    // Get the new expenses and purchase ID from the form
    var newExpenses = document.getElementById("newexpenses").value;
    var purchasesId = document.getElementById("purchases_id").value;

    // Send an AJAX request to update the expenses
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "sections/_expenses_update.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Define the callback function when the request is complete
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) { // Request is complete
            if (xhr.status === 200) { // Request was successful
                var response = xhr.responseText;
                if (response === "success") {
                    alert("Expenses updated successfully.");
                    // Close the modal and refresh the table
                    closeEditModal();
                    window.location.reload();
                } else {
                    alert("Error updating expenses.");
                }
            } else {
                alert("Error: " + xhr.statusText);
            }
        }
    };

    // Send the request with the purchase ID and new expenses as data
    xhr.send("purchases_id=" + purchasesId + "&new_expenses=" + newExpenses);
}

// JavaScript to close the edit expenses modal
function closeEditModal() {
    var modal = document.getElementById('updatePriceModal');
    modal.style.display = 'none';
}
