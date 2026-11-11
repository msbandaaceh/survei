<?php

/**
 * @property CI_Config $config
 * @property CI_Input $input
 * @property CI_Model $model
 * @property CI_Model $api
 * @property CI_Encryption $encryption
 * @property Apihelper $apihelper
 * @property TanggalHelper $tanggalhelper
 * @property Pdf $pdf
 * @property CI_Session $session
 * @property CI_Upload $upload
 * @property CI_Form_validation $form_validation
 */

class MY_Controller extends CI_Controller
{
    private $jwt_key;

    public function __construct()
    {
        parent::__construct();

        $this->jwt_key = $this->config->item('jwt_key'); // inisialisasi di sini
        $cookie_domain = $this->config->item('cookie_domain');
        $sso_server = $this->config->item('sso_server');

        $this->load->model('Model', 'model');

        if (!$this->session->userdata('logged_in')) {
            $token = $this->input->cookie('sso_token');
            if ($token) {
                $this->cek_token($token);
            } else {
                $this->session->sess_destroy();
                $redirect_url = current_url();
                setcookie('redirect_to', urlencode($redirect_url), time() + 300, "/", $cookie_domain);
                redirect($sso_server . 'login');
            }
        }

        # Cek Data Aplikasi Ini
        if (!$this->session->userdata('e-supel_logged_in')) {
            $this->model->cek_aplikasi($this->config->item('id_app'));
            
            $akses = [
                'super',
                'admin_satker',
                'validator_kepeg_satker',
                'validator_uk_satker',
                'validator_ptip_satker'
            ];

            #Cek peran pegawai
            if (in_array($this->session->userdata('role'), $akses)) {
                $this->session->set_userdata('peran', 'admin');
            } else {
                $sso_url = $this->config->item('sso_server'); // misalnya simpan di config
                $this->session->set_flashdata('info', '3');
                $this->session->set_flashdata('pesan', 'Anda tidak diizinkan mengakses aplikasi ini.');
                redirect($sso_url);
            }

            $this->session->set_userdata('e-supel_logged_in', TRUE);
        }

        $this->session->set_userdata('logged_in', TRUE);
    }

    protected function cek_token($token)
    {
        $cookie_domain = $this->config->item('sso_server');
        $sso_api = $cookie_domain . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'userid' => $data['user']['userid'],
                'status_plh' => $data['user']['status_plh'],
                'status_plt' => $data['user']['status_plt']
            ]);
            redirect(current_url());
        } else {
            redirect($cookie_domain . 'login');
        }
    }

    private function get_config_value($seleksi)
    {
        $params = [
            'tabel' => 'sys_config',
            'kolom_seleksi' => 'id',
            'seleksi' => $seleksi
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            return $user_data['value'];
        }
    }
}