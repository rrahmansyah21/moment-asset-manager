<?php
require 'function.php';
session_start();

// Redirect jika belum login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Check if the required POST parameters are set
if (isset($_POST['status'], $_POST['type'])) {
    $status = $_POST['status'];
    $type = $_POST['type'];

    // Set table, column, and columns to display
    $table = 'hardware';
    $column = 'kontrak_support_finish';
    $columns = [
        'ID' => 'id',
        'Nama Hardware' => 'nama_perangkat',
        'Support Finish' => 'kontrak_support_finish',
        'Vendor' => 'vendor'
    ];

    // Filter berdasarkan lokasi
    $where_lokasi = "lokasi = '" . mysqli_real_escape_string($koneksi, $type) . "'";

    // Bangun query berdasarkan status
    $where_status = "";
    $now = date('Y-m-d');
    if ($status === 'active') {
        $where_status = "AND $column > DATE_ADD('$now', INTERVAL 6 MONTH)";
    } elseif ($status === 'warning') {
        $where_status = "AND $column <= DATE_ADD('$now', INTERVAL 6 MONTH) AND $column > DATE_ADD('$now', INTERVAL 3 MONTH)";
    } elseif ($status === 'critical') {
        $where_status = "AND $column <= DATE_ADD('$now', INTERVAL 3 MONTH) AND $column IS NOT NULL AND $column != '0000-00-00'";
    } elseif ($status === 'nodata') {
        $where_status = "AND ($column IS NULL OR $column = '0000-00-00')";
    }

    // Eksekusi query
    $query = "SELECT * FROM $table WHERE $where_lokasi $where_status ORDER BY id DESC";
    $result = mysqli_query($koneksi, $query);

    // Tampilkan hasil
    if ($result && mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive"><table class="table table-bordered table-striped">';
        echo '<thead><tr>';
        foreach ($columns as $colName => $colField) {
            echo '<th>' . htmlspecialchars($colName) . '</th>';
        }
        echo '</tr></thead><tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            foreach ($columns as $colField) {
                $value = isset($row[$colField]) ? $row[$colField] : '-';
                // Format tanggal jika kolomnya adalah tanggal
                if ($colField === 'kontrak_support_finish' && $value !== '-') {
                    $value = date('d/m/Y', strtotime($value));
                }
                echo '<td>' . htmlspecialchars($value) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    } else {
        echo '<div class="alert alert-info">Tidak ada data hardware yang ditemukan untuk kriteria yang dipilih.</div>';
    }
} else {
    echo '<div class="alert alert-danger">Permintaan tidak valid.</div>';
}
?>