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
    <title>Moment-Rack DC</title>
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

        .rack-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 20px;
            margin-top: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            min-height: 600px;
        }

        .rack-box {
            flex: 0 0 auto;
            min-width: 280px;
        }

        .rack-table {
            border-collapse: collapse;
            text-align: center;
            font-size: 11px;
            width: 100%;
            min-width: 250px;
        }

        .rack-table th {
            background: #ccc;
            padding: 5px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .rack-table td {
            padding: 5px;
            border: 1px solid #ddd;
            height: 30px;
        }

        .device-occupied {
            background: green;
            color: white;
        }

        .slot-empty {
            background: yellow;
        }

        .table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        /* Scrollbar styling */
        .rack-container::-webkit-scrollbar {
            height: 12px;
        }

        .rack-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .rack-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .rack-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .table-container::-webkit-scrollbar {
            width: 12px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Legend styles */
        .legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 1px solid #ddd;
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
                        <div class="collapse show" id="collapseRack" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link active" href="rack_dc.php">DC</a>
                                <a class="nav-link " href="rack_drc.php">DRC</a>
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
                            <a class="nav-link" href="user.php">
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
                    <h1 class="mt-4">Rack - DC</h1>



                    <div class="mb-3">
                        <label for="filterLorong" class="form-label fw-bold">Pilih Lorong:</label>
                        <select id="filterLorong" class="form-select" style="max-width:250px;">
                            <option value="all">Tampilkan Semua</option>
                            <option value="L1">Lorong 1</option>
                            <option value="L2">Lorong 2</option>
                            <option value="L3">Lorong 3</option>
                        </select>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="rack-container">
                                <?php
                                // Hanya ambil rack dari lokasi DC
                                $sql = "SELECT DISTINCT rack FROM hardware WHERE lokasi='DC' AND rack IS NOT NULL AND rack != ''";
                                $result = $koneksi->query($sql);

                                $racks = [];
                                while ($row = $result->fetch_assoc()) {
                                    $racks[] = $row['rack'];
                                }

                                foreach ($racks as $rack) {
                                    $lorong = strtoupper(substr($rack, 0, 2));

                                    // Hanya ambil hardware dari lokasi DC
                                    $sql = "SELECT nama_perangkat, u_position, u_height 
                                            FROM hardware 
                                            WHERE lokasi='DC' AND rack='$rack' 
                                            AND u_position IS NOT NULL AND u_height IS NOT NULL";
                                    $result = $koneksi->query($sql);

                                    $hardware = [];
                                    while ($row = $result->fetch_assoc()) {
                                        $start = (int) $row['u_position'];
                                        $height = (int) $row['u_height'];
                                        $end = $start + $height - 1;

                                        $hardware[] = [
                                            'nama' => $row['nama_perangkat'],
                                            'start' => $start,
                                            'end' => $end,
                                            'height' => $height
                                        ];
                                    }

                                    echo "<div class='rack-box' data-lorong='{$lorong}'>";
                                    echo "<h5 style='text-align:center; background:#f8f9fa; padding:10px; border-radius:5px;'>$rack</h5>";
                                    echo "<div class='table-container'>";
                                    echo "<table class='rack-table'>
                                            <tr>
                                                <th style='width:25px;'>U</th>
                                                <th>Nama Perangkat</th>
                                                <th style='width:30px;'>Slot</th>
                                            </tr>";

                                    $i = 42;
                                    while ($i >= 1) {
                                        $found = false;

                                        foreach ($hardware as $device) {
                                            if ($i == $device['end']) {
                                                echo "<tr>";
                                                echo "<td>{$i}</td>";
                                                echo "<td rowspan='{$device['height']}' class='device-occupied' title='{$device['nama']}'>{$device['nama']}</td>";
                                                echo "<td class='device-occupied'>1</td>";
                                                echo "</tr>";

                                                for ($j = $i - 1; $j >= $device['start']; $j--) {
                                                    echo "<tr>";
                                                    echo "<td>{$j}</td>";
                                                    echo "<td class='device-occupied'>1</td>";
                                                    echo "</tr>";
                                                }

                                                $i = $device['start'] - 1;
                                                $found = true;
                                                break;
                                            }
                                        }

                                        if (!$found) {
                                            echo "<tr>";
                                            echo "<td>{$i}</td>";
                                            echo "<td class='slot-empty'></td>";
                                            echo "<td class='slot-empty'></td>";
                                            echo "</tr>";
                                            $i--;
                                        }
                                    }

                                    echo "</table>";
                                    echo "</div>"; // table-container
                                    echo "</div>"; // rack-box
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 mt-auto" style="background-color: transparent !important;">
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
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        document.getElementById("filterLorong").addEventListener("change", function () {
            let selected = this.value;
            document.querySelectorAll(".rack-box").forEach(box => {
                if (selected === "all" || box.getAttribute("data-lorong") === selected) {
                    box.style.display = "block";
                } else {
                    box.style.display = "none";
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("filterLorong").value = "L1";
            document.getElementById("filterLorong").dispatchEvent(new Event("change"));
        });
    </script>
</body>

</html>