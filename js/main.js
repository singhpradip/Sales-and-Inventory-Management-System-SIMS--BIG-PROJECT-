// Function to load content into the 'content' div
function loadContent(section) {
    const contentDiv = document.getElementById('content');
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'sections/' + section + '.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            contentDiv.innerHTML = xhr.responseText;

            // After loading content, run section-specific JavaScript if available
            const scriptFilePath = 'js/'+section+'.js';
            loadScript(scriptFilePath);

            // // section specific JavaScript load garda
            // if (section === 'sales') {
            //     loadSalesScript();
            // }
           
            // if kunai section ma js include garaun parda yeha batw function call garer garne and tala tyo function define garne
        }
    };
    xhr.send();
}

// Default page first ma matra load garaun ko lagi, Variable to track if the default page has been loaded
let defaultPageLoaded = false;

// Handle initial route
if (window.location.hash) {
    loadContent(window.location.hash.substring(1));
} else {
    // Load the default page only if it hasn't been loaded yet
    if (!defaultPageLoaded) {
        loadContent('dashboard'); // default section
        defaultPageLoaded = true; // Mark the default page as loaded
    }
}

// Hash change handle garx (navigation)
window.addEventListener('hashchange', function() {
    const section = window.location.hash.substring(1);
    loadContent(section);
});

// Yeha mathi specify gareko function haru define garne, Function to load section specific JavaScript
// function loadSalesScript() {
//     const script = document.createElement('script');
//     script.src = 'js/_table.js'; // Path
//     document.head.appendChild(script);
// }

// Function to load JavaScript dynamically
function loadScript(scriptFilePath) {
    const script = document.createElement('script');
    script.src = scriptFilePath;
    script.type = 'text/javascript';
    document.head.appendChild(script);
}



//============================== JavaScript for displaying current date and time ================================
            
     function updateCurrentDateTime() {
            const currentDate = new Date();

            // Define custom date and time
            const options = {
                weekday: 'long', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true, 
            };

            const formattedDate = currentDate.toLocaleDateString('en-US', options);
            document.getElementById('current-date-time').textContent = formattedDate;
            }

        // Update the date and time initially and then every second
        updateCurrentDateTime();
        setInterval(updateCurrentDateTime, 1000);






// =====================background style of active section==============================


        // Function to update the active section based on the hash
        function updateActiveSection() {
            // Get the current section from the URL hash
            const currentSection = window.location.hash;

            // Get all navigation links in the sidebar
            const navLinks = document.querySelectorAll("#left-nav a");

            // Loop through the navigation links
            navLinks.forEach((link) => {
                // Remove the "active" class from all links
                link.classList.remove("active");

                // Check if the link's href matches the current section
                if (link.getAttribute("href") === currentSection) {
                    // Add the "active" class to highlight the active link
                    link.classList.add("active");
                }
            });
        }

        // Call the function to update the active section initially
        updateActiveSection();

        // Listen for the hashchange event and update the active section
        window.addEventListener("hashchange", updateActiveSection);
