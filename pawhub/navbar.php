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

    /* SPLIT PILL BUTTON GROUP */
    .pill-group {
        display: inline-flex;
        background-color: #000;
        border-radius: 50px;
        align-items: center;
        padding: 2px;
        transition: transform 0.2s ease;
    }

    .pill-group:hover {
        transform: translateY(-1px);
    }

    .pill-btn-main {
        color: #fff;
        padding: 8px 20px 8px 24px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
        transition: color 0.2s ease;
    }

    .pill-btn-sub {
        color: rgba(255, 255, 255, 0.7);
        padding: 8px 18px 8px 14px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        border-left: 1px solid rgba(255, 255, 255, 0.2);
        border-top-right-radius: 50px;
        border-bottom-right-radius: 50px;
        transition: all 0.2s ease;
    }

    .pill-btn-main:hover {
        color: #e0e0e0;
    }

    .pill-btn-sub:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<nav class="navbar navbar-expand-lg sticky-top py-3 navbar-custom mb-3 d-print-none">
    <div class="container">
        <a class="navbar-brand" href="<?= url('/') ?>"><img src='<?= url('assets/images/pawpark-logo.svg') ?>'
                width="100" /></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <?php
            $isLoggedIn = isset($_SESSION['user_id']);
            $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] == 0);
            ?>
            <ul class="navbar-nav mx-auto mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('pawhub/bookings') ?>">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('pawhub/my-pets') ?>">My Pets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('pawhub/you') ?>">You</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <div class="pill-group">
                    <?php if ($isLoggedIn): ?>
                        <?php if ($isAdmin): ?>
                            <a href="<?= url('admin') ?>" class="pill-btn-main">Admin Panel</a>
                        <?php else: ?>
                            <a href="<?= url('pawhub') ?>" class="pill-btn-main">My Pawhub</a>
                        <?php endif; ?>
                        <a href="<?= url('logout.php') ?>" class="pill-btn-sub" title="Keluar"><i
                                class="bi bi-box-arrow-right"></i></a>
                    <?php else: ?>
                        <a href="<?= url('masuk') ?>" class="pill-btn-sub text-white">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>