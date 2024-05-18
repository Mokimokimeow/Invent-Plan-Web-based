let barChart;

function calculateTotal() {
    const rows = document.querySelectorAll('#weeklyEarningsTable tbody tr');
    const earnings = [];

    // Loop through each row
    rows.forEach((row, index) => {
        const totalCell = row.querySelector('.total-cell');
        if (totalCell) {
            const inputs = row.querySelectorAll('.editable-cell input');
            let total = 0;

            inputs.forEach(input => {
                const value = parseFloat(input.value) || 0; // Get value from input field
                total += value;
            });

            totalCell.textContent = total;
            earnings.push(total);
        } else {
            console.error('Total cell not found in row:', row);
        }
    });

    updateBarChart(earnings);
    updateTotalSales(earnings);
}

function updateBarChart(earnings) {
    if (barChart) {
        barChart.data.datasets[0].data = earnings;
        barChart.update();
    }
}

function updateTotalSales(earnings) {
    const totalSalesSum = earnings.reduce((total, earnings) => total + earnings, 0);
    document.getElementById("totalSales").textContent = totalSalesSum;
}

function saveDataToLocalStorage() {
    const rows = document.querySelectorAll('#weeklyEarningsTable tbody tr');
    const data = [];

    rows.forEach((row, rowIndex) => {
        const inputs = row.querySelectorAll('.editable-cell input');
        const rowData = [];

        inputs.forEach(input => {
            rowData.push(input.value);
        });

        data.push(rowData);
    });

    localStorage.setItem('earningsData', JSON.stringify(data));
}

function loadDataFromLocalStorage() {
    const data = JSON.parse(localStorage.getItem('earningsData'));

    if (data) {
        const rows = document.querySelectorAll('#weeklyEarningsTable tbody tr');

        rows.forEach((row, rowIndex) => {
            const inputs = row.querySelectorAll('.editable-cell input');
            const rowData = data[rowIndex];

            inputs.forEach((input, inputIndex) => {
                input.value = rowData[inputIndex];
            });
        });

        calculateTotal();
    }
}

document.getElementById("earningsForm").addEventListener("submit", function (event) {
    event.preventDefault();
    saveDataToLocalStorage();
    calculateTotal();
});

window.addEventListener('load', loadDataFromLocalStorage);

const canvas = document.getElementById("barChart");

if (canvas) {
    const ctx = canvas.getContext("2d");

    const earningsData = {
        labels: [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ],
        datasets: [
            {
                label: "Earnings",
                backgroundColor: "rgba(54, 162, 235, 0.5)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1,
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            },
        ],
    };

    barChart = new Chart(ctx, {
        type: "bar",
        data: earningsData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5000,
                },
            },
        },
    });
}
