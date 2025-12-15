<?php
session_start();

// 1. Authorization Check (Role 0 = Admin)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 0) {
    header("Location: " . url('masuk'));
    exit;
}

require_once "../core/koneksi.php";
include_once '../core/page.php';
include_once '../core/components.php';
$PAGE_TITLE = "Manage Bookings";

// --- HANDLE STATUS UPDATE ---
$msg = "";
$msgType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $appointId = intval($_POST['appointment_id']);
    $newStatus = $_POST['status'];


    // Allowed statuses
    $allowed = ['pending', 'active', 'finished', 'cancelled'];

    if (in_array($newStatus, $allowed)) {
        $updateSql = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = $koneksi->prepare($updateSql);
        $stmt->bind_param("si", $newStatus, $appointId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $msg = "Status Booking #$appointId berhasil diubah menjadi <strong>" . ucfirst($newStatus) . "</strong>.";
                $msgType = "success";
            } else {
                $msg = "Status tidak berubah (Nilai mungkin sama atau ID tidak ditemukan).";
                $msgType = "warning";
            }
        } else {
            $msg = "Gagal mengubah status: " . $stmt->error;
            $msgType = "danger";
        }
        $stmt->close();
    } else {
        $msg = "Nilai status tidak valid: " . htmlspecialchars($newStatus);
        $msgType = "danger";
    }
}

// --- FETCH ALL BOOKINGS ---
$sql = "
    SELECT 
        a.id, a.appointment_date, a.appointment_time, a.status, a.notes, a.service,
        u.fullname as client_name,
        p.nama as pet_name, p.ras as pet_breed
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    JOIN pets p ON a.pet_id = p.id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
";
$result = $koneksi->query($sql);

page_start($PAGE_TITLE);
include_once "../pawhub/navbar.php";
?>

<div class="container py-5">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('admin') ?>" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Booking</li>
    </nav>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Semua Janji Temu</h2>
    </div>

    <?php renderAlert($msg, $msgType); ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th>Tanggal & Waktu</th>
                            <th>Klien & Hewan</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 text-muted">#<?= $row['id'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= date('M d, Y', strtotime($row['appointment_date'])) ?></div>
                                        <div class="small text-muted"><?= date('h:i A', strtotime($row['appointment_time'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($row['client_name']) ?></div>
                                        <div class="small text-muted mb-1">
                                            <i class="bi bi-paw me-1"></i><?= htmlspecialchars($row['pet_name']) ?>
                                            (<?= htmlspecialchars($row['pet_breed']) ?>)
                                        </div>
                                        <?php renderServiceBadge($row['service']); ?>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate text-muted" style="max-width: 150px;"
                                            title="<?= htmlspecialchars($row['notes']) ?>">
                                            <?= htmlspecialchars($row['notes']) ?: '-' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php renderStatusBadge($row['status']); ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form method="POST" class="d-inline-flex gap-2">
                                            <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="update_status" value="1">

                                            <select name="status" class="form-select form-select-sm" style="width: auto;">
                                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>
                                                    Pending</option>
                                                <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>Aktif
                                                </option>
                                                <option value="finished" <?= $row['status'] == 'finished' ? 'selected' : '' ?>>
                                                    Selesai</option>
                                                <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>
                                                    Dibatalkan</option>
                                            </select>

                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Update Status">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>

                                        <?php if ($row['status'] == 'active'): ?>
                                            <a href="manage_report.php?id=<?= $row['id'] ?>"
                                                class="btn btn-sm btn-outline-success ms-1" title="Update Activity Report">
                                                <i class="bi bi-clipboard-check"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($row['status'] == 'finished' || $row['status'] == 'completed'): ?>
                                            <a href="print_receipt.php?id=<?= $row['id'] ?>" target="_blank"
                                                class="btn btn-sm btn-outline-secondary ms-1" title="Print Receipt">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada booking ditemukan.</td>
                            </tr>
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