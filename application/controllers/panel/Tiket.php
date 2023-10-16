<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PhpMqtt\Client\Exceptions\MQTTClientException;
use PhpMqtt\Client\MQTTClient;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class Tiket extends CI_Controller
{

	public $parent_modul = 'Tiket';
	public $title = 'Tiket';

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
        $this->koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);
	}

	public function daftarTiket($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$id_pengguna = $this->input->post('id_pengguna');
			$status = $this->input->post('status');
			$kategori_tiket = $this->input->post('kategori_tiket');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			return $this->MasterDataModel->getTiket($id_pengguna,$status,$kategori_tiket,$start_date,$end_date);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['pengguna'] = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','status','actived','hak_akses','member');
			$data['id_pengguna'] = $this->input->get('id_pengguna');
			$data['status'] = $this->input->get('status');
			$data['kategori_tiket'] = $this->input->get('kategori_tiket');
			if(empty($this->input->get('start_date')) || empty($this->input->get('end_date'))){
				$data['start_date'] = DATE("Y-m-01");
				$data['end_date'] = DATE("Y-m-d");
			}else{
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}
			$data['subtitle'] = 'Daftar Tiket';
			$data['content'] = 'panel/tiket/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function detailTiket($kode_tiket){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		$data['title'] = $this->title;
		$data['subtitle'] = 'Detail Tiket '.$kode_tiket;
		$data['kode_tiket'] = $kode_tiket;
		$data['isi'] = $this->GeneralModel->get_by_id_general('ms_tiket','kode_tiket',$kode_tiket);
		$data['content'] = 'panel/tiket/detail';
		$this->load->view('panel/content', $data);
	}

	public function isiTiket($id){
		$data['pengguna'] = $this->input->post('pengguna');
		$data['getIsi'] = $this->GeneralModel->get_by_id_general_order_by('v_isi_tiket','tiket',$id,'waktu_pesan','DESC');
        $data['appsProfile'] = $this->SettingsModel->get_profile();
		$this->load->view('panel/tiket/isiChat', $data);
	}

	public function updateTiket($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='balas'){
			$tiket = $this->GeneralModel->get_by_id_general('ms_tiket','id_tiket',$this->input->post('id_tiket'));
			$cekDeviceId = $this->GeneralModel->get_by_id_general('ms_device_notif','id_pengguna',$tiket[0]->pengguna);

            $messages = [
                new ExpoMessage([
                    'title' => '#Tiket '.$tiket[0]->kode_tiket,
                    'body' => 'Ada balasan pada tiket kamu..',
					'data' => [
						'screen' => 'MahkotaCare', // Replace 'ScreenName' with the actual name of the screen you want to navigate to
					],
                ]),
            ];

            foreach ($cekDeviceId as $key) {
				if(!empty($key->deviceid)){
					$defaultRecipients = [
						$key->deviceid
					];
					(new Expo)->send($messages)->to($defaultRecipients)->push();
				}
			}
			foreach ($tiket as $key) {
				if(!empty($key->nama_tiket)){
					$message = 'Halo *'.$key->nama_tiket.'*!';
				}else{
					$message = 'Halo *'.$key->no_wa.'*!';
				}
				$message .= urlencode("\n"); 
				$message .= "Ada balasan pesan kamu,";
				$message .= urlencode("\n"); 
				$message .= "Kode tiket : *".$key->kode_tiket."*";
				$message .= urlencode("\n"); 
				$message .= urlencode("\n"); 
				$message .= "Segera cek pesan kamu dengan memasukkan kode tiket tersebut, atau refresh halaman";
				$message .= urlencode("\n"); 
				$message .= "Terima kasih!";
				sendNotifWA2($key->no_wa,$message);	
			}

			$data = array(
				'tiket' => $this->input->post('id_tiket'),
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
						$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer2');
						$client->connect('mahkota', $key->mqtt_password);
					try{
						$client->publish('tiket/update/'.my_simple_crypt($data['tiket'],'e'), "update", 0);
						$client->publish('tiket/update/'.$data['tiket'], "update", 0);
					} catch (MqttClientException $e) {
					}
				}

				$this->session->set_flashdata('notif','<div class="alert alert-success">Pesan berhasil dikirim</div>');
				redirect('panel/tiket/detailTiket/'.$tiket[0]->kode_tiket);		
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kode tiket ini tidak tersedia untuk</div>');
				redirect('panel/tiket');		
			}
		}elseif($param1=='batal'){
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
				echo 'true';
				foreach($this->koneksi as $key){
						$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer2');
						$client->connect('mahkota', $key->mqtt_password);
					try{
						$client->publish('tiket/update/'.my_simple_crypt($cek[0]->tiket,'e'), "update", 0);
						$client->publish('tiket/update/'.$cek[0]->tiket, "update", 0);
					} catch (MqttClientException $e) {
					}
				}
			}else{
				echo 'false';
			}
		}else{
			$id_tiket = $this->input->post('id_tiket');
			$status_tiket = $this->input->post('status_tiket');
			$data = array(
				'status_tiket' => $status_tiket
			);
			if($this->GeneralModel->update_general('ms_tiket','id_tiket',$id_tiket,$data) == TRUE){
				echo 'true';
			}else{
				echo 'false';
			}
		}
	}

}
