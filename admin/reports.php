<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 0) {
    header("Location: " . url('masuk'));
    exit;
}

require_once "../core/koneksi.php";
include_once '../core/page.php';
$PAGE_TITLE = "Admin Reports";

// --- FETCH AVAILABLE YEARS ---
$years = [];
$yearSql = "SELECT DISTINCT YEAR(appointment_date) as y FROM appointments ORDER BY y DESC";
$yearRes = $koneksi->query($yearSql);
while ($row = $yearRes->fetch_assoc()) {
    $years[] = $row['y'];
}
// Default to current year if available, otherwise latest year, or just current year
$currentYear = date('Y');
if (empty($years)) {
    $years[] = $currentYear;
}
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : $currentYear;
if (!in_array($selectedYear, $years)) {
    $selectedYear = $years[0];
}

// --- FETCH DATA FOR CHART ---
// Counts bookings per month for the selected year
$sql = "
    SELECT MONTH(appointment_date) as month, COUNT(*) as count 
    FROM appointments 
    WHERE YEAR(appointment_date) = ? 
    GROUP BY MONTH(appointment_date) 
    ORDER BY month
";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

$monthlyData = array_fill(1, 12, 0); // Initialize 1-12 with 0
while ($row = $result->fetch_assoc()) {
    $monthlyData[$row['month']] = $row['count'];
}
$stmt->close();
$chartData = json_encode(array_values($monthlyData));

// --- FETCH SERVICE STATS ---
$stats = [
    'Boarding' => 0,
    'Daycare' => 0,
    'Grooming' => 0,
    'Total' => 0
];
$serviceSql = "SELECT service, COUNT(*) as count FROM appointments WHERE YEAR(appointment_date) = ? GROUP BY service";
$stmtService = $koneksi->prepare($serviceSql);
$stmtService->bind_param("i", $selectedYear);
$stmtService->execute();
$serviceRes = $stmtService->get_result();

while ($row = $serviceRes->fetch_assoc()) {
    $serviceName = $row['service'] ?: 'Daycare'; // Default to Daycare if null
    $stats[$serviceName] = $row['count'];
    $stats['Total'] += $row['count'];
}
$stmtService->close();
$serviceChartData = json_encode([$stats['Boarding'], $stats['Daycare'], $stats['Grooming']]);

page_start($PAGE_TITLE);
include_once "../pawhub/navbar.php";
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    @media print {
        .card {
            border: none !important;
            box-shadow: none !important;
        }

        /* Ensure charts take full width */
        .col-md-3,
        .col-lg-8,
        .col-lg-4 {
            width: 100% !important;
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Hide specific elements if d-print-none isn't enough */
    }
</style>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('admin') ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                </ol>
            </nav>
            <h2 class="fw-bold fs-3">Analitik Booking</h2>
        </div>
        <div>
            <form method="GET" class="d-flex align-items-center bg-white p-2 rounded shadow-sm border">
                <label for="year" class="me-2 fw-bold small text-muted">Tahun:</label>
                <select name="year" id="year" class="form-select form-select-sm border-0 bg-light fw-bold"
                    onchange="this.form.submit()" style="width: auto;">
                    <?php foreach ($years as $y): ?>
                        <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="p-3 bg-white shadow-sm rounded-4 border-start border-4 border-primary">
                <div class="text-muted small fw-bold text-uppercase">Total Bookings</div>
                <h2 class="fw-bold mb-0 text-primary"><?= $stats['Total'] ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-white shadow-sm rounded-4 border-start border-4 border-info">
                <div class="text-muted small fw-bold text-uppercase">Boarding</div>
                <h2 class="fw-bold mb-0 text-info"><?= $stats['Boarding'] ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-white shadow-sm rounded-4 border-start border-4 border-success">
                <div class="text-muted small fw-bold text-uppercase">Daycare</div>
                <h2 class="fw-bold mb-0 text-success"><?= $stats['Daycare'] ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-white shadow-sm rounded-4 border-start border-4 border-warning">
                <div class="text-muted small fw-bold text-uppercase">Grooming</div>
                <h2 class="fw-bold mb-0 text-warning"><?= $stats['Grooming'] ?></h2>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- LINE CHART -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Tren Bulanan (<?= $selectedYear ?>)</h5>
                </div>
                <div class="card-body">
                    <canvas id="bookingChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- PIE CHART -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Distribusi Layanan</h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- EXPORT BUTTONS -->
    <div class="text-end mb-5 d-print-none">
        <button class="btn btn-danger me-2 shadow-sm" onclick="exportPDF()">
            <i class="bi bi-file-pdf me-2"></i>Export PDF
        </button>
        <button class="btn btn-success shadow-sm" onclick="exportCSV()">
            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
        </button>
    </div>

    <script>
        // 1. RENDER LINE CHART
        const ctx = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Appointments',
                    data: <?= $chartData ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // 2. RENDER PIE CHART
        const ctxService = document.getElementById('serviceChart').getContext('2d');
        new Chart(ctxService, {
            type: 'doughnut',
            data: {
                labels: ['Boarding', 'Daycare', 'Grooming'],
                datasets: [{
                    data: <?= $serviceChartData ?>,
                    backgroundColor: ['#0dcaf0', '#198754', '#ffc107'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // 2. EXPORT CSV
        function exportCSV() {
            // Logic: Create a simpler manual CSV download for the visible data
            // For a full report, normally we'd hit a PHP endpoint. Here we export the chart data.
            let csvContent = "data:text/csv;charset=utf-8,Month,Appointments\n";
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const data = <?= $chartData ?>;

            months.forEach((month, index) => {
                csvContent += `${month},${data[index]}\n`;
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "appointment_report_<?= $selectedYear ?>.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // 3. EXPORT PDF (NATIVE PRINT)
        function exportPDF() {
            window.print();
        }
    </script>

    <?php
    page_end();
    ?>