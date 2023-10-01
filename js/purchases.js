
var rowCounter = 0; // Global variable to keep track of the row count

//fetch info to first row only
function fetchProduct() {
    var productId = document.getElementById('show_product_id').value;

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Prepare the request
    xhr.open('GET', 'sections/_purchases_fetch_product.php?product_id=' + productId, true);

    // Set the callback function when the request is completed
    xhr.onload = function() {
        if (xhr.status === 200) {
            var productDetails = xhr.responseText;
            if (productDetails !== '') {
                var details = productDetails.split(',');
                document.getElementById('show_product_name').value = details[0];
                document.getElementById('show_product_category').value = details[1];
                document.getElementById('show_sales_rate').value = details[2];
            } else {
                // Handle case when product ID is not found
                document.getElementById('show_product_name').value = '';
                document.getElementById('show_product_category').value = '';
                document.getElementById('show_sales_rate').value = '';
            }
        }
    };

    // Send the request
    xhr.send();
}

//adds new row
function addTableRow() {
    var purchasesTable = document.getElementById("purchasesTable");
    var newRow = purchasesTable.insertRow(purchasesTable.rows.length - 1);
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    var cell6 = newRow.insertCell(5);
    var cell7 = newRow.insertCell(6);

    cell1.innerHTML = '<input type="text" id="show_product_id' + rowCounter + '" onblur="fetchProductDetails(' + rowCounter + ')">';
    cell2.innerHTML = '<input type="text" id="show_product_name' + rowCounter + '">';
    cell3.innerHTML = '<input type="text" id="show_product_category' + rowCounter + '">';
    cell4.innerHTML = '<input type="text" id="show_sales_rate' + rowCounter + '">';
    cell5.innerHTML = '<input type="text" id="show_quantity' + rowCounter + '">';
    cell6.innerHTML = '<input type="text" id="show_purchases_rate' + rowCounter + '">';
    cell7.innerHTML = '<input type="text" id="show_total' + rowCounter + '" onfocus="calculatetotal(' + rowCounter + ')">';

    rowCounter++;

    // Move the newly added row to the correct position
    var rows = purchasesTable.rows;
    for (var i = 1; i < rows.length - 1; i++) {
        rows[i].parentNode.insertBefore(rows[i], rows[i + 1]);
    }
}

//fetch info to added rows
function fetchProductDetails(row) {
    var productId = document.getElementById('show_product_id' + row).value;

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Prepare the request
    xhr.open('GET', 'sections/_purchases_fetch_product.php?product_id=' + productId, true);

    // Set the callback function when the request is completed
    xhr.onload = function() {
      if (xhr.status === 200) {
        var productDetails = xhr.responseText;
        if (productDetails !== '') {
            var details = productDetails.split(',');
            document.getElementById('show_product_name' + row).value = details[0];
            document.getElementById('show_product_category' + row).value = details[1];
            document.getElementById('show_sales_rate' + row).value = details[2];
        } else {
            // Handle case when product ID is not found
            document.getElementById('show_product_name' + row).value = '';
            document.getElementById('show_product_category' + row).value = '';
            document.getElementById('show_sales_rate' + row).value = '';
        }
      }
    };

    // Send the request
    xhr.send();
}
//calculate total for first row
function calculate() {
    var cp = document.getElementById('show_purchases_rate').value;
    var quant = document.getElementById('show_quantity').value;
    var total = (cp * quant);
    document.getElementById('show_total').value = total;
}
//calculate total for added rows
function calculatetotal(row) {
    var cp = document.getElementById('show_purchases_rate' + row).value;
    var quant = document.getElementById('show_quantity' + row).value;
    var total = (cp * quant);
    document.getElementById('show_total' + row).value = total;
}
//calculate Aggregate Total
function totalpayment(){
    var p1 = parseInt(document.getElementById('show_total').value);
    var rowCount = rowCounter;
    
    for (var j = 0; j < rowCount; j++) {
        p1 += parseInt(document.getElementById('show_total' + j).value);
    }
    document.getElementById('show_grand_total').value= p1;
    document.getElementById('bill_amount').value= p1;
}

