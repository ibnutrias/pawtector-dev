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

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $hewan = (int) $_POST['hewan'];
    $ras = htmlspecialchars(trim($_POST['ras']));
    $umur = (int) $_POST['umur'];
    $note = htmlspecialchars(trim($_POST['note']));
    $owner_id = $_SESSION['user_id'];

    if (empty($nama)) {
        $error_msg = "Nama peliharaan wajib diisi.";
    } else {
        $stmt = $koneksi->prepare("INSERT INTO pets (owner_id, hewan, ras, nama, umur, note) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $owner_id, $hewan, $ras, $nama, $umur, $note);

        if ($stmt->execute()) {
            header("Location: " . url('pawhub/my-pets'));
            exit;
        } else {
            $error_msg = "Gagal menambah peliharaan: " . $stmt->error;
        }
        $stmt->close();
    }
}

page_start('Tambah Peliharaan');
include_once "../navbar.php";
?>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="mb-4 fw-bold">Tambah Peliharaan Baru</h4>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Nama Peliharaan</label>
                                <input type="text" name="nama" class="form-control rounded-3" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Jenis Hewan</label>
                                <select name="hewan" class="form-select rounded-3">
                                    <option value="1">Kucing</option>
                                    <option value="2">Anjing</option>
                                    <option value="3">Burung</option>
                                    <option value="4">Lainnya</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Ras</label>
                                <input type="text" name="ras" class="form-control rounded-3"
                                    placeholder="Contoh: Angora, Golden Retriever">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Umur (Tahun)</label>
                                <input type="number" name="umur" class="form-control rounded-3" min="0">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Catatan</label>
                                <textarea name="note" class="form-control rounded-3" rows="3"
                                    placeholder="Info tambahan..."></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">Simpan
                                    Peliharaan</button>
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