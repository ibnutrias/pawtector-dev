<?php
session_start();

// 1. Authorization Check (Role 0 = Admin)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 0) {
    header("Location: " . url('masuk'));
    exit;
}

require_once "../core/koneksi.php";
include_once '../core/page.php';
$PAGE_TITLE = "Admin Dashboard";

// --- FETCH STATS ---
// 1. Total Users
$userStmt = $koneksi->query("SELECT COUNT(*) as count FROM users WHERE role != 0");
$userCount = $userStmt->fetch_assoc()['count'];

// 2. Pending Bookings
$pendingStmt = $koneksi->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'");
$pendingCount = $pendingStmt->fetch_assoc()['count'];

// 3. Active Bookings
$activeStmt = $koneksi->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'active'");
$activeCount = $activeStmt->fetch_assoc()['count'];

page_start($PAGE_TITLE);
include_once "../pawhub/navbar.php"; // Reuse existing navbar (it now has logic to show 'Admin Panel' link active state if needed, or we can just use it for nav)
?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang kembali, <?= htmlspecialchars($_SESSION['fullname']) ?>.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-2">Total Klien</h6>
                            <h2 class="fw-bold mb-0"><?= $userCount ?></h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50 text-uppercase mb-2">Permintaan Pending</h6>
                            <h2 class="fw-bold mb-0"><?= $pendingCount ?></h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-2">Sesi Aktif</h6>
                            <h2 class="fw-bold mb-0"><?= $activeCount ?></h2>
                        </div>
                        <i class="bi bi-activity fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h4 class="fw-bold mb-3">Manajemen</h4>
    <div class="row g-4">
        <div class="col-md-6">
            <a href="<?= url('admin/reports.php') ?>"
                class="card border-0 shadow-sm h-100 text-decoration-none text-dark card-hover">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info me-3">
                        <i class="bi bi-bar-chart-line fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Laporan & Analitik</h5>
                        <p class="text-muted small mb-0">Lihat tren booking dan ekspor data (CSV/PDF).</p>
                    </div>
                    <i class="bi bi-arrow-right ms-auto text-muted"></i>
                </div>
            </a>
        </div>
        <!-- Manage Bookings -->
        <div class="col-md-6">
            <a href="<?= url('admin/bookings.php') ?>"
                class="card border-0 shadow-sm h-100 text-decoration-none text-dark card-hover">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Kelola Booking</h5>
                        <p class="text-muted small mb-0">Lihat semua janji temu dan update status.</p>
                    </div>
                    <i class="bi bi-arrow-right ms-auto text-muted"></i>
                </div>
            </a>
        </div>
    </div>

</div>

<style>
    .card-hover:hover {
        transform: translateY(-3px);
        transition: transform 0.2s;
    }
</style>

<?php
page_end();
?>