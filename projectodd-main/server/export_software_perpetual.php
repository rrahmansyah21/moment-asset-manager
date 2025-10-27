<?php
require 'function.php';

session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="software_perpetual_' . date('Ymd') . '.xls"');
header('Cache-Control: max-age=0');

$result = mysqli_query($koneksi, "SELECT * FROM software WHERE lokasi = 'Perpetual' ORDER BY TANGGAL_BERAKHIR DESC");

echo "Software Perpetual - BPJS Ketenagakerjaan\n";
echo "Tanggal Export: " . date('d/m/Y H:i:s') . "\n\n";

echo "Kode\tNo. Subscription\tNama Software\tKeterangan\tTanggal Awal\tTanggal Berakhir\tStatus\tPartner\n";

while ($row = mysqli_fetch_assoc($result)) {
    $today = date('Y-m-d H:i:s');
    $berakhir = $row['TANGGAL_BERAKHIR'];

    if ($berakhir > $today) {
        $status = 'Active';
    } else if (date('Y-m-d', strtotime($berakhir)) == date('Y-m-d')) {
        $status = 'Expiring Today';
    } else {
        $status = 'Expired';
    }

    echo htmlspecialchars($row['KODE_SUBS']) . "\t";
    echo htmlspecialchars($row['NO_SUBS']) . "\t";
    echo htmlspecialchars($row['NAMA_SUBS']) . "\t";
    echo htmlspecialchars($row['KETERANGAN']) . "\t";
    echo date('d M Y H:i', strtotime($row['TANGGAL_AWAL'])) . "\t";
    echo date('d M Y H:i', strtotime($row['TANGGAL_BERAKHIR'])) . "\t";
    echo $status . "\t";
    echo htmlspecialchars($row['partner']) . "\n";
}
?>