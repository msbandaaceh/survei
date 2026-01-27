<?php

class HalamanUtama extends MY_Controller
{
    public function index()
    {
        #die(var_dump($this->session->all_userdata()));
        $peran = $this->session->userdata('peran');

        $data['peran'] = $peran;
        $data['page'] = 'dashboard';

        $tahun_periode = $this->model->ambil_tahun_survei();

        /*
        if (!$this->input->post('jenis_periode')) {
            $params = [
                'jenis' => '0'
            ];
            $periode = 'TAHUN ' . date('Y');
        } else {
            $jenis_periode = $this->input->post('jenis_periode');
            if ($jenis_periode == '1') {
                $tahun = $this->input->post('tahun_periode');
                $triwulan = $this->input->post('triwulan');

                $params = [
                    'jenis' => $jenis_periode,
                    'data_nilai' => [
                        'tahun' => $tahun,
                        'triwulan' => $triwulan
                    ]
                ];

                $periode = 'TRIWULAN ' . $triwulan . ' TAHUN ' . $tahun;
            } else {
                $tgl_awal = $this->input->post('tgl_awal');
                $tgl_akhir = $this->input->post('tgl_akhir');

                $params = [
                    'jenis' => $jenis_periode,
                    'data_nilai' => [
                        'tgl_awal' => $tgl_awal,
                        'tgl_akhir' => $tgl_akhir
                    ]
                ];

                $periode = $this->tanggalhelper->konversiTanggal($tgl_awal) . ' s/d ' . $this->tanggalhelper->konversiTanggal($tgl_akhir);
            }
        }

        // Statistik umum
        $data['total_responden'] = count($this->model->all_nilai_petugas_periode($params));
        $data['total_keramahan'] = $this->model->nilai_keramahan_periode($params)->row()->rata_keramahan;
        $data['total_kepuasan'] = $this->model->nilai_kepuasan_periode($params)->row()->rata_kepuasan;

        // Statistik per petugas
        $data['statistik_petugas'] = $this->model->statistik_per_petugas($params);

        // Distribusi skor keramahan
        $data['distribusi_keramahan'] = $this->distribusi_skor_keramahan($params);

        // Distribusi skor kepuasan
        $data['distribusi_kepuasan'] = $this->model->distribusi_skor_kepuasan($params);

        // Statistik per hari dalam seminggu
        $data['statistik_hari'] = $this->model->statistik_per_hari($params);

        $data['periode'] = $periode;
        */
        $data['tahun_periode'] = $tahun_periode;
        $data['judul_halaman'] = 'Dashboard Penilaian Petugas Pelayanan';
        $data['breadcrumb'] = 'Dashboard Penilaian Petugas Layanan';

        $this->load->view('layout', $data);
    }

