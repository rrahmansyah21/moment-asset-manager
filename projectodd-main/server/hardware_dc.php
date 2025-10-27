    <?php
    require 'function.php';

    session_start();

    if (!isset($_SESSION['role'])) {
        header("Location: login.php");
        exit;
    }

    $role = $_SESSION['role'];
    $username = $_SESSION['username'];
    $is_admin = $_SESSION['role'] === 'admin';

    $current_page = basename($_SERVER['PHP_SELF']);
    $hardware_pages = ['hardware_dc.php', 'hardware_drc.php'];
    $is_hardware_active = in_array($current_page, $hardware_pages);
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="icon" href="img/logo1.png" />
        <title>Moment | Hardware DC</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
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
            /* Style for action buttons (Edit / Delete) to show side-by-side */
            .action-column {
                width: 90px;
                text-align: center;
                white-space: nowrap;
            }
            .action-buttons {
                display: inline-flex;
                gap: 6px;
                justify-content: center;
                align-items: center;
            }
            .action-buttons .btn {
                width: 32px;
                height: 32px;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 4px;
            }
            /* Compact table appearance */
            .table-compact th,
            .table-compact td {
                padding: 0.45rem 0.6rem;
                vertical-align: middle;
                font-size: 0.85rem;
            }

            /* Column sizing and truncation for long text */
            #datatablesSimple .col-nama { max-width: 220px; }
            #datatablesSimple .col-keterangan { max-width: 260px; }
            #datatablesSimple .col-fungsi { max-width: 140px; }
            #datatablesSimple .col-serial { max-width: 120px; }
            #datatablesSimple .col-ip { max-width: 120px; }
            #datatablesSimple .col-inv { max-width: 120px; }

            #datatablesSimple td[class^="col-"] {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
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
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown"
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
                                <a class="nav-link active" href="hardware_dc.php">DC</a>
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
                                <a class="nav-link " href="user.php">
                                    <i class="fas fa-user me-2"></i>User
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
        </div>    
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Hardware - DC</h1>

                        <?php
                        if (isset($_GET['add'])) {
                            $alertType = ($_GET['add'] === 'success') ? 'success' : 'danger';
                            $message = ($_GET['add'] === 'success') ? 'Data berhasil ditambahkan!' : 'Gagal menambahkan data!';
                            if (isset($_GET['message'])) {
                                $message .= "<br>Error: " . htmlspecialchars($_GET['message']);
                            }
                            echo "<div class='alert alert-$alertType alert-dismissible fade show' role='alert'>$message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                        }

                        if (isset($_GET['edit'])) {
                            $alertType = ($_GET['edit'] === 'success') ? 'success' : 'danger';
                            $message = ($_GET['edit'] === 'success') ? 'Data berhasil diperbarui!' : 'Gagal memperbarui data!';
                            if (isset($_GET['message'])) {
                                $message .= "<br>Error: " . htmlspecialchars($_GET['message']);
                            }
                            echo "<div class='alert alert-$alertType alert-dismissible fade show' role='alert'>$message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                        }

                        if (isset($_GET['delete'])) {
                            $alertType = ($_GET['delete'] === 'success') ? 'success' : 'danger';
                            $message = ($_GET['delete'] === 'success') ? 'Data berhasil dihapus!' : 'Gagal menghapus data!';
                            if (isset($_GET['message'])) {
                                $message .= "<br>Error: " . htmlspecialchars($_GET['message']);
                            }
                            echo "<div class='alert alert-$alertType alert-dismissible fade show' role='alert'>$message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                        }
                        ?>

                        <div class="card mb-4">
                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <?php if ($is_admin): ?>
                                            <button class="btn btn-primary me-2" data-bs-toggle="modal"
                                                data-bs-target="#addModal">
                                                Add Hardware
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <a href="export_hardware_dc.php" class="btn btn-success me-2">
                                            <i class="fas fa-file-excel me-1"></i> Download Excel
                                        </a>
                                        <?php if ($is_admin): ?>
                                            <a href="export_hardware_all.php" class="btn btn-info">
                                                <i class="fas fa-file-excel me-1"></i> Export All Data
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-striped table-bordered table-sm table-compact mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Type</th>
                                            <th>Nama</th>
                                            <th>IP Console</th>
                                            <th>Tahun</th>
                                            <th>Serial Number</th>
                                            <th>Rack</th>
                                            <th>OS</th>
                                            <th>No Inventaris</th>
                                            <th>Fungsi</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                            <th>End of Support</th>
                                            <th>Vendor Support</th>
                                            <th>Kontrak Start</th>
                                            <th>Kontrak Finish</th>
                                            <th>BAST</th>
                                            <th>Partner</th>
                                            <?php if ($is_admin): ?>
                                                    <th class="action-column">Aksi</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = mysqli_query($koneksi, "SELECT * FROM hardware WHERE lokasi = 'DC' ORDER BY nama_perangkat ASC");
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['type_perangkat']) . "</td>";
                                            echo '<td class="col-nama" title="' . htmlspecialchars($row['nama_perangkat'], ENT_QUOTES) . '">' . htmlspecialchars($row['nama_perangkat']) . '</td>';
                                            echo '<td class="col-ip" title="' . htmlspecialchars($row['ip_console'], ENT_QUOTES) . '">' . htmlspecialchars($row['ip_console']) . '</td>';
                                            echo "<td>" . htmlspecialchars($row['tahun_pengadaan']) . "</td>";
                                            echo '<td class="col-serial" title="' . htmlspecialchars($row['serial_number'], ENT_QUOTES) . '">' . htmlspecialchars($row['serial_number']) . '</td>';
                                            echo "<td>" . htmlspecialchars($row['rack']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['operating_system']) . "</td>";
                                            echo '<td class="col-inv" title="' . htmlspecialchars($row['no_iventaris'], ENT_QUOTES) . '">' . htmlspecialchars($row['no_iventaris']) . '</td>';
                                            echo '<td class="col-fungsi" title="' . htmlspecialchars($row['fungsi'], ENT_QUOTES) . '">' . htmlspecialchars($row['fungsi']) . '</td>';
                                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                            echo '<td class="col-keterangan" title="' . htmlspecialchars($row['keterangan'], ENT_QUOTES) . '">' . htmlspecialchars($row['keterangan']) . '</td>';
                                            echo "<td>" . htmlspecialchars($row['end_of_support']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['support_vendor']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['kontrak_support_start']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['kontrak_support_finish']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['tanggal_bast']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['partner']) . "</td>";

                                            if ($is_admin) {
                                                // escape attribute values to avoid breaking HTML when values contain quotes
                                                $d_id = htmlspecialchars($row['id'] ?? '', ENT_QUOTES);
                                                $d_type = htmlspecialchars($row['type_perangkat'] ?? '', ENT_QUOTES);
                                                $d_nama = htmlspecialchars($row['nama_perangkat'] ?? '', ENT_QUOTES);
                                                $d_ip = htmlspecialchars($row['ip_console'] ?? '', ENT_QUOTES);
                                                $d_tahun = htmlspecialchars($row['tahun_pengadaan'] ?? '', ENT_QUOTES);
                                                $d_serial = htmlspecialchars($row['serial_number'] ?? '', ENT_QUOTES);
                                                $d_rack = htmlspecialchars($row['rack'] ?? '', ENT_QUOTES);
                                                $d_u_position = htmlspecialchars($row['u_position'] ?? '', ENT_QUOTES);
                                                $d_u_height = htmlspecialchars($row['u_height'] ?? '', ENT_QUOTES);
                                                $d_os = htmlspecialchars($row['operating_system'] ?? '', ENT_QUOTES);
                                                $d_no_inv = htmlspecialchars($row['no_iventaris'] ?? '', ENT_QUOTES);
                                                $d_fungsi = htmlspecialchars($row['fungsi'] ?? '', ENT_QUOTES);
                                                $d_status = htmlspecialchars($row['status'] ?? '', ENT_QUOTES);
                                                $d_keterangan = htmlspecialchars($row['keterangan'] ?? '', ENT_QUOTES);
                                                $d_eos = htmlspecialchars($row['end_of_support'] ?? '', ENT_QUOTES);
                                                $d_vendor = htmlspecialchars($row['support_vendor'] ?? '', ENT_QUOTES);
                                                $d_kontrak_start = htmlspecialchars($row['kontrak_support_start'] ?? '', ENT_QUOTES);
                                                $d_kontrak_finish = htmlspecialchars($row['kontrak_support_finish'] ?? '', ENT_QUOTES);
                                                $d_bast = htmlspecialchars($row['tanggal_bast'] ?? '', ENT_QUOTES);
                                                $d_lokasi = htmlspecialchars($row['lokasi'] ?? '', ENT_QUOTES);
                                                $d_backup = htmlspecialchars($row['backup_type'] ?? '', ENT_QUOTES);
                                                $d_ups = htmlspecialchars($row['ups_status'] ?? '', ENT_QUOTES);
                                                $d_partner = htmlspecialchars($row['partner'] ?? '', ENT_QUOTES);

                                                echo '<td class="action-column"><div class="action-buttons">'
                                                    . '<button type="button" title="Edit" class="btn btn-warning btn-sm editButton" '
                                                    . 'data-bs-toggle="modal" data-bs-target="#editModal" '
                                                    . 'data-id="' . $d_id . '" '
                                                    . 'data-type_perangkat="' . $d_type . '" '
                                                    . 'data-nama_perangkat="' . $d_nama . '" '
                                                    . 'data-ip_console="' . $d_ip . '" '
                                                    . 'data-tahun_pengadaan="' . $d_tahun . '" '
                                                    . 'data-serial_number="' . $d_serial . '" '
                                                    . 'data-rack="' . $d_rack . '" '
                                                    . 'data-u_position="' . $d_u_position . '" '
                                                    . 'data-u_height="' . $d_u_height . '" '
                                                    . 'data-operating_system="' . $d_os . '" '
                                                    . 'data-no_iventaris="' . $d_no_inv . '" '
                                                    . 'data-fungsi="' . $d_fungsi . '" '
                                                    . 'data-status="' . $d_status . '" '
                                                    . 'data-keterangan="' . $d_keterangan . '" '
                                                    . 'data-end_of_support="' . $d_eos . '" '
                                                    . 'data-support_vendor="' . $d_vendor . '" '
                                                    . 'data-kontrak_support_start="' . $d_kontrak_start . '" '
                                                    . 'data-kontrak_support_finish="' . $d_kontrak_finish . '" '
                                                    . 'data-tanggal_bast="' . $d_bast . '" '
                                                    . 'data-lokasi="' . $d_lokasi . '" '
                                                    . 'data-backup_type="' . $d_backup . '" '
                                                        . 'data-ups_status="' . $d_ups . '" '
                                                        . 'data-partner="' . $d_partner . '" '
                                                        . 'onclick="populateEditModal(this)">'
                                                    . '<i class="fas fa-edit"></i></button>'
                                                    . '<a href="process_hardware.php?delete_id=' . $d_id . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data?\')">'
                                                    . '<i class="fas fa-trash"></i></a>'
                                                    . '</div></td>';
                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="process_hardware.php" method="POST">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="addModalLabel">Tambah Hardware Baru</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Tipe Perangkat</label>
                                                    <input type="text" name="type_perangkat" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Perangkat</label>
                                                    <input type="text" name="nama_perangkat" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">IP Console</label>
                                                    <input type="text" name="ip_console" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tahun Pengadaan</label>
                                                    <input type="number" name="tahun_pengadaan" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Serial Number</label>
                                                    <input type="text" name="serial_number" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Rack</label>
                                                    <select name="rack" class="form-select" required>
                                                        <option value="">Pilih Rack</option>
                                                        <option value="L1-01">L1-01</option>
                                                        <option value="L1-02">L1-02</option>
                                                        <option value="L1-03">L1-03</option>
                                                        <option value="L1-04">L1-04</option>
                                                        <option value="L1-05">L1-05</option>
                                                        <option value="L1-06">L1-06</option>
                                                        <option value="L2-01">L2-01</option>
                                                        <option value="L2-02">L2-02</option>
                                                        <option value="L2-03">L2-03</option>
                                                        <option value="L2-04">L2-04</option>
                                                        <option value="L2-05">L2-05</option>
                                                        <option value="L2-06">L2-06</option>
                                                        <option value="L3-01">L3-01</option>
                                                        <option value="L3-02">L3-02</option>
                                                        <option value="L3-03">L3-03</option>
                                                        <option value="L3-04">L3-04</option>
                                                        <option value="L3-05">L3-05</option>
                                                        <option value="L3-06">L3-06</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Posisi U (1-42)</label>
                                                    <input type="number" name="u_position" class="form-control" min="1"
                                                        max="42" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tinggi U (1-42)</label>
                                                    <input type="number" name="u_height" class="form-control" min="1"
                                                        max="42" value="1" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Operating System</label>
                                                    <input type="text" name="operating_system" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">No Inventaris</label>
                                                    <input type="text" name="no_iventaris" class="form-control">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Fungsi</label>
                                                    <input type="text" name="fungsi" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Status</label>
                                                    <input type="text" name="status" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control"></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">End of Support</label>
                                                    <input type="date" name="end_of_support" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Vendor Support</label>
                                                    <input type="text" name="support_vendor" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Kontrak Support Start</label>
                                                    <input type="date" name="kontrak_support_start" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Kontrak Support Finish</label>
                                                    <input type="date" name="kontrak_support_finish" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tanggal BAST</label>
                                                    <input type="date" name="tanggal_bast" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Lokasi</label>
                                                    <select name="lokasi" class="form-select" required>
                                                        <option value="DC" selected>DC</option>
                                                        <option value="DRC">DRC</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Backup Type</label>
                                                    <input type="text" name="backup_type" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">UPS Status</label>
                                                    <input type="text" name="ups_status" class="form-control">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Partner</label>
                                                    <input type="text" name="partner" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="add_hardware"
                                                class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="process_hardware.php" method="POST">
                                        <!-- PERBAIKAN: Gunakan input hidden untuk ID seperti di software_subscription.php -->
                                        <input type="hidden" name="id" id="edit_id">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title" id="editModalLabel">Edit Data Hardware</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">ID</label>
                                                    <input type="text" id="display_id" class="form-control" disabled>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tipe Perangkat</label>
                                                    <input type="text" name="type_perangkat" id="edit_type_perangkat"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Perangkat</label>
                                                    <input type="text" name="nama_perangkat" id="edit_nama_perangkat"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">IP Console</label>
                                                    <input type="text" name="ip_console" id="edit_ip_console"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tahun Pengadaan</label>
                                                    <input type="number" name="tahun_pengadaan" id="edit_tahun_pengadaan"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Serial Number</label>
                                                    <input type="text" name="serial_number" id="edit_serial_number"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Rack</label>
                                                    <select name="rack" id="edit_rack" class="form-select" required>
                                                        <option value="">Pilih Rack</option>
                                                        <option value="L1-01">L1-01</option>
                                                        <option value="L1-02">L1-02</option>
                                                        <option value="L1-03">L1-03</option>
                                                        <option value="L1-04">L1-04</option>
                                                        <option value="L1-05">L1-05</option>
                                                        <option value="L1-06">L1-06</option>
                                                        <option value="L2-01">L2-01</option>
                                                        <option value="L2-02">L2-02</option>
                                                        <option value="L2-03">L2-03</option>
                                                        <option value="L2-04">L2-04</option>
                                                        <option value="L2-05">L2-05</option>
                                                        <option value="L2-06">L2-06</option>
                                                        <option value="L3-01">L3-01</option>
                                                        <option value="L3-02">L3-02</option>
                                                        <option value="L3-03">L3-03</option>
                                                        <option value="L3-04">L3-04</option>
                                                        <option value="L3-05">L3-05</option>
                                                        <option value="L3-06">L3-06</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Posisi U (1-42)</label>
                                                    <input type="number" name="u_position" id="edit_u_position"
                                                        class="form-control" min="1" max="42" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tinggi U (1-42)</label>
                                                    <input type="number" name="u_height" id="edit_u_height"
                                                        class="form-control" min="1" max="42" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Operating System</label>
                                                    <input type="text" name="operating_system" id="edit_operating_system"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">No Inventaris</label>
                                                    <input type="text" name="no_iventaris" id="edit_no_iventaris"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Fungsi</label>
                                                    <input type="text" name="fungsi" id="edit_fungsi" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Status</label>
                                                    <input type="text" name="status" id="edit_status" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" id="edit_keterangan"
                                                        class="form-control"></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">End of Support</label>
                                                    <input type="date" name="end_of_support" id="edit_end_of_support"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Vendor Support</label>
                                                    <input type="text" name="support_vendor" id="edit_support_vendor"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Kontrak Support Start</label>
                                                    <input type="date" name="kontrak_support_start"
                                                        id="edit_kontrak_support_start" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Kontrak Support Finish</label>
                                                    <input type="date" name="kontrak_support_finish"
                                                        id="edit_kontrak_support_finish" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tanggal BAST</label>
                                                    <input type="date" name="tanggal_bast" id="edit_tanggal_bast"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Lokasi</label>
                                                    <select name="lokasi" id="edit_lokasi" class="form-select" required>
                                                        <option value="DC">DC</option>
                                                        <option value="DRC">DRC</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Backup Type</label>
                                                    <input type="text" name="backup_type" id="edit_backup_type"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">UPS Status</label>
                                                    <input type="text" name="ups_status" id="edit_ups_status"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Partner</label>
                                                    <input type="text" name="partner" id="edit_partner"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="edit_hardware"
                                                class="btn btn-warning">Update</button>
                                        </div>
                                    </form>
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
            crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editButtons = document.querySelectorAll('.editButton');

                editButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        document.getElementById('edit_id').value = this.getAttribute('data-id');
                        document.getElementById('display_id').value = this.getAttribute('data-id');
                        document.getElementById('edit_type_perangkat').value = this.getAttribute('data-type_perangkat');
                        document.getElementById('edit_nama_perangkat').value = this.getAttribute('data-nama_perangkat');
                        document.getElementById('edit_ip_console').value = this.getAttribute('data-ip_console');
                        document.getElementById('edit_tahun_pengadaan').value = this.getAttribute('data-tahun_pengadaan');
                        document.getElementById('edit_serial_number').value = this.getAttribute('data-serial_number');
                        document.getElementById('edit_rack').value = this.getAttribute('data-rack');
                        document.getElementById('edit_u_position').value = this.getAttribute('data-u_position');
                        document.getElementById('edit_u_height').value = this.getAttribute('data-u_height');
                        document.getElementById('edit_operating_system').value = this.getAttribute('data-operating_system');
                        document.getElementById('edit_no_iventaris').value = this.getAttribute('data-no_iventaris');
                        document.getElementById('edit_fungsi').value = this.getAttribute('data-fungsi');
                        document.getElementById('edit_status').value = this.getAttribute('data-status');
                        document.getElementById('edit_keterangan').value = this.getAttribute('data-keterangan');
                        document.getElementById('edit_end_of_support').value = this.getAttribute('data-end_of_support');
                        document.getElementById('edit_support_vendor').value = this.getAttribute('data-support_vendor');
                        document.getElementById('edit_kontrak_support_start').value = this.getAttribute('data-kontrak_support_start');
                        document.getElementById('edit_kontrak_support_finish').value = this.getAttribute('data-kontrak_support_finish');
                        document.getElementById('edit_tanggal_bast').value = this.getAttribute('data-tanggal_bast');
                        document.getElementById('edit_lokasi').value = this.getAttribute('data-lokasi');
                        document.getElementById('edit_backup_type').value = this.getAttribute('data-backup_type');
                        document.getElementById('edit_ups_status').value = this.getAttribute('data-ups_status');
                        document.getElementById('edit_partner').value = this.getAttribute('data-partner');
                    });
                });
            });
        </script>
        <script>
        function populateEditModal(btn) {
            if (!btn) return;
            const attrs = ['id','type_perangkat','nama_perangkat','ip_console','tahun_pengadaan','serial_number','rack','u_position','u_height','operating_system','no_iventaris','fungsi','status','keterangan','end_of_support','support_vendor','kontrak_support_start','kontrak_support_finish','tanggal_bast','lokasi','backup_type','ups_status','partner'];
            const dataId = btn.getAttribute('data-id');
            const hid = document.getElementById('edit_id'); if (hid) hid.value = dataId || '';
            const disp = document.getElementById('display_id'); if (disp) disp.value = dataId || '';
            attrs.forEach(function (attr) {
                if (attr === 'id') return;
                const dataVal = btn.getAttribute('data-' + attr);
                const el = document.getElementById('edit_' + attr);
                if (!el) return;
                if (['input','select','textarea'].includes(el.tagName.toLowerCase())) el.value = dataVal !== null ? dataVal : '';
                else el.textContent = dataVal !== null ? dataVal : '';
            });
        }
        </script>
    </body>

    </html>