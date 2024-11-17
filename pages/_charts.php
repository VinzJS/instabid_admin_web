

        <!-- <?php
                // Initialize store owner count
                $storeOwnerCount = 0;
                foreach ($users['items'] as $data):
                    // Count how many users are store owners
                    if ($data['isStoreOwner']) {
                        $storeOwnerCount++;
                    }
                ?>

        <?php endforeach; ?>
        <div class="stats-wrapper">
            <br />
            <h1>Statistics</h1>
            <div class="user-wrapper">
                <canvas id="myChart"></canvas>
            </div>
        </div> -->

        
<!-- <script>
    // Prepare your data for Chart.js
    const totalItems = <?php echo json_encode($users['totalItems']); ?>; // Total users
    const storeOwnerCount = <?php echo json_encode($storeOwnerCount); ?>; // Count of store owners

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', // You can change to 'line', 'pie', etc.
        data: {
            labels: ['Total Users', 'Store Owners'], // Labels for your chart
            datasets: [{
                label: 'User Statistics',
                data: [totalItems, storeOwnerCount], // Data for the chart
                backgroundColor: [
                    'rgb(75, 192, 192)', // Color for total users
                    'rgb(153, 102, 255)' // Color for store owners
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)', // Border color for total users
                    'rgba(153, 102, 255, 1)' // Border color for store owners
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Start the y-axis at zero
                }
            }
        }
    });
</script> -->