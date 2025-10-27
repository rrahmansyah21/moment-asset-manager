<?php
require 'function.php';

session_start();

// Redirect jika belum login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$is_admin = $_SESSION['role'] === 'admin';

$current_page = basename($_SERVER['PHP_SELF']);
$software_pages = ['software_subscription.php', 'software_perpetual.php'];
$is_software_active = in_array($current_page, $software_pages);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="img/logo1.png" />
    <title>Moment-Software Subscription</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .status-active {
            background-color: #d4edda;
            padding: 3px 8px;
            border-radius: 4px;
            color: #155724;
        }

        .status-expired {
            background-color: #f8d7da;
            padding: 3px 8px;
            border-radius: 4px;
            color: #721c24;
        }

        .date-warning {
            background-color: #fff3cd;
            padding: 3px 8px;
            border-radius: 4px;
            color: #856404;
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
                        <div class="collapse show" id="collapseSoftware" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link active" href="software_subscription.php">Subscription</a>
                                <a class="nav-link " href="software_perpetual.php">Perpetual</a>
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
                    <h1 class="mt-4">Software Subscription</h1>

                    <!-- Notifikasi -->
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
                                            data-bs-target="#addModal">Add Software</button>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="export_software_subscription.php" class="btn btn-success">
                                        <i class="fas fa-file-excel me-1"></i> Download Excel
                                    </a>
                                </div>
                            </div>

                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>No. Subscription</th>
                                        <th>Nama Software</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Status</th>
                                        <th>Partner</th>
                                        <?php if ($is_admin): ?>
                                            <th>Aksi</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($koneksi, "SELECT * FROM software WHERE lokasi = 'Subscription' ORDER BY TANGGAL_BERAKHIR DESC");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $today = date('Y-m-d H:i:s');
                                        $berakhir = $row['TANGGAL_BERAKHIR'];

                                        if ($berakhir > $today) {
                                            $status_class = 'status-active';
                                            $status_text = 'Active';
                                        } else if (date('Y-m-d', strtotime($berakhir)) == date('Y-m-d')) {
                                            $status_class = 'date-warning';
                                            $status_text = 'Expiring Today';
                                        } else {
                                            $status_class = 'status-expired';
                                            $status_text = 'Expired';
                                        }

                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['KODE_SUBS']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['NO_SUBS']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['NAMA_SUBS']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['KETERANGAN']) . "</td>";
                                        echo "<td>" . date('d M Y H:i', strtotime($row['TANGGAL_AWAL'])) . "</td>";
                                        echo "<td>" . date('d M Y H:i', strtotime($row['TANGGAL_BERAKHIR'])) . "</td>";
                                        echo "<td><span class='{$status_class}'>$status_text</span></td>";
                                        echo "<td>" . htmlspecialchars($row['partner']) . "</td>";

                                        if ($is_admin) {
                                            echo "<td>
                                                <button class='btn btn-warning btn-sm editButton' 
                                                    data-bs-toggle='modal' data-bs-target='#editModal'
                                                    data-kode_subs='{$row['KODE_SUBS']}'
                                                    data-no_subs='{$row['NO_SUBS']}'
                                                    data-nama_subs='{$row['NAMA_SUBS']}'
                                                    data-keterangan='{$row['KETERANGAN']}'
                                                    data-tanggal_awal='" . date('Y-m-d\TH:i', strtotime($row['TANGGAL_AWAL'])) . "'
                                                    data-tanggal_berakhir='" . date('Y-m-d\TH:i', strtotime($row['TANGGAL_BERAKHIR'])) . "'
                                                    data-partner='{$row['partner']}'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                <a href='process_software.php?delete_id={$row['KODE_SUBS']}' 
                                                   class='btn btn-danger btn-sm' 
                                                   onclick='return confirm(\"Yakin ingin menghapus data?\")'>
                                                   <i class='fas fa-trash'></i>
                                                </a>
                                            </td>";
                                        }
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Add Software -->
                    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="process_software.php" method="POST">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="addModalLabel">Tambah Software Baru</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Kode Subscription</label>
                                                <input type="number" name="kode_subs" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">No. Subscription</label>
                                                <input type="text" name="no_subs" class="form-control" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Nama Software</label>
                                                <input type="text" name="nama_subs" class="form-control" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Keterangan</label>
                                                <textarea name="keterangan" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Awal</label>
                                                <input type="datetime-local" name="tanggal_awal" class="form-control"
                                                    required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Berakhir</label>
                                                <input type="datetime-local" name="tanggal_berakhir"
                                                    class="form-control" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Partner</label>
                                                <input type="text" name="partner" class="form-control">
                                            </div>
                                            <input type="hidden" name="lokasi" value="Subscription">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="add_software"
                                            class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Edit Software -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="process_software.php" method="POST">
                                    <input type="hidden" name="kode_subs" id="edit_kode_subs">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title" id="editModalLabel">Edit Data Software</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">No. Subscription</label>
                                                <input type="text" name="no_subs" id="edit_no_subs" class="form-control"
                                                    required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Kode Subscription</label>
                                                <input type="text" id="display_kode_subs" class="form-control" disabled>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Nama Software</label>
                                                <input type="text" name="nama_subs" id="edit_nama_subs"
                                                    class="form-control" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Keterangan</label>
                                                <textarea name="keterangan" id="edit_keterangan"
                                                    class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Awal</label>
                                                <input type="datetime-local" name="tanggal_awal" id="edit_tanggal_awal"
                                                    class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Berakhir</label>
                                                <input type="datetime-local" name="tanggal_berakhir"
                                                    id="edit_tanggal_berakhir" class="form-control" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Partner</label>
                                                <input type="text" name="partner" id="edit_partner"
                                                    class="form-control">
                                            </div>
                                            <input type="hidden" name="lokasi" value="Subscription">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="edit_software"
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
                    document.getElementById('edit_kode_subs').value = this.getAttribute('data-kode_subs');
                    document.getElementById('display_kode_subs').value = this.getAttribute('data-kode_subs');
                    document.getElementById('edit_no_subs').value = this.getAttribute('data-no_subs');
                    document.getElementById('edit_nama_subs').value = this.getAttribute('data-nama_subs');
                    document.getElementById('edit_keterangan').value = this.getAttribute('data-keterangan');
                    document.getElementById('edit_tanggal_awal').value = this.getAttribute('data-tanggal_awal');
                    document.getElementById('edit_tanggal_berakhir').value = this.getAttribute('data-tanggal_berakhir');
                    document.getElementById('edit_partner').value = this.getAttribute('data-partner');
                });
            });
        });
    </script>
</body>

</html>