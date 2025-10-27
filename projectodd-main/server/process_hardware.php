<?php
require 'function.php';

function emptyToNull($value)
{
    return ($value === '') ? null : $value;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_hardware'])) {
        $type_perangkat = $_POST['type_perangkat'];
        $nama_perangkat = $_POST['nama_perangkat'];
        $ip_console = emptyToNull($_POST['ip_console']);
        $tahun_pengadaan = emptyToNull($_POST['tahun_pengadaan']);
        $serial_number = emptyToNull($_POST['serial_number']);
        $rack = emptyToNull($_POST['rack']);
        $u_position = emptyToNull($_POST['u_position']);
        $u_height = emptyToNull($_POST['u_height']);
        $operating_system = emptyToNull($_POST['operating_system']);
        $no_iventaris = emptyToNull($_POST['no_iventaris']);
        $fungsi = emptyToNull($_POST['fungsi']);
        $status = emptyToNull($_POST['status']);
        $keterangan = emptyToNull($_POST['keterangan']);
        $end_of_support = emptyToNull($_POST['end_of_support']);
        $support_vendor = emptyToNull($_POST['support_vendor']);
        $kontrak_support_start = emptyToNull($_POST['kontrak_support_start']);
        $kontrak_support_finish = emptyToNull($_POST['kontrak_support_finish']);
        $tanggal_bast = emptyToNull($_POST['tanggal_bast']);
        $lokasi = $_POST['lokasi'];
        $backup_type = emptyToNull($_POST['backup_type']);
        $ups_status = emptyToNull($_POST['ups_status']);
        $partner = emptyToNull($_POST['partner']);

        if (empty($type_perangkat) || empty($nama_perangkat) || empty($lokasi)) {
            header("Location: hardware_" . strtolower($lokasi) . ".php?add=error&message=" . urlencode("Data wajib tidak boleh kosong."));
            exit();
        }

        // Cek duplikat serial number
        if (!empty($serial_number)) {
            $check_duplicate = "SELECT serial_number FROM hardware WHERE serial_number = ?";
            $stmt = mysqli_prepare($koneksi, $check_duplicate);
            mysqli_stmt_bind_param($stmt, 's', $serial_number);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                header("Location: hardware_" . strtolower($lokasi) . ".php?add=error&message=" . urlencode("Serial number '$serial_number' sudah terdaftar."));
                exit();
            }
        }

        // Validasi posisi rack jika rack, u_position, dan u_height diisi
        if (!empty($rack) && !empty($u_position) && !empty($u_height)) {
            $u_position = (int) $u_position;
            $u_height = (int) $u_height;

            // Cek batas rack (1-42)
            if ($u_position < 1 || $u_position > 42 || $u_height < 1 || $u_height > 42) {
                header("Location: hardware_" . strtolower($lokasi) . ".php?add=error&message=" . urlencode("Posisi U harus antara 1-42."));
                exit();
            }

            $end_position = $u_position + $u_height - 1;
            if ($end_position > 42) {
                header("Location: hardware_" . strtolower($lokasi) . ".php?add=error&message=" . urlencode("Posisi akhir U ($end_position) melebihi batas rack 42U."));
                exit();
            }

            // Cek konflik dengan hardware lain di lokasi dan rack yang sama
            $check_conflict = "SELECT nama_perangkat, u_position, u_height FROM hardware
                              WHERE lokasi = ? AND rack = ? AND u_position IS NOT NULL AND u_height IS NOT NULL";
            $stmt = mysqli_prepare($koneksi, $check_conflict);
            mysqli_stmt_bind_param($stmt, 'ss', $lokasi, $rack);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $existing_start = (int) $row['u_position'];
                $existing_height = (int) $row['u_height'];
                $existing_end = $existing_start + $existing_height - 1;

                // Cek overlap
                if (($u_position <= $existing_end && $end_position >= $existing_start)) {
                    header("Location: hardware_" . strtolower($lokasi) . ".php?add=error&message=" . urlencode("Posisi U {$u_position}-{$end_position} bertabrakan dengan '{$row['nama_perangkat']}' di posisi {$existing_start}-{$existing_end}."));
                    exit();
                }
            }
        }

        $data = [
            'type_perangkat' => $type_perangkat,
            'nama_perangkat' => $nama_perangkat,
            'ip_console' => $ip_console,
            'tahun_pengadaan' => $tahun_pengadaan,
            'serial_number' => $serial_number,
            'rack' => $rack,
            'u_position' => $u_position,
            'u_height' => $u_height,
            'operating_system' => $operating_system,
            'no_iventaris' => $no_iventaris,
            'fungsi' => $fungsi,
            'status' => $status,
            'keterangan' => $keterangan,
            'end_of_support' => $end_of_support,
            'support_vendor' => $support_vendor,
            'kontrak_support_start' => $kontrak_support_start,
            'kontrak_support_finish' => $kontrak_support_finish,
            'tanggal_bast' => $tanggal_bast,
            'lokasi' => $lokasi,
            'backup_type' => $backup_type,
            'ups_status' => $ups_status,
            'partner' => $partner
        ];

        $columns = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $columnsStr = implode(', ', array_map(fn($c) => "`$c`", $columns));

        $query = "INSERT INTO hardware ($columnsStr) VALUES ($placeholders)";
        $stmt = mysqli_prepare($koneksi, $query);

        // Tipe data: s=string, i=integer. Sesuaikan jika ada tipe lain.
        $typeMap = [
            'type_perangkat' => 's', 'nama_perangkat' => 's', 'ip_console' => 's', 'tahun_pengadaan' => 'i',
            'serial_number' => 's', 'rack' => 's', 'u_position' => 'i', 'u_height' => 'i', 'operating_system' => 's',
            'no_iventaris' => 's', 'fungsi' => 's', 'status' => 's', 'keterangan' => 's', 'end_of_support' => 's',
            'support_vendor' => 's', 'kontrak_support_start' => 's', 'kontrak_support_finish' => 's',
            'tanggal_bast' => 's', 'lokasi' => 's', 'backup_type' => 's', 'ups_status' => 's', 'partner' => 's'
        ];
        $types = implode('', array_values(array_intersect_key($typeMap, $data)));
        $values = array_values($data);

        mysqli_stmt_bind_param($stmt, $types, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: hardware_" . strtolower($lokasi) . ".php?add=success");
        } else {
            header("Location: hardware_" . strtolower($lokasi) . ".php?add=error&message=" . urlencode(mysqli_stmt_error($stmt)));
        }
        exit();
    }

    if (isset($_POST['edit_hardware'])) {
        // SAMAKAN DENGAN SOFTWARE - GUNAKAN PARAMETER YANG SAMA
        $id = $_POST['id']; // Tetap gunakan id sebagai identifier

        // Validasi required fields sama seperti software
        if (empty($id)) {
            header("Location: hardware_dc.php?edit=error&message=" . urlencode("ID hardware tidak ditemukan."));
            exit();
        }

        // Definisikan semua field seperti software
        $type_perangkat = $_POST['type_perangkat'];
        $nama_perangkat = $_POST['nama_perangkat'];
        $lokasi = $_POST['lokasi'];

        // Validasi required fields
        if (empty($type_perangkat) || empty($nama_perangkat) || empty($lokasi)) {
            header("Location: hardware_" . strtolower($lokasi) . ".php?edit=error&message=" . urlencode("Data wajib tidak boleh kosong."));
            exit();
        }

        // Validasi posisi rack jika rack, u_position, dan u_height diisi
        $new_rack = emptyToNull($_POST['rack']);
        $new_u_position = emptyToNull($_POST['u_position']);
        $new_u_height = emptyToNull($_POST['u_height']);

        if (!empty($new_rack) && !empty($new_u_position) && !empty($new_u_height)) {
            $new_u_position = (int) $new_u_position;
            $new_u_height = (int) $new_u_height;

            // Cek batas rack (1-42)
            if ($new_u_position < 1 || $new_u_position > 42 || $new_u_height < 1 || $new_u_height > 42) {
                header("Location: hardware_" . strtolower($lokasi) . ".php?edit=error&message=" . urlencode("Posisi U harus antara 1-42."));
                exit();
            }

            $new_end_position = $new_u_position + $new_u_height - 1;
            if ($new_end_position > 42) {
                header("Location: hardware_" . strtolower($lokasi) . ".php?edit=error&message=" . urlencode("Posisi akhir U ($new_end_position) melebihi batas rack 42U."));
                exit();
            }

            // Cek konflik dengan hardware lain di lokasi dan rack yang sama (exclude current id)
            $check_conflict = "SELECT nama_perangkat, u_position, u_height FROM hardware
                              WHERE lokasi = ? AND rack = ? AND u_position IS NOT NULL AND u_height IS NOT NULL AND id != ?";
            $stmt = mysqli_prepare($koneksi, $check_conflict);
            mysqli_stmt_bind_param($stmt, 'ssi', $lokasi, $new_rack, $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $existing_start = (int) $row['u_position'];
                $existing_height = (int) $row['u_height'];
                $existing_end = $existing_start + $existing_height - 1;

                // Cek overlap
                if (($new_u_position <= $existing_end && $new_end_position >= $existing_start)) {
                    header("Location: hardware_" . strtolower($lokasi) . ".php?edit=error&message=" . urlencode("Posisi U {$new_u_position}-{$new_end_position} bertabrakan dengan '{$row['nama_perangkat']}' di posisi {$existing_start}-{$existing_end}."));
                    exit();
                }
            }
        }

        $fields = [
            'type_perangkat' => emptyToNull($type_perangkat),
            'nama_perangkat' => emptyToNull($nama_perangkat),
            'ip_console' => emptyToNull($_POST['ip_console']),
            'tahun_pengadaan' => emptyToNull($_POST['tahun_pengadaan']),
            'serial_number' => emptyToNull($_POST['serial_number']),
            'rack' => emptyToNull($_POST['rack']),
            'u_position' => emptyToNull($_POST['u_position']),
            'u_height' => emptyToNull($_POST['u_height']),
            'operating_system' => emptyToNull($_POST['operating_system']),
            'no_iventaris' => emptyToNull($_POST['no_iventaris']),
            'fungsi' => emptyToNull($_POST['fungsi']),
            'status' => emptyToNull($_POST['status']),
            'keterangan' => emptyToNull($_POST['keterangan']),
            'end_of_support' => emptyToNull($_POST['end_of_support']),
            'support_vendor' => emptyToNull($_POST['support_vendor']),
            'kontrak_support_start' => emptyToNull($_POST['kontrak_support_start']),
            'kontrak_support_finish' => emptyToNull($_POST['kontrak_support_finish']),
            'tanggal_bast' => emptyToNull($_POST['tanggal_bast']),
            'lokasi' => emptyToNull($lokasi),
            'backup_type' => emptyToNull($_POST['backup_type']),
            'ups_status' => emptyToNull($_POST['ups_status']),
            'partner' => emptyToNull($_POST['partner'])
        ];

        $updateFields = [];
        $values = [];
        $types = '';

        // Tipe data: s=string, i=integer.
        $typeMap = ['type_perangkat'=>'s', 'nama_perangkat'=>'s', 'ip_console'=>'s', 'tahun_pengadaan'=>'i', 'serial_number'=>'s', 'rack'=>'s', 'u_position'=>'i', 'u_height'=>'i', 'operating_system'=>'s', 'no_iventaris'=>'s', 'fungsi'=>'s', 'status'=>'s', 'keterangan'=>'s', 'end_of_support'=>'s', 'support_vendor'=>'s', 'kontrak_support_start'=>'s', 'kontrak_support_finish'=>'s', 'tanggal_bast'=>'s', 'lokasi'=>'s', 'backup_type'=>'s', 'ups_status'=>'s', 'partner'=>'s'];

        foreach ($fields as $key => $value) {
            $updateFields[] = "`$key` = ?";
            $values[] = $value;
            $types .= $typeMap[$key];
        }
        $values[] = $id; // Tambahkan id untuk WHERE clause
        $types .= 'i';

        $setClause = implode(', ', $updateFields);
        $query = "UPDATE hardware SET $setClause WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: hardware_" . strtolower($lokasi) . ".php?edit=success");
        } else {
            header("Location: hardware_" . strtolower($lokasi) . ".php?edit=error&message=" . urlencode(mysqli_stmt_error($stmt)));
        }
        exit();
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Gunakan prepared statement untuk mengambil lokasi
    $lokasi_stmt = mysqli_prepare($koneksi, "SELECT lokasi FROM hardware WHERE id = ?");
    mysqli_stmt_bind_param($lokasi_stmt, 'i', $id);
    mysqli_stmt_execute($lokasi_stmt);
    $result = mysqli_stmt_get_result($lokasi_stmt);

    if (mysqli_num_rows($result) == 0) {
        header("Location: hardware_dc.php?delete=error&message=" . urlencode("Data tidak ditemukan."));
        exit();
    }

    $lokasi_row = mysqli_fetch_assoc($result);
    $lokasi = $lokasi_row['lokasi'];

    // Gunakan prepared statement untuk menghapus data
    $delete_stmt = mysqli_prepare($koneksi, "DELETE FROM hardware WHERE id = ?");
    mysqli_stmt_bind_param($delete_stmt, 'i', $id);

    if (mysqli_stmt_execute($delete_stmt)) {
        header("Location: hardware_" . strtolower($lokasi) . ".php?delete=success");
    } else {
        header("Location: hardware_" . strtolower($lokasi) . ".php?delete=error&message=" . urlencode(mysqli_stmt_error($delete_stmt)));
    }
    exit();
}
?>