<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'core/page.php';
page_start('About');
include_once 'komponen/navbar.php';
?>
<!-- HERO SECTION -->
<section class="py-5 text-center container">
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-bold display-4">Tentang PawPark</h1>
            <p class="lead text-muted">Kami berdedikasi untuk memberikan pengalaman penitipan hewan peliharaan terbaik,
                aman, dan penuh kasih sayang untuk teman berbulu Anda.</p>
        </div>
    </div>
</section>

<!-- TENTANG KAMI -->
<div class="container mb-5">
    <div class="row align-items-center g-5">
        <div class="col-md-6">
            <div class="p-4 bg-light rounded-4 border border-light-subtle shadow-sm h-100 d-flex flex-column justify-content-center text-center text-md-start"
                style="min-height: 400px;">
                <i class="bi bi-shop text-primary mb-3" style="font-size: 3rem;"></i>
                <h3 class="fw-bold mb-3">Siapa Kami?</h3>
                <p class="text-secondary">PawPark adalah rumah kedua bagi peliharaan Anda. Didirikan oleh pecinta hewan
                    untuk pecinta hewan, kami mengerti bahwa meninggalkan peliharaan bisa menjadi hal yang sulit. Itulah
                    mengapa kami menciptakan lingkungan yang aman, bersih, dan menyenangkan di mana peliharaan Anda
                    diperlakukan seperti keluarga sendiri.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 bg-white rounded-4 border shadow-sm h-100 d-flex flex-column justify-content-center text-center text-md-start"
                style="min-height: 400px;">
                <i class="bi bi-shield-check text-success mb-3" style="font-size: 3rem;"></i>
                <h3 class="fw-bold mb-3">Misi Kami</h3>
                <ul class="list-unstyled text-secondary text-start mx-auto mx-md-0">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Menyediakan perawatan
                        standar tertinggi.</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Menciptakan lingkungan
                        bebas stres.</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Transparansi penuh kepada
                        pemilik.</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Layanan kesehatan 24 jam
                        on-call.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<hr class="container opacity-10 my-5">

<!-- KEBIJAKAN PENITIPAN -->
<div class="container mb-5">
    <h2 class="fw-bold text-center mb-5">Kebijakan Penitipan</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center">
                <div class="card-body p-4">
                    <div class="mb-3 text-warning">
                        <i class="bi bi-file-medical" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold">Vaksinasi Lengkap</h5>
                    <p class="text-muted small">Semua hewan wajib memiliki buku vaksin yang up-to-date demi keamanan
                        bersama.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center">
                <div class="card-body p-4">
                    <div class="mb-3 text-info">
                        <i class="bi bi-bug" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold">Bebas Kutu</h5>
                    <p class="text-muted small">Hewan harus bebas dari kutu dan jamur. Pemeriksaan akan dilakukan saat
                        check-in.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center">
                <div class="card-body p-4">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-basket" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold">Makanan Pribadi</h5>
                    <p class="text-muted small">Disarankan membawa makanan sendiri untuk mencegah gangguan pencernaan.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-center mt-3">
        <a href="<?= url('syarat-ketentuan.php') ?>" class="btn btn-outline-dark rounded-pill">
            Lihat Syarat & Ketentuan Lengkap
        </a>
    </div>
</div>
</div>

<hr class="container opacity-10 my-5">

<!-- FAQ SECTION -->
<div class="container mb-5">
    <h2 class="fw-bold text-center mb-4">Sering Ditanyakan (FAQ)</h2>
    <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
        <div class="accordion-item border-0 border-bottom">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse"
                    data-bs-target="#faq1">
                    Apakah kandang kucing dan anjing dipisah?
                </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    Tentu saja! Kami memiliki area terpisah dan kedap suara untuk kucing dan anjing agar mereka tetap
                    tenang dan tidak stres.
                </div>
            </div>
        </div>
        <div class="accordion-item border-0 border-bottom">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse"
                    data-bs-target="#faq2">
                    Bisakah saya melihat kondisi hewan saya?
                </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    Kami mengirimkan update foto dan video harian melalui WhatsApp. Untuk member premium, tersedia akses
                    CCTV 24 jam.
                </div>
            </div>
        </div>
        <div class="accordion-item border-0">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse"
                    data-bs-target="#faq3">
                    Apa yang terjadi jika hewan saya sakit?
                </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    Kami memiliki dokter hewan rekanan yang siap dipanggil 24 jam (on-call). Kami akan segera
                    menghubungi Anda jika terjadi keadaan darurat.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KONTAK KAMI -->
<div class="container py-5">
    <div class="bg-dark text-white rounded-5 p-5 text-center shadow-lg position-relative overflow-hidden">
        <div class="position-relative z-1">
            <h2 class="fw-bold display-6 mb-4">Hubungi Kami</h2>
            <p class="text-white-50 mb-5 col-lg-8 mx-auto">Punya pertanyaan lebih lanjut? Tim kami siap membantu Anda
                kapan saja.</p>

            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="p-3 border border-secondary rounded-4 bg-white bg-opacity-10">
                        <i class="bi bi-whatsapp fs-3 mb-2 d-block"></i>
                        <h6 class="fw-bold">WhatsApp</h6>
                        <p class="mb-0 text-white-50 small">+62 812 3456 7890</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border border-secondary rounded-4 bg-white bg-opacity-10">
                        <i class="bi bi-envelope fs-3 mb-2 d-block"></i>
                        <h6 class="fw-bold">Email</h6>
                        <p class="mb-0 text-white-50 small">hello@pawpark.com</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border border-secondary rounded-4 bg-white bg-opacity-10">
                        <i class="bi bi-geo-alt fs-3 mb-2 d-block"></i>
                        <h6 class="fw-bold">Lokasi</h6>
                        <p class="mb-0 text-white-50 small">Jl. Hewan Bahagia No. 12, Jakarta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
page_end();
?>