<?php
require 'function.php';

session_start();

// Redirect jika belum login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Blokir akses jika bukan user
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$success = '';
$error = '';

// Penanganan form ganti password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $user_id = $_SESSION['user_id'];

        // Validasi input
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "Semua field harus diisi!";
        } elseif ($new_password !== $confirm_password) {
            $error = "Password baru dan konfirmasi password tidak cocok!";
        } else {
            // Validasi kompleksitas password baru
            $password_validation = validatePassword($new_password);
            if ($password_validation !== true) {
                $error = $password_validation;
            } else {
                // Cek password saat ini menggunakan password_verify()
                $stmt_check = mysqli_prepare($koneksi, "SELECT password FROM users WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt_check, "s", $user_id);
                mysqli_stmt_execute($stmt_check);
                $result = mysqli_stmt_get_result($stmt_check);
                $user = mysqli_fetch_assoc($result);

                $password_matches = false;
                if ($user) {
                    $db_password_hash = $user['password'];
                    // Cek apakah hash modern atau lama (MD5)
                    if (password_get_info($db_password_hash)['algoName'] !== 'unknown') {
                        $password_matches = password_verify($current_password, $db_password_hash);
                    } else {
                        $password_matches = (md5($current_password) === $db_password_hash);
                    }
                }

                if (!$password_matches) {
                    $error = "Password saat ini salah!";
                } else {
                    // Update password dengan hash baru yang aman
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt_update = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE user_id = ?");
                    mysqli_stmt_bind_param($stmt_update, "ss", $new_hashed_password, $user_id);
                    $update = mysqli_stmt_execute($stmt_update);

                    if ($update) {
                        // Password berhasil diubah, hancurkan sesi dan arahkan ke login
                        session_unset();
                        session_destroy();
                        session_start(); // Mulai sesi baru untuk pesan notifikasi
                        $_SESSION['logout_message'] = "Password berhasil diubah. Silakan login kembali.";
                        header("Location: logout.php"); // Arahkan ke logout.php untuk sesi yang bersih
                        exit; // Pastikan script berhenti setelah redirect
                    } else {
                        $error = "Gagal mengubah password.";
                    }
                }
            }
        }
    }
}

// Ambil pesan logout dari session jika ada
if (isset($_SESSION['logout_message'])) {
    $success = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']);
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$current_page = 'user.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="img/logo1.png" />
    <title>Moment - User</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .password-field .position-relative {
            position: relative;
        }

        .toggle-password {
            z-index: 10;
            color: #6c757d;
            cursor: pointer;
        }

        .user-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 10px;
            overflow: hidden;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .user-badge {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            color: white;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <a class="navbar-brand ps-3" href="index.php">
            <img src="img/logo2.png" alt="Logo" height="50">
        </a>
        <button class="btn btn-link btn-sm me-4 text-white" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i> <?= htmlspecialchars($username) ?> | <?= strtoupper($role) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseHardware" aria-expanded="false" aria-controls="collapseHardware">
                            <i class="fas fa-server me-2"></i>Hardware
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseHardware" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="hardware_dc.php">DC</a>
                                <a class="nav-link" href="hardware_drc.php">DRC</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRack"
                            aria-expanded="false" aria-controls="collapseRack">
                            <i class="fas fa-network-wired me-2"></i>Rack
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseRack" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="rack_dc.php">DC</a>
                                <a class="nav-link" href="rack_drc.php">DRC</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseSoftware" aria-expanded="false" aria-controls="collapseSoftware">
                            <i class="fas fa-id-badge me-2"></i>Software
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseSoftware" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="software_subscription.php">Subscription</a>
                                <a class="nav-link" href="software_perpetual.php">Perpetual</a>
                            </nav>
                        </div>
                        <?php if ($role === 'admin'): ?>
                            <a class="nav-link" href="admin.php">
                                <i class="fas fa-user-cog me-2"></i>Admin
                            </a>
                        <?php elseif ($role === 'user'): ?>
                            <a class="nav-link active" href="user.php">
                                <i class="fas fa-user me-2"></i>User
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0"><i class="fas fa-user me-2"></i>User Dashboard</h1>
                        <span class="badge user-badge fs-6 p-2"><?= strtoupper($role) ?></span>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= $success ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm mb-4 user-card">
                                <div class="card-header bg-primary text-white py-3">
                                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Ganti Password</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="mb-3 password-field">
                                            <label class="form-label">Password Saat Ini</label>
                                            <div class="position-relative">
                                                <input type="password" name="current_password" id="currentPasswordField"
                                                    class="form-control pe-5" placeholder="Masukkan password saat ini"
                                                    required>
                                                <span
                                                    class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                                    onclick="togglePassword('currentPasswordField', 'currentToggleIcon')">
                                                    <i class="fas fa-eye" id="currentToggleIcon"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-3 password-field">
                                            <label class="form-label">Password Baru</label>
                                            <div class="position-relative">
                                                <input type="password" name="new_password" id="newPasswordField"
                                                    class="form-control pe-5" placeholder="Masukkan password baru"
                                                    required>
                                                <span
                                                    class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                                    onclick="togglePassword('newPasswordField', 'newToggleIcon')">
                                                    <i class="fas fa-eye" id="newToggleIcon"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-4 password-field">
                                            <label class="form-label">Konfirmasi Password Baru</label>
                                            <div class="position-relative">
                                                <input type="password" name="confirm_password" id="confirmPasswordField"
                                                    class="form-control pe-5" placeholder="Konfirmasi password baru"
                                                    required>
                                                <span
                                                    class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                                    onclick="togglePassword('confirmPasswordField', 'confirmToggleIcon')">
                                                    <i class="fas fa-eye" id="confirmToggleIcon"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <button type="submit" name="change_password" class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-sync-alt me-2"></i>Ganti Password
                                        </button>
                                    </form>
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Pastikan password baru kuat dan mudah diingat
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 mt-auto" style="background-color: transparent !important;">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; BPJS Ketenagakerjaan <?= date('Y') ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Fungsi untuk toggle password visibility
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>

</html>