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

$pet_id = $_GET['id'] ?? null;
if (!$pet_id) {
    header("Location: index.php");
    exit;
}

// Fetch pet ensuring ownership
$stmt = $koneksi->prepare("SELECT * FROM pets WHERE id = ? AND owner_id = ?");
$owner_id = $_SESSION['user_id'];
$stmt->bind_param("ii", $pet_id, $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    header("Location: index.php");
    exit;
}

$error_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $hewan = (int) $_POST['hewan'];
    $ras = htmlspecialchars(trim($_POST['ras']));
    $umur = (int) $_POST['umur'];
    $note = htmlspecialchars(trim($_POST['note']));

    if (empty($nama)) {
        $error_msg = "Nama peliharaan wajib diisi.";
    } else {
        $updateStmt = $koneksi->prepare("UPDATE pets SET nama=?, hewan=?, ras=?, umur=?, note=? WHERE id=? AND owner_id=?");
        $updateStmt->bind_param("sisissi", $nama, $hewan, $ras, $umur, $note, $pet_id, $owner_id);

        if ($updateStmt->execute()) {
            header("Location: " . url('pawhub/my-pets'));
            exit;
        } else {
            $error_msg = "Gagal mengupdate: " . $updateStmt->error;
        }
        $updateStmt->close();
    }
}

page_start('Edit Peliharaan');
include_once "../navbar.php";
?>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="mb-4 fw-bold">Edit Peliharaan</h4>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Nama Peliharaan</label>
                                <input type="text" name="nama" class="form-control rounded-3"
                                    value="<?php echo htmlspecialchars($pet['nama']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Jenis Hewan</label>
                                <select name="hewan" class="form-select rounded-3">
                                    <option value="1" <?php if ($pet['hewan'] == 1)
                                        echo 'selected'; ?>>Kucing
                                    </option>
                                    <option value="2" <?php if ($pet['hewan'] == 2)
                                        echo 'selected'; ?>>Anjing
                                    </option>
                                    <option value="3" <?php if ($pet['hewan'] == 3)
                                        echo 'selected'; ?>>Burung
                                    </option>
                                    <option value="4" <?php if ($pet['hewan'] == 4)
                                        echo 'selected'; ?>>Lainnya
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Ras</label>
                                <input type="text" name="ras" class="form-control rounded-3"
                                    value="<?php echo htmlspecialchars($pet['ras']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Umur (Tahun)</label>
                                <input type="number" name="umur" class="form-control rounded-3" min="0"
                                    value="<?php echo $pet['umur']; ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">Catatan</label>
                                <textarea name="note" class="form-control rounded-3"
                                    rows="3"><?php echo htmlspecialchars($pet['note']); ?></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">Simpan
                                    Perubahan</button>
                                <a href="<?= url('pawhub/my-pets') ?>"
                                    class="btn btn-light px-4 rounded-pill fw-bold ms-2">Batal</a>
                            </div>
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