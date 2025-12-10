<div class="container pb-5">

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-lg-5">

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div><?php echo $success_msg; ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <div><?php echo $error_msg; ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <h5 class="mb-4 text-primary fw-bold text-uppercase small letter-spacing-1">Informasi Pemilik</h5>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="fullName" name="fullname" placeholder="Name"
                                value="<?php echo htmlspecialchars($fullname); ?>">
                            <label for="fullName">Nama Lengkap</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="emailAddress" name="email"
                                placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>">
                            <label for="emailAddress">Email</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password">
                            <label for="password">Password (Biarkan kosong jika tidak ingin mengganti)</label>
                        </div>
                    </div>
                </div>

                <hr class="my-5 opacity-10">

                <h5 class="mb-4 text-secondary fw-bold text-uppercase small letter-spacing-1">Statistik Akun</h5>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control bg-light text-muted" id="petCount"
                                value="<?php echo $pet_count; ?>" readonly style="cursor: not-allowed;">
                            <label for="petCount">Jumlah Peliharaan</label>
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control bg-light text-muted" id="dateCreated"
                                value="<?php echo $date_created; ?>" readonly style="cursor: not-allowed;">
                            <label for="dateCreated">Tanggal Daftar</label>
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                <i class="bi bi-calendar3"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <button class="btn btn-primary px-5 py-2 fw-medium shadow-sm" type="submit">Perbarui Profil</button>
                </div>

            </form>
        </div>
    </div>
</div>