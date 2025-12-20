<?php
include_once 'core/page.php';
page_start('Rumah');
include_once 'komponen/navbar.php';
?>
<style>
    .hero-image-col {
        height: 40vh;
        /* Mobile: Image takes top 40% of screen */
    }

    @media (min-width: 992px) {

        /* Desktop (lg) Breakpoint */
        .hero-image-col {
            height: 100vh;
            /* Desktop: Image takes full height */
        }

        .hero-text-col {
            height: 100vh;
            /* Desktop: Text takes full height */
        }
    }

    .facility-img {
        height: 250px;
        /* Fixed height for consistency */
        object-fit: cover;
        width: 100%;
    }
</style>
<?php
include_once "section-utama.php";
include "section-layanan.php";
include_once "section-review.php";
include_once "section-cta.php";
include 'komponen/footer.php';
page_end();
?>