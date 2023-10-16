<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Finance extends CI_Controller
{

	public $parent_modul = 'Finance';
	public $title = 'Finance';

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('LoggedIN') == FALSE || $this->session->userdata('hak_akses') == 'member') redirect('auth/logout');
		if (cekParentModul($this->parent_modul) == FALSE) redirect('panel/dashboard');
		if(empty($this->session->userdata('uuid_toko'))){
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Silahkan pilih toko terlebih dahulu</div>');
			redirect('panel/dashboard');
		}
		$this->akses_controller = $this->uri->segment(3);
	}

	public function indukGroupAkun($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->FinanceModel->getIndukGroupAkun($uuid_toko);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Induk Group Kas/Bank';
			$data['content'] = 'panel/finance/indukGroupKas/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahIndukGroupAkun($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'kode_induk_group_kas' => $this->input->post('kode_induk_group_kas'),
				'nama_induk_group_kas' => $this->input->post('nama_induk_group_kas'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'created_time' => date('Y-m-d H:i:s')
			);
			
            if ($this->GeneralModel->create_general('ms_induk_group_kas', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data induk group kas berhasil ditambahkan!</div>');
                redirect('panel/finance/indukGroupAkun');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data induk group kas gagal ditambahkan!</div>');
                redirect('panel/finance/indukGroupAkun');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Induk Group Kas/Bank';
			$data['content'] = 'panel/finance/indukGroupKas/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateIndukGroupAkun($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'kode_induk_group_kas' => $this->input->post('kode_induk_group_kas'),
				'nama_induk_group_kas' => $this->input->post('nama_induk_group_kas'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'updated_time' => date('Y-m-d H:i:s')
			);
                
            if ($this->GeneralModel->update_multi_id_general('ms_induk_group_kas','id_induk_group_kas', $param2, 'uuid_toko', $this->session->userdata('uuid_toko'), $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data induk group kas berhasil diubah!</div>');
                redirect('panel/finance/indukGroupAkun');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data induk group kas gagal diubah!</div>');
                redirect('panel/finance/indukGroupAkun');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Induk Group Kas/Bank';
			$data['content'] = 'panel/finance/indukGroupKas/update';
			$data['indukGroup'] = $this->GeneralModel->get_by_multi_id_general('ms_induk_group_kas','id_induk_group_kas',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusIndukGroupAkun($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
        if ($this->GeneralModel->delete_multi_id_general('ms_induk_group_kas', 'id_induk_group_kas', $param1, 'uuid_toko', $this->session->userdata('uuid_toko')) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data induk group kas berhasil dihapus!</div>');
			redirect('panel/finance/indukGroupAkun');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data induk group kas gagal dihapus!</div>');
			redirect('panel/finance/indukGroupAkun');
        }
	}

	public function daftarGroupAkun($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$kode_induk_group_kas = $this->input->post('kode_induk_group_kas');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->FinanceModel->getGroupAkun($uuid_toko,$kode_induk_group_kas);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Group Akun';
			$data['content'] = 'panel/finance/groupKas/index';
			$data['indukGroup'] =  $this->GeneralModel->get_by_id_general('ms_induk_group_kas','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['kode_induk_group_kas'] =  $this->input->get('kode_induk_group_kas');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahGroupAkun($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'kode_induk_group_kas' => $this->input->post('kode_induk_group_kas'),
				'kode_group_kas' => $this->input->post('kode_group_kas'),
				'nama_group_kas' => $this->input->post('nama_group_kas'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'created_time' => date('Y-m-d H:i:s')
			);
			
            if ($this->GeneralModel->create_general('ms_group_kas', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data group kas berhasil ditambahkan!</div>');
                redirect('panel/finance/daftarGroupAkun');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data group kas gagal ditambahkan!</div>');
                redirect('panel/finance/daftarGroupAkun');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Group Kas/Bank';
			$data['content'] = 'panel/finance/groupKas/create';
			$data['indukGroup'] =  $this->GeneralModel->get_by_id_general('ms_induk_group_kas','uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function updateGroupAkun($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'kode_induk_group_kas' => $this->input->post('kode_induk_group_kas'),
				'kode_group_kas' => $this->input->post('kode_group_kas'),
				'nama_group_kas' => $this->input->post('nama_group_kas'),
  				'updated_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'updated_time' => date('Y-m-d H:i:s')
			);
                
            if ($this->GeneralModel->update_multi_id_general('ms_group_kas','id_group_kas', $param2, 'uuid_toko', $this->session->userdata('uuid_toko'), $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data induk group kas berhasil diubah!</div>');
                redirect('panel/finance/daftarGroupAkun');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data induk group kas gagal diubah!</div>');
                redirect('panel/finance/daftarGroupAkun');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Group Kas/Bank';
			$data['content'] = 'panel/finance/groupKas/update';
			$data['indukGroup'] = $this->GeneralModel->get_by_id_general('ms_induk_group_kas','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['groupKas'] =  $this->GeneralModel->get_by_multi_id_general('ms_group_kas','id_group_kas',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusGroupAkun($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
        if ($this->GeneralModel->delete_multi_id_general('ms_group_kas', 'id_group_kas', $param1, 'uuid_toko', $this->session->userdata('uuid_toko')) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data induk group kas berhasil dihapus!</div>');
			redirect('panel/finance/daftarGroupAkun');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data induk group kas gagal dihapus!</div>');
			redirect('panel/finance/daftarGroupAkun');
        }
	}

	public function daftarAkun($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$kode_induk_group_kas = $this->input->post('kode_induk_group_kas');
			$kode_group_kas = $this->input->post('kode_group_kas');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->FinanceModel->getAkun($uuid_toko,$kode_induk_group_kas,$kode_group_kas);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Akun';
			$data['content'] = 'panel/finance/akunKas/index';
			$data['indukAkun'] =  $this->GeneralModel->get_by_id_general('ms_induk_group_kas','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['kode_induk_group_kas'] =  $this->input->get('kode_induk_group_kas');
			$data['group'] =  $this->GeneralModel->get_by_multi_id_general('ms_group_kas','kode_induk_group_kas',$data['kode_induk_group_kas'],'uuid_toko',$this->session->userdata('uuid_toko'));
			$data['kode_group_kas'] =  $this->input->get('kode_group_kas');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahAkun($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'kode_induk_group_kas' => $this->input->post('kode_induk_group_kas'),
				'kode_group_kas' => $this->input->post('kode_group_kas'),
				'kode_kas' => $this->input->post('kode_kas'),
				'nama_kas' => $this->input->post('nama_kas'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'created_time' => date('Y-m-d H:i:s')
			);
			
            if ($this->GeneralModel->create_general('ms_akun_kas', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data akun kas berhasil ditambahkan!</div>');
                redirect('panel/finance/daftarAkun');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data akun kas gagal ditambahkan!</div>');
                redirect('panel/finance/daftarAkun');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Akun Kas/Bank';
			$data['content'] = 'panel/finance/akunKas/create';
			$data['indukGroup'] =  $this->GeneralModel->get_by_id_general('ms_induk_group_kas','uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function updateAkun($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'kode_induk_group_kas' => $this->input->post('kode_induk_group_kas'),
				'kode_group_kas' => $this->input->post('kode_group_kas'),
				'kode_kas' => $this->input->post('kode_kas'),
				'nama_kas' => $this->input->post('nama_kas'),
  				'updated_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'updated_time' => date('Y-m-d H:i:s')
			);
                
            if ($this->GeneralModel->update_multi_id_general('ms_akun_kas','id_akun_kas', $param2, 'uuid_toko', $this->session->userdata('uuid_toko'), $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data akun kas berhasil diubah!</div>');
                redirect('panel/finance/daftarAkun');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data akun kas gagal diubah!</div>');
                redirect('panel/finance/daftarAkun');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Group Kas/Bank';
			$data['content'] = 'panel/finance/akunKas/update';
			$data['indukGroup'] =  $this->GeneralModel->get_by_id_general('ms_induk_group_kas','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['akunKas'] =  $this->GeneralModel->get_by_multi_id_general('ms_akun_kas','id_akun_kas',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function getGroupKas(){
		$kode_induk_group_kas = $this->input->post('kode_induk_group_kas');
		$data = $this->GeneralModel->get_by_multi_id_general('ms_group_kas','kode_induk_group_kas',$kode_induk_group_kas,'uuid_toko',$this->session->userdata('uuid_toko'));
		if($data){
			echo json_encode($data,JSON_PRETTY_PRINT);
		}else{
			echo 'false';
		}
	}

	public function hapusAkun($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
        if ($this->GeneralModel->delete_multi_id_general('ms_akun_kas', 'id_akun_kas', $param1, 'uuid_toko', $this->session->userdata('uuid_toko')) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data akun kas berhasil dihapus!</div>');
			redirect('panel/finance/daftarAkun');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data akun kas gagal dihapus!</div>');
			redirect('panel/finance/daftarAkun');
        }
	}

	public function rekening($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
			return $this->FinanceModel->getRekening($status);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Rekening';
			$data['content'] = 'panel/finance/rekening/index';
			if(!empty($this->input->get('status'))){
				$data['status'] = $this->input->get('status');
			}else{
				$data['status'] = 'Y';
			}
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahRekening($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'kode_rekening' => $this->input->post('kode_rekening'),
				'nama_rekening' => $this->input->post('nama_rekening'),
				'no_rekening' => $this->input->post('no_rekening'),
				'an_rekening' => $this->input->post('an_rekening'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'created_time' => date('Y-m-d H:i:s')
			);
			
            if ($this->GeneralModel->create_general('ms_rekening', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data rekening berhasil ditambahkan!</div>');
                redirect('panel/finance/rekening');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data rekening gagal ditambahkan!</div>');
                redirect('panel/finance/rekening');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Rekening';
			$data['content'] = 'panel/finance/rekening/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateRekening($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'kode_rekening' => $this->input->post('kode_rekening'),
				'nama_rekening' => $this->input->post('nama_rekening'),
				'no_rekening' => $this->input->post('no_rekening'),
				'an_rekening' => $this->input->post('an_rekening'),
				'status_rekening' => $this->input->post('status_rekening'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => date('Y-m-d H:i:s')
			);
                
            if ($this->GeneralModel->update_general('ms_rekening','id_rekening', $param2, $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data rekening berhasil diubah!</div>');
                redirect('panel/finance/rekening');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data rekening gagal diubah!</div>');
                redirect('panel/finance/rekening');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Rekening';
			$data['content'] = 'panel/finance/rekening/update';
			$data['rekening'] = $this->GeneralModel->get_by_id_general('ms_rekening','id_rekening',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusRekening($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
        if ($this->GeneralModel->delete_general('ms_rekening', 'id_rekening', $param1) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data rekening berhasil dihapus!</div>');
			redirect('panel/finance/rekening');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data rekening gagal dihapus!</div>');
			redirect('panel/finance/rekening');
        }
	}

	public function daftarPenarikan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status_penarikan');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			return $this->FinanceModel->getPenarikan($status,$start_date,$end_date);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Penarikan';
			$data['content'] = 'panel/finance/penarikan/index';
			if(!empty($this->input->get('uuid_toko'))){
				$data['uuid_toko'] = $this->input->get('uuid_toko');
			}else{
				$data['uuid_toko'] = $this->session->userdata('uuid_toko');
			}

			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}

			if(!empty($this->input->get('status_penarikan'))){
				$data['status'] = $this->input->get('status_penarikan');
			}else{
				$data['status'] = 'pending';
			}
			$data['toko'] = $this->GeneralModel->get_by_id_general('ms_toko','status_toko','1');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahPenarikan($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'jumlah_penarikan' => $this->input->post('jumlah_penarikan'),
				'keterangan_rekening' => $this->input->post('keterangan_rekening'),
				'request_by' => $this->session->userdata('id_pengguna'),
                'uuid_toko' => $this->session->userdata('uuid_toko'),
				'request_date' => date('Y-m-d H:i:s')                
			);
			
            if ($this->GeneralModel->create_general('ms_penarikan', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data penarikan berhasil ditambahkan!</div>');
                redirect('panel/finance/daftarPenarikan');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data penarikan gagal ditambahkan!</div>');
                redirect('panel/finance/daftarPenarikan');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Penarikan';
			$data['content'] = 'panel/finance/penarikan/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updatePenarikan($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'jumlah_penarikan' => $this->input->post('jumlah_penarikan'),
				'keterangan_rekening' => $this->input->post('keterangan_rekening'),
                'request_by' => $this->session->userdata('id_pengguna'),
                'uuid_toko' => $this->session->userdata('uuid_toko'),
				'request_date' => date('Y-m-d H:i:s')                
			);
                
            if ($this->GeneralModel->update_multi_id_general('ms_penarikan','id_penarikan', $param2, 'uuid_toko', $this->session->userdata('uuid_toko'), $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data penarikan berhasil diubah!</div>');
                redirect('panel/finance/daftarPenarikan');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data penarikan gagal diubah!</div>');
                redirect('panel/finance/daftarPenarikan');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Penarikan';
			$data['content'] = 'panel/finance/penarikan/update';
			$data['penarikan'] = $this->GeneralModel->get_by_multi_id_general('ms_penarikan','id_penarikan',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusPenarikan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
        if ($this->GeneralModel->delete_multi_id_general('ms_penarikan', 'id_penarikan', $param1, 'uuid_toko',$this->session->userdata('uuid_toko')) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data penarikan berhasil dihapus!</div>');
			redirect('panel/finance/daftarPenarikan');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data penarikan gagal dihapus!</div>');
			redirect('panel/finance/daftarPenarikan');
        }
	}

	public function detailPenarikan($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$data['title'] = $this->title;
		$data['subtitle'] = 'Detail Penarikan';
		$data['content'] = 'panel/finance/penarikan/detail';
		$data['penarikan'] = $this->GeneralModel->get_by_id_general('ms_penarikan','id_penarikan',$param1);
		$this->load->view('panel/content', $data);
	}
	
}
