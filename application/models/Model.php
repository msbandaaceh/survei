<?php

class Model extends CI_Model
{
    private $db_sso;

    public function __construct()
    {
        parent::__construct();

        // Inisialisasi variabel private dengan nilai dari session
        $this->db_sso = $this->session->userdata('sso_db');
    }

    private function add_audittrail($action, $title, $table, $descrip)
    {

        $params = [
            'tabel' => 'sys_audittrail',
            'data' => [
                'datetime' => date("Y-m-d H:i:s"),
                'ipaddress' => $this->input->ip_address(),
                'action' => $action,
                'title' => $title,
                'tablename' => $table,
                'description' => $descrip,
                'username' => $this->session->userdata('username')
            ]
        ];

        $this->apihelper->post('apiclient/simpan_data', $params);
    }

    public function cek_aplikasi($id)
    {
        $params = [
            'tabel' => 'ref_client_app',
            'kolom_seleksi' => 'id',
            'seleksi' => $id
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            $this->session->set_userdata(
                [
                    'nama_client_app' => $user_data['nama_app'],
                    'deskripsi_client_app' => $user_data['deskripsi']
                ]
            );
        }
    }

    public function kirim_notif($data)
    {
        $params = [
            'tabel' => 'sys_notif',
            'data' => $data
        ];

        $this->apihelper->post('apiclient/simpan_data', $params);
    }

    function api_data_pegawai($pegawai_id)
    {
        $params = [
            'tabel' => 'v_pegawai',
            'kolom_seleksi' => 'id',
            'seleksi' => $pegawai_id
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $pegawai_data = $result['response']['data'][0];
            return $pegawai_data;
        }
    }

    function api_semua_data_pegawai()
    {
        $params = [
            'tabel' => 'v_pegawai'
        ];

        $result = $this->apihelper->get('apiclient/get_data_tabel', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $pegawai_data = $result['response']['data'];
            return $pegawai_data;
        }
    }

