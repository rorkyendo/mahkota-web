<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
  {
	parent::__construct();
  }

	public function index()
 {
	if($this->session->userdata('LoggedIN') != TRUE && !empty($_COOKIE['code'])){
		$cekUser = $this->GeneralModel->get_by_id_general('v_pengguna','login_token',$_COOKIE['code']);
		if ($cekUser) {
			foreach ($cekUser as $key) {
				$dataAkun = array(
					'id_pengguna' => $key->id_pengguna,
					'nama_lengkap' => $key->nama_lengkap,
					'username' => $key->username,
					'no_wa' => $key->no_wa,
					'no_telp' => $key->no_telp,
					'email' => $key->email,
					'foto_pengguna' => $key->foto_pengguna,
					'hak_akses' => $key->hak_akses,
					'nama_toko' => $key->nama_toko,
					'uuid_toko' => $key->uuid_toko,
					'status_member' => $key->status_member,
					'LoggedIN' => TRUE
				);
			}
	
			$this->session->set_userdata($dataAkun);
			$updateLogin = array(
				'last_login' => date('Y-m-d H:i:s'),
				'login_token' => sha1($dataAkun['username']).strtotime(date('Y-m-d H:i:s'))
			);
			$this->GeneralModel->update_general('ms_pengguna','id_pengguna',$dataAkun['id_pengguna'],$updateLogin);
			setcookie('code', $updateLogin['login_token'], time() + (86400 * 30), "/");
			$this->session->set_flashdata('notif','<div class="alert alert-success">Login Berhasil</div>');
			if ($dataAkun['hak_akses']!='member') {
				if ($dataAkun['hak_akses']=='kasir' && $this->KasirModel->cekOpenedTime($dataAkun['id_pengguna'])) {
					redirect('panel/kasir/bukaKasir');
				}else{
					redirect('panel/dashboard');
				}
			}else{
				redirect('/');
			}
		}
	}
	$data['title'] = 'Home';
	$data['content'] = 'frontend2/home';
	$data['promosi'] = $this->GeneralModel->get_by_id_general_order_by('ms_promosi','status_promosi','Y','urutan_promosi','ASC');
	$data['kategori_produk'] = $this->GeneralModel->get_general('ms_kategori_produk');
	$this->load->view('frontend2/content', $data);
 }

 public function privacyPolicy()
 {
	$data['content'] = 'frontend/privacyPolicy';
	$this->load->view('frontend/content', $data);
 }

}