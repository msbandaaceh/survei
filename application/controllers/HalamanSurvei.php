<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HalamanSurvei extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Model', 'model');
    }

    public function index()
    {
        $query = $this->model->get_seleksi_array('v_petugas', ['aktif' => '1'], ['id' => 'ASC'])->result();

        $data = [];
        foreach ($query as $row) {
            $data_pegawai = $this->model->api_data_pegawai($row->pegawai_id);

            $data['petugas'][] = [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'nama_petugas' => $data_pegawai['nama_gelar'],
                'foto' => $this->config->item('sso_server') . $data_pegawai['foto'],
                'posisi' => $row->nama_posisi,
                'aktif' => $row->aktif
            ];
        }

        $this->load->view('survei', $data);
    }

    public function modal_petugas()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));

        $judul = "PENILAIAN PETUGAS";
        $query = $this->model->get_seleksi_array('v_petugas', ['id' => $id]);
        $query_pegawai = $this->model->api_data_pegawai($query->row()->pegawai_id);

        $nama = $query_pegawai['nama_gelar'];
        $foto = $query_pegawai['foto'];

        $posisi = $query->row()->nama_posisi;

        echo json_encode(
            array(
                'st' => 1,
                'judul' => $judul,
                'nama' => $nama,
                'id' => $id,
                'posisi' => $posisi,
                'foto' => $this->config->item('sso_server') . $foto
            )
        );
        return;
    }

    public function simpan_penilaian()
    {
        $this->form_validation->set_rules('ramah', 'Keramahan Petugas', 'trim|required');
        $this->form_validation->set_rules('puas', 'Kepuasan Layanan', 'trim|required');
        $this->form_validation->set_rules('id', 'ID Petugas', 'trim|required');

        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $petugas_id = $this->input->post('id', TRUE);
        $ramah = $this->input->post('ramah', TRUE);
        $puas = $this->input->post('puas', TRUE);

        $data = [
            'petugas_id' => $petugas_id,
            'skor_ramah' => $ramah,
            'skor_puas' => $puas,
            'created_on' => date('Y-m-d H:i:s')
        ];

        $query = $this->model->simpan_data('nilai_survei', $data);

        if ($query == '1') {
            echo json_encode(['success' => true, 'message' => 'Berhasil Simpan Penilaian']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal Simpan Penilaian, Ulangi Lagi']);
        }
    }
}