    public function get_seleksi_array($tabel, $where = [], $order_by = [])
    {
        try {
            $this->db->where('hapus', '0');

            // multiple where
            if (!empty($where)) {
                foreach ($where as $kolom => $nilai) {
                    $this->db->where($kolom, $nilai);
                }
            }

            // multiple order by
            if (!empty($order_by)) {
                foreach ($order_by as $kolom => $arah) {
                    $this->db->order_by($kolom, $arah); // ASC / DESC
                }
            }

            return $this->db->get($tabel);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function simpan_data($tabel, $data)
    {
        try {
            $this->db->insert($tabel, $data);
            $title = 'E-SUPEL';
            $descrip = "Simpan Data <br />Insert tabel <b>" . $tabel . "</b>[]";
            $this->add_audittrail("INSERT", $title, $tabel, $descrip);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function pembaharuan_data($tabel, $data, $kolom_seleksi, $seleksi)
    {
        try {
            $this->db->where($kolom_seleksi, $seleksi);
            $this->db->update($tabel, $data);
            $title = 'E-SUPEL';
            $descrip = "Pembaharuan Data <br />Update tabel <b>" . $tabel . "</b>[Pada kolom<b>" . $kolom_seleksi . "</b>]";
            $this->add_audittrail("UPDATE", $title, $tabel, $descrip);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    # AWAL FUNGSI PENGISIAN SURVEI
    public function get_petugas()
    {
        $this->db->order_by('aktif', 'DESC');
        $this->db->order_by('id', 'ASC');
        return $this->db->select('*')->from('v_petugas')->where('aktif', '1')->get()->result();
    }


    # AKHIR FUNGSI PENGISIAN SURVEI

    public function all_petugas_data()
    {
        $this->db->order_by('aktif', 'DESC');
        $this->db->order_by('id', 'ASC');
        return $this->db->select('*')->from('v_petugas')->get()->result();
    }

    public function all_posisi_data()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->select('*')->from('ref_posisi')->get()->result();
    }

    public function all_nilai_petugas_periode($data)
    {
        $jenis = $data['jenis'];

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }
        return $this->db->select('*')->from('v_nilai_petugas')->get()->result();
    }

    public function nilai_petugas_periode($data)
    {
        $jenis = $data['jenis'];
        $this->db->select('petugas_id, pegawai_id,
        	ROUND(AVG(skor_ramah),2) as rata_keramahan,
        	ROUND(AVG(skor_puas),2) as rata_kepuasan,
        	ROUND((AVG(skor_ramah) + AVG(skor_puas)) / 2,2) as total_skor');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }
        $this->db->group_by('petugas_id');
        $query = $this->db->get('v_nilai_petugas')->result();

        $data = [];
        foreach ($query as $row) {
            $data_pegawai = $this->api_data_pegawai($row->pegawai_id);

            $obj = new stdClass();
            $obj->nama = $data_pegawai['nama_gelar'];
            $obj->rata_keramahan = $row->rata_keramahan;
            $obj->rata_kepuasan = $row->rata_kepuasan;
            $obj->rata_total = $row->total_skor;

            $data[] = $obj;
        }
        return $data;
    }

    public function petugas_terbaik_periode($data)
    {
        $jenis = $data['jenis'];
        // Hitung rata-rata keramahan, kepuasan, rata-rata total, dan jumlah responden
        $this->db->select('petugas_id, pegawai_id,
        	ROUND(AVG(skor_ramah),2) as rata_keramahan,
        	ROUND(AVG(skor_puas),2) as rata_kepuasan,
        	ROUND((AVG(skor_ramah) + AVG(skor_puas)) / 2,2) as rata_total,
        	COUNT(*) as jumlah_responden');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        $this->db->group_by('petugas_id');
        // Urutkan berdasarkan rata-rata total (rata-rata keramahan + kepuasan) DESC, kemudian jumlah responden DESC jika rata-rata sama
        $this->db->order_by('rata_total', 'DESC');
        $this->db->order_by('jumlah_responden', 'DESC');
        $this->db->limit('3');
        $query = $this->db->get('v_nilai_petugas')->result();

        $data = [];
        foreach ($query as $row) {
            $data_pegawai = $this->api_data_pegawai($row->pegawai_id);

            $obj = new stdClass();
            $obj->nama = $data_pegawai['nama_gelar'];
            $obj->foto = $this->session->userdata('sso_server') . $data_pegawai['foto'];
            $obj->rata_keramahan = $row->rata_keramahan;
            $obj->rata_kepuasan = $row->rata_kepuasan;
            $obj->rata_total = $row->rata_total;
            $obj->jumlah_responden = $row->jumlah_responden;

            $data[] = $obj;
        }
        return $data;
    }

    public function ambil_tahun_survei()
    {
        $this->db->select('YEAR(tgl_nilai) as tahun');
        $this->db->group_by('tahun');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get('v_nilai_petugas')->result();
    }

    public function nilai_keramahan_periode($data)
    {
        $jenis = $data['jenis'];
        $this->db->select('ROUND(AVG(skor_ramah),2) as rata_keramahan');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        return $this->db->get('v_nilai_petugas');
    }

    public function nilai_kepuasan_periode($data)
    {
        $jenis = $data['jenis'];
        $this->db->select('ROUND(AVG(skor_puas),2) as rata_kepuasan');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        return $this->db->get('v_nilai_petugas');
    }

    /**
     * Statistik per petugas dengan detail lengkap
     */
    public function statistik_per_petugas($data)
    {
        $jenis = $data['jenis'];
        $this->db->select('petugas_id, pegawai_id,
        	ROUND(AVG(skor_ramah),2) as rata_keramahan,
        	ROUND(AVG(skor_puas),2) as rata_kepuasan,
        	ROUND((AVG(skor_ramah) + AVG(skor_puas)) / 2,2) as rata_total,
        	COUNT(*) as jumlah_responden,
        	MIN(skor_ramah) as min_keramahan,
        	MAX(skor_ramah) as max_keramahan,
        	MIN(skor_puas) as min_kepuasan,
        	MAX(skor_puas) as max_kepuasan');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        $this->db->group_by('petugas_id');
        $this->db->order_by('rata_total', 'DESC');
        $this->db->order_by('jumlah_responden', 'DESC');
        $query = $this->db->get('v_nilai_petugas')->result();

        $data = [];
        foreach ($query as $row) {
            $data_pegawai = $this->api_data_pegawai($row->pegawai_id);

            $obj = new stdClass();
            $obj->nama = $data_pegawai['nama_gelar'];
            $obj->rata_keramahan = $row->rata_keramahan;
            $obj->rata_kepuasan = $row->rata_kepuasan;
            $obj->rata_total = $row->rata_total;
            $obj->jumlah_responden = $row->jumlah_responden;
            $obj->min_keramahan = $row->min_keramahan;
            $obj->max_keramahan = $row->max_keramahan;
            $obj->min_kepuasan = $row->min_kepuasan;
            $obj->max_kepuasan = $row->max_kepuasan;

            $data[] = $obj;
        }
        return $data;
    }

    /**
     * Distribusi skor keramahan (berapa banyak yang memberikan skor 1, 2, 3, 4, 5)
     */
    public function distribusi_skor_keramahan($data)
    {
        $jenis = $data['jenis'];

        $this->db->select('skor_ramah as skor, COUNT(*) as jumlah');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        $this->db->group_by('skor_ramah');
        $this->db->order_by('skor_ramah', 'ASC');
        return $this->db->get('v_nilai_petugas')->result();
    }

    /**
     * Distribusi skor kepuasan
     */
    public function distribusi_skor_kepuasan($data)
    {
        $jenis = $data['jenis'];

        $this->db->select('skor_puas as skor, COUNT(*) as jumlah');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        $this->db->group_by('skor_puas');
        $this->db->order_by('skor_puas', 'ASC');
        return $this->db->get('v_nilai_petugas')->result();
    }

    /**
     * Trend penilaian bulanan
     */
    public function trend_penilaian_bulanan($data)
    {
        $jenis = $data['jenis'];

        $this->db->select('MONTH(tgl_nilai) as bulan, 
			YEAR(tgl_nilai) as tahun,
			ROUND(AVG(skor_ramah),2) as rata_keramahan,
			ROUND(AVG(skor_puas),2) as rata_kepuasan,
			COUNT(*) as jumlah_responden');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        $this->db->group_by('MONTH(tgl_nilai), YEAR(tgl_nilai)');
        $this->db->order_by('tahun', 'ASC');
        $this->db->order_by('bulan', 'ASC');
        return $this->db->get('v_nilai_petugas')->result();
    }

    /**
     * Statistik per hari dalam seminggu
     */
    public function statistik_per_hari($data)
    {
        $jenis = $data['jenis'];

        $this->db->select('DAYNAME(tgl_nilai) as nama_hari,
			DAYOFWEEK(tgl_nilai) as hari,
			ROUND(AVG(skor_ramah),2) as rata_keramahan,
			ROUND(AVG(skor_puas),2) as rata_kepuasan,
			COUNT(*) as jumlah_responden');

        if ($jenis == '0') {
            $this->db->where('YEAR(tgl_nilai)', date('Y'));
        } elseif ($jenis == '1') {
            $tahun = $data['data_nilai']['tahun'];
            $triwulan = $data['data_nilai']['triwulan'];

            switch ($triwulan) {
                case '1':
                    $tgl_awal = $tahun . '-01-01';
                    $tgl_akhir = $tahun . '-03-31';
                    break;
                case '2':
                    $tgl_awal = $tahun . '-04-01';
                    $tgl_akhir = $tahun . '-06-30';
                    break;
                case '3':
                    $tgl_awal = $tahun . '-07-01';
                    $tgl_akhir = $tahun . '-09-30';
                    break;
                case '4':
                    $tgl_awal = $tahun . '-10-01';
                    $tgl_akhir = $tahun . '-12-31';
                    break;
            }
            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        } else {
            $tgl_awal = $data['data_nilai']['tgl_awal'];
            $tgl_akhir = $data['data_nilai']['tgl_akhir'];

            $this->db->where('DATE(tgl_nilai) <= "' . $tgl_akhir . '"');
            $this->db->where('DATE(tgl_nilai) >= "' . $tgl_awal . '"');
        }

        $this->db->group_by('DAYOFWEEK(tgl_nilai)');
        $this->db->order_by('hari', 'ASC');
        return $this->db->get('v_nilai_petugas')->result();
    }
}