// open the payment modal and pre-fill the amount
function openModal(customerId, totalReceivable) {
    var modal = document.getElementById('paymentModal');
    modal.style.display = 'block';

    // Pre-fill the total receivable amount input with the total receivable amount
    document.getElementById('totalReceivableDisplay').value = totalReceivable;

    // Store the customer ID and total receivable amount in hidden input fields for reference
    document.getElementById('customerId').value = customerId;
    document.getElementById('totalReceivable').value = totalReceivable;

    // Clear the received amount input
    document.getElementById('receivedAmount').value = '';
}

// JavaScript to close the payment modal
function closeModal() {
    var modal = document.getElementById('paymentModal');
    modal.style.display = 'none';
}

// JavaScript to submit the payment
function submitPayment() {
        // Get the new sales price and product ID from the form
        var receivedAmount = document.getElementById("receivedAmount").value;
        var customerId = document.getElementById("customerId").value;

        // Send an AJAX request to update the sales rate
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "sections/_customers_process_payment.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        // Define the callback function when the request is complete
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) { // Request is complete
                if (xhr.status === 200) { // Request was successful
                    var response = xhr.responseText;
                    if (response === "success") {
                        alert("Sales price updated successfully.");
                        // Close the modal and refresh the table
                        closeModal();
                        window.location.reload();
                    } else {
                        alert("Error updating sales price.");
                    }
                } else {
                    alert("Error: " + xhr.statusText);
                }
            }
        };

        // Send the request with the product ID and new sales price as data
        xhr.send("customerId=" + customerId + "&receivedAmount=" + receivedAmount);
    }
// JavaScript to validate input (allow only numbers and one decimal point)
function validateInput(input) {
    input.value = input.value.replace(/[^0-9.]/g, ''); // Remove non-numeric and non-dot characters
    var decimalCount = (input.value.match(/\./g) || []).length;
    if (decimalCount > 1) {
        input.value = input.value.replace(/\.+$/, ''); // Remove extra dots
    }
}


// =========== Search in table=================

