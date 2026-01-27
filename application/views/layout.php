<!DOCTYPE html>
<html lang="id" class="light-theme color-header headercolor4">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->session->userdata('nama_client_app') ?> | <?= $this->session->userdata('deskripsi_client_app') ?>
    </title>
    <!--favicon-->
    <link rel="icon" href="assets/images/e-supel.png" type="image/png">
    <link rel="shortcut icon" href="assets/images/e-supel.png">
    <!--plugins-->
    <link href="assets/plugins/notifications/css/lobibox.min.css" rel="stylesheet">
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet">
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet">

    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <link href="assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
    <link href="assets/plugins/datetimepicker/css/classic.time.css" rel="stylesheet" />
    <link href="assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet">
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="assets/css/dark-theme.css">
    <link rel="stylesheet" href="assets/css/semi-dark.css">
    <link rel="stylesheet" href="assets/css/header-colors.css">
    <style type="text/css">
        /* Chart.js */
        @-webkit-keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        @keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        .chartjs-render-monitor {
            -webkit-animation: chartjs-render-animation 0.001s;
            animation: chartjs-render-animation 0.001s;
        }
    </style>
    <style type="text/css">
        .jqstooltip {
            position: absolute;
            left: 0px;
            top: 0px;
            visibility: hidden;
            background: rgb(0, 0, 0) transparent;
            background-color: rgba(0, 0, 0, 0.6);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
            color: white;
            font: 10px arial, san serif;
            text-align: left;
            white-space: nowrap;
            padding: 5px;
            border: 1px solid white;
            box-sizing: content-box;
            z-index: 10000;
        }

        .jqsfield {
            color: white;
            font: 10px arial, san serif;
            text-align: left;
        }
    </style>
</head>

<body class="pace-done">
    <div class="pace pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99"
            style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>
    <!--wrapper-->
    <div class="wrapper">
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="topbar-logo-header">
                        <div class="">
                            <img src="assets/images/e-supel.webp" class="logo-icon" alt="logo icon">
                        </div>
                        <div class="">
                            <h4 class="logo-text">E-SUPEL</h4>
                        </div>
                    </div>
                    <div class="mobile-toggle-menu"><i class="bx bx-menu"></i></div>
                    <div class="search-bar flex-grow-1"></div>
                    <div class="top-menu ms-auto"></div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $this->session->userdata('foto') ?>" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">
                                    <?= $this->session->userdata('fullname') ?>
                                </p>
                                <p class="designattion mb-0">
                                    <?= $this->session->userdata('jabatan') ?>
                                </p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= $this->config->item('sso_server') ?>"><i
                                        class='bx bx-transfer-alt'></i><span>Pindah Layanan</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" href="keluar"><i
                                        class="bx bx-log-out-circle"></i><span>Keluar</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->
        <!--navigation-->
        <div class="nav-container">
            <div class="mobile-topbar-header">
                <div>
                    <img src="assets/images/e-supel.webp" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">E-SUPEL</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class="bx bx-arrow-to-left"></i>
                </div>
            </div>
            <nav class="topbar-nav">
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="javascript:;" data-page="dashboard">
                            <div class="parent-icon"><i class="bx bx-home-circle"></i>
                            </div>
                            <div class="menu-title">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:;">
                            <div class="parent-icon"><i class="bx bx-line-chart"></i>
                            </div>
                            <div class="menu-title">Manajemen Data</div>
                        </a>
                        <ul class="mm-collapse">
                            <li> <a href="javascript:;" data-page="data_petugas"><i
                                        class="bx bx-right-arrow-alt"></i>Data Petugas</a>
                            </li>
                            <li> <a href="javascript:;" data-page="data_posisi"><i
                                        class="bx bx-right-arrow-alt"></i>Data Posisi</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <!--end navigation-->

        <div class="page-wrapper">
            <div class="page-content">
                <div id="app"></div>
            </div>
        </div>

        <div class="overlay toggle-icon"></div>
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class="bx bxs-up-arrow-alt"></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2025. All right reserved.</p>
        </footer>

        <!-- Bootstrap JS -->
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <!--plugins-->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
        <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
        <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js" defer></script>
        <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js" defer></script>
        <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js" defer></script>
        <script src="assets/plugins/chartjs/js/Chart.min.js" defer></script>
        <script src="assets/plugins/chartjs/js/Chart.extension.js" defer></script>
        <script src="assets/plugins/sparkline-charts/jquery.sparkline.min.js" defer></script>

        <script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>

        <script src="assets/plugins/datatable/js/jquery.dataTables.min.js" defer></script>
        <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js" defer></script>
        <!--notification js -->
        <script src="assets/plugins/notifications/js/lobibox.min.js" defer></script>
        <script src="assets/plugins/notifications/js/notifications.min.js" defer></script>

        <script src="assets/plugins/sweetalert2/sweetalert2.all.min.js" defer></script>
        <script src="assets/plugins/select2/js/select2.min.js" defer></script>
        <script src="assets/plugins/datetimepicker/js/legacy.js"></script>
        <script src="assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="assets/plugins/datetimepicker/js/picker.time.js"></script>
        <script src="assets/plugins/datetimepicker/js/picker.date.js"></script>

        <script src="assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
        <!--app JS-->
        <script src="assets/js/app.js"></script>

        <?php
        if ($this->session->flashdata('info')) {
            $result = $this->session->flashdata('info');
            if ($result == '1') {
                $pesan = $this->session->flashdata('pesan_sukses');
            } elseif ($result == '2') {
                $pesan = $this->session->flashdata('pesan_peringatan');
            } else {
                $pesan = $this->session->flashdata('pesan_gagal');
            }
        } else {
            $result = "-1";
            $pesan = "";
        }
        ?>

        <script type="text/javascript">
            var config = {
                result: '<?= $result ?>',
                pesan: '<?= $pesan ?>'
            };
        </script>

        <script>
            $(document).ready(function () {
                // Load page
                loadPage('dashboard');

                // Navigasi SPA
                $(document).on('click', '[data-page]', function (e) {
                    e.preventDefault();
                    $('.wrapper').removeClass('toggled');
                    let page = $(this).data('page');
                    loadPage(page);
                });
            });
        </script>

        <script src="assets/js/survei.js"></script>
    </div>
</body>

</html>