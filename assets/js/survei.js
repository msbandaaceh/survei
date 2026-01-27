var result = config.result;
var pesan = config.pesan;

$(function () {
    $(document).off('submit', '#formPosisi').on('submit', '#formPosisi', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_posisi',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                if (res.success == '1') {
                    $('#modal-posisi').modal('hide');
                    loadDaftarPosisi();
                }
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });

    $(document).off('submit', '#formPetugas').on('submit', '#formPetugas', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_petugas',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                if (res.success == '1') {
                    $('#modal-petugas').modal('hide');
                    loadDaftarPetugas();
                }
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });
});

function loadPage(page) {
    cekToken();
    $('#app').html(`
        <div class="page-wrapper">
            <div class="page-content">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div class="text-center">
                    <span>Memuat Halaman... Harap Tunggu Sebentar</span>
                </div>
            </div>
        </div>
    `);
    $.get("halamanutama/page/" + page, function (data) {
        $('#app').html(data);
    }).fail(function () {
        $('#app').html(`
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="text-center p-4">Halaman tidak ditemukan.</div>
                </div>
            </div>
        `);
    });
}

function cekToken() {
    $.ajax({
        url: 'cek_token',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (!res.valid) {
                alert(res.message);
                window.location.href = res.url;
            }
        }
    });
}

function setJudul(text) {
    document.getElementById('judul_halaman').textContent = text;
}

function setBreadcrumb(text) {
    document.getElementById('breadcrumb').textContent = text;
}

function notifikasi(pesan, result) {
    let icon;
    if (result == '1') {
        sukses(pesan);
    } else if (result == '2') {
        peringatan(pesan);
    } else if (result == '3') {
        gagal(pesan);
    } else {
        peringatan(pesan);
    }
}

function sukses(pesan) {
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        html: '<h5>' + pesan + '</h5>',
        confirmButtonText: 'OK'
    });
}

function peringatan(pesan) {
    Swal.fire({
        icon: 'warning',
        title: 'Oops...',
        html: '<h5>' + pesan + '</h5>',
        confirmButtonText: 'OK'
    });
}

function gagal(pesan) {
    Swal.fire({
        icon: 'error',
        title: 'Galat',
        html: '<h5>' + pesan + '</h5>',
        confirmButtonText: 'OK'
    });
}

function gantiJenisPeriode() {
    var nilai = document.getElementById('jenis_periode').value;
    if (nilai == 1) {
        $('#tahun_periode').show();
        $('#triwulan').show();
        $('#periode_tgl_awal').hide();
        $('#periode_tgl_akhir').hide();
    } else {
        $('#tahun_periode').hide();
        $('#triwulan').hide();
        $('#periode_tgl_awal').show();
        $('#periode_tgl_akhir').show();
    }
}

function BukaModalPosisi(id) {
    $.post('modal_posisi', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#title").html("");
            $("#id_").val('');
            $("#nama_posisi_").val('');

            $("#title").append(json.judul);
            $("#id_").val(json.id);
            $("#nama_posisi_").val(json.nama_posisi);
        }
    });
}

function loadDaftarPosisi() {
    $.post('show_daftar_posisi', function (response) {
        try {
            const json = JSON.parse(response); // Pastikan server kirim JSON valid

            $('#daftarPosisi').html(''); // kosongkan wrapper

            if (!json.data_posisi || json.data_posisi.length === 0) {
                // Kalau kosong
                $('#daftarPosisi').html(`
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-info"><i class='bx bx-info-square'></i></div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-info">Informasi</h6>
                                        <div>Belum Ada Posisi Pelayanan yang Diinput. Terima kasih.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                return;
            }

            // Kalau ada data, buat tabelnya
            let data = `
            <div class="table-responsive">
				<table id="tabelPosisi" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Posisi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
            `;

            json.data_posisi.forEach((row, index) => {
                // Daftar Barang
                data += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td>${row.nama_posisi}</td>
                        <td class="text-center">
                            <div class="dropdown ms-auto">
								<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
								</a>
								<ul class="dropdown-menu">
									<li>
                                        <button type="button" class="dropdown-item btn btn-warning"
                                        data-bs-toggle="modal" data-bs-target="#modal-posisi"
                                        onclick="BukaModalPosisi('${row.id}')"><i
                                            class="mdi mdi-account-edit"></i> Ubah Data</button>
									</li>
									<li>
                                        <button type="button" class="dropdown-item btn btn-danger"
                                        onclick="hapusPosisi('${row.id}')"><i 
                                            class="mdi mdi-delete"></i> Hapus</button>
									</li>
								</ul>
							</div>
                        </td>
                    </tr>
                `;
            });

            data += `
                    </tbody>
                </table>
            </div>
            `;

            $('#daftarPosisi').append(data);

            $('#tabelPosisi').DataTable({ "searching": false });
        } catch (e) {
            console.error("Gagal parsing JSON:", e);
            $('#daftarPosisi').html('<div class="alert alert-danger">Gagal memuat data posisi.</div>');
        }
    });
}

