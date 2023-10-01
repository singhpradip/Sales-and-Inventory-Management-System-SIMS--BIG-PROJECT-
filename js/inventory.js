function handleFormSubmit(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the form data
    const formData = new FormData(event.target);

    // Make an AJAX request to submit the form data
    fetch(event.target.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Handle the response from the server
        if (data.success) {
            alert('Success');
            // const successMessage = document.getElementById("successMessage");
            // successMessage.textContent = "Product added successfully!";
            reload();
            
        } else {
            const errorMessage = document.getElementById("errorMessage");
            errorMessage.textContent = data.message; // Assuming the server sends an error message
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}



function openPopupForm() {
    const popupForm = document.getElementById('popupForm');
    popupForm.style.display = 'block'; // Show the popup form
}

document.addEventListener("DOMContentLoaded", function () {
    // Function to handle form submission via AJAX
    function handleDelete(product_id) {
        if (confirm("Are you sure you want to delete this product?")) {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open("POST", "sections/_delete_product.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // Define the callback function when the request is complete
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) { // Request is complete
                    if (xhr.status === 200) { // Request was successful
                        var response = xhr.responseText;
                        if (response === "success") {
                            alert("Product deleted successfully.");
                            window.location.reload();
                        } else {
                            alert("Error deleting product.");
                        }
                    } else {
                        alert("Error: " + xhr.statusText);
                    }
                }
            };

            // Send the request with the product_id as data
            xhr.send("product_id=" + product_id);
        }
    }

    // Add event listener for the delete buttons
    var deleteButtons = document.querySelectorAll(".delete-button");
    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            var productId = this.getAttribute("data-product-id");
            handleDelete(productId);
        });
    });
});
