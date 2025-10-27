<?php
$host = 'localhost:8111';
$user = 'root';
$pass = ''; // <-- Sesuaikan jika password Anda berbeda
$db = 'server_mgmt'; // <-- UBAH NAMA DATABASE DI SINI

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function validatePassword($password)
{
    $errors = [];

    // Aturan 1: Minimal 8 karakter
    if (strlen($password) < 8) {
        $errors[] = "Password harus memiliki minimal 8 karakter.";
    }

    // Aturan 2: Setidaknya satu huruf kapital
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password harus mengandung setidaknya satu huruf kapital.";
    }

    // Aturan 3: Setidaknya satu huruf kecil
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password harus mengandung setidaknya satu huruf kecil.";
    }

    // Aturan 4: Setidaknya satu angka
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password harus mengandung setidaknya satu angka.";
    }

    // Aturan 5: Setidaknya satu karakter khusus
    if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬\.-]/', $password)) {
        $errors[] = "Password harus mengandung setidaknya satu karakter khusus (cth: -,._!@#$).";
    }

    return empty($errors) ? true : implode("<br>", $errors);
}

?>