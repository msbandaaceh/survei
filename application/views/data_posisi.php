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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div>
                                <button class="btn btn-primary mb-3 mb-lg-0"
                                    onclick="BukaModalPosisi('<?php echo base64_encode($this->encryption->encrypt(-1)); ?>')"
                                    data-bs-toggle="modal" data-bs-target="#modal-posisi">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-20">
                    <div class="card-body">
                        <div id="daftarPosisi"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="modal fade" id="modal-posisi" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="formPosisi">
                            <div class="alert alert-info text-center" role="alert">
                                Informasi Posisi Jabatan Layanan
                            </div>
                            <input type="hidden" id="id_" name="id" class="form-control" />
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Posisi<code> *</code></label>
                                <input type="text" id='nama_posisi_' name='nama_posisi' autocomplete="off" required
                                    class="form-control" placeholder="Nama Posisi" />
                            </div>
                            <code>* Wajib diisi</code>
                            <div class="form-group">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                        Simpan
                                    </button>
                                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadDaftarPosisi();
    });
</script>