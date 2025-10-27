<?php
require 'function.php';

session_start();

// Redirect jika belum login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'];
$username = $_SESSION['username'];

function ambilDataPieChart($lokasi, $table = "hardware", $column = "kontrak_support_finish")
{
    global $koneksi;
    $now = date("Y-m-d");
    $data = ['lebih_6' => 0, 'kurang_6' => 0, 'kurang_3' => 0, 'no_data' => 0];

    if ($table == "hardware") {
        $query = "SELECT $column FROM $table WHERE lokasi = '$lokasi'";
    } else {
        $query = "SELECT $column FROM $table";
    }

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        // Handle query error
        return $data;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $tgl = $row[$column];
        if (!$tgl || $tgl == '0000-00-00') {
            $data['no_data']++;
        } else {
            $selisih = (strtotime($tgl) - strtotime($now)) / (60 * 60 * 24);
            if ($selisih > 180) {
                $data['lebih_6']++;
            } elseif ($selisih > 90) {
                $data['kurang_6']++;
            } else {
                $data['kurang_3']++;
            }
        }
    }

    return $data;
}

$data_dc = ambilDataPieChart("DC");
$data_drc = ambilDataPieChart("DRC");
$data_subscription = ambilDataPieChart("Subscription", "software", "tanggal_berakhir");
$data_perpetual = ambilDataPieChart("Perpetual", "software", "tanggal_berakhir");

