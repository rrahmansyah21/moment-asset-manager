
<?php
require 'function.php';

session_start();

// Redirect jika belum login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Blokir akses jika bukan admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$success = '';
$error = '';

// Penanganan form tambah user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_user'])) {
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Validasi kompleksitas password
        $password_validation = validatePassword($password);
        if ($password_validation !== true) {
            $_SESSION['error'] = $password_validation;
            header("Location: admin.php");
            exit;
        }

        // Cek apakah user_id sudah dipakai
        $stmt_check = mysqli_prepare($koneksi, "SELECT user_id FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $user_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $_SESSION['error'] = "Username sudah digunakan.";
        } else {
            // Gunakan password_hash() untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO users (user_id, password, role) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_insert, "sss", $user_id, $hashed_password, $role);
            if (mysqli_stmt_execute($stmt_insert)) {
                $_SESSION['success'] = "User berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan user: " . mysqli_stmt_error($stmt_insert);
            }
        }
        header("Location: admin.php");
        exit;
    }

    if (isset($_POST['delete_user'])) {
        $delete_id = $_POST['delete_id'];
        $stmt_delete = mysqli_prepare($koneksi, "DELETE FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt_delete, "s", $delete_id);
        if (mysqli_stmt_execute($stmt_delete)) {
            $_SESSION['success'] = "User berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus user: " . mysqli_stmt_error($stmt_delete);
        }
        header("Location: admin.php");
        exit;
    }

    // Penanganan ganti password admin
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $user_id = $_SESSION['user_id'];

        // Validasi input
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = "Semua field harus diisi!";
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Password baru dan konfirmasi password tidak cocok!";
        } elseif (validatePassword($new_password) !== true) {
            $_SESSION['error'] = validatePassword($new_password);
        } else {
            // Ambil hash password dari DB
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
                $_SESSION['error'] = "Password saat ini salah!";
            } else {
                // Update password dengan hash baru yang aman
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt_update = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt_update, "ss", $new_hashed_password, $user_id);

                if (mysqli_stmt_execute($stmt_update)) {
                    $_SESSION['logout_message'] = "Password berhasil diubah. Silakan login kembali.";
                    header("Location: logout.php"); // Logout agar user login ulang
                    exit;
                } else {
                    $_SESSION['error'] = "Gagal mengubah password: " . mysqli_stmt_error($stmt_update);
                }
            }
        }
        header("Location: admin.php");
    }
}

// Ambil pesan logout dari session jika ada
if (isset($_SESSION['logout_message'])) {
    $success = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']);
}

// Ambil pesan dari session jika ada
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Ambil daftar user untuk ditampilkan
$users = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role, user_id");
$role = $_SESSION['role'];
$username = $_SESSION['username'];
$current_page = 'admin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="img/logo1.png" />
    <title>Moment - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .legend-custom {
            margin-top: 10px;
            font-size: 0.85rem;
        }

        .legend-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .card-footer a:hover {
            text-decoration: underline;
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

        .admin-badge {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            color: white;
        }

        .user-badge {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            color: white;
        }

        .password-field .position-relative {
            position: relative;
        }

        .toggle-password {
            z-index: 10;
            color: #6c757d;
            cursor: pointer;
        }

        .action-icon {
            transition: all 0.3s;
        }

        .action-icon:hover {
            transform: scale(1.2);
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid #0d6efd;
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
                            <a class="nav-link active" href="admin.php">
                                <i class="fas fa-user-cog me-2"></i>Admin
                            </a>
                        <?php elseif ($role === 'user'): ?>
                            <a class="nav-link" href="user.php">
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
                        <h1 class="mb-0"><i class="fas fa-user-cog me-2"></i>Admin Dashboard</h1>
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

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm mb-4 user-card">
                                <div class="card-header bg-primary text-white py-3">
                                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tambah User Baru</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="user_id" class="form-control"
                                                placeholder="Masukkan ID User" required>
                                        </div>
                                        <div class="mb-3 password-field">
                                            <label class="form-label">Password</label>
                                            <div class="position-relative">
                                                <input type="password" name="password" id="passwordField"
                                                    class="form-control pe-5" placeholder="Masukkan Password" required>
                                                <span
                                                    class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                                    onclick="togglePassword('passwordField', 'toggleIcon')"
                                                    style="cursor: pointer;">
                                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                                </span>
                                            </div>
                                            <small class="form-text text-muted">
                                                Password harus min. 8 karakter, mengandung huruf kapital, kecil, angka, dan
                                                simbol (cth: -,._!@#$).
                                            </small>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select" required>
                                                <option value="user">User</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="add_user" class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-plus me-2"></i>Tambah User
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-4 user-card">
                                <div class="card-header bg-warning text-dark py-3">
                                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Ganti Password </h5>
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
                                            <small class="form-text text-muted">
                                                Password harus min. 8 karakter, mengandung huruf kapital, kecil, angka, dan
                                                simbol (cth: -,._!@#$).
                                            </small>
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
                                        <button type="submit" name="change_password" class="btn btn-warning w-100 py-2">
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

                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-info text-white py-3">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Pengguna</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Role</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($user = mysqli_fetch_assoc($users)): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-user-circle me-2 fs-4"></i>
                                                                <strong><?= htmlspecialchars($user['user_id']) ?></strong>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge <?= $user['role'] === 'admin' ? 'admin-badge' : 'user-badge' ?>">
                                                                <?= strtoupper($user['role']) ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <form method="POST" action="" class="d-inline">
                                                                <input type="hidden" name="delete_id"
                                                                    value="<?= $user['user_id'] ?>">
                                                                <button type="submit" name="delete_user"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                                                    <?= ($user['user_id'] === $_SESSION['user_id']) ? 'disabled' : '' ?>>
                                                                    <i class="fas fa-trash-alt action-icon"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Anda tidak dapat menghapus akun sendiri
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
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