    public function page($halaman)
    {
        // Amanin nama file view agar tidak sembarang file bisa diload
        $allowed = [
            'dashboard',
            'data_penilaian',
            'data_petugas',
            'data_posisi'
        ];

        if (in_array($halaman, $allowed)) {
            $peran = $this->session->userdata('peran');

            $data['peran'] = $peran;
            $data['page'] = $halaman;

            // Jika halaman dashboard, siapkan data yang sama seperti index()
            if ($halaman == 'dashboard') {
                $tahun_periode = $this->model->ambil_tahun_survei();

                /*
                if (!$this->input->post('jenis_periode')) {
                    $params = [
                        'jenis' => '0'
                    ];
                    $periode = 'TAHUN ' . date('Y');
                } else {
                    $jenis_periode = $this->input->post('jenis_periode');
                    if ($jenis_periode == '1') {
                        $tahun = $this->input->post('tahun_periode');
                        $triwulan = $this->input->post('triwulan');

                        $params = [
                            'jenis' => $jenis_periode,
                            'data_nilai' => [
                                'tahun' => $tahun,
                                'triwulan' => $triwulan
                            ]
                        ];

                        $periode = 'TRIWULAN ' . $triwulan . ' TAHUN ' . $tahun;
                    } else {
                        $tgl_awal = $this->input->post('tgl_awal');
                        $tgl_akhir = $this->input->post('tgl_akhir');

                        $params = [
                            'jenis' => $jenis_periode,
                            'data_nilai' => [
                                'tgl_awal' => $tgl_awal,
                                'tgl_akhir' => $tgl_akhir
                            ]
                        ];

                        $periode = $this->tanggalhelper->konversiTanggal($tgl_awal) . ' s/d ' . $this->tanggalhelper->konversiTanggal($tgl_akhir);
                    }
                }

                // Statistik umum
                $data['total_responden'] = count($this->model->all_nilai_petugas_periode($params));
                $data['total_keramahan'] = $this->model->nilai_keramahan_periode($params)->row()->rata_keramahan;
                $data['total_kepuasan'] = $this->model->nilai_kepuasan_periode($params)->row()->rata_kepuasan;

                // Statistik per petugas
                $data['statistik_petugas'] = $this->model->statistik_per_petugas($params);

                // Distribusi skor keramahan
                $data['distribusi_keramahan'] = $this->model->distribusi_skor_keramahan($params);

                // Distribusi skor kepuasan
                $data['distribusi_kepuasan'] = $this->model->distribusi_skor_kepuasan($params);

                // Statistik per hari dalam seminggu
                $data['statistik_hari'] = $this->model->statistik_per_hari($params);

                $data['tahun_periode'] = $tahun_periode;
                $data['periode'] = $periode;
                */
                $data['tahun_periode'] = $tahun_periode;
                $data['judul_halaman'] = 'Dashboard Penilaian Petugas Pelayanan';
                $data['breadcrumb'] = 'Dashboard Penilaian Petugas Layanan';
            } elseif ($halaman == 'data_posisi') {
                $data['judul_halaman'] = 'Daftar Posisi Pelayanan';
                $data['breadcrumb'] = 'Manajemen Posisi Petugas Layanan';
            } elseif ($halaman == 'data_petugas') {
                $data['judul_halaman'] = 'Daftar Petugas Pelayanan';
                $data['breadcrumb'] = 'Manajemen Petugas Layanan';
            }

            $this->load->view($halaman, $data);
        } else {
            show_404();
        }
    }