function hapusPosisi(id) {
    Swal.fire({
        title: "<h5>HAPUS POSISI JABATAN INI</h5>",
        html: "<h5>Apa Anda Yakin Akan Menghapus Posisi Ini?</h5>",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD2A2A",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus !",
        cancelButtonText: "Tidak !"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('hapus_posisi', { id: id }, function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil !",
                        text: "Anda Sudah Menghapus Posisi",
                        icon: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke"
                    }).then(() => {
                        loadDaftarPosisi();
                    });
                } else {
                    Swal.fire("Gagal", "Anda Gagal Menghapus Posisi, Silakan Periksa Kembali", "error");
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Batal", "Anda Batal Menghapus Posisi", "info");
        }
    });
}

function BukaModalPetugas(id) {
    $.post('modal_petugas', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#title").html("");
            $("#id_").val('');
            $("#pegawai_").html('');
            $("#posisi_").html('');

            $("#title").append(json.judul);
            $("#id_").val(json.id);
            $("#pegawai_").append(json.pegawai);
            $("#posisi_").append(json.posisi);

            $('#pegawai').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modal-petugas .modal-content'),
                width: '100%',
                dropdownAutoWidth: true
            });

            $('#posisi').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modal-petugas .modal-content'),
                width: '100%',
                dropdownAutoWidth: true
            });

            if (json.id) {
                // Buat Select2 tidak bisa diedit
                $('#pegawai').prop('disabled', true);
            } else {
                $('#pegawai').prop('disabled', false);
            }
        }
    });
}

function loadDaftarPetugas() {
    $.post('show_daftar_petugas', function (response) {
        try {
            const json = JSON.parse(response); // Pastikan server kirim JSON valid

            $('#daftarPetugas').html(''); // kosongkan wrapper

            if (!json.data_petugas || json.data_petugas.length === 0) {
                // Kalau kosong
                $('#daftarPetugas').html(`
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-info"><i class='bx bx-info-square'></i></div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-info">Informasi</h6>
                                        <div>Belum Ada Penunjukan Petugas Pelayanan. Terima kasih.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                return;
            }

            // Kalau ada data, buat tabelnya
            let data = `
            <div class="table-responsive">
				<table id="tabelPetugas" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Petugas</th>
                            <th>Jabatan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
            `;

            json.data_petugas.forEach((row, index) => {

                if (row.aktif == '1') {
                    var badgeStatus = `<span class="badge bg-success">Aktif</span>`;
                } else {
                    var badgeStatus = `<span class="badge bg-danger">Tidak Aktif</span>`;
                }
                // Daftar Barang
                data += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td>${row.nama_petugas}</td>
                        <td>${row.nama_posisi}</td>
                        <td class="text-center">${badgeStatus}</td>
                        <td class="text-center">
                            <div class="dropdown ms-auto">
								<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
								</a>
								<ul class="dropdown-menu">
									<li>
                                        <button type="button" class="dropdown-item btn btn-warning"
                                        data-bs-toggle="modal" data-bs-target="#modal-petugas"
                                        onclick="BukaModalPetugas('${row.id}')"><i
                                            class="mdi mdi-account-edit"></i> Ubah Data</button>
									</li>
									<li>
                                        <button type="button" class="dropdown-item btn btn-danger"
                                        onclick="ubahStatus('${row.id}')"><i 
                                            class="mdi mdi-delete"></i> Ubah Status</button>
									</li>
								</ul>
							</div>
                        </td>
                    </tr>
                `;
            });

            data += `
                    </tbody>
                </table>
            </div>
            `;

            $('#daftarPetugas').append(data);

            $('#tabelPetugas').DataTable({ "searching": false });

        } catch (e) {
            console.error("Gagal parsing JSON:", e);
            $('#daftarPetugas').html('<div class="alert alert-danger">Gagal memuat data petugas.</div>');
        }
    });
}

