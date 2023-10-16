<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public $parent_modul = 'Dashboard';
	public $title = 'Dashboard';

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('LoggedIN') == FALSE || $this->session->userdata('hak_akses') == 'member') redirect('auth/logout');
	}

	public function index()
	{
		activityLog($this->parent_modul,'','');
		$data['title'] = $this->title;
		$data['subtitle'] = 'Dashboard';
		$data['pengguna'] = $this->GeneralModel->get_general('v_pengguna');
		$data['produk'] = $this->GeneralModel->get_by_id_general('v_produk','uuid_toko',$this->session->userdata('uuid_toko'));
		$data['toko'] = $this->GeneralModel->get_general('ms_toko');
		$data['content'] = 'panel/dashboard/index';
		$this->load->view('panel/content', $data);
	}

	public function setToko($param1=''){
		$dataToko = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',$param1);
		foreach ($dataToko as $key) {
			$data = array(
				'uuid_toko' => $key->uuid_toko,
				'nama_toko' => $key->nama_toko,
				'no_wa' => $key->no_wa,
			);
		}
		$this->session->set_userdata($data);
		$this->session->set_flashdata('notif','<div class="alert alert-success">Toko berhasil dipilih</div>');
		redirect('panel/dashboard');
	}

}
