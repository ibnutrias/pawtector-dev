<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'core/page.php';
page_start('Syarat & Ketentuan');
include_once 'komponen/navbar.php';

$terms = [
    "Ketentuan Umum Layanan" => [
        "PawPark merupakan platform yang menyediakan layanan penitipan dan perawatan hewan peliharaan.",
        "Layanan PawPark dapat diakses secara gratis, namun penggunaan layanan tertentu dikenakan biaya.",
        "PawPark berhak melakukan perubahan, pembaruan, atau penghentian layanan sewaktu-waktu.",
        "PawPark berhak menolak atau membatalkan penitipan apabila ketentuan tidak terpenuhi."
    ],
    "Ketentuan Hewan yang Dititipkan" => [
        "Hewan yang dititipkan harus berada dalam kondisi sehat dan tidak menunjukkan gejala penyakit menular.",
        "Hewan disarankan telah menerima vaksinasi dasar sesuai jenis dan usia.",
        "PawPark tidak menerima hewan dengan kondisi medis serius tanpa persetujuan khusus.",
        "Hewan yang memiliki riwayat agresif wajib diinformasikan sebelumnya kepada PawPark."
    ],
    "Informasi & Tanggung Jawab Pemilik" => [
        "Pemilik wajib memberikan data yang akurat mengenai identitas hewan.",
        "Informasi terkait kebiasaan makan, perilaku, alergi, dan kebutuhan khusus harus disampaikan dengan jelas.",
        "Pemilik bertanggung jawab atas kebenaran informasi yang diberikan.",
        "PawPark tidak bertanggung jawab atas dampak yang timbul akibat informasi yang tidak lengkap atau tidak benar."
    ],
    "Proses & Masa Penitipan" => [
        "Masa penitipan dihitung berdasarkan hari kalender sesuai tanggal pemesanan.",
        "Penitipan dianggap dimulai sejak hewan diterima oleh PawPark.",
        "Keterlambatan pengambilan hewan dapat dikenakan biaya tambahan.",
        "Perpanjangan masa penitipan tergantung pada ketersediaan tempat."
    ],
    "Perawatan Selama Penitipan" => [
        "PawPark memberikan perawatan dasar berupa pemberian makan, minum, dan kebersihan kandang.",
        "Aktivitas harian hewan disesuaikan dengan kondisi dan kebutuhan masing-masing.",
        "PawPark berupaya menjaga kenyamanan dan keselamatan hewan selama penitipan.",
        "Stres ringan akibat perubahan lingkungan merupakan kondisi yang mungkin terjadi."
    ],
    "Kesehatan & Kondisi Darurat" => [
        "PawPark melakukan pemantauan kondisi kesehatan hewan selama penitipan.",
        "Dalam keadaan darurat, PawPark berhak mengambil tindakan yang diperlukan demi keselamatan hewan.",
        "PawPark berhak membawa hewan ke dokter hewan terdekat.",
        "Biaya pemeriksaan dan pengobatan ditanggung oleh pemilik hewan.",
        "PawPark akan berusaha menghubungi pemilik sebelum tindakan medis dilakukan, jika memungkinkan."
    ],
    "Grooming & Layanan Tambahan" => [
        "Layanan grooming bersifat opsional dan dilakukan berdasarkan permintaan pemilik.",
        "Jenis layanan grooming disesuaikan dengan paket yang dipilih.",
        "Kondisi bulu yang kusut parah atau perilaku hewan yang sulit ditangani dapat dikenakan biaya tambahan.",
        "PawPark tidak bertanggung jawab atas reaksi alergi yang tidak diinformasikan sebelumnya."
    ],
    "Pembayaran & Biaya" => [
        "Seluruh biaya layanan tercantum dalam sistem PawPark.",
        "Pembayaran dilakukan sesuai dengan total tagihan yang tertera.",
        "PawPark berhak menahan pengambilan hewan apabila pembayaran belum diselesaikan.",
        "Biaya yang telah dibayarkan tidak dapat dikembalikan kecuali terjadi kesalahan dari pihak PawPark."
    ],
    "Barang Pribadi Hewan" => [
        "Pemilik dapat menitipkan barang pribadi hewan seperti mainan atau alas tidur.",
        "PawPark tidak menjamin keamanan barang pribadi selama penitipan.",
        "Pemilik disarankan membawa barang seperlunya."
    ],
    "Batasan Tanggung Jawab" => [
        "PawPark bertanggung jawab atas layanan sesuai dengan paket yang disepakati.",
        "PawPark tidak bertanggung jawab atas kejadian di luar kendali, termasuk bencana alam atau keadaan darurat lainnya.",
        "Tanggung jawab PawPark terbatas pada periode penitipan yang telah disepakati."
    ],
    "Privasi & Data Pengguna" => [
        "Data pengguna dan hewan digunakan untuk keperluan layanan PawPark.",
        "PawPark berkomitmen menjaga kerahasiaan data pengguna.",
        "Data tidak akan dibagikan kepada pihak ketiga tanpa persetujuan pengguna, kecuali diwajibkan oleh hukum."
    ],
    "Penutup" => [
        "PawPark berhak memperbarui syarat dan ketentuan ini sewaktu-waktu.",
        "Ketentuan yang diperbarui akan ditampilkan pada website PawPark.",
        "Syarat dan ketentuan ini berlaku selama pengguna menggunakan layanan PawPark."
    ]
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="fw-bold text-center mb-4">Syarat & Ketentuan</h1>
            <p class="text-center text-muted mb-5">
                Harap membaca syarat dan ketentuan berikut dengan seksama sebelum menggunakan layanan kami.
            </p>

            <div class="accordion shadow-sm rounded-4 overflow-hidden" id="accordionTerms">
                <?php $i = 0;
                foreach ($terms as $title => $points):
                    $i++; ?>
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="heading<?= $i ?>">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="false"
                                aria-controls="collapse<?= $i ?>">
                                <?= $i ?>. <?= htmlspecialchars($title) ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $i ?>"
                            data-bs-parent="#accordionTerms">
                            <div class="accordion-body bg-light">
                                <ul class="mb-0 text-secondary">
                                    <?php foreach ($points as $point): ?>
                                        <li class="mb-2"><?= htmlspecialchars($point) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-5">
                <a href="<?= url('/') ?>" class="btn btn-outline-primary rounded-pill px-4">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'komponen/footer.php';
page_end();
?>