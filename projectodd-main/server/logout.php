<?php
session_start();

// Simpan pesan logout jika ada, sebelum session dihancurkan
$logout_message = isset($_SESSION['logout_message']) ? $_SESSION['logout_message'] : null;

session_unset();
session_destroy();

session_start(); // Mulai sesi baru untuk membawa pesan
if ($logout_message) {
    $_SESSION['logout_message'] = $logout_message;
}

header("Location: login.php?logout=success");
exit;