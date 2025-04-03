var ctx = document.getElementById("lineChart").getContext("2d");

// Define months
var months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];

// Convert Laravel's PHP array to JavaScript array
var jobApplicationsData = Array(12).fill(0);
var hiredData = Array(12).fill(0);
var activeTrainingData = Array(12).fill(0);

// Fill in actual data from backend
for (var key in jobApplicationsByMonth) {
    jobApplicationsData[key - 1] = jobApplicationsByMonth[key];
}
for (var key in hiredByMonth) {
    hiredData[key - 1] = hiredByMonth[key];
}
for (var key in activeTrainingByMonth) {
    activeTrainingData[key - 1] = activeTrainingByMonth[key];
}

var lineChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: months,
        datasets: [
            {
                label: "Total Job Applications",
                data: jobApplicationsData,
                fill: false,
                borderColor: "#007bff",
                backgroundColor: "#007bff",
                borderWidth: 2,
            },
            {
                label: "Hired Candidates",
                data: hiredData,
                fill: false,
                borderColor: "#28a745",
                backgroundColor: "#28a745",
                borderWidth: 2,
            },
            {
                label: "Active Training Sessions",
                data: activeTrainingData,
                fill: false,
                borderColor: "#ffcc00",
                backgroundColor: "#ffcc00",
                borderWidth: 2,
            },
        ],
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: "top",
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: "Count",
                },
            },
            x: {
                title: {
                    display: true,
                    text: "Months",
                },
            },
        },
    },
});
