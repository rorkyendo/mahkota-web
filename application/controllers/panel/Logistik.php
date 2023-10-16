<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Logistik extends CI_Controller
{

	public $parent_modul = 'Logistik';
	public $title = 'Logistik';

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

	public function daftarSupplier($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->LogistikModel->getSupplier($uuid_toko);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Supplier';
			$data['content'] = 'panel/logistik/supplier/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahSupplier($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'kode_supplier' => $this->input->post('kode_supplier'),
				'nama_supplier' => $this->input->post('nama_supplier'),
				'alamat_supplier' => $this->input->post('alamat_supplier'),
				'no_hp_supplier' => $this->input->post('no_hp_supplier'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'created_time' => date('Y-m-d H:i:s')
			);
			
            if ($this->GeneralModel->create_general('ms_supplier', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data Supplier berhasil ditambahkan!</div>');
                redirect('panel/logistik/daftarSupplier');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data Supplier gagal ditambahkan!</div>');
                redirect('panel/logistik/daftarSupplier');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Supplier';
			$data['content'] = 'panel/logistik/supplier/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateSupplier($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'kode_supplier' => $this->input->post('kode_supplier'),
				'nama_supplier' => $this->input->post('nama_supplier'),
				'alamat_supplier' => $this->input->post('alamat_supplier'),
				'no_hp_supplier' => $this->input->post('no_hp_supplier'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'updated_time' => date('Y-m-d H:i:s')
			);
                
            if ($this->GeneralModel->update_multi_id_general('ms_supplier','id_supplier', $param2, 'uuid_toko', $this->session->userdata('uuid_toko'), $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data Supplier berhasil diubah!</div>');
                redirect('panel/logistik/daftarSupplier');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data Supplier gagal diubah!</div>');
                redirect('panel/logistik/daftarSupplier');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Supplier';
			$data['content'] = 'panel/logistik/supplier/update';
			$data['indukGroup'] = $this->GeneralModel->get_by_multi_id_general('ms_supplier','id_supplier',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusSupplier($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
        if ($this->GeneralModel->delete_multi_id_general('ms_supplier', 'id_supplier', $param1, 'uuid_toko', $this->session->userdata('uuid_toko')) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data Supplier berhasil dihapus!</div>');
			redirect('panel/logistik/daftarSupplier');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data Supplier gagal dihapus!</div>');
			redirect('panel/logistik/daftarSupplier');
        }
	}	
}
