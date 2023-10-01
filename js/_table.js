document.addEventListener("DOMContentLoaded", function() {
    const search = document.querySelector('.input-group input');
    const tableRows = document.querySelectorAll('#salesDataTable tbody tr');
    const noDataMessage = document.getElementById('noDataMessage');

    // 1. Searching for specific data of HTML table
    search.addEventListener('input', searchTable);

    function searchTable() {
        let anyRowVisible = false; // Flag to check if any row is visible

        tableRows.forEach((row, i) => {
            let tableData = row.textContent.toLowerCase();
            let searchData = search.value.toLowerCase();

            row.classList.toggle('hide', tableData.indexOf(searchData) < 0);

            if (!row.classList.contains('hide')) {
                anyRowVisible = true; // Set the flag to true if any row is visible
            }

            row.style.setProperty('--delay', i / 25 + 's');
        });

        // Show or hide the "No such item" message based on visibility
        noDataMessage.style.display = anyRowVisible ? 'none' : 'table-row';

        // Iterate through all rows to fix odd/even background color
        tableRows.forEach((visibleRow, i) => {
            visibleRow.style.backgroundColor = (i % 2 == 0) ? 'transparent' : '#0000000b';
        });
    }
});