function ubahStatus(id) {
    Swal.fire({
        title: "<h5>UBAH STATUS PETUGAS</h5>",
        html: "<h5>Apa Anda Yakin Akan Mengubah Status Petugas Ini?</h5>",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD2A2A",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Ubah !",
        cancelButtonText: "Tidak !"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('ubah_status', { id: id }, function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil !",
                        text: "Anda Sudah Mengubah Status Petugas",
                        icon: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke"
                    }).then(() => {
                        loadDaftarPetugas();
                    });
                } else {
                    Swal.fire("Gagal", "Anda Gagal Mengubah Status, Silakan Periksa Kembali", "error");
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Batal", "Anda Batal Mengubah Status", "info");
        }
    });
}

// Fungsi untuk ganti jenis periode (harus global untuk onchange)
function gantiJenisPeriode() {
    var jenisPeriode = $('#jenis_periode').val();

    if (jenisPeriode == '1') {
        // Triwulan
        $('#tahun_periode').show();
        $('#triwulan').show();
        $('#periode_tgl_awal').hide();
        $('#periode_tgl_akhir').hide();
        $('#tgl_awal').val('');
        $('#tgl_akhir').val('');
        $('#tgl_awal_kirim').val('');
        $('#tgl_akhir_kirim').val('');
    } else {
        // Periode Tanggal
        $('#tahun_periode').hide();
        $('#triwulan').hide();
        $('#periode_tgl_awal').show();
        $('#periode_tgl_akhir').show();
    }
}

let chartDistribusi = null;
let chartHari = null;

function tampilkanStatistik(response) {

    const json = JSON.parse(response); // Pastikan server kirim JSON valid

    /* ================= JUDUL ================= */
    $('#judul_statistik').html(json.judul_statistik || '');

    /* ================= STATISTIK UMUM ================= */
    $('#statistik_umum').html(`
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card radius-10 bg-gradient-cosmic">
                    <div class="card-body">
                        <p class="mb-0 text-white">Total Responden</p>
                        <h4 class="my-1 text-white">${json.total_responden || 0}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card radius-10 bg-gradient-ohhappiness">
                    <div class="card-body">
                        <p class="mb-0 text-white">Rata-rata Keramahan</p>
                        <h4 class="my-1 text-white">${json.total_keramahan || 0}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card radius-10 bg-gradient-kyoto">
                    <div class="card-body">
                        <p class="mb-0 text-dark">Rata-rata Kepuasan</p>
                        <h4 class="my-1 text-dark">${json.total_kepuasan || 0}</h4>
                    </div>
                </div>
            </div>
        </div>
    `);

    /* ================= PETUGAS TERBAIK ================= */
    $('#petugas_terbaik').html(`
        <div class="row">
            <div class="col"> 
                <h6 class="mb-0 text-uppercase">Petugas Layanan Terbaik</h6>
                    <hr> <div class="card m-b-20">
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    `);

    $('#distribusi_skor').html(`
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12"> 
            <h6 class="mb-0 text-uppercase">Distribusi Skor Keramahan dan Kepuasan</h6>
                <hr> <div class="card m-b-20">
                <div class="card-body">
                    <div class="chart-container-1 mt-4">
                        <canvas id="chart_distribusi_keramahan_kepuasan"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <h6 class="mb-0 text-uppercase">Statistik Per Hari dalam Seminggu</h6>
            <hr>
            <div class="card m-b-20">
                <div class="card-body">
                    <div class="chart-container-1 mt-4">
                        <canvas id="chart_statistik_hari"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `);

    /* ================= DISTRIBUSI ================= */
    const distribusiKeramahan = buildDistribusi(json.distribusi_keramahan);
    const distribusiKepuasan = buildDistribusi(json.distribusi_kepuasan);

    /* ================= STATISTIK HARI ================= */
    const hari = buildStatistikHari(json.statistik_hari);

    /* ================= RENDER CHART ================= */
    renderChartDistribusi(distribusiKeramahan, distribusiKepuasan);
    renderChartHari(hari.labels, hari.keramahan, hari.kepuasan);

    /* ================= STATISTIK PETUGAS ================= */
    renderStatistikPetugas(json.statistik_petugas);

    /* ================= PETUGAS TERBAIK ================= */
    renderPetugasTerbaik(json.petugas_terbaik);
}