    public function cek_token_sso()
    {
        $token = $this->input->cookie('sso_token');
        $cookie_domain = $this->config->item('sso_server');
        $sso_api = $cookie_domain . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            echo json_encode(['valid' => true]);
        } else {
            echo json_encode(['valid' => false, 'message' => 'Token Expired, Silakan login ulang', 'url' => $cookie_domain . 'login']);
        }
    }

    public function keluar()
    {
        $sso_server = $this->config->item('sso_server');
        $this->session->sess_destroy();
        redirect($sso_server . '/keluar');
    }

    public function show_daftar_posisi()
    {
        $query = $this->model->get_seleksi_array('ref_posisi', '', ['id' => 'AS'])->result();

        $data = [];
        foreach ($query as $row) {
            $data[] = [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'nama_posisi' => $row->nama_posisi
            ];
        }

        echo json_encode(['data_posisi' => $data]);
    }

    public function modal_posisi()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));

        $nama_posisi = "";

        if ($id == '-1') {
            $id = '';
            $judul = "TAMBAH DATA POSISI JABATAN PELAYANAN";
        } else {
            $judul = "EDIT DATA POSISI JABATAN PELAYANAN";
            $query = $this->model->get_seleksi_array('ref_posisi', ['id' => $id]);
            $nama_posisi = $query->row()->nama_posisi;
        }

        echo json_encode(
            array(
                'st' => 1,
                'judul' => $judul,
                'nama_posisi' => $nama_posisi,
                'id' => $id
            )
        );
        return;
    }

    public function simpan_posisi()
    {
        $this->form_validation->set_rules('nama_posisi', 'Nama Posisi Jabatan', 'trim|required');

        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => 2, 'message' => validation_errors()]);
            return;
        }
        $id = $this->input->post('id');
        $data = [
            'id' => $id,
            'nama_posisi' => $this->input->post('nama_posisi'),
        ];

        if ($id) {
            $data['modified_by'] = $this->session->userdata('fullname');
            $data['modified_on'] = date('Y-m-d H:i:s');
            $result = $this->model->pembaharuan_data('ref_posisi', $data, 'id', $id);
        } else {
            $data['created_by'] = $this->session->userdata('fullname');
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->model->simpan_data('ref_posisi', $data);
        }

        if ($result === 1) {
            echo json_encode(['success' => 1, 'message' => 'Posisi Jabatan Pelayanan Berhasil Di Simpan']);
        } else {
            echo json_encode(['success' => 3, 'message' => 'Posisi Jabatan Pelayanan Gagal Di Simpan, Periksa Kembali']);
        }
    }

    public function hapus_posisi()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $hapus = $this->model->pembaharuan_data('ref_posisi', ['hapus' => '1'], 'id', $id);

        if ($hapus == 1) {
            echo json_encode(
                array(
                    'st' => 1
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => 0
                )
            );
        }

        return;
    }

    public function show_daftar_petugas()
    {
        $query = $this->model->get_seleksi_array('v_petugas', '', ['aktif' => 'DESC', 'id' => 'ASC'])->result();

        $data = [];
        foreach ($query as $row) {
            $data_pegawai = $this->model->api_data_pegawai($row->pegawai_id);

            $data[] = [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'nama_petugas' => $data_pegawai['nama_gelar'],
                'nama_posisi' => $row->nama_posisi,
                'aktif' => $row->aktif
            ];
        }

        echo json_encode(['data_petugas' => $data]);
    }

    public function modal_petugas()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));

        $query_pegawai = $this->model->api_semua_data_pegawai();
        $data_pegawai = [];
        foreach ($query_pegawai as $row) {
            $data_pegawai[$row['id']] = $row['nama_gelar'];
        }

        $query_posisi = $this->model->get_seleksi_array('ref_posisi')->result();
        $data_posisi = [];
        foreach ($query_posisi as $row) {
            $data_posisi[$row->id] = $row->nama_posisi;
        }

        if ($id == '-1') {
            $id = '';
            $judul = "TAMBAH DATA PETUGAS";
            $cbPegawai = form_dropdown('pegawai', $data_pegawai, '', 'form-select id="pegawai"');
            $cbPosisi = form_dropdown('posisi', $data_posisi, '', 'form-select id="posisi"');
        } else {
            $judul = "EDIT DATA PETUGAS";
            $query = $this->model->get_seleksi_array('petugas', ['id' => $id]);
            $cbPegawai = form_dropdown('pegawai', $data_pegawai, $query->row()->pegawai_id, 'form-select id="pegawai"');
            $cbPosisi = form_dropdown('posisi', $data_posisi, $query->row()->posisi_id, 'form-select id="posisi"');
        }

        echo json_encode(
            array(
                'st' => 1,
                'judul' => $judul,
                'id' => $id,
                'pegawai' => $cbPegawai,
                'posisi' => $cbPosisi
            )
        );
        return;
    }

    public function simpan_petugas()
    {

        $this->form_validation->set_rules('pegawai', 'Pegawai', 'trim|required');
        $this->form_validation->set_rules('posisi', 'Posisi', 'trim|required');

        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => 2, 'message' => validation_errors()]);
            return;
        }

        $id = $this->input->post('id');
        $pegawai = $this->input->post('pegawai');
        $posisi = $this->input->post('posisi');

        $data = [
            'id' => $id,
            'pegawai_id' => $pegawai,
            'posisi_id' => $posisi
        ];

        if ($id) {
            $data['modified_by'] = $this->session->userdata('fullname');
            $data['modified_on'] = date('Y-m-d H:i:s');
            $result = $this->model->pembaharuan_data('petugas', $data, 'id', $id);
        } else {
            $data['created_by'] = $this->session->userdata('fullname');
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->model->simpan_data('petugas', $data);

            $query_posisi = $this->model->get_seleksi_array('ref_posisi', ['id' => $posisi])->row();

            $pesanWA = "Assalamualaikum Wr. Wb.\n";
            $pesanWA .= "Anda Telah Ditunjuk Menjadi Petugas Pelayanan dalam aplikasi E-SUPEL (Survei Petugas Layanan) sebagai " . $query_posisi->nama_posisi . ".\n";
            $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";

            $dataNotif = array(
                'jenis_pesan' => 'e-supel',
                'id_pemohon' => $this->session->userdata("pegawai_id"),
                'pesan' => $pesanWA,
                'id_tujuan' => $pegawai,
                'created_by' => 'system',
                'created_on' => date('Y-m-d H:i:s')
            );

            $this->model->kirim_notif($dataNotif);
        }

        if ($result === 1) {
            echo json_encode(['success' => 1, 'message' => 'Posisi Jabatan Pelayanan Berhasil Di Simpan']);
        } else {
            echo json_encode(['success' => 3, 'message' => 'Posisi Jabatan Pelayanan Gagal Di Simpan, Periksa Kembali']);
        }
    }

    public function ubah_status()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));

        $query_petugas = $this->model->get_seleksi_array('v_petugas', ['id' => $id])->row();

        $aktif = $query_petugas->aktif;

        $nohp = $this->model->api_data_pegawai($query_petugas->pegawai_id);


        if ($aktif == '1') {
            $status = '0';

            $pesanWA = "Assalamualaikum Wr. Wb. \n";
            $pesanWA .= "Anda Telah Dinonaktifkan Dari Daftar Petugas Pelayanan dalam aplikasi E-SUPEL (Survei Petugas Layanan) sebagai " . $query_petugas->nama_posisi . ".\n";
            $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";
        } else {
            $status = '1';

            $pesanWA = "Assalamualaikum Wr. Wb.\n";
            $pesanWA .= "Anda Telah Ditunjuk Kembali Menjadi Petugas Pelayanan dalam aplikasi E-SUPEL (Survei Petugas Layanan) sebagai " . $query_petugas->nama_posisi . ".\n";
            $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";
        }

        $ubah = $this->model->pembaharuan_data('petugas', ['aktif' => $status], 'id', $id);

        $dataNotif = array(
            'jenis_pesan' => 'e-supel',
            'id_pemohon' => $this->session->userdata("pegawai_id"),
            'pesan' => $pesanWA,
            'id_tujuan' => $query_petugas->pegawai_id,
            'created_by' => 'system',
            'created_on' => date('Y-m-d H:i:s')
        );

        $this->model->kirim_notif($dataNotif);

        if ($ubah == 1) {
            echo json_encode(
                array(
                    'st' => 1
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => 0
                )
            );
        }

        return;
    }

    public function nilai_petugas()
    {
        $tahun_periode = $this->model->ambil_tahun_survei();

        if (!$this->input->post('jenis_periode')) {
            $data = [
                'jenis' => '0'
            ];

            $periode = 'TAHUN ' . date('Y');
        } else {
            $jenis_periode = $this->input->post('jenis_periode');
            if ($jenis_periode == '1') {
                $tahun = $this->input->post('tahun_periode');
                $triwulan = $this->input->post('triwulan');

                $data = [
                    'jenis' => $jenis_periode,
                    'data_nilai' => [
                        'tahun' => $tahun,
                        'triwulan' => $triwulan
                    ]
                ];

                $periode = 'TRIWULAN ' . $triwulan . ' TAHUN ' . $tahun;
            } else {
                $tgl_awal = $this->input->post('tgl_awal');
                $tgl_akhir = $this->input->post('tgl_akhir');

                $data = [
                    'jenis' => $jenis_periode,
                    'data_nilai' => [
                        'tgl_awal' => $tgl_awal,
                        'tgl_akhir' => $tgl_akhir
                    ]
                ];

                $periode = $this->tanggalhelper->konversiTanggal($tgl_awal) . ' s/d ' . $this->tanggalhelper->konversiTanggal($tgl_akhir);
            }
        }

        $nilai['total_responden'] = count($this->model->all_nilai_petugas_periode($data));
        $nilai['total_keramahan'] = $this->model->nilai_keramahan_periode($data)->row()->rata_keramahan;
        $nilai['total_kepuasan'] = $this->model->nilai_kepuasan_periode($data)->row()->rata_kepuasan;
        $nilai['petugas_terbaik'] = $this->model->petugas_terbaik_periode($data);
        $nilai['tahun_periode'] = $tahun_periode;
        $nilai['periode'] = $periode;
        $nilai['petugas'] = $this->model->nilai_petugas_periode($data);
        #die(var_dump($nilai));
        $this->load->view('admin/header');
        $this->load->view('admin/list_nilai_petugas', $nilai);
    }

    public function data_statistik()
    {
        if (!$this->input->post('jenis_periode')) {
            $params = [
                'jenis' => '0'
            ];
            $periode = 'TAHUN ' . date('Y');
        } else {
            $jenis_periode = $this->input->post('jenis_periode');
            if ($jenis_periode == '1') {
                $tahun = $this->input->post('tahun_periode');
                $triwulan = $this->input->post('triwulan');

                $params = [
                    'jenis' => $jenis_periode,
                    'data_nilai' => [
                        'tahun' => $tahun,
                        'triwulan' => $triwulan
                    ]
                ];

                $periode = 'TRIWULAN ' . $triwulan . ' TAHUN ' . $tahun;
            } else {
                $tgl_awal = $this->input->post('tgl_awal');
                $tgl_akhir = $this->input->post('tgl_akhir');

                $params = [
                    'jenis' => $jenis_periode,
                    'data_nilai' => [
                        'tgl_awal' => $tgl_awal,
                        'tgl_akhir' => $tgl_akhir
                    ]
                ];

                $periode = $this->tanggalhelper->konversiTanggal($tgl_awal) . ' s/d ' . $this->tanggalhelper->konversiTanggal($tgl_akhir);
            }
        }

        // Judul Statistik
        $judul_statistik = 'STATISTIK PENILAIAN PETUGAS LAYANAN PERIODE '.$periode;

        // Statistik umum
        $total_responden = count($this->model->all_nilai_petugas_periode($params));
        $total_keramahan = $this->model->nilai_keramahan_periode($params)->row()->rata_keramahan ? $this->model->nilai_keramahan_periode($params)->row()->rata_keramahan : '0';
        $total_kepuasan = $this->model->nilai_kepuasan_periode($params)->row()->rata_kepuasan ? $this->model->nilai_kepuasan_periode($params)->row()->rata_kepuasan : '0';

        // Distribusi skor keramahan
        $distribusi_keramahan = $this->model->distribusi_skor_keramahan($params);

        // Distribusi skor kepuasan
        $distribusi_kepuasan = $this->model->distribusi_skor_kepuasan($params);

        // Statistik per hari dalam seminggu
        $statistik_hari = $this->model->statistik_per_hari($params);

        // Statistik per petugas
        $statistik_petugas = $this->model->statistik_per_petugas($params);

        // Statistik Petugas Terbaik
        $petugas_terbaik = $this->model->petugas_terbaik_periode($params);

        // Statistik Seluruh Petugas Layanan
        $petugas = $this->model->model->nilai_petugas_periode($params);

        echo json_encode([
            'judul_statistik' => $judul_statistik,
            'statistik_petugas' => $statistik_petugas,
            'total_responden' => $total_responden,
            'total_keramahan' => $total_keramahan,
            'total_kepuasan' => $total_kepuasan,
            'distribusi_keramahan' => $distribusi_keramahan,
            'distribusi_kepuasan' => $distribusi_kepuasan,
            'petugas_terbaik' => $petugas_terbaik,
            'petugas' => $petugas,
            'statistik_hari' => $statistik_hari
        ]);
    }
}