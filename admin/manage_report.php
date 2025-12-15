<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 0) {
    header("Location: " . url('masuk'));
    exit;
}

require_once "../core/koneksi.php";
include_once '../core/page.php';

if (!isset($_GET['id'])) {
    header("Location: bookings.php");
    exit;
}

$appointId = intval($_GET['id']);
$PAGE_TITLE = "Update Activity Report";

$msg = "";
$msgType = "";

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eating = isset($_POST['eating']) ? 1 : 0;
    $playing = isset($_POST['playing']) ? 1 : 0;
    $grooming = isset($_POST['grooming']) ? 1 : 0;
    $notes = isset($_POST['staff_notes']) ? htmlspecialchars(trim($_POST['staff_notes'])) : '';

    // Check if report exists
    $checkSql = "SELECT id FROM appointment_reports WHERE appointment_id = ?";
    $stmt = $koneksi->prepare($checkSql);
    $stmt->bind_param("i", $appointId);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res->num_rows > 0;
    $stmt->close();

    if ($exists) {
        $sql = "UPDATE appointment_reports SET eating=?, playing=?, grooming=?, staff_notes=? WHERE appointment_id=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("iiisi", $eating, $playing, $grooming, $notes, $appointId);
    } else {
        $sql = "INSERT INTO appointment_reports (appointment_id, eating, playing, grooming, staff_notes) VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("iiiis", $appointId, $eating, $playing, $grooming, $notes);
    }

    if ($stmt->execute()) {
        $msg = "Laporan aktivitas berhasil diperbarui.";
        $msgType = "success";
    } else {
        $msg = "Gagal membuat laporan: " . $stmt->error;
        $msgType = "danger";
    }
    $stmt->close();
}

// --- FETCH CURRENT DATA ---
// 1. Appointment Info
$appCtx = $koneksi->prepare("
    SELECT a.appointment_date, p.nama, u.fullname 
    FROM appointments a
    JOIN pets p ON a.pet_id = p.id
    JOIN users u ON a.user_id = u.id
    WHERE a.id = ?
");
$appCtx->bind_param("i", $appointId);
$appCtx->execute();
$appInfo = $appCtx->get_result()->fetch_assoc();
$appCtx->close();

if (!$appInfo) {
    echo "Appointment tidak ditemukan.";
    exit;
}

// 2. Report Info
$reportCtx = $koneksi->prepare("SELECT * FROM appointment_reports WHERE appointment_id = ?");
$reportCtx->bind_param("i", $appointId);
$reportCtx->execute();
$report = $reportCtx->get_result()->fetch_assoc();
$reportCtx->close();

// Set defaults
$eating = $report['eating'] ?? 0;
$playing = $report['playing'] ?? 0;
$grooming = $report['grooming'] ?? 0;
$notes = $report['staff_notes'] ?? '';

page_start($PAGE_TITLE);
include_once "../pawhub/navbar.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="bookings.php" class="text-decoration-none">Booking</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Laporan</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white p-4">
                    <h4 class="mb-1"><i class="bi bi-activity me-2"></i>Laporan Kegiatan Harian</h4>
                    <p class="mb-0 opacity-75">
                        Klien: <?= htmlspecialchars($appInfo['fullname']) ?> &bull;
                        Hewan: <?= htmlspecialchars($appInfo['nama']) ?>
                    </p>
                </div>
                <div class="card-body p-4">

                    <?php if ($msg): ?>
                        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show">
                            <?= $msg ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <h6 class="fw-bold text-uppercase text-muted mb-3">Checklist</h6>

                        <div class="d-flex flex-column gap-3 mb-4">
                            <label class="d-flex align-items-center p-3 border rounded-3 cursor-pointer bg-light">
                                <input type="checkbox" name="eating" class="form-check-input fs-4 me-3" <?= $eating ? 'checked' : '' ?>>
                                <div>
                                    <div class="fw-bold">Makan</div>
                                    <small class="text-muted">Apakah hewan sudah makan dengan baik hari ini?</small>
                                </div>
                            </label>

                            <label class="d-flex align-items-center p-3 border rounded-3 cursor-pointer bg-light">
                                <input type="checkbox" name="playing" class="form-check-input fs-4 me-3" <?= $playing ? 'checked' : '' ?>>
                                <div>
                                    <div class="fw-bold">Bermain</div>
                                    <small class="text-muted">Sudah diajak bermain atau berolahraga?</small>
                                </div>
                            </label>

                            <label class="d-flex align-items-center p-3 border rounded-3 cursor-pointer bg-light">
                                <input type="checkbox" name="grooming" class="form-check-input fs-4 me-3" <?= $grooming ? 'checked' : '' ?>>
                                <div>
                                    <div class="fw-bold">Grooming</div>
                                    <small class="text-muted">Langkah grooming selesai (jika ada)?</small>
                                </div>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase text-muted">Catatan Staff</label>
                            <textarea name="staff_notes" class="form-control" rows="4"
                                placeholder="Tambahkan update khusus untuk pemilik..."><?= htmlspecialchars($notes) ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Simpan Laporan</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
page_end();
?>