function buildDistribusi(data = []) {
    let hasil = [0, 0, 0, 0, 0];
    data.forEach(d => {
        if (d.skor >= 1 && d.skor <= 5) {
            hasil[d.skor - 1] = parseInt(d.jumlah);
        }
    });
    return hasil;
}

function buildStatistikHari(data = []) {
    const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    let labels = [], keramahan = [], kepuasan = [];

    data.forEach(d => {
        labels.push(namaHari[d.hari - 1]);
        keramahan.push(parseFloat(d.rata_keramahan));
        kepuasan.push(parseFloat(d.rata_kepuasan));
    });

    return { labels, keramahan, kepuasan };
}

function renderChartDistribusi(keramahan, kepuasan) {
    if (chartDistribusi) chartDistribusi.destroy();

    const ctx = document.getElementById('chart_distribusi_keramahan_kepuasan').getContext('2d');
    chartDistribusi = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['1', '2', '3', '4', '5'],
            datasets: [
                { label: 'Kepuasan', data: kepuasan, backgroundColor: '#42e695' },
                { label: 'Keramahan', data: keramahan, backgroundColor: '#7f00ff' }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
}

function renderChartHari(labels, keramahan, kepuasan) {
    if (chartHari) chartHari.destroy();

    const ctx = document.getElementById('chart_statistik_hari').getContext('2d');
    chartHari = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Keramahan', data: keramahan, backgroundColor: '#7f00ff' },
                { label: 'Kepuasan', data: kepuasan, backgroundColor: '#42e695' }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true, max: 5 } } }
    });
}

function renderStatistikPetugas(statistik_petugas) {
    $('#statistik_petugas').html('');
    if (!statistik_petugas || statistik_petugas.length === 0) { // Kalau kosong 
        $('#statistik_petugas').html(`
        <div class="row" id="statistik_petugas"> 
            <div class="col-12"> 
                <h6 class="mb-0 text-uppercase">Statistik Detail Per Petugas</h6> 
                <hr> 
                <div class="card m-b-20"> 
                    <div class="card-body"> 
                        <div class="alert alert-info"> 
                            <div class="d-flex align-items-center"> 
                                <div class="font-35 text-info"><i class='bx bx-info-square'></i></div> 
                                <div class="ms-3"> 
                                    <h6 class="mb-0 text-info">Informasi</h6> 
                                    <div>Belum Ada Petugas Pelayanan Yang Dinilai.</div> 
                                </div>
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    `);
        return;
    }

    // Kalau ada data, buat tabelnya 
    let dataStatistikPetugas = `
    <div class="row"> 
        <div class="col-12"> 
            <h6 class="mb-0 text-uppercase">Statistik Detail Per Petugas</h6> 
            <hr> 
            <div class="card m-b-20"> 
                <div class="card-body"> 
                    <div class="table-responsive"> 
                        <table id="tabelStatistikPetugas" class="table table-striped table-bordered" style="width:100%"> 
                            <thead> 
                                <tr> 
                                    <th>NO</th>
                                    <th>NAMA PETUGAS</th> 
                                    <th>RATA-RATA KERAMAHAN</th> 
                                    <th>RATA-RATA KEPUASAN</th> 
                                    <th>RATA-RATA TOTAL</th> 
                                    <th>MIN KERAMAHAN</th> 
                                    <th>MAX KERAMAHAN</th> 
                                    <th>MIN KEPUASAN</th> 
                                    <th>MAX KEPUASAN</th> 
                                    <th>JUMLAH RESPONDEN</th> 
                                    </tr> 
                                </thead> 
                            <tbody> 
    `;

    statistik_petugas.forEach((row, index) => {
        // Daftar Statistik Petugas 
        dataStatistikPetugas += `
        <tr> 
            <td>${index + 1}</td> 
            <td>${row.nama}</td> 
            <td>${row.rata_keramahan} / 5.00</td> 
            <td>${row.rata_kepuasan} / 5.00</td> 
            <td>${row.rata_total} / 5.00</td> 
            <td>${row.min_keramahan}</td> 
            <td>${row.max_keramahan}</td> 
            <td>${row.min_kepuasan}</td> 
            <td>${row.max_kepuasan}</td> 
            <td>${row.jumlah_responden}</td> 
        </tr> `;
    });

    dataStatistikPetugas += `
    </tbody> 
    </table> 
    </div> 
    </div> 
    </div> 
    </div> 
    </div>
    `;

    $('#statistik_petugas').append(dataStatistikPetugas);
    $('#tabelStatistikPetugas').DataTable({ "searching": false });
}

