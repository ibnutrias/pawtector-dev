<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Authorization Check
if (!isset($_SESSION['user_id'])) {
    header("Location: " . url('/'));
    exit;
}

require_once "../../core/koneksi.php";
include_once '../../core/page.php';

$PAGE_TITLE = "Pawhub - New Appointment";

// --- BACKEND LOGIC ---
$msg = "";
$msgType = "";
$bookingSuccess = false;

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_booking'])) {

    $userId = $_SESSION['user_id'];
    $petId = isset($_POST['pet_id']) ? intval($_POST['pet_id']) : null;
    $date = isset($_POST['app_date']) ? $_POST['app_date'] : null;
    $time = isset($_POST['app_time']) ? $_POST['app_time'] : null;
    $appNotes = isset($_POST['app_notes']) ? trim($_POST['app_notes']) : '';
    $terms = isset($_POST['terms']);

    if ($petId && $date && $time && $terms) {
        // Check for Existing Active/Pending Booking
        $checkSql = "SELECT id, status FROM appointments WHERE pet_id = ? AND status IN ('pending', 'active') LIMIT 1";
        $checkStmt = $koneksi->prepare($checkSql);
        $checkStmt->bind_param("i", $petId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $existing = $checkResult->fetch_assoc();
            $msg = "Peringatan: Hewan ini sudah memiliki janji temu status " . strtoupper($existing['status']) . " (#" . $existing['id'] . "). Tidak bisa booking lagi sampai selesai atau dibatalkan.";
            $msgType = "warning";
        } else {
            // Proceed with Insert
            $sql = "INSERT INTO appointments (user_id, pet_id, service, appointment_date, appointment_time, notes, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'pending')";

            $stmt = $koneksi->prepare($sql);

            if ($stmt) {
                $service = $_POST['service'];
                $stmt->bind_param("iissss", $userId, $petId, $service, $date, $time, $appNotes);

                if ($stmt->execute()) {
                    $bookingSuccess = true;
                } else {
                    $msg = "Database Error: " . $stmt->error;
                    $msgType = "danger";
                }
                $stmt->close();
            } else {
                $msg = "Query Preparation Error: " . $koneksi->error;
                $msgType = "danger";
            }
        }
    } else {
        $msg = "Mohon isi semua kolom yang wajib dan setujui syarat ketentuan.";
        $msgType = "warning";
    }
}

// Fetch User's Pets
$userId = $_SESSION['user_id'];
$petSql = "SELECT id, hewan, ras, nama, umur, note FROM pets WHERE owner_id = ?";
$stmt = $koneksi->prepare($petSql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$pets = [];
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}
$stmt->close();


// --- FRONTEND RENDER ---
page_start($PAGE_TITLE);
include_once "../navbar.php";
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h3 class="fw-bold text-primary mb-0">Buat Janji Temu</h3>
                    <p class="text-muted">Jadwalkan kunjungan untuk hewan peliharaanmu.</p>
                </div>

                <div class="card-body p-4">

                    <?php if ($msg): ?>
                        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
                            <?= $msg ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <!-- 1. Select Pet -->
                        <h5 class="fw-bold mb-3"><i class="bi bi-person-heart me-2"></i>Pilih Peliharaan</h5>
                        <?php if (empty($pets)): ?>
                            <div class="alert alert-warning">
                                No pets found. <a href="<?= url('pawhub/my-pets/add.php') ?>">Daftarkan data
                                    peliharaanmu</a>.
                            </div>
                        <?php else: ?>
                            <div class="row g-3 mb-4">
                                <?php foreach ($pets as $pet): ?>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="pet_id" id="pet_<?= $pet['id'] ?>"
                                            value="<?= $pet['id'] ?>" required>
                                        <label class="btn btn-outline-light text-dark w-100 p-3 text-start border shadow-sm"
                                            for="pet_<?= $pet['id'] ?>">
                                            <div class="fw-bold"><?= htmlspecialchars($pet['nama']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($pet['ras']) ?></small>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- 1.1 Service Selection -->
                        <h5 class="fw-bold mb-3 mt-4"><i class="bi bi-grid me-2"></i>Pilih Layanan</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="service" id="service_boarding"
                                    value="Boarding" required>
                                <label class="btn btn-outline-primary w-100 p-3 text-start shadow-sm h-100"
                                    for="service_boarding">
                                    <div class="fw-bold"><i class="bi bi-house-heart me-2"></i>Boarding</div>
                                    <small class="text-muted">Penginapan aman & nyaman.</small>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="service" id="service_daycare"
                                    value="Daycare" required>
                                <label class="btn btn-outline-success w-100 p-3 text-start shadow-sm h-100"
                                    for="service_daycare">
                                    <div class="fw-bold"><i class="bi bi-sun me-2"></i>Daycare</div>
                                    <small class="text-muted">Penitipan harian penuh aktivitas.</small>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="service" id="service_grooming"
                                    value="Grooming" required>
                                <label class="btn btn-outline-info w-100 p-3 text-start shadow-sm h-100"
                                    for="service_grooming">
                                    <div class="fw-bold"><i class="bi bi-scissors me-2"></i>Grooming</div>
                                    <small class="text-muted">Perawatan kecantikan & kebersihan.</small>
                                </label>
                            </div>
                        </div>

                        <hr class="my-4 user-select-none opacity-25">

                        <!-- 2. Date & Time -->
                        <h5 class="fw-bold mb-3"><i class="bi bi-calendar-event me-2"></i>Jadwal</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="app_date" min="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Waktu</label>
                                <select class="form-select" name="app_time" required>
                                    <option value="" selected disabled>Pilih Waktu...</option>
                                    <option value="09:00">09:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="13:00">01:00 PM</option>
                                    <option value="14:00">02:00 PM</option>
                                    <option value="15:00">03:00 PM</option>
                                    <option value="16:00">04:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <!-- 3. Notes -->
                        <div class="mb-4">
                            <label class="form-label">Lainnya</label>
                            <textarea class="form-control" name="app_notes" rows="3"
                                placeholder="Deskripsikan..."></textarea>
                        </div>

                        <!-- 4. Terms -->
                        <div class="form-check mb-4 bg-light p-3 rounded">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label small" for="terms">
                                Saya setuju dengan <a href="#">Syarat & Ketentuan</a> Yang Berlaku.
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="submit_booking" class="btn btn-primary btn-lg" <?= empty($pets) ? 'disabled' : '' ?>>
                                Konfirmasi
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="text-center mt-4">
                <a href="<?= url('pawhub/bookings') ?>" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke
                    Booking</a>
            </div>

        </div>
    </div>
</div>

<?php if ($bookingSuccess): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Booking Terkonfirmasi!',
                text: 'Jadwalmu berhasil disimpan.',
                icon: 'success',
                confirmButtonText: 'Lihat Booking',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        });
    </script>
<?php endif; ?>

<?php
page_end();
?>