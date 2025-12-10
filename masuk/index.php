<?php
session_start();
require_once "../core/koneksi.php";
include_once '../core/page.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Initialize variables
$error_msg = "";
$email    = "";
$invalid_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_msg = "Please fill in all fields.";
        $invalid_class = "is-invalid";
    } else {
        // 1. UPDATED QUERY: Check your database column names here!
        // If your database uses 'full_name' instead of 'fullname', change it below.
        $stmt = $koneksi->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            // 2. USE get_result() INSTEAD OF bind_result()
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // 3. Fetch data as an associative array
                $row = $result->fetch_assoc();
                
                // Securely access the password from the array
                $stored_hash = $row['password']; 

                if (password_verify($password, $stored_hash)) {
                    // Login Success
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['fullname'] = $row['fullname'];
                    $_SESSION['email'] = $email;

                    header("Location: ../index.php");
                    exit;
                } else {
                    $error_msg = "Password salah.";
                    $invalid_class = "is-invalid";
                }
            } else {
                $error_msg = "Email tidak ditemukan.";
                $invalid_class = "is-invalid";
            }
            $stmt->close();
        } else {
            // This helps debug if the query itself is wrong (e.g. wrong column name)
            $error_msg = "Database query failed: " . $koneksi->error;
        }
    }
}

page_start('Masuk');
include_once '../komponen/navbar.php';
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">

    <div class="col-12 col-md-10 col-lg-6 col-xl-5">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-5">

                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">Selamat Datang Kembali</h2>
                    <p class="text-secondary">Silakan masuk untuk melanjutkan.</p>
                </div>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div><?php echo $error_msg; ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" novalidate>
                    
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
                        Masuk
                    </button>
                </form>

                <div class="text-center mt-4 pt-2 border-top">
                    <p class="mb-0 text-secondary">
                        Belum punya akun?
                        <a href="register.php" class="link-primary text-decoration-none fw-bold">Daftar</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
page_end();
?>