function renderPetugasTerbaik(petugas_terbaik) {
    $('#petugas_terbaik').html('');

    if (!petugas_terbaik || petugas_terbaik.length === 0) {
        $('#petugas_terbaik').html(`
            <div class="row"> 
                <div class="col"> 
                    <h6 class="mb-0 text-uppercase">Petugas Layanan Terbaik</h6> 
                    <hr> 
                    <div class="card m-b-20"> 
                        <div class="card-body"> 
                            <div class="alert alert-info"> 
                                <div class="d-flex align-items-center"> 
                                    <div class="font-35 text-info"><i class='bx bx-info-square'></i></div> 
                                    <div class="ms-3"> 
                                        <h6 class="mb-0 text-info">Informasi</h6> 
                                        <div>Belum Ada Petugas Pelayanan Yang Dinilai.</div> 
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        `);
        return;
    }

    let dataStatistikPeringkat = `
        <div class="row">
            <div class="col"> 
                <h6 class="mb-0 text-uppercase">Petugas Layanan Terbaik</h6> 
                <hr> 
                <div class="card m-b-20"> 
                    <div class="card-body">
                        <div class="row">
    `;

    let peringkat = 1;
    petugas_terbaik.forEach((row, index) => {
        // Tentukan badge warna berdasarkan peringkat
        let badge_class = '';
        let badge_text = '';
        switch (peringkat) {
            case 1:
                badge_class = 'badge-warning';
                badge_text = 'PERINGKAT 1';
                break;
            case 2:
                badge_class = 'badge-secondary';
                badge_text = 'PERINGKAT 2';
                break;
            case 3:
                badge_class = 'badge-info';
                badge_text = 'PERINGKAT 3';
                break;
        }

        dataStatistikPeringkat += `
            <div class="col-lg-4 col-md-4 col-sm-4 text-center">
                <div class="card m-b-20 text-white bg-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <span class="badge ${badge_class} badge-lg">${badge_text}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <img class="rounded-circle shadow" alt="200x200" width="120"
                                    src="${row.foto}"
                                    title="Rata-rata Total: ${row.rata_total} / 5.00, Responden: ${row.jumlah_responden}"
                                    data-holder-rendered="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>${row.nama}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                Keramahan : ${row.rata_keramahan} / 5.00
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                Kepuasan : ${row.rata_kepuasan} / 5.00
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>Rata-rata Total: ${row.rata_total} / 5.00</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <small>Responden: ${row.jumlah_responden}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        peringkat++;
    });

    dataStatistikPeringkat += `
        </div>
        </div>
        </div>
        </div>
        </div>
    `;

    $('#petugas_terbaik').append(dataStatistikPeringkat);

}