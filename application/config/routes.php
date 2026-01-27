<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'halamanutama';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['cek_token'] = 'HalamanUtama/cek_token_sso';
$route['keluar'] = 'HalamanUtama/keluar';

$route['show_daftar_petugas'] = 'HalamanUtama/show_daftar_petugas';
$route['modal_petugas'] = 'HalamanUtama/modal_petugas';
$route['simpan_petugas'] = 'HalamanUtama/simpan_petugas';
$route['ubah_status'] = 'HalamanUtama/ubah_status';

$route['show_daftar_posisi'] = 'HalamanUtama/show_daftar_posisi';
$route['modal_posisi'] = 'HalamanUtama/modal_posisi';
$route['simpan_posisi'] = 'HalamanUtama/simpan_posisi';
$route['hapus_posisi'] = 'HalamanUtama/hapus_posisi';

$route['data_statistik'] = 'HalamanUtama/data_statistik';

$route['survei'] = 'HalamanSurvei';
$route['v_modal_petugas'] = 'HalamanSurvei/modal_petugas';
$route['simpan_penilaian'] = 'HalamanSurvei/simpan_penilaian';

