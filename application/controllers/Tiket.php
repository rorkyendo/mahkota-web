<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpMqtt\Client\Exceptions\MQTTClientException;
use PhpMqtt\Client\MQTTClient;


class Tiket extends CI_Controller {

	public function __construct()
  {
	parent::__construct();
	$this->koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);
  }

	public function index()
 {
	$data['title'] = 'Live Chat Specialist';
	$data['content'] = 'frontend2/tiket';
	$data['kategori_produk'] = $this->GeneralModel->get_general('ms_kategori_produk');
	$this->load->view('frontend2/content', $data);
 }
 
 	public function openTiket()
 {
	if ($this->session->userdata('LoggedIN') == FALSE) {
		$this->form_validation->set_rules(
			'nama_tiket', 'Nama Lengkap',
			'required|max_length[250]',
			array(
					'required'      => 'Masukkan Nama Lengkap',
			)
		);
		$this->form_validation->set_rules(
			'email_tiket', 'Email',
			'required|max_length[250]',
			array(
					'required'      => 'Masukkan Email',
					'email'      => 'Masukkan Email dengan benar',
			)
		);
		$this->form_validation->set_rules(
			'no_wa', 'Nomor Whatsapp',
			'required|max_length[20]',
			array(
					'required'      => 'Masukkan No Wa',
			)
		);
	}
	$this->form_validation->set_rules(
		'judul_tiket', 'Perihal pesan',
		'required|max_length[250]',
		array(
				'required'      => 'Perihal pesan tidak boleh kosong',
		)
	);
	if($this->form_validation->run() == FALSE) {
	    $this->session->set_flashdata('notif', validation_errors());
		redirect('tiket');
	}else{

		if($this->session->userdata('LoggedIN') == TRUE){
			$getPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			foreach ($getPengguna as $key) {
				$data = array(
					'pengguna' => $key->id_pengguna,
					'nama_tiket' => $key->nama_lengkap,
					'email_tiket' => $key->email,
					'no_wa' => $key->no_telp,
					'kategori_tiket' => $this->input->post('kategori_tiket'),
					'judul_tiket' => $this->input->post('judul_tiket'),
					'isi_tiket' => $this->input->post('isi_tiket'),
				);
			}
		}else{
			$data = array(
				'nama_tiket' => $this->input->post('nama_tiket'),
				'email_tiket' => $this->input->post('email_tiket'),
				'no_wa' => $this->input->post('no_wa'),
				'kategori_tiket' => $this->input->post('kategori_tiket'),
				'judul_tiket' => $this->input->post('judul_tiket'),
				'isi_tiket' => $this->input->post('isi_tiket'),
			);
		}

		$kode_tiket = substr(bin2hex(random_bytes(5)),0,5);
		$cek = $this->GeneralModel->get_by_id_general('ms_tiket','kode_tiket',$kode_tiket);
		if($cek){
			$kode_tiket = substr(bin2hex(random_bytes(5)),0,5);
		}
		$kode_tiket = strtoupper($kode_tiket);
		$data += array(
			'kode_tiket' => $kode_tiket
		);
		//---------------- FILE TIKET ---------------//
		$config['upload_path']          = 'assets/fileTiket/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 10000;


		$this->upload->initialize($config);

		if (!$this->upload->do_upload('lampiran_tiket')) {
		} else {
			$data += array('lampiran_tiket' => $config['upload_path'] . $this->upload->data('file_name'));
		}
		if($this->GeneralModel->create_general('ms_tiket',$data) == TRUE){
			if(!empty($data['nama_tiket'])){
				$message = 'Halo *'.$data['nama_tiket'].'*!';
			}else{
				$message = 'Halo *'.$data['no_wa'].'*!';
			}
			$message .= urlencode("\n"); 
			$message .= "Pesan kamu saat ini tentang : ";
			$message .= urlencode("\n"); 
			$message .= "*".$data['judul_tiket']."*";
			$message .= urlencode("\n"); 
			$message .= urlencode("\n"); 
			$message .= "Sedang di proses oleh admin dan akan dibalas sesegara mungkin,";
			$message .= urlencode("\n"); 
			$message .= "Berikut ini kode tiket yang harus kamu simpan agar dapat terus berkomunikasi dengan admin";
			$message .= urlencode("\n"); 
			$message .= urlencode("\n"); 
			$message .= "Kode tiket : *".$kode_tiket."*";
			$message .= urlencode("\n"); 
			$message .= urlencode("\n"); 
			$message .= "Terima kasih!";
			sendNotifWA2($data['no_wa'],$message);	
			$this->session->set_flashdata('notif', '<div class="alert alert-success">Informasi berhasil disimpan!</div>');
			redirect('tiket');
		}
	}
 }

 public function daftarTiket($param1=''){
	if($this->session->userdata('LoggedIN') == TRUE){
		if($param1=='cari'){
			return $this->MasterDataModel->getTiket($this->session->userdata('id_pengguna'));
		}else{
			$data['title'] = 'Daftar Tiket';
			$data['content'] = 'frontend2/daftarTiket';
			$data['kategori_produk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$this->load->view('frontend2/content', $data);		
		}
	}else{
		$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kamu harus login terlebih dahulu.. atau kamu dapat melihat status tiket kamu melalui kode tiket yang diberikan</div>');
		redirect('tiket');
	}
 }

 public function cekTiket($param1=''){
	if(empty($param1)){
		$kode_tiket = $this->input->post('kode_tiket');
	}else{
		$kode_tiket = $param1;
	}
	$cek = $this->GeneralModel->get_by_id_general('ms_tiket','kode_tiket',$kode_tiket);
	if($cek){
		foreach ($cek as $key) {
			if (!empty($key->pengguna)) {
				if($key->pengguna != $this->session->userdata('id_pengguna')){
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kode tiket ini tidak tersedia untuk kamu..</div>');
					redirect('tiket');		
				}
			}
		}
		$data['title'] = 'Detail Tiket '.$kode_tiket;
		$data['kode_tiket'] = $kode_tiket;
		$data['content'] = 'frontend2/detailTiket';
		$data['isi'] = $cek;
		$data['kategori_produk'] = $this->GeneralModel->get_general('ms_kategori_produk');
		$this->load->view('frontend2/content', $data);	
	}else{
		$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kode tiket tidak ditemukan</div>');
		redirect('tiket');		
	}
 }

 public function balas($param1=''){
	if(!empty($param1)){
		$kode_tiket = my_simple_crypt($param1,'d');
	}
	$cek = $this->GeneralModel->get_by_id_general('ms_tiket','kode_tiket',$kode_tiket);
	if($cek){
		foreach ($cek as $key) {
			if (!empty($key->pengguna)) {
				if($key->pengguna != $this->session->userdata('id_pengguna')){
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kode tiket ini tidak tersedia untuk kamu..</div>');
					redirect('tiket');		
				}
			}

			$data = array(
				'tiket' => $key->id_tiket,
				'pesan' => $this->input->post('pesan'),
				'created_by' => $this->session->userdata('id_pengguna')
			);
			//---------------- FILE TIKET ---------------//
			$config['upload_path']          = 'assets/fileTiket/';
			$config['allowed_types']        = 'jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if (!$this->upload->do_upload('lampiran')) {
			} else {
				$data += array('lampiran' => $config['upload_path'] . $this->upload->data('file_name'));
			}
			if($this->GeneralModel->create_general('ms_isi_tiket',$data) == TRUE){
				foreach($this->koneksi as $key){
					$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer3');
					$client->connect('mahkota', $key->mqtt_password);
				try{
					$client->publish('tiket/update/'.my_simple_crypt($data['tiket'],'e'), "update", 0);
					$client->publish('tiket/update/'.$data['tiket'], "update", 0);
					} catch (MqttClientException $e) {
					}
				}
				$this->session->set_flashdata('notif','<div class="alert alert-success">Pesan berhasil dikirim silahkan tunggu beberapa saat hingga admin membalas</div>');
				redirect('tiket/cekTiket/'.$kode_tiket);		
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kode tiket ini tidak tersedia untuk kamu..</div>');
				redirect('tiket');		
			}
		}
	}else{
		$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kode tiket tidak ditemukan</div>');
		redirect('tiket');		
	}
 }

 public function batalKirim(){
	$id = $this->input->post('id');
	$id = my_simple_crypt($id,'d');
	$cek = $this->GeneralModel->get_by_id_general('ms_isi_tiket','id_isi_tiket',$id);
	if($cek){
		foreach ($cek as $key) {
			if (!empty($key->lampiran)) {
				try {
					unlink($key->lampiran);
				} catch (\Throwable $th) {
				}
			}
		}
		$data = array(
			'status' => 'cancel'
		);
		$this->GeneralModel->update_general('ms_isi_tiket','id_isi_tiket',$id,$data);
		foreach($this->koneksi as $key){
			$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer');
			$client->connect('mahkota', $key->mqtt_password);
		try{
			$client->publish('tiket/update/'.my_simple_crypt($cek[0]->tiket,'e'), "update", 0);
			$client->publish('tiket/update/'.$cek[0]->tiket, "update", 0);
		} catch (MqttClientException $e) {
		}
	}
		echo 'true';
	}else{
		echo 'false';
	}
 }

 	public function chat($kode_tiket){
		$data['isi'] = $this->GeneralModel->get_by_id_general('ms_tiket','kode_tiket',$kode_tiket);
        $data['appsProfile'] = $this->SettingsModel->get_profile();
		$this->load->view('frontend2/chat', $data);
	}	

}