//============ fetch supplier's info ==============
function fetch_supplier(){
    // Get the supplier ID from the input field
    var supplierId = document.getElementById('supplier_id').value;

    // Check if the supplier ID is not empty
    if (supplierId.trim() !== '') {
        // Send an AJAX POST request to fetch supplier information
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'sections/_purchases_supplier_fetch.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                // Update the supplier name and address fields with the fetched data
                if (response.exists) {
                    document.getElementById('supplier_name').value = response.supplierName;
                    document.getElementById('supplier_address').value = response.supplierAddress;
                } else {
                    // Clear the fields if supplier ID doesn't exist
                    document.getElementById('supplier_name').value = '';
                    document.getElementById('supplier_address').value = '';
                }
            } else {
                console.error('Error:', xhr.status);
            }
        };

        // Send the supplier ID as data in the request
        var data = 'supplier_id=' + supplierId;
        xhr.send(data);
    }
}
// ========== check, create and update Supplier info ===
function supplier_info() {
    var supplierId = parseFloat(document.getElementById('supplier_id').value);
    var supplierName = document.getElementById('supplier_name').value;
    var supplierAddress = document.getElementById('supplier_address').value;
    var payable = parseFloat(document.getElementById('payable').value);
    // Check if payable is not null and not equal to 0
    if (!isNaN(payable) && payable !== 0) {

        if (supplierId && supplierName && supplierAddress) {
            // Create a JavaScript object with user data
            var userData = {
                supplierId: supplierId,
                supplierName: supplierName,
                supplierAddress: supplierAddress,
                payable: payable
            };

            // Convert the JavaScript object to a JSON string
            var jsonData = JSON.stringify(userData);

            // Send an AJAX POST request to check if the supplier_id exists
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'sections/_purchases_supplier_check.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.exists) {
                        // If supplier_id exists, send an update request
                        var xhrUpdate = new XMLHttpRequest();
                        xhrUpdate.open('POST', 'sections/_purchases_supplier_update.php', true);
                        xhrUpdate.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

                        xhrUpdate.onload = function () {
                            if (xhrUpdate.status === 200) {
                                // alert('Supplier information updated successfully!');
                                console.log('Supplier information updated successfully!');
                            } else {
                                // alert('Error updating supplier information.');
                                console.log('Error updating supplier information.');
                            }
                        };

                        xhrUpdate.send(jsonData);
                    } else {
                        // If supplier_id does not exist, send an insert request
                        var xhrInsert = new XMLHttpRequest();
                        xhrInsert.open('POST', 'sections/_purchases_supplier_insert.php', true);
                        xhrInsert.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

                        xhrInsert.onload = function () {
                            if (xhrInsert.status === 200) {
                                // alert('New supplier added successfully!');
                                console.log('New supplier added successfully!');
                            } else {
                                // alert('Error adding new supplier.');
                                console.log('Error adding new supplier.');
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
            alert('Supplier information is required when there is a due amount.');
            console.log('Supplier information is required when there is a due amount.');
        }

        xhr.send(jsonData);
    } else {
        // alert('Payable amount is not valid.');
        console.log('Payable amount is not valid.');
    }
}

function sendDataToServer(){
    // Gather data for sales header and customers information
    var totalAmount = parseFloat(document.getElementById('show_grand_total').value);
    var paid = parseFloat(document.getElementById('paid').value);
    
    // Check if totalAmount is not null and not zero
    if (!isNaN(totalAmount) && totalAmount !== 0 && !isNaN(paid) && paid !== 0) {
        var expenses = parseFloat(document.getElementById('expenses').value);
        var payable = parseFloat(document.getElementById('payable').value);
        var supplier_id = parseFloat(document.getElementById('supplier_id').value);


        // console.log('totalAmount: ' + totalAmount);
        // console.log('expenses: ' + expenses);
        // console.log('paid: ' + paid);
        // console.log('payable: ' + payable);
        // console.log('supplier_id: ' + supplier_id);



        // Create an array to store data for sales items
        var purchasesItemsArray = [];

        // Gather data for the initial sales item
        var show_product_id = parseFloat(document.getElementById('show_product_id').value);
        var show_product_name = document.getElementById('show_product_name').value;
        var show_product_category = document.getElementById('show_product_category').value;
        var show_sales_rate = parseFloat(document.getElementById('show_sales_rate').value);
        var show_quantity = parseFloat(document.getElementById('show_quantity').value);
        var show_purchases_rate = parseFloat(document.getElementById('show_purchases_rate').value);
        var show_total = parseFloat(document.getElementById('show_total').value);
        
                // Log the gathered data (for debugging)
                // console.log('Product ID: ' + show_product_id);
                // console.log('Product Name: ' + show_product_name);
                // console.log('show_product_category: ' + show_product_category);
                // console.log('show_sales_rate: ' + show_sales_rate);
                // console.log('show_quantity: ' + show_quantity);
                // console.log('show_purchases_rate: ' + show_purchases_rate);
                // console.log('show_total: ' + show_total);

        // Add the data for the initial sales item to the array
        purchasesItemsArray.push({
            show_product_id: show_product_id,
            show_product_name: show_product_name,
            show_product_category: show_product_category,
            show_sales_rate: show_sales_rate,
            show_quantity: show_quantity,
            show_purchases_rate: show_purchases_rate,
            show_total: show_total
        });

        // Assuming you have defined rowCounter for the number of rows
        for (var i = 0; i < rowCounter; i++) {
            // Gather data for each additional sales item
            show_product_id = parseFloat(document.getElementById('show_product_id' +i).value);
            show_product_name = document.getElementById('show_product_name' +i).value;
            show_product_category = document.getElementById('show_product_category' +i).value;
            show_sales_rate = parseFloat(document.getElementById('show_sales_rate' +i).value);
            show_quantity = parseFloat(document.getElementById('show_quantity' +i).value);
            show_purchases_rate = parseFloat(document.getElementById('show_purchases_rate' +i).value);
            show_total = parseFloat(document.getElementById('show_total' +i).value);
            

                // Log the gathered data (for debugging)
                // console.log('Product ID: ' + show_product_id);
                // console.log('Product Name: ' + show_product_name);
                // console.log('show_product_category: ' + show_product_category);
                // console.log('show_sales_rate: ' + show_sales_rate);
                // console.log('show_quantity: ' + show_quantity);
                // console.log('show_purchases_rate: ' + show_purchases_rate);
                // console.log('show_total: ' + show_total);


            // Add the data to the array
            purchasesItemsArray.push({
                show_product_id: show_product_id,
                show_product_name: show_product_name,
                show_product_category: show_product_category,
                show_sales_rate: show_sales_rate,
                show_quantity: show_quantity,
                show_purchases_rate: show_purchases_rate,
                show_total: show_total
            });
        }

        // Create an object to hold all the data to be sent to the server
        var requestData = {
                totalAmount: totalAmount,
                expenses: expenses,
                paid: paid,
                payable: payable,
                supplier_id: supplier_id,
                purchasesItemsArray: purchasesItemsArray
        };

        // console.log(JSON.stringify(requestData));

        // Send the data to the server
        sendSalesDataToServer(requestData);
    } else {
        alert('Please enter Purchases record before submitting !');
    }
}
    function sendSalesDataToServer(requestData) {
        // Create an XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Prepare the request
        xhr.open('POST', 'sections/_purchases_insert.php', true);

        // Set the content type header for form data (optional)
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


    // Calculate deu
function  calculateDeu(){
     var billAmount = parseFloat(document.getElementById('bill_amount').value) || 0;
     var paid = parseFloat(document.getElementById('paid').value) || 0;
     var payable = billAmount - paid;
     document.getElementById('payable').value = payable;
 }
//  document.getElementById('bill_amount').onfocus = calculateBillAmount;
 document.getElementById('payable').onfocus = calculateDeu;
 



