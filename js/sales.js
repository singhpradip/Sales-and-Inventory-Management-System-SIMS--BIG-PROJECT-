var rowCounter = 0; // Global variable to keep track of the row count
function fetchProduct() {
    var productId = document.getElementById('show_product_id').value;

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Prepare the request
    xhr.open('GET', 'sections/sales_fetch_product.php?product_id=' + productId, true);

    // Set the callback function when the request is completed
    xhr.onload = function() {
      if (xhr.status === 200) {
        var productDetails = xhr.responseText;
        if (productDetails !== '') {
            var details = productDetails.split(',');
            document.getElementById('show_product_name' ).value = details[0];
            document.getElementById('show_product_sp').value = details[1];
        } else {
          // Handle case when product ID is not found
          document.getElementById('show_product_name' + row).value = '';
          document.getElementById('show_product_sp' + row).value = '';
        }
      }
    };
        // Send the request
        xhr.send();
}

function calculate() {
    var sp = document.getElementById('show_product_sp').value;
    var quant = document.getElementById('show_quantity').value;
    var total = (sp * quant);
    document.getElementById('show_total').value = total;
}

function addTableRow() {
    var salesTable = document.getElementById("salesTable");
    var newRow = salesTable.insertRow(salesTable.rows.length - 1);
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    
    cell1.innerHTML = '<input type="text" id="show_product_id' + rowCounter + '" onblur="fetchProductDetails(' + rowCounter + ')">';
    cell2.innerHTML = '<input type="text" id="show_product_name' + rowCounter + '">';
    cell3.innerHTML = '<input type="text" id="show_product_sp' + rowCounter + '">';
    cell4.innerHTML = '<input type="text" id="show_quantity' + rowCounter + '">';
    cell5.innerHTML = '<input type="text" id="show_total' + rowCounter + '" class="product_cost" onfocus="calculatetotal(' + rowCounter + ')">';

    rowCounter++;
    var rows = salesTable.rows;
    for (var i = 1; i < rows.length - 1; i++) {
        rows[i].parentNode.insertBefore(rows[i], rows[i + 1]);
    }
}

function fetchProductDetails(row) {
    var productId = document.getElementById('show_product_id' + row).value;

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Prepare the request
    xhr.open('GET', 'sections/_sales_fetch_product.php?product_id=' + productId, true);

    // Set the callback function when the request is completed
    xhr.onload = function() {
      if (xhr.status === 200) {
        var productDetails = xhr.responseText;
        if (productDetails !== '') {
            var details = productDetails.split(',');
            document.getElementById('show_product_name' + row).value = details[0];
            document.getElementById('show_product_sp' + row).value = details[1];
        } else {
            // Handle case when product ID is not found
            document.getElementById('show_product_name' + row).value = '';
            document.getElementById('show_product_sp' + row).value = '';
        }
      }
    };

    // Send the request
    xhr.send();
}

function calculatetotal(row) {
var sp = document.getElementById('show_product_sp' + row).value;
var quant = document.getElementById('show_quantity' + row).value;
var total = (sp * quant);
document.getElementById('show_total' + row).value = total;
}


//calculate Aggregate Total =====================================

function totalpayment(){
var p1 = parseInt(document.getElementById('show_total').value);
var rowCount = rowCounter;

for (var j = 0; j < rowCount; j++) {
    p1 += parseInt(document.getElementById('show_total' + j).value);
}
document.getElementById('show_grand_total').value= p1;
}



//============ fetch customer's info ==============
function fetch_customer(){
    // Get the customer ID from the input field
    var customerId = document.getElementById('customer_id').value;

    // Check if the customer ID is not empty
    if (customerId.trim() !== '') {
        // Send an AJAX POST request to fetch customer information
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'sections/_sales_customer_fetch.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                // Update the customer name and address fields with the fetched data
                if (response.exists) {
                    document.getElementById('customer_name').value = response.customerName;
                    document.getElementById('customer_address').value = response.customerAddress;
                } else {
                    // Clear the fields if customer ID doesn't exist
                    document.getElementById('customer_name').value = '';
                    document.getElementById('customer_address').value = '';
                }
            } else {
                console.error('Error:', xhr.status);
            }
        };

        // Send the customer ID as data in the request
        var data = 'customer_id=' + customerId;
        xhr.send(data);
    }
}




