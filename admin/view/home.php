<?php
function getTotalOrders($conn) {
    $sql = "SELECT COUNT(*) AS total_orders FROM tbl_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_orders'] ?? 0;
}

function getCompletedOrders($conn) {
    $sql = "SELECT COUNT(*) AS completed_orders FROM tbl_order WHERE trangthai = '3'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['completed_orders'] ?? 0;
}

function getUncompletedOrders($conn) {
    $sql = "SELECT COUNT(*) AS uncompleted_orders FROM tbl_order WHERE trangthai != '3'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['uncompleted_orders'] ?? 0;
}

function getTotalRevenue($conn) {
    $sql = "SELECT SUM(tongdonhang) AS total_revenue FROM tbl_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_revenue'] ?? 0;
}

// Kết nối đến cơ sở dữ liệu
$conn = connectdb();

$totalOrders = getTotalOrders($conn);
$completedOrders = getCompletedOrders($conn);
$uncompletedOrders = getUncompletedOrders($conn);
$totalRevenue = getTotalRevenue($conn);

// Đóng kết nối
$conn = null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bốn Cột</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            padding: 20px;
        }
        .col-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            height: 152px;
            width: 280px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="col-box" style="background: #A9A9A9;">
                <h3 class="text-center">Tổng Đơn Hàng</h3>
                <p class="text-center"><?php echo $totalOrders; ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <a href="index.php?act=donhang&key=dahoanthanh" style="text-decoration: none; color: black;">
                <div class="col-box" style="background: #808080;">
                    <h3 class="text-center">Đơn Hàng Đã Hoàn Thành</h3>
                    <p class="text-center"><?php echo $completedOrders; ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="index.php?act=donhang&key=chuahoanthanh" style="text-decoration: none; color: black;">
                <div class="col-box" style="background: #778899;">
                    <h3 class="text-center">Đơn Hàng Chưa Hoàn Thành</h3>
                    <p class="text-center"><?php echo $uncompletedOrders; ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <div class="col-box" style="background:#708090;">
                <h3 class="text-center">Tổng Doanh Thu</h3>
                <p class="text-center"><?php echo number_format($totalRevenue, 0, ',', '.') . " VNĐ"; ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Biểu Đồ Đơn Hàng</h1>
            <div id="chartContainer">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Lấy dữ liệu từ API
    fetch('../view/bieudo.php')
        .then(response => response.json())
        .then(data => {
            // Xử lý dữ liệu
            const ordersData = processData(data);
            // Vẽ biểu đồ
            drawChart(ordersData);
        })
        .catch(error => {
            console.error('Lỗi:', error);
        });

    // Hàm xử lý dữ liệu
    function processData(orders) {
        const ordersByDate = {};
        orders.forEach(order => {
            const date = order.ngaydat;
            const totalRevenue = order.totalRevenue;
            if (!ordersByDate[date]) {
                ordersByDate[date] = { ordersCount: order.ordersCount, totalRevenue };
            } else {
                ordersByDate[date].ordersCount += order.ordersCount;
                ordersByDate[date].totalRevenue += totalRevenue;
            }
        });
        return ordersByDate;
    }

    // Hàm vẽ biểu đồ
    function drawChart(data) {
        const ctx = document.getElementById('ordersChart').getContext('2d');
        const dates = Object.keys(data);
        const orderCounts = dates.map(date => data[date].ordersCount);
        const totalRevenues = dates.map(date => data[date].totalRevenue);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Tổng Đơn Hàng',
                    data: orderCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Tổng Doanh Thu',
                    data: totalRevenues,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
</script>
</body>
</html>
