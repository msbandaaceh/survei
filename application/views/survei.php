<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/e-supel.png" type="image/png" />
    <!--plugins-->
    <link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="assets/css/dark-theme.css" />
    <link rel="stylesheet" href="assets/css/semi-dark.css" />
    <link rel="stylesheet" href="assets/css/header-colors.css" />
    <title>Survei Penilaian Petugas Layanan</title>

    <style>
        .star-rating {
            direction: rtl;
            font-size: 2rem;
            unicode-bidi: bidi-override;
            display: inline-block;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: gold;
        }

        .nama {
            font-size: 25px;
            color: white;
            text-shadow:
                -2px -2px 0 black,
                2px -2px 0 black,
                -2px 2px 0 black,
                2px 2px 0 black;
            /* garis tepi hitam */
        }
    </style>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            role="button" aria-expanded="false">
                            <img src="assets/images/e-supel.png" class="logo-icon" alt="logo icon">
                            <div class="user-info ps-3">
                                <h4 class="logo-text">E-SUPEL (Elektronik Survei Pelayanan)</h4>
                            </div>
                        </a>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <?php
                $no = 1;
                if (isset($petugas) && !empty($petugas)) { ?>
                    <div class="row row-cols-2 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 product-grid">
                        <?php
                        foreach ($petugas as $person) {
                            ?>
                            <div class="col">
                                <div class="card radius-15 position-relative overflow-hidden">
                                    <img src="<?= $person['foto'] ?>" height="100%" width="100%" alt="">
                                    <div class="card-img-overlay d-flex align-items-end p-0">
                                        <div class="card-body text-center">
                                            <div class="p-1 radius-15">
                                                <h5 class="mb-0 mt-5 text-white nama"><?= $person['nama_petugas'] ?></h5>
                                                <p class="mb-3 text-white"><?= $person['posisi'] ?></p>
                                                <div class="d-grid">
                                                    <button class="btn btn-white radius-15" data-bs-toggle="modal"
                                                        data-bs-target="#modalPenilaian"
                                                        onclick="BukaModalPenilaian('<?= $person['id'] ?>')">Berikan
                                                        Penilaian</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $no++;
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <div class="col">
                            <div class="card radius-15">
                                <div class="card-body text-center">
                                    Belum Ada Petugas Pelayanan
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="modal fade" id="modalPenilaian" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formPenilaian">
                        <div class="modal-body">
                            <input type="hidden" id="id_" name="id" class="form-control" />
                            <div class="card border-primary border-bottom border-3 border-0 align-items-center">
                                <img id="foto" class="card-img-top" alt="Foto Pegawai">
                                <div class="card-body">
                                    <hr>
                                    <h5 class="card-title text-primary" id="nama_"></h5>
                                    <p class="card-text" id="posisi_"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-center">
                                    Bagaimana keramahan petugas saat melayani?
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-center">
                                    <div class="star-rating">
                                        <input type="radio" id="ramah5" name="ramah" value="5" />
                                        <label for="ramah5">★</label>
                                        <input type="radio" id="ramah4" name="ramah" value="4" />
                                        <label for="ramah4">★</label>
                                        <input type="radio" id="ramah3" name="ramah" value="3" />
                                        <label for="ramah3">★</label>
                                        <input type="radio" id="ramah2" name="ramah" value="2" />
                                        <label for="ramah2">★</label>
                                        <input type="radio" id="ramah1" name="ramah" value="1" />
                                        <label for="ramah1">★</label>
                                    </div>
                                    <p id="ramah-description" style="font-weight: bold; color: #333;"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-center">
                                    Bagaimana tingkat kepuasan anda dalam menerima layanan?
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-center">
                                    <div class="star-rating">
                                        <input type="radio" id="puas5" name="puas" value="5" />
                                        <label for="puas5">★</label>
                                        <input type="radio" id="puas4" name="puas" value="4" />
                                        <label for="puas4">★</label>
                                        <input type="radio" id="puas3" name="puas" value="3" />
                                        <label for="puas3">★</label>
                                        <input type="radio" id="puas2" name="puas" value="2" />
                                        <label for="puas2">★</label>
                                        <input type="radio" id="puas1" name="puas" value="1" />
                                        <label for="puas1">★</label>
                                    </div>
                                    <p id="puas-description" style="font-weight: bold; color: #333;"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>

        <footer class="page-footer">
            <p class="mb-0">Copyright © 2025. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!--start switcher-->

    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/notifications/js/lobibox.min.js"></script>
    <script src="assets/plugins/notifications/js/notifications.min.js"></script>

    <!--app JS-->
    <script src="assets/js/app.js"></script>
</body>

</html>