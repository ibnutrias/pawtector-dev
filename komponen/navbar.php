<style>
    body {
        padding: 0;
    }

    /* NAVBAR CONTAINER */
    .navbar-custom {
        background-color: rgba(255, 255, 255, 0.85);
        /* Semi-transparent */
        backdrop-filter: blur(12px);
        /* Glass effect */
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 15px;
        padding-bottom: 15px;
        transition: all 0.3s ease;
    }

    /* BRAND LOGO */
    .navbar-brand {
        font-weight: 700;
        font-size: 1.35rem;
        letter-spacing: -0.5px;
        /* Tighter spacing looks more premium */
        color: #000;
    }

    /* NAV LINKS */
    .nav-link {
        font-weight: 500;
        font-size: 0.95rem;
        color: #555 !important;
        margin: 0 12px;
        position: relative;
        transition: color 0.3s ease;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #000 !important;
    }

    /* Animated Underline Effect */
    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0px;
        left: 50%;
        background-color: #000;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after {
        width: 100%;
    }

    /* BUTTON STYLING */
    .btn-black {
        background-color: #000;
        color: #fff;
        border-radius: 50px;
        padding: 8px 26px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .btn-black:hover {
        background-color: transparent;
        color: #000;
        border-color: #000;
        transform: translateY(-1px);
    }

    /* REMOVE BLUE GLOW ON MOBILE CLICK */
    .navbar-toggler:focus {
        box-shadow: none;
    }
</style>

<nav class="navbar navbar-expand-lg  sticky-top py-3  navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="/"><img src='assets/images/pawpark-logo.svg' width="100" /></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#layanan">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tentang.php">Tentang</a>
                </li>
            </ul>

            <div class="d-flex">
                <a href="/registrasi" class="btn btn-black">Daftar</a>
            </div>
        </div>
    </div>
</nav>