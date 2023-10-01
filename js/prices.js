// JavaScript to open the update price modal and pre-fill the fields
function openUpdateModal(productId, currentSalesPrice) {
    var modal = document.getElementById('updatePriceModal');
    modal.style.display = 'block';

    // Pre-fill the current sales price input (readonly) with the current sales price
    document.getElementById('currentSalesPrice').value = currentSalesPrice;

    // Store the product ID in a hidden input field for reference
    document.getElementById('updateProductId').value = productId;

    // Clear the new sales price input
    document.getElementById('newSalesPrice').value = '';
}

// JavaScript to close the update price modal
function closeUpdateModal() {
    var modal = document.getElementById('updatePriceModal');
    modal.style.display = 'none';
}


// JavaScript to validate input (allow only numbers and one decimal point)
function validateInput(input) {
    input.value = input.value.replace(/[^0-9.]/g, ''); // Remove non-numeric and non-dot characters
    var decimalCount = (input.value.match(/\./g) || []).length;
    if (decimalCount > 1) {
        input.value = input.value.replace(/\.+$/, ''); // Remove extra dots
    }
}


    // Function to update the sales rate
    function updateSalesPrice() {
        // Get the new sales price and product ID from the form
        var newSalesPrice = document.getElementById("newSalesPrice").value;
        var productId = document.getElementById("updateProductId").value;

        // Send an AJAX request to update the sales rate
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "sections/_price_update_sales_price.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        // Define the callback function when the request is complete
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) { // Request is complete
                if (xhr.status === 200) { // Request was successful
                    var response = xhr.responseText;
                    if (response === "success") {
                        alert("Sales price updated successfully.");
                        // Close the modal and refresh the table
                        closeUpdateModal();
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
        xhr.send("product_id=" + productId + "&new_sales_price=" + newSalesPrice);
    }