// ========== check, create and update user info ==========
function customer_info() {
    var customerId = parseFloat(document.getElementById('customer_id').value);
    var customerName = document.getElementById('customer_name').value;
    var customerAddress = document.getElementById('customer_address').value;    
    var receivable = parseFloat(document.getElementById('receivable').value);
    
  if (receivable !== 0) {
    if (customerId && customerName && customerAddress) {

        // Create a JavaScript object with user data
        var userData = {
            customerId: customerId,
            customerName: customerName,
            customerAddress: customerAddress,
            receivable: receivable
        };

        // Convert the JavaScript object to a JSON string
        var jsonData = JSON.stringify(userData);

        // Send an AJAX POST request to check if the customer_id exists
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'sections/_sales_customer_check.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    
                    if (response.exists) {
                        // If customer_id exists, send an update request
                        var xhrUpdate = new XMLHttpRequest();
                        xhrUpdate.open('POST', 'sections/_sales_customer_update.php', true);
                        xhrUpdate.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                        
                        xhrUpdate.onload = function() {
                            if (xhrUpdate.status === 200) {
                                // alert('Customer information updated successfully!');
                                console.log('Customer information updated successfully!');

                            } else {
                                // alert('Error updating customer information.');
                                console.log('Error updating customer information.');
                            }
                        };
                        
                        xhrUpdate.send(jsonData);
                    } else {
                        // If customer_id does not exist, send an insert request
                        var xhrInsert = new XMLHttpRequest();
                        xhrInsert.open('POST', 'sections/_sales_customer_insert.php', true);
                        xhrInsert.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                        
                        xhrInsert.onload = function() {
                            if (xhrInsert.status === 200) {
                                // alert('New customer added successfully!');
                                console.log('New customer added successfully!');

                            } else {
                                // alert('Error adding new customer.');
                                console.log('Error adding new customer.');

                            }
                        };
                        
                        xhrInsert.send(jsonData);
                    }
                } else {
                    console.error('Error:', xhr.status);
                }
            };
    } else {
        // Show an alert message if customer information is empty
        alert('Customer information is required when there is a receivable amount.');
        console.log('Customer information is required when there is a receivable amount.');
    }
    xhr.send(jsonData);
    } else {
        // alert('Receivable amount is not valid.');
        console.log('Receivable amount is not valid.');
    }
}

// --------------------------------------------------





//======================== SALES UPDATE ================================================================



