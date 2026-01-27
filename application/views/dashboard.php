<style>
    .chart-container-1 {
        position: relative;
        height: 400px;
        width: 100%;
    }
</style>


<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">
        <?= $judul_halaman ?>
    </div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;" data-page="dashboard"><i
                            class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= $breadcrumb ?>
                </li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formPeriode" method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <label for="jenis_periode" class="form-label">Jenis Periode</label>
                                    <select class="form-select" id="jenis_periode" name="jenis_periode"
                                        onchange="gantiJenisPeriode()">
                                        <option value="1" <?= (!isset($_POST['jenis_periode']) || $_POST['jenis_periode'] == '1') ? 'selected' : '' ?>>Triwulan</option>
                                        <option value="2" <?= (isset($_POST['jenis_periode']) && $_POST['jenis_periode'] == '2') ? 'selected' : '' ?>>Periode Tanggal</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group" id="tahun_periode"
                                        style="<?= (isset($_POST['jenis_periode']) && $_POST['jenis_periode'] == '2') ? 'display: none;' : '' ?>">
                                        <label for="tahun_periode" class="form-label">Tahun Periode</label>
                                        <select class="form-select" name="tahun_periode" id="select_tahun_periode">
                                            <?php foreach ($tahun_periode as $tahun) { ?>
                                                <option value="<?= $tahun->tahun ?>" <?= (isset($_POST['tahun_periode']) && $_POST['tahun_periode'] == $tahun->tahun) ? 'selected' : '' ?>>
                                                    <?= $tahun->tahun ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group" id="periode_tgl_awal"
                                        style="<?= (!isset($_POST['jenis_periode']) || $_POST['jenis_periode'] == '1') ? 'display: none;' : '' ?>">
                                        <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Tanggal Awal"
                                                id="tgl_awal" name="tgl_awal"
                                                value="<?= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group" id="triwulan"
                                        style="<?= (isset($_POST['jenis_periode']) && $_POST['jenis_periode'] == '2') ? 'display: none;' : '' ?>">
                                        <label for="triwulan" class="form-label">Periode Triwulan</label>
                                        <select class="form-select" name="triwulan" id="select_triwulan">
                                            <option value="1" <?= (isset($_POST['triwulan']) && $_POST['triwulan'] == '1') ? 'selected' : '' ?>>Triwulan I</option>
                                            <option value="2" <?= (isset($_POST['triwulan']) && $_POST['triwulan'] == '2') ? 'selected' : '' ?>>Triwulan II</option>
                                            <option value="3" <?= (isset($_POST['triwulan']) && $_POST['triwulan'] == '3') ? 'selected' : '' ?>>Triwulan III</option>
                                            <option value="4" <?= (isset($_POST['triwulan']) && $_POST['triwulan'] == '4') ? 'selected' : '' ?>>Triwulan IV</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="periode_tgl_akhir"
                                        style="<?= (!isset($_POST['jenis_periode']) || $_POST['jenis_periode'] == '1') ? 'display: none;' : '' ?>">
                                        <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Tanggal Akhir"
                                                id="tgl_akhir" name="tgl_akhir"
                                                value="<?= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-block btn-outline-primary waves-effect waves-light">Tampilkan
                                        Data</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Judul Statistik -->
        <div class="row">
            <div class="col-12">
                <h4 class="text-center" id="judul_statistik">
                </h4>
            </div>
        </div>

        <!-- Statistik Umum -->
        <div id="statistik_umum"></div>

        <!-- Petugas Terbaik -->
        <div id="petugas_terbaik"></div>

        <!-- Distribusi Skor -->
        <div id="distribusi_skor"></div>

        <!-- Statistik Per Petugas -->
        <div id="statistik_petugas"></div>
    </div>
</div>

<script>
    // Datepicker untuk tanggal
    $(document).ready(function () {

        // Inisialisasi datepicker untuk tanggal awal
        $('#tgl_awal').pickadate({
            selectMonths: true,
            selectYears: true,

            monthsFull: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            weekdaysFull: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            weekdaysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            today: 'Hari ini',
            clear: 'Hapus',
            close: 'OK',
            firstDay: 1,

            format: 'dd mmmm yyyy',          // tampilan user
            formatSubmit: 'yyyy-mm-dd',    // format DB
            hiddenName: true
        });

        $('#tgl_akhir').pickadate({
            selectMonths: true,
            selectYears: true,

            monthsFull: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            weekdaysFull: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            weekdaysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            today: 'Hari ini',
            clear: 'Hapus',
            close: 'OK',
            firstDay: 1,

            format: 'dd mmmm yyyy',          // tampilan user
            formatSubmit: 'yyyy-mm-dd',    // format DB
            hiddenName: true
        });

        // Set nilai awal jika sudah ada dari POST
        <?php if (isset($_POST['tgl_awal']) && $_POST['tgl_awal'] != '') { ?>
            $('#tgl_awal').val('<?= $_POST['tgl_awal'] ?>');
        <?php } ?>
        <?php if (isset($_POST['tgl_akhir']) && $_POST['tgl_akhir'] != '') { ?>
            $('#tgl_akhir').val('<?= $_POST['tgl_akhir'] ?>');
        <?php } ?>

        // Handle form submit dengan AJAX untuk SPA
        $(document).off('submit', '#formPeriode').on('submit', '#formPeriode', function (e) {
            e.preventDefault();
            var jenisPeriode = $('#jenis_periode').val();

            if (jenisPeriode == '2') {
                // Validasi tanggal jika pilih periode tanggal
                var tglAwal = $('#tgl_awal').val();
                var tglAkhir = $('#tgl_akhir').val();

                if (!tglAwal || !tglAkhir) {
                    notifikasi('Mohon pilih tanggal awal dan tanggal akhir!', 3);
                    return false;
                }

                if (tglAwal > tglAkhir) {
                    notifikasi('Tanggal awal tidak boleh lebih besar dari tanggal akhir!', 3);
                    return false;
                }
            }

            Swal.fire({
                title: 'Memuat Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form dengan AJAX
            var formData = new FormData(this);

            $.ajax({
                url: 'data_statistik',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Re-initialize datepicker setelah reload
                    //console.log(response);
                    Swal.close();
                    tampilkanStatistik(response);
                },
                error: function () {
                    Swal.close();
                    alert('Terjadi kesalahan saat memfilter data. Silakan coba lagi.');
                }
            });
        });
    });
</script>