$total_dc = array_sum($data_dc);
$total_drc = array_sum($data_drc);
$total_subscription = array_sum($data_subscription);
$total_perpetual = array_sum($data_perpetual);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="img/logo1.png" />
    <title>Moment | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-legend {
            display: none !important;
        }

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

        .card-footer a:hover {
            text-decoration: underline;
        }

        /* Efek hover pada card */
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Layout pie chart */
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }

        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: #0d6efd;
        }

        /* Tab styling */
        .nav-tabs .nav-link {
            font-weight: 600;
            padding: 12px 25px;
        }

        .nav-tabs .nav-link.active {
            border-bottom: 3px solid #0d6efd;
        }

        /* Badge status */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Icon size */
        .card-icon {
            font-size: 2rem;
            opacity: 0.8;
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
                <a class="nav-link dropdown-toggle text-white" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown"
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
                        <a class="nav-link active" href="index.php">
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
                    <h1 class="mt-4 mb-4">Dashboard Overview</h1>

                    <ul class="nav nav-tabs mb-4" id="dataTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="card-tab" data-bs-toggle="tab"
                                data-bs-target="#card-content" type="button" role="tab" aria-controls="card-content"
                                aria-selected="true">
                                <i class="fas fa-th-large me-2"></i> Card View
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="chart-tab" data-bs-toggle="tab" data-bs-target="#chart-content"
                                type="button" role="tab" aria-controls="chart-content" aria-selected="false">
                                <i class="fas fa-chart-pie me-2"></i> Pie Chart View
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="dataTabsContent">
                        <div class="tab-pane fade show active" id="card-content">
                            <!-- Data Center (DC) Section -->

                            <div class="section-title">
                                <h4>Data Center (DC) <span class="badge bg-primary"><?= $total_dc ?> Hardware</span>
                                </h4>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-success shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Active</div>
                                                    <p class="card-text mb-0"><?= $data_dc['lebih_6'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-check-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="active" data-category="hardware" data-type="DC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-warning shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Warning</div>
                                                    <p class="card-text"><?= $data_dc['kurang_6'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-exclamation-triangle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="warning" data-category="hardware" data-type="DC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-danger shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Critical</div>
                                                    <p class="card-text"><?= $data_dc['kurang_3'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-times-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="critical" data-category="hardware" data-type="DC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div
                                        class="card text-white bg-secondary shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Inactive</div>
                                                    <p class="card-text"><?= $data_dc['no_data'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-ban card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="nodata" data-category="hardware" data-type="DC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Recovery Center (DRC) Section -->
                            <div class="section-title">
                                <h4>Data Recovery Center (DRC) <span class="badge bg-primary"><?= $total_drc ?>
                                        Hardware</span></h4>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-success shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Active</div>
                                                    <p class="card-text mb-0"><?= $data_drc['lebih_6'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-check-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="active" data-category="hardware" data-type="DRC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-warning shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Warning</div>
                                                    <p class="card-text"><?= $data_drc['kurang_6'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-exclamation-triangle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="warning" data-category="hardware" data-type="DRC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-danger shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Critical</div>
                                                    <p class="card-text"><?= $data_drc['kurang_3'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-times-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="critical" data-category="hardware" data-type="DRC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div
                                        class="card text-white bg-secondary shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Inactive</div>
                                                    <p class="card-text"><?= $data_drc['no_data'] ?> Hardware</p>
                                                </div>
                                                <i class="fas fa-ban card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="nodata" data-category="hardware" data-type="DRC">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Software Subscription Section -->
                            <div class="section-title">
                                <h4>Software - Subscription <span class="badge bg-primary"><?= $total_subscription ?>
                                        Software</span></h4>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-success shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Active</div>
                                                    <p class="card-text"><?= $data_subscription['lebih_6'] ?> Software
                                                    </p>
                                                </div>
                                                <i class="fas fa-check-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="active" data-category="software" data-type="Subscription">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-warning shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Warning</div>
                                                    <p class="card-text"><?= $data_subscription['kurang_6'] ?> Software
                                                    </p>
                                                </div>
                                                <i class="fas fa-exclamation-triangle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="warning" data-category="software" data-type="Subscription">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-danger shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Critical</div>
                                                    <p class="card-text"><?= $data_subscription['kurang_3'] ?> Software
                                                    </p>
                                                </div>
                                                <i class="fas fa-times-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="critical" data-category="software"
                                                data-type="Subscription">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div
                                        class="card text-white bg-secondary shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Inactive</div>
                                                    <p class="card-text"><?= $data_subscription['no_data'] ?> Software
                                                    </p>
                                                </div>
                                                <i class="fas fa-ban card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="nodata" data-category="software" data-type="Subscription">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Software Perpetual Section -->
                            <div class="section-title">
                                <h4>Software - Perpetual <span class="badge bg-primary"><?= $total_perpetual ?>
                                        Software</span></h4>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-success shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Active</div>
                                                    <p class="card-text"><?= $data_perpetual['lebih_6'] ?> Software</p>
                                                </div>
                                                <i class="fas fa-check-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="active" data-category="software" data-type="Perpetual">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-warning shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Warning</div>
                                                    <p class="card-text"><?= $data_perpetual['kurang_6'] ?> Software</p>
                                                </div>
                                                <i class="fas fa-exclamation-triangle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="warning" data-category="software" data-type="Perpetual">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card text-white bg-danger shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Critical</div>
                                                    <p class="card-text"><?= $data_perpetual['kurang_3'] ?> Software</p>
                                                </div>
                                                <i class="fas fa-times-circle card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="critical" data-category="software" data-type="Perpetual">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div
                                        class="card text-white bg-secondary shadow h-100 d-flex flex-column card-hover">
                                        <div class="card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-uppercase mb-1 fw-bold">Inactive</div>
                                                    <p class="card-text"><?= $data_perpetual['no_data'] ?> Software</p>
                                                </div>
                                                <i class="fas fa-ban card-icon text-white"></i>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="#"
                                                class="text-white d-flex align-items-center text-decoration-none pt-2 view-details"
                                                data-status="nodata" data-category="software" data-type="Perpetual">
                                                View Details
                                                <i class="fas fa-angle-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="chart-content">
                            <div class="container-fluid">
                                <div class="section-title">
                                    <h4>Hardware - Data Center (DC)</h4>
                                </div>
                                <div class="chart-container">
                                    <canvas id="pieChartDC"></canvas>
                                </div>

                                <div class="section-title">
                                    <h4>Hardware - Data Recovery Center (DRC)</h4>
                                </div>
                                <div class="chart-container">
                                    <canvas id="pieChartDRC"></canvas>
                                </div>

                                <div class="section-title">
                                    <h4>Software - Subscription</h4>
                                </div>
                                <div class="chart-container">
                                    <canvas id="pieChartSubscription"></canvas>
                                </div>

                                <div class="section-title">
                                    <h4>Software - Perpetual</h4>
                                </div>
                                <div class="chart-container">
                                    <canvas id="pieChartPerpetual"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; ODD BPJS Ketenagakerjaan <?= date('Y') ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailsModalLabel">Detail Hardware</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailsContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading data...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fungsi untuk membuat pie chart
            function createPieChart(ctx, data, title) {
                return new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Active (>6 months)', 'Warning (3-6 months)', 'Critical (<3 months)', 'Inactive/No Data'],
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                'rgba(40, 167, 69, 0.8)',
                                'rgba(255, 193, 7, 0.8)',
                                'rgba(220, 53, 69, 0.8)',
                                'rgba(108, 117, 125, 0.8)'
                            ],
                            borderColor: [
                                'rgba(40, 167, 69, 1)',
                                'rgba(255, 193, 7, 1)',
                                'rgba(220, 53, 69, 1)',
                                'rgba(108, 117, 125, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: title,
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.chart.getDatasetMeta(0).total;
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Buat chart untuk setiap bagian
            const dcChart = createPieChart(
                document.getElementById('pieChartDC'),
                [<?= $data_dc['lebih_6'] ?>, <?= $data_dc['kurang_6'] ?>, <?= $data_dc['kurang_3'] ?>, <?= $data_dc['no_data'] ?>],
                'Hardware - Data Center (DC)'
            );

            const drcChart = createPieChart(
                document.getElementById('pieChartDRC'),
                [<?= $data_drc['lebih_6'] ?>, <?= $data_drc['kurang_6'] ?>, <?= $data_drc['kurang_3'] ?>, <?= $data_drc['no_data'] ?>],
                'Hardware - Data Recovery Center (DRC)'
            );

            const subscriptionChart = createPieChart(
                document.getElementById('pieChartSubscription'),
                [<?= $data_subscription['lebih_6'] ?>, <?= $data_subscription['kurang_6'] ?>, <?= $data_subscription['kurang_3'] ?>, <?= $data_subscription['no_data'] ?>],
                'Software - Subscription'
            );

            const perpetualChart = createPieChart(
                document.getElementById('pieChartPerpetual'),
                [<?= $data_perpetual['lebih_6'] ?>, <?= $data_perpetual['kurang_6'] ?>, <?= $data_perpetual['kurang_3'] ?>, <?= $data_perpetual['no_data'] ?>],
                'Software - Perpetual'
            );

            // Handle View Details button click
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const status = this.getAttribute('data-status');
                    const category = this.getAttribute('data-category');
                    const type = this.getAttribute('data-type');

                    // Tampilkan modal
                    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                    const modalTitle = document.getElementById('detailsModalLabel');
                    const modalBody = document.getElementById('detailsContent');

                    // Set judul modal
                    modalTitle.textContent = `Detail ${category === 'hardware' ? 'Hardware' : 'Software'} - ${type} (${status.charAt(0).toUpperCase() + status.slice(1)})`;

                    // Tampilkan loading
                    modalBody.innerHTML = `
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3">Memuat data...</p>
                        </div>
                    `;

                    // Tampilkan modal
                    modal.show();

                    // Tentukan file tujuan berdasarkan kategori
                    const targetFile = category === 'hardware' ? 'get_hardware_details.php' : 'get_software_details.php';

                    // Kirim permintaan AJAX untuk mengambil data
                    const formData = new FormData();
                    formData.append('status', status);
                    formData.append('category', category);
                    formData.append('type', type);

                    fetch(targetFile, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.text();
                        })
                        .then(data => {
                            modalBody.innerHTML = data;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            modalBody.innerHTML = `
                            <div class="alert alert-danger">
                                Gagal memuat data: ${error.message}
                            </div>
                        `;
                        });
                });
            });
        });
    </script>
</body>

</html>