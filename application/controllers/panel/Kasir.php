<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {

	/**
		*created by https://medandigitalinnovation.com
		*Estimate 2019
	 */

	public $parent_modul = "Kasir";
	public $title = 'Kasir';

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('LoggedIN') == FALSE || $this->session->userdata('hak_akses') == 'member') redirect('auth/logout');
		if (cekParentModul($this->parent_modul) == FALSE) redirect('panel/dashboard');
		if(empty($this->session->userdata('uuid_toko'))){
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Silahkan pilih toko terlebih dahulu</div>');
			redirect('panel/dashboard');
		}
		$this->akses_controller = $this->uri->segment(3);
	}

	public function index(){
		$this->bukaKasir();
	}

	public function bukaKasir($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1=='doCreate') {
			$dataKasir = array(
				'saldo_awal' => $this->input->post('saldo_awal'),
				'pengguna' => $this->session->userdata('id_pengguna'),
				'opened_time' => date("Y-m-d H:i:s"),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
			);

			$this->session->unset_userdata('buka_kasir');

			$this->GeneralModel->create_general('ms_kasir',$dataKasir);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Kasir berhasil dibuka</div>');
			redirect('panel/dashboard');
		}else {
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Buka Kasir';
			$data['kasir'] = $this->KasirModel->cekWaktuBuka($this->session->userdata('id_pengguna'));
			if ($data['kasir']) {
				$data['content'] = 'panel/kasir/bukaTerakhir';
			}else{
				$this->session->set_userdata('buka_kasir',TRUE);
				$data['content'] = 'panel/kasir/buka';
			}
			$this->load->view('panel/content',$data);
		}
	}

	public function tutupKasir($param1='',$param2=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1=='doCreate') {
			$dataKasir = array(
				'total_pendapatan' => $this->input->post('total_pendapatan'),
				'pendapatan_kasir' => $this->input->post('pendapatan_kasir'),
				'selisih_pendapatan' => $this->input->post('total_pendapatan') - $this->input->post('pendapatan_kasir'),
				'total_pengeluaran' => $this->input->post('total_pengeluaran'),
				'closure_time' => date("Y-m-d H:i:s"),
				'notes' => $this->input->post('notes'),
			);

			$this->GeneralModel->update_general('ms_kasir','id_kasir',$param2,$dataKasir);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Kasir berhasil ditutup terimakasih..</div>');
			$getKasir = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));

			$message = "Laporan Tutup kasir *(".$getKasir[0]->username.")*";
			$message .= "                                                            *Pendapatan* : ".number_format($dataKasir['total_pendapatan'],0,'.','.');
			$message .= "                                                            *Pendapatan Kasir* : ".number_format($dataKasir['pendapatan_kasir'],0,'.','.');
			$message .= "                                                            *Selisih pendapatan* : ".number_format($dataKasir['selisih_pendapatan'],0,'.','.');
			$message .= "                                                            *Pengeluaran* : ".number_format($dataKasir['total_pengeluaran'],0,'.','.');
			$message .= "                                                            *Ditutup pada* : ".$dataKasir['closure_time'];
			$message .= "                                                            *Catatan* :".$dataKasir['notes'];

			sendNotifWA($getKasir->no_wa,$message);

			try {
				// $kasir = $this->KasirModel->getByIdKasir($param2);
				// sendMail('Laporan Tutup Kasir', 'panel/email/tutupKasir', 'ownerfamilyhill@gmail.com', $kasir);
			} catch (\Exception $e) {

			}
			redirect('auth/logout');
		}else {
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tutup Kasir';
			$data['content'] = 'panel/kasir/tutup';
			$data['pendapatan'] = $this->KasirModel->getTotalPendapatan($this->session->userdata('id_pengguna'),$this->session->userdata('id_location'));
			$data['kasir'] = $this->KasirModel->cekWaktuBuka($this->session->userdata('id_pengguna'));
			$this->load->view('panel/content',$data);
		}
	}

}
