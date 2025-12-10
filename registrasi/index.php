<?php
session_start();
require_once "../core/koneksi.php";
include_once '../core/page.php';
page_start('Buat Akun');
include_once '../komponen/navbar.php';
?>

<?php
// Initialize variables for the form
$success_msg = "";
$error_msg = "";
$fullname = "";
$email    = "";
$password = "";
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Get and sanitize input
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email    = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // 2. Validate input
    if (empty($fullname) || empty($email) || empty($password)) {
        $error_msg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    } else {
        $checkStmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();
        if ($checkStmt->num_rows > 0) {
            $error_msg = "Akun dengan email tersebut sudah terdaftar. Ingin Masuk?";
            $invalid_class = "is-invalid";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $koneksi->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
            $role_id = 1;
            $insertStmt->bind_param("sssi", $fullname, $email, $hashed_password, $role_id);
            if ($insertStmt->execute()) {
                // Clear the form fields on success
                $success_msg = "Akun kamu <strong>$email</strong> berhasil dibuat!";
                $fullname = $email = "";
                $invalid_class = "";
            } else {
                $error_msg = "Database Error: " . $insertStmt->error;
            }
            $insertStmt->close();
        }
        $checkStmt->close();
        // 3. Success Simulation (Database logic would go here)
        // Example: $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Clear the form
        $fullname = $username = $email = "";
    }
}
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">

    <div class="col-12 col-md-10 col-lg-6 col-xl-5">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-5">

                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">Buat Akun Pemilik</h2>
                    <p class="text-secondary">Berikan peliharaan kamu kegembiraan.</p>
                </div>
                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div><?php echo $error_msg; ?></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?php echo $success_msg; ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" novalidate>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control rounded-3 <?php echo $invalid_class; ?>"
                            id="fullname" name="fullname" placeholder="Name"
                            value="<?php echo $fullname; ?>">
                        <label for="fullname">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" class="form-control rounded-3 <?php echo $invalid_class; ?>"
                            id="email" name="email" placeholder="name@example.com"
                            value="<?php echo $email; ?>">
                        <label for="email">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control rounded-3 <?php echo $invalid_class; ?>"
                            id="password" name="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold fs-5 shadow-sm">
                        Mulai
                    </button>
                </form>

                <div class="text-center mt-4 pt-2 border-top">
                    <p class="mb-0 text-secondary">
                        Sudah punya akun?
                        <a href="/masuk" class="link-primary text-decoration-none fw-bold">Masuk</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
page_end();
?>