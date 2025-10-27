<?php
require 'function.php';

session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="hardware_dc_' . date('Ymd') . '.xls"');
header('Cache-Control: max-age=0');

$result = mysqli_query($koneksi, "SELECT * FROM hardware WHERE lokasi = 'DC' ORDER BY nama_perangkat ASC");

echo "Hardware DC - BPJS Ketenagakerjaan\n";
echo "Tanggal Export: " . date('d/m/Y H:i:s') . "\n\n";

echo "Type\tNama\tIP Console\tTahun\tSerial Number\tRack\tOS\tNo Inventaris\tFungsi\tStatus\tKeterangan\tEnd of Support\tVendor Support\tKontrak Start\tKontrak Finish\tBAST\tPartner\n";

while ($row = mysqli_fetch_assoc($result)) {
    echo htmlspecialchars($row['type_perangkat']) . "\t";
    echo htmlspecialchars($row['nama_perangkat']) . "\t";
    echo htmlspecialchars($row['ip_console']) . "\t";
    echo htmlspecialchars($row['tahun_pengadaan']) . "\t";
    echo htmlspecialchars($row['serial_number']) . "\t";
    echo htmlspecialchars($row['rack']) . "\t";
    echo htmlspecialchars($row['operating_system']) . "\t";
    echo htmlspecialchars($row['no_iventaris']) . "\t";
    echo htmlspecialchars($row['fungsi']) . "\t";
    echo htmlspecialchars($row['status']) . "\t";
    echo htmlspecialchars($row['keterangan']) . "\t";
    echo htmlspecialchars($row['end_of_support']) . "\t";
    echo htmlspecialchars($row['support_vendor']) . "\t";
    echo htmlspecialchars($row['kontrak_support_start']) . "\t";
    echo htmlspecialchars($row['kontrak_support_finish']) . "\t";
    echo htmlspecialchars($row['tanggal_bast']) . "\t";
    echo htmlspecialchars($row['partner']) . "\n";
}
?>