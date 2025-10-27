<?php
session_start();
require 'function.php';

$logout_message = '';
if (isset($_SESSION['logout_message'])) {
    $logout_message = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']);
}

// Ambil pesan error dari session jika ada, lalu hapus
$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input untuk memastikan tidak kosong
    if (empty($_POST['user_id']) || empty($_POST['password'])) {
        $_SESSION['login_error'] = "User ID dan Password harus diisi!";
        header("Location: login.php"); // Redirect kembali ke halaman login
        exit;
    } else {
        $user_id = $_POST['user_id'];
        $password = $_POST['password']; // Password asli yang dimasukkan user

        // Gunakan prepared statement untuk mengambil data user berdasarkan user_id
        $stmt = mysqli_prepare($koneksi, "SELECT user_id, password, role FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // 1. Cek apakah user ditemukan
        if (mysqli_num_rows($result) != 1) {
            // Jika user tidak ditemukan, langsung set error dan redirect
            $_SESSION['login_error'] = "Username atau Password salah!";
            header("Location: login.php");
            exit;
        }

        // 2. Jika user ditemukan, ambil data dan verifikasi password
        $user = mysqli_fetch_assoc($result);
        $db_password_hash = $user['password'];

        // Cek apakah hash adalah hash modern (dibuat oleh password_hash)
        $is_modern_hash = password_get_info($db_password_hash)['algoName'] !== 'unknown';

        $password_matches = false;
        if ($is_modern_hash) {
            // Jika hash modern, verifikasi dengan password_verify
            $password_matches = password_verify($password, $db_password_hash);
        } else {
            // Jika hash lama (asumsi MD5), verifikasi dengan MD5 dan rehash jika cocok
            if (md5($password) === $db_password_hash) {
                $password_matches = true;
                // Segera update hash ke format modern
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt_rehash = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt_rehash, "ss", $new_hash, $user_id);
                mysqli_stmt_execute($stmt_rehash);
            }
        }

        // 3. Jika password cocok, lanjutkan ke login sukses
        if ($password_matches) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['user_id'];

            // Arahkan pengguna berdasarkan perannya
            if ($user['role'] === 'admin') {
                header("Location: index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }

        // 4. Jika sampai sini, berarti password salah. Set error dan redirect.
        $_SESSION['login_error'] = "Username atau Password salah!";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOMENT | Login</title>
    <link rel="icon" href="img/logo1.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... (CSS Anda yang sudah ada, tidak perlu diubah) ... */
        :root {
            /* Warna gradien background utama (Sesuai contoh) */
            --primary-color: #0d6efd; /* Biru agak terang */
            --dark-bg: #0038de;       /* Biru lebih gelap */

            /* Warna untuk kartu login glassmorphism */
            --login-card-bg: rgba(255, 255, 255, 0.15); /* Lebih terang & transparan */
            --login-card-border: rgba(255, 255, 255, 0.25);
            --login-input-bg: rgba(255, 255, 255, 0.9); /* Input putih solid */
            --login-input-border: #ced4da;
            --login-input-text: #495057; /* Teks input gelap */
            --login-placeholder-color: #6c757d; /* Placeholder abu-abu */
            --login-title-color: #495057; /* Judul kanan gelap */
            --login-button-start: #6a11cb; /* Gradien tombol ungu-biru */
            --login-button-end: #2575fc;
            --login-left-text: #e0e0e0; /* Teks di kiri terang */
            --login-left-bg: rgba(0, 0, 0, 0.1); /* Overlay gelap halus di kiri */
            --login-left-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-color), var(--dark-bg));
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            overflow-x: hidden;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            width: 100%;
            padding: 2rem 1rem;
        }

        /* Kartu Login Glassmorphism */
        .login-card-glass {
            border-radius: 1.5rem; /* Lebih rounded */
            overflow: hidden;
            background: var(--login-card-bg);
            backdrop-filter: blur(12px) saturate(160%);
            -webkit-backdrop-filter: blur(12px) saturate(160%);
            border: 1px solid var(--login-card-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            max-width: 750px; /* Lebar disesuaikan (lebih kecil) */
            width: 100%;
            animation: fadeSlideUp 1s ease-out;
            transition: box-shadow 0.3s ease;
        }
         @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card-glass:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }

        /* Kolom Kiri (Branding) */
        .login-left-column {
            background: var(--login-left-bg); /* Sedikit overlay gelap di kiri */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            flex-direction: column;
            text-align: center;
            border-right: 1px solid var(--login-left-border); /* Garis pemisah halus */
        }

        .card-logo {
            height: 120px; /* Ukuran logo */
            margin-bottom: 1rem;
            animation: floatLogo 4s ease-in-out infinite alternate;
        }
         @keyframes floatLogo {
            from { transform: translateY(-5px); }
            to { transform: translateY(5px); }
        }

        .login-left-column h5 {
             color: var(--login-left-text);
             font-weight: 600;
             margin-bottom: 0.3rem;
        }
         .login-left-column p {
             color: rgba(255, 255, 255, 0.7);
             font-size: 0.85rem;
         }

        /* Kolom Kanan (Form) */
        .login-right-column {
             color: var(--login-title-color); /* Teks default gelap */
        }

        .login-right-column .card-body {
             padding: 3rem; /* Padding lebih besar */
        }
         @media (max-width: 991.98px) { /* Di bawah lg */
            .login-card-glass {
                max-width: 500px; /* Kecilkan kartu saat layout tumpuk */
            }
            .login-left-column {
                 padding: 2rem;
                 border-right: none; /* Hapus border saat layout tumpuk */
                 border-bottom: 1px solid var(--login-left-border); /* Tambah border bawah */
            }
             .login-right-column .card-body {
                 padding: 2.5rem;
             }
         }
         @media (max-width: 767.98px) { /* Di bawah md */
              .login-left-column {
                  padding: 2rem 1rem;
              }
             .login-right-column .card-body {
                 padding: 2rem 1.5rem;
             }
              .login-right-column h3 {
                  font-size: 1.7rem; /* Kecilkan judul */
                  margin-bottom: 1.5rem;
              }
              .card-logo {
                  height: 70px; /* Kecilkan logo sedikit di layar kecil */
              }
         }
         @media (max-width: 575.98px) { /* Di bawah sm */
            .login-container {
                padding: 1rem 0.5rem;
            }
            .login-card-glass {
                max-width: 95%;
            }
            .login-right-column .card-body {
                padding: 1.5rem 1rem;
            }
             .login-right-column h3 {
                font-size: 1.5rem;
            }
             .btn-login-gradient {
                padding: 0.7rem;
                font-size: 0.9rem;
            }
            .input-group-login .form-control,
            .input-group-login .input-icon,
            .password-toggle-login {
                font-size: 0.85rem;
            }
         }

        .login-right-column h3 {
            color: var(--login-title-color); /* Ubah ke warna gelap */
            margin-bottom: 2rem;
            font-weight: 700;
        }

        /* Sembunyikan label, gunakan placeholder */
        .login-right-column .form-label {
           display: none;
        }

        /* Styling Input Field seperti contoh */
        .input-group-login {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .input-group-login .form-control {
            background-color: var(--login-input-bg);
            border: 1px solid var(--login-input-border);
            color: var(--login-input-text);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem 0.75rem 2.5rem; /* Padding kiri untuk ikon */
            font-size: 0.9rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
         .input-group-login .form-control:focus {
             border-color: #86b7fe;
             box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
             background-color: #fff;
         }
        .input-group-login .form-control::placeholder {
            color: var(--login-placeholder-color);
        }
        /* Ikon di dalam input */
        .input-group-login .input-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--login-placeholder-color);
            z-index: 3;
            font-size: 0.9rem;
        }

         /* Tombol Toggle Password */
        .password-toggle-login {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--login-placeholder-color);
            cursor: pointer;
            padding: 0;
            z-index: 3;
        }
        .password-toggle-login:focus { outline: none; box-shadow: none; }
        .password-toggle-login:hover { color: #495057; }

        /* Tombol Login Gradien */
        .btn-login-gradient {
            background-image: linear-gradient(to right, var(--login-button-start) 0%, var(--login-button-end) 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.4s ease;
            margin-top: 1rem;
            background-size: 200% auto;
        }

        .btn-login-gradient:hover {
            color: white;
            background-position: right center; /* Pindah gradien saat hover */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        /* Alert styling */
        .alert {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            border: none; /* Hapus border default */
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
         .alert-success {
            background: rgba(40, 167, 69, 0.1);
             color: #198754;
         }

        /* Footer */
        footer {
            width: 100%;
            background: transparent;
            color: rgba(255, 255, 255, 0.6);
            padding: 1rem 0;
            text-align: center;
            font-size: 0.8rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card-glass shadow">
            <div class="row g-0">
                <div class="col-lg-5 login-left-column d-none d-lg-flex">
                    <div class="branding-content text-center">
                        <img src="img/logo1.png" alt="MOMENT Logo" class="card-logo mb-3">
                        <h5 class="text-white mb-2">MOMENT</h5>
                        <p class="text-white-50 small">Monitoring System License & Warranty</p>
                    </div>
                </div>

                <div class="col-lg-7 login-right-column">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4 fw-bold text-white">Login Now</h3>

                         <?php if ($logout_message): ?>
                            <div class="alert alert-success mb-4"><?= htmlspecialchars($logout_message) ?></div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="input-group-login mb-3">
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="user_id" id="user_id" placeholder="Username" required autocomplete="off">
                                <label for="user_id" class="form-label visually-hidden">User ID</label>
                            </div>

                            <div class="input-group-login mb-4">
                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required autocomplete="new-password">
                                <button type="button" class="password-toggle-login" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <label for="password" class="form-label visually-hidden">Password</label>
                            </div>

                            <button type="submit" class="btn btn-login-gradient w-100 mt-3">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <footer class="py-3 mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-center small">
                <div class="text-white-50">Copyright &copy; ODD BPJS Ketenagakerjaan <?= date('Y') ?></div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>