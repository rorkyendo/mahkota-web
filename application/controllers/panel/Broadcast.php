<?php

defined('BASEPATH') or exit('No direct script access allowed');
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class Broadcast extends CI_Controller
{

	public $parent_modul = 'Broadcast';
	public $title = 'Broadcast';

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('LoggedIN') == FALSE || $this->session->userdata('hak_akses') == 'member') redirect('auth/logout');
		if (cekParentModul($this->parent_modul) == FALSE) redirect('panel/dashboard');
		if(empty($this->session->userdata('uuid_toko'))){
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Silahkan pilih toko terlebih dahulu</div>');
			redirect('panel/dashboard');
		}
		$this->identitasAplikasi = $this->GeneralModel->get_general('ms_identitas');
		$this->akses_controller = $this->uri->segment(3);
	}


	//--------------- TEMPLATE BROADCAST BEGIN------------------//
	public function daftarTemplatePesan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			return $this->BroadcastModel->getTemplatePesan();
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Template Pesan';
			$data['content'] = 'panel/broadcast/templatePesan/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahTemplatePesan($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'judul_template' => $this->input->post('judul_template'),
				'isi_pesan' => $this->input->post('isi_pesan'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'created_time' => date('Y-m-d H:i:s')
			);
			
            if ($this->GeneralModel->create_general('ms_template_pesan', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Template Pesan berhasil ditambahkan!</div>');
                redirect('panel/broadcast/daftarTemplatePesan');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Template Pesan gagal ditambahkan!</div>');
                redirect('panel/broadcast/daftarTemplatePesan');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Template Pesan';
			$data['content'] = 'panel/broadcast/templatePesan/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateTemplatePesan($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'judul_template' => $this->input->post('judul_template'),
				'isi_pesan' => $this->input->post('isi_pesan'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => date('Y-m-d H:i:s')
			);
			
            if ($this->GeneralModel->update_general('ms_template_pesan', 'id_template', $param2, $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Template Pesan berhasil diupdate!</div>');
                redirect('panel/broadcast/daftarTemplatePesan');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Template Pesan gagal diupdate!</div>');
                redirect('panel/broadcast/daftarTemplatePesan');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Template Pesan';
			$data['content'] = 'panel/broadcast/templatePesan/update';
			$data['templatePesan'] = $this->GeneralModel->get_by_id_general('ms_template_pesan','id_template',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusTemplatePesan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
        if ($this->GeneralModel->delete_general('ms_template_pesan', 'id_template', $param1) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Template Pesan berhasil dihapus!</div>');
			redirect('panel/broadcast/daftarTemplatePesan');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Template Pesan gagal dihapus!</div>');
			redirect('panel/broadcast/daftarTemplatePesan');
        }
	}

	//--------------- TEMPLATE BROADCAST BEGIN------------------//
	public function kirimPesan($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$pengguna = $this->input->post('pengguna');
			$jenis_pesan = $this->input->post('jenis_pesan');
			for ($i=0; $i < count($pengguna); $i++) { 
				$dataPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$pengguna[$i]);
				$data = array(
					'template' => $this->input->post('template'),
					'isi_pesan' => str_replace('[nama_pengguna]',$dataPengguna[0]->nama_lengkap,$this->input->post('isi_pesan')),
					'pengguna' => $pengguna[$i],
					'no_wa' => $dataPengguna[0]->no_telp,
					'jenis_pesan' => $jenis_pesan,
					'created_by' => $this->session->userdata('id_pengguna'),
					'created_time' => date('Y-m-d H:i:s')
				);

				if($jenis_pesan == 'NOTiF'){
					$data += array(
						'judul' => str_replace('[nama_pengguna]',$dataPengguna[0]->nama_lengkap,$this->input->post('judul')),
					);
				}

				$config['upload_path']          = 'assets/img/';
				$config['allowed_types']        = 'gif|jpg|png|jpeg';
				$config['max_size']             = 10000;
	
	
				$this->upload->initialize($config);
	
				if ( ! $this->upload->do_upload('gambar'))
				{
					if($jenis_pesan == 'WA'){
						sendNotifWA($data['no_wa'],$data['isi_pesan']);
					}else{
						$cekDeviceId = $this->GeneralModel->get_by_id_general('ms_device_notif','id_pengguna',$pengguna[$i]);

						$messages = [
							new ExpoMessage([
								'title' => $data['judul'],
								'body' => $data['isi_pesan'],
								'data' => [
									'screen' => 'Home',
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
			
					}
				}
				else {
					$data += array('gambar' => $config['upload_path'].$this->upload->data('file_name'));
					$image = base_url().$config['upload_path'].$this->upload->data('file_name');
					sendNotifWA($data['no_wa'],$data['isi_pesan'],$image);
				}

				$this->GeneralModel->create_general('ms_pesan', $data);
			}
			
			$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Template Pesan berhasil ditambahkan!</div>');
			redirect('panel/broadcast/riwayatKirim');

		}elseif($param1=='getTemplate'){
			$template = $this->GeneralModel->get_general('ms_template_pesan');
			echo json_encode($template,JSON_PRETTY_PRINT);
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Kirim Pesan';
			$data['content'] = 'panel/broadcast/pesan/create';
			$data['pengguna'] = $this->GeneralModel->get_by_id_general('ms_pengguna','status','actived');
			$data['templatePesan'] = $this->GeneralModel->get_general('ms_template_pesan');
			$this->load->view('panel/content', $data);
		}
	}

	public function riwayatKirim($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			return $this->BroadcastModel->getPesan($start_date,$end_date);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Riwayat Kirim';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			$data['content'] = 'panel/broadcast/pesan/riwayat';
			$this->load->view('panel/content', $data);
		}
	}


}