function sendDataToServer() {
    // Gather data for sales header and customers information
    var totalAmount = parseFloat(document.getElementById('show_grand_total').value);
    var received = parseFloat(document.getElementById('received').value);
    
    // Check if totalAmount is not null and not zero
    if (!isNaN(totalAmount) && totalAmount !== 0 && received!==0 && !isNaN(received)) {
        var discount = parseFloat(document.getElementById('discount').value);
        var billAmount = parseFloat(document.getElementById('bill_amount').value);
        var receivable = parseFloat(document.getElementById('receivable').value);


        // console.log('totalAmount: ' + totalAmount);
        // console.log('discount: ' + discount);
        // console.log('billAmount: ' + billAmount);
        // console.log('received: ' + received);
        // console.log('receivable: ' + receivable);



        var customerId = parseFloat(document.getElementById('customer_id').value);

            // console.log('customerId: ' + customerId);
            // console.log('customerName: ' + customerName);
            // console.log('customerAddress: ' + customerAddress);



        // Create an array to store data for sales items
        var salesItemsArray = [];

        // Gather data for the initial sales item
        var product_id = parseFloat(document.getElementById('show_product_id').value);
        var product_name = document.getElementById('show_product_name').value;
        var sales_rate = parseFloat(document.getElementById('show_product_sp').value);
        var quantity = parseFloat(document.getElementById('show_quantity').value);
        var item_total = parseFloat(document.getElementById('show_total').value);
        
                // // Log the gathered data (for debugging)
                // console.log('Product ID: ' + product_id);
                // console.log('Product Name: ' + product_name);
                // console.log('Sales Rate: ' + sales_rate);
                // console.log('Quantity: ' + quantity);
                // console.log('Item Total: ' + item_total);

        // Add the data for the initial sales item to the array
        salesItemsArray.push({
            product_id: product_id,
            product_name: product_name,
            sales_rate: sales_rate,
            quantity: quantity,
            item_total: item_total
        });

        // Assuming you have defined rowCounter for the number of rows
        for (var i = 0; i < rowCounter; i++) {
            // Gather data for each additional sales item
            product_id = parseFloat(document.getElementById('show_product_id' + i).value);
            product_name = document.getElementById('show_product_name' + i).value;
            sales_rate = parseFloat(document.getElementById('show_product_sp' + i).value);
            quantity = parseFloat(document.getElementById('show_quantity' + i).value);
            item_total = parseFloat(document.getElementById('show_total' + i).value);

                // // Log the gathered data (for debugging)
                // console.log('Product ID: ' + product_id);
                // console.log('Product Name: ' + product_name);
                // console.log('Sales Rate: ' + sales_rate);
                // console.log('Quantity: ' + quantity);
                // console.log('Item Total: ' + item_total);

            // Add the data to the array
            salesItemsArray.push({
                product_id: product_id,
                product_name: product_name,
                sales_rate: sales_rate,
                quantity: quantity,
                item_total: item_total
            });
        }

        // Create an object to hold all the data to be sent to the server
        var requestData = {
                totalAmount: totalAmount,
                discount: discount,
                billAmount: billAmount,
                received: received,
                receivable: receivable,
                customerId: customerId,
            salesItems: salesItemsArray
        };

        // console.log(JSON.stringify(requestData));

        // Send the data to the server
        sendSalesDataToServer(requestData);
    } else {
        alert('Please enter sales record before submitting !');
    }
}
    function sendSalesDataToServer(requestData) {
        // Create an XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        xhr.open('POST', 'sections/_sales_insert.php', true);

        xhr.setRequestHeader('Content-type', 'application/json');

        // Set the callback function when the request is completed
        xhr.onload = function () {
            if (xhr.status === 200) {
                // console.log('Data sent successfully');
                alert('Successfully recorded !');
                location.reload();
            } else {
                alert('Error sending data !');
                // console.error('Error sending data');
            }
        };

        // Convert the requestData object to JSON
        var jsonData = JSON.stringify(requestData);

        // Send the request with the JSON data
        xhr.send(jsonData);
    }



//=======================scroll================================================

document.getElementById('scrollButton').addEventListener('click', function () {
    var tableRecElement = document.querySelector('main.tableRec');

    if (tableRecElement) {
        tableRecElement.scrollIntoView({ behavior: 'smooth' });
    }
});


//Calculate billAmount and Receivable amount =============================================

function calculateBillAmount() {
    // Calculate billAmount
    var grandTotal = parseFloat(document.getElementById('show_grand_total').value) || 0;
    var discount = parseFloat(document.getElementById('discount').value) || 0;
    var billAmount = grandTotal - discount;
    document.getElementById('bill_amount').value = billAmount;
}

function calculateReceivable() {
    // Calculate receivable
    var billAmount = parseFloat(document.getElementById('bill_amount').value) || 0;
    var received = parseFloat(document.getElementById('received').value) || 0;
    var receivable = billAmount - received;
    document.getElementById('receivable').value = receivable;
}
// Attach onfocus event handlers
document.getElementById('bill_amount').onfocus = calculateBillAmount;
document.getElementById('receivable').onfocus = calculateReceivable;



// ======================= Input validation ======================
function validateInput(input) {
    // Remove any non-numeric or non-float characters using a regular expression
    input.value = input.value.replace(/[^0-9.]/g, '');

    // Ensure there is only one decimal point
    var decimalCount = (input.value.match(/\./g) || []).length;
    if (decimalCount > 1) {
        input.value = input.value.replace(/\.+$/, '');
    }
}


function validateNeg(inputElement) {
    var inputValue = inputElement.value;
    if (parseFloat(inputValue) < 0) {
        alert("Negative values are not allowed.");
        inputElement.value = ''; // Clear the input field
    }
}