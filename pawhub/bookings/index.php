<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: " . url('/'));
    exit;
}

require_once "../../core/koneksi.php";
include_once '../../core/page.php';
include_once '../../core/components.php';

$userId = $_SESSION['user_id'];
$PAGE_TITLE = "Booking Saya";

// --- BACKEND LOGIC ---
$sql = "
    SELECT 
        a.id, a.appointment_date, a.appointment_time, a.status, a.notes, a.service,
        p.nama AS pet_name, p.ras AS pet_breed,
        ar.eating, ar.playing, ar.grooming, ar.staff_notes 
    FROM appointments a
    JOIN pets p ON a.pet_id = p.id
    LEFT JOIN appointment_reports ar ON a.id = ar.appointment_id
    WHERE a.user_id = ? 
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$active_apps = [];
$history_apps = [];

while ($row = $result->fetch_assoc()) {
    if (in_array($row['status'], ['pending', 'active'])) {
        $active_apps[] = $row;
    } else {
        $history_apps[] = $row;
    }
}
$stmt->close();

page_start($PAGE_TITLE);
include_once "../navbar.php";
?>


<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark">Booking Saya</h2>
            <p class="text-muted mb-0">Kelola kunjungan hewan peliharaan Anda.</p>
        </div>
        <a href="<?= url('pawhub/bookings/new.php') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Buat Janji Baru
        </a>
    </div>

    <!-- Active/Upcoming Section -->
    <div class="mb-5">
        <h4 class="fw-bold mb-3">Jadwal Mendatang</h4>
        <?php if (empty($active_apps)): ?>
            <div class="alert alert-light border text-center py-4">
                <p class="text-muted mb-0">Belum ada booking mendatang.</p>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($active_apps as $app): ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($app['pet_name']) ?></h5>
                                        <small class="text-muted"><?= htmlspecialchars($app['pet_breed']) ?></small>
                                        <div class="mt-2">
                                            <?php renderServiceBadge($app['service']); ?>
                                        </div>
                                    </div>
                                    <?php renderStatusBadge($app['status']); ?>
                                </div>
                                <div class="row text-muted small">
                                    <div class="col-6">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?= date('d M Y', strtotime($app['appointment_date'])) ?>
                                    </div>
                                    <div class="col-6">
                                        <i class="bi bi-clock me-1"></i> <?= date('H:i', strtotime($app['appointment_time'])) ?>
                                    </div>
                                </div>
                                <?php if ($app['status'] === 'active'): ?>
                                    <div class="mt-3 pt-3 border-top">
                                        <h6 class="text-success small fw-bold"><i class="bi bi-activity"></i> Laporan Aktivitas
                                            Langsung</h6>
                                        <div class="d-flex gap-3 small">
                                            <span class="<?= $app['eating'] ? 'text-success' : 'text-muted' ?>">
                                                <i class="bi bi-check-circle<?= $app['eating'] ? '-fill' : '' ?>"></i> Makan
                                            </span>
                                            <span class="<?= $app['playing'] ? 'text-success' : 'text-muted' ?>">
                                                <i class="bi bi-check-circle<?= $app['playing'] ? '-fill' : '' ?>"></i> Main
                                            </span>
                                            <span class="<?= $app['grooming'] ? 'text-success' : 'text-muted' ?>">
                                                <i class="bi bi-check-circle<?= $app['grooming'] ? '-fill' : '' ?>"></i> Grooming
                                            </span>
                                        </div>
                                        <?php if (!empty($app['staff_notes'])): ?>
                                            <div class="mt-3 p-3 bg-light rounded-3 small border-start border-4 border-info">
                                                <div class="fw-bold text-info mb-1"><i class="bi bi-info-circle me-1"></i> Catatan
                                                    Staff:</div>
                                                <div class="text-secondary"><?= nl2br(htmlspecialchars($app['staff_notes'])) ?></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- History Section -->
    <div>
        <h4 class="fw-bold mb-3 text-muted">Riwayat</h4>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Hewan</th>
                            <th>Tanggal</th>
                            <th>Layanan</th>
                            <th>Catatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($history_apps)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($history_apps as $app): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= htmlspecialchars($app['pet_name']) ?></td>
                                    <td><?= date('d M Y', strtotime($app['appointment_date'])) ?></td>
                                    <td><?php renderServiceBadge($app['service']); ?></td>
                                    <td class="text-muted small" style="max-width: 250px;">
                                        <?php if (!empty($app['notes'])): ?>
                                            <div class="mb-1"><i
                                                    class="bi bi-person me-1"></i><?= htmlspecialchars($app['notes']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($app['staff_notes'])): ?>
                                            <div class="text-info"><i
                                                    class="bi bi-pencil-square me-1"></i><?= htmlspecialchars($app['staff_notes']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (empty($app['notes']) && empty($app['staff_notes']))
                                            echo '-'; ?>
                                    </td>
                                    <td>
                                        <?php renderStatusBadge($app['status']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
page_end();
?>