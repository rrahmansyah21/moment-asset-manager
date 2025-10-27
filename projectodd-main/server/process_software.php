<?php
require 'function.php';
function emptyToNull($value)
{
    return ($value === '') ? null : $value;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_software'])) {
        $kode_subs = $_POST['kode_subs'];
        $no_subs = $_POST['no_subs'];
        $nama_subs = $_POST['nama_subs'];
        $tanggal_awal = $_POST['tanggal_awal'];
        $tanggal_berakhir = $_POST['tanggal_berakhir'];
        $lokasi = $_POST['lokasi'];
        $partner = emptyToNull($_POST['partner']);
        $keterangan = emptyToNull($_POST['keterangan']);

        if (empty($kode_subs) || empty($no_subs) || empty($nama_subs) || empty($tanggal_awal) || empty($tanggal_berakhir) || empty($lokasi)) {
            header("Location: software_subscription.php?add=error&message=" . urlencode("Data wajib tidak boleh kosong."));
            exit();
        }

        $check_duplicate = "SELECT KODE_SUBS FROM software WHERE KODE_SUBS = ?";
        $stmt = mysqli_prepare($koneksi, $check_duplicate);
        mysqli_stmt_bind_param($stmt, 's', $kode_subs);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("Location: software_subscription.php?add=error&message=" . urlencode("Kode subscription '$kode_subs' sudah terdaftar."));
            exit();
        }

        $data = [
            'KODE_SUBS' => $kode_subs,
            'NO_SUBS' => $no_subs,
            'NAMA_SUBS' => $nama_subs,
            'KETERANGAN' => $keterangan,
            'TANGGAL_AWAL' => $tanggal_awal,
            'TANGGAL_BERAKHIR' => $tanggal_berakhir,
            'lokasi' => $lokasi,
            'partner' => $partner
        ];

        $columns = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $columnsStr = implode(', ', array_map(fn($c) => "`$c`", $columns));

        $query = "INSERT INTO software ($columnsStr) VALUES ($placeholders)";
        $stmt = mysqli_prepare($koneksi, $query);

        // Tipe data: s=string. Semua kolom di sini adalah string.
        $types = str_repeat('s', count($data));
        $values = array_values($data);

        mysqli_stmt_bind_param($stmt, $types, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: software_subscription.php?add=success");
        } else {
            header("Location: software_subscription.php?add=error&message=" . urlencode(mysqli_stmt_error($stmt)));
        }
        exit();
    }

    if (isset($_POST['edit_software'])) {
        $kode_subs = $_POST['kode_subs'];
        $no_subs = $_POST['no_subs'];
        $nama_subs = $_POST['nama_subs'];
        $tanggal_awal = $_POST['tanggal_awal'];
        $tanggal_berakhir = $_POST['tanggal_berakhir'];
        $partner = emptyToNull($_POST['partner']);
        $keterangan = emptyToNull($_POST['keterangan']);

        if (empty($kode_subs)) {
            header("Location: software_subscription.php?edit=error&message=" . urlencode("Kode subscription tidak ditemukan."));
            exit();
        }

        $fields = [
            'NO_SUBS' => emptyToNull($no_subs),
            'NAMA_SUBS' => emptyToNull($nama_subs),
            'KETERANGAN' => $keterangan,
            'TANGGAL_AWAL' => emptyToNull($tanggal_awal),
            'TANGGAL_BERAKHIR' => emptyToNull($tanggal_berakhir),
            'partner' => $partner
        ];

        $updates = [];
        $values = [];
        $types = '';

        foreach ($fields as $key => $value) {
            $updates[] = "`$key` = ?";
            $values[] = $value;
            $types .= 's'; // Semua tipe data dianggap string
        }
        $values[] = $kode_subs; // Tambahkan kode_subs untuk WHERE clause
        $types .= 's';

        $setClause = implode(', ', $updates);
        $query = "UPDATE software SET $setClause WHERE KODE_SUBS = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: software_subscription.php?edit=success");
        } else {
            header("Location: software_subscription.php?edit=error&message=" . urlencode(mysqli_stmt_error($stmt)));
        }
        exit();
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Gunakan prepared statement untuk keamanan
    $stmt_check = mysqli_prepare($koneksi, "SELECT KODE_SUBS FROM software WHERE KODE_SUBS = ?");
    mysqli_stmt_bind_param($stmt_check, 's', $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        header("Location: software_subscription.php?delete=error&message=" . urlencode("Data tidak ditemukan."));
        exit();
    }

    $stmt_delete = mysqli_prepare($koneksi, "DELETE FROM software WHERE KODE_SUBS = ?");
    mysqli_stmt_bind_param($stmt_delete, 's', $id);
    if (mysqli_stmt_execute($stmt_delete)) {
        header("Location: software_subscription.php?delete=success");
    } else {
        header("Location: software_subscription.php?delete=error&message=" . urlencode(mysqli_stmt_error($stmt_delete)));
    }
    exit();
}
?>