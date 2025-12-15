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

page_start('Pawhub');
include_once "../navbar.php";
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM pets WHERE owner_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$pets = [];
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}
$stmt->close();
?>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Peliharaan Saya</h4>
        <a href="<?= url('pawhub/my-pets/add.php') ?>" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Tambah
        </a>
    </div>

    <?php if (empty($pets)): ?>
        <div class="text-center py-5">
            <div class="mb-3 text-muted" style="font-size: 3rem;"><i class="bi bi-emoji-frown"></i></div>
            <h5 class="fw-bold text-secondary">Belum ada peliharaan.</h5>
            <p class="text-muted">Tambahkan peliharaan kesayanganmu sekarang!</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($pets as $pet):
                $icon = "bi-question-circle";
                $bg_color = "bg-light";
                // 1=Cat, 2=Dog, 3=Bird, 4=Other
                if ($pet['hewan'] == 1) {
                    $icon = "bi-chat-heart-fill";
                    $bg_color = "bg-warning-subtle text-warning-emphasis";
                } elseif ($pet['hewan'] == 2) {
                    $icon = "bi-heart-fill";
                    $bg_color = "bg-danger-subtle text-danger-emphasis";
                } elseif ($pet['hewan'] == 3) {
                    $icon = "bi-feather";
                    $bg_color = "bg-info-subtle text-info-emphasis";
                } else {
                    $icon = "bi-stars";
                    $bg_color = "bg-secondary-subtle text-secondary-emphasis";
                }
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="<?php echo $bg_color; ?> rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px; font-size: 1.25rem;">
                                    <i class="bi <?php echo $icon; ?>"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4">
                                            <li><a class="dropdown-item"
                                                href="<?= url('pawhub/my-pets/edit.php?id=' . $pet['id']) ?>">Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger"
                                                href="<?= url('pawhub/my-pets/delete.php?id=' . $pet['id']) ?>"
                                                onclick="return confirm('Yakin ingin menghapus?')">Hapus</a></li>
                                    </ul>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($pet['nama']); ?></h5>
                            <p class="text-secondary small mb-3"><?php echo htmlspecialchars($pet['ras']); ?> •
                                <?php echo $pet['umur']; ?> Tahun
                            </p>

                            <?php if (!empty($pet['note'])): ?>
                                <div class="bg-light p-3 rounded-3 small text-muted">
                                    <i class="bi bi-sticky me-1"></i> <?php echo htmlspecialchars($pet['note']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


<?php
page_end();
?>