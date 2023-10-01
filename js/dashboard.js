
$(document).ready(function () {
  $.ajax({
      type: "GET",
      url: "sections/_dashboard_pieChart.php",
      success: function (data) {
          // Parse the JSON data received from the server
          var chartData = JSON.parse(data);

          // Extract labels categories and data counts
          var labels = chartData.map(function (item) {
              return item.category;
          });

          var counts = chartData.map(function (item) {
              return item.count;
          });

          // Create the pie chart
          const ctx1 = document.getElementById("chart-1").getContext("2d");
          const myChart = new Chart(ctx1, {
              type: "pie",
              data: {
                  labels: labels,
                  datasets: [
                      {
                          data: counts,
                          backgroundColor: [
                              "rgba(54, 162, 235, 1)",
                              "rgba(255, 99, 132, 1)",
                              "rgba(255, 206, 86, 1)",
                              "rgba(75, 192, 192, 1)",
                              "rgba(153, 102, 255, 1)",
                              "rgba(255, 159, 64, 1)",
                          ],
                      },
                  ],
              },
              options: {
                  responsive: true,
              },
          });
      },
      error: function (xhr, status, error) {
          console.error("Error: " + xhr.responseText);
      },
  });
});

$(document).ready(function () {
  $.ajax({
      type: "GET",
      url: "sections/_dashboard_barDiagram.php",
      success: function (data) {
          // Parse the JSON data received from the server
          var chartData = JSON.parse(data);

          // Extract labels and data for the chart
          var labels = chartData.map(function (item) {
              return item.date;
          });

          var billAmounts = chartData.map(function (item) {
              return item.total;
          });

          // Create the bar chart
          const ctx2 = document.getElementById("chart-2").getContext("2d");
          const myChart2 = new Chart(ctx2, {
              type: "bar",
              data: {
                  labels: labels,
                  datasets: [
                      {
                          label: "Monthly Revenue",
                          data: billAmounts,
                          backgroundColor: [
                            "rgba(54, 162, 235, 1)",
                            "rgba(255, 99, 132, 1)",
                            "rgba(255, 206, 86, 1)",
                            "rgba(75, 192, 192, 1)",
                            "rgba(153, 102, 255, 1)",
                            "rgba(255, 159, 64, 1)",
                            "rgba(0, 128, 0, 1)"
                          ],
                      },
                  ],
              },
              options: {
                  responsive: true,
              },
          });
      },
      error: function (xhr, status, error) {
          console.error("Error: " + xhr.responseText);
      },
  });
});