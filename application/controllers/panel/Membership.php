<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'third_party/phpqrcode/qrlib.php';
use PhpMqtt\Client\Exceptions\MQTTClientException;
use PhpMqtt\Client\MQTTClient;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class Membership extends CI_Controller
{

	public $parent_modul = 'Membership';
	public $title = 'Membership';

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
        $this->koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);
	}


	//--------------- TIPE MEMBERSHIP BEGIN------------------//
	public function daftarTipeMembership($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
			return $this->MembershipModel->getTipeMembership($status);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Tipe Membership';
			$data['content'] = 'panel/membership/tipeMembership/index';
			$data['getToko'] = $this->GeneralModel->get_general('ms_toko');
			$data['status'] = $this->input->get('status');
			$data['uuid_toko'] = $this->input->get('uuid_toko');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahTipeMembership($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'kode_tipe_member' => $this->input->post('kode_tipe_member'),
				'nama_tipe_member' => $this->input->post('nama_tipe_member'),
				'biaya_pendaftaran' => $this->input->post('biaya_pendaftaran'),
				'biaya_upgrade' => $this->input->post('biaya_upgrade'),
                'potongan_member' => $this->input->post('potongan_member'),
                'status_tipe' => 'active',
                'waktu_berlaku' => $this->input->post('waktu_berlaku'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'created_time' => date('Y-m-d H:i:s')
			);

			$config['upload_path'] = 'assets/img/coverMember/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('cover_depan'))
			{

			}
			else {
				$data += array('cover_depan' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('cover_belakang'))
			{

			}
			else {
				$data += array('cover_belakang' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('icon_member'))
			{

			}
			else {
				$data += array('icon_member' => $config['upload_path'].$this->upload->data('file_name'));
			}
			
            if ($this->GeneralModel->create_general('ms_tipe_member', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
                redirect('panel/membership/daftarTipeMembership');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal ditambahkan!</div>');
                redirect('panel/membership/daftarTipeMembership');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Tipe Member';
			$data['content'] = 'panel/membership/tipeMembership/create';
			$data['toko'] = $this->GeneralModel->get_general('ms_toko');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateTipeMembership($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$data = array(
				'kode_tipe_member' => $this->input->post('kode_tipe_member'),
				'nama_tipe_member' => $this->input->post('nama_tipe_member'),
				'biaya_pendaftaran' => $this->input->post('biaya_pendaftaran'),
				'biaya_upgrade' => $this->input->post('biaya_upgrade'),
				'potongan_member' => $this->input->post('potongan_member'),
                'status_tipe' => $this->input->post('status_tipe'),
                'waktu_berlaku' => $this->input->post('waktu_berlaku'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => date('Y-m-d H:i:s')
			);

			$tipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',$param2);

			// if ($this->input->post('update_berlaku') == 'Y') {
			// 	$dataUpdateMember = array(
			// 		'expired_date' => $data['waktu_berlaku']
			// 	);

			// 	$this->GeneralModel->update_general('ms_member','kode_tipe',$data['kode_tipe_member'],$dataUpdateMember);
			// }

			$config['upload_path'] = 'assets/img/coverMember/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('cover_depan'))
			{
       
			}
			else {
				if(!empty($tipeMember[0]->cover_depan)){
						unlink($tipeMember[0]->cover_depan);				}
				$data += array('cover_depan' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('cover_belakang'))
			{

			}
			else {
				if(!empty($tipeMember[0]->cover_belakang)){
						unlink($tipeMember[0]->cover_belakang);
				}
				$data += array('cover_belakang' => $config['upload_path'].$this->upload->data('file_name'));
			}
			
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('icon_member'))
			{

			}
			else {
				if(!empty($tipeMember[0]->icon_member)){
						unlink($tipeMember[0]->icon_member);
				}
				$data += array('icon_member' => $config['upload_path'].$this->upload->data('file_name'));
			}
                
            if ($this->GeneralModel->update_general('ms_tipe_member','id_tipe_member', $param2,$data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
                redirect('panel/membership/daftarTipeMembership');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
                redirect('panel/membership/daftarTipeMembership');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Tipe Member';
			$data['content'] = 'panel/membership/tipeMembership/update';
			$data['tipeMembership'] = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',$param1);
			$data['toko'] = $this->GeneralModel->get_general('ms_toko');
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusTipeMembership($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$member = $this->GeneralModel->get_by_id_general('ms_tipe_member', 'id_tipe_member', $param1);
        if ($this->GeneralModel->delete_general('ms_tipe_member', 'id_tipe_member', $param1) == TRUE) {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('panel/membership/daftarTipeMembership');
        } else {
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('panel/membership/daftarTipeMembership');
        }
	}

	//--------------- MEMBERSHIP BEGIN------------------//
	public function daftarMembership($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
			return $this->MembershipModel->getMembership($status);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Membership';
			$data['content'] = 'panel/membership/index';
			$data['getToko'] = $this->GeneralModel->get_general('ms_toko');
			$data['status'] = $this->input->get('status');
			$data['uuid_toko'] = $this->input->get('uuid_toko');
			$this->load->view('panel/content', $data);
		}
	}

	public function detailMembership($param1='',$param2='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$data = array(
				'status' => $this->input->post('status'),
				'expired_date' => $this->input->post('expired_date'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => date('Y-m-d H:i:s')
			);
			if ($this->GeneralModel->update_general('ms_member','id_member', $param2,$data) == true) {
				foreach($this->koneksi as $key){
						$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer');
						$client->connect('mahkota', $key->mqtt_password);
					try{
						$client->publish('command/member/', "update", 0);
					} catch (MqttClientException $e) {
					}
				}
				$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
				redirect('panel/membership/daftarMembership');
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
				redirect('panel/membership/daftarMembership');
			}
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Detail Membership';
			$data['content'] = 'panel/membership/detail';
			$data['membership'] = $this->GeneralModel->get_by_id_general('v_member','id_member',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahMembership($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			if (memberShip($this->input->post('id_pengguna'),$this->input->post('id_tipe_member')) == true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
				redirect('panel/membership/daftarMembership');
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal ditambahkan!</div>');
				redirect('panel/membership/daftarMembership');
			}
		}else if($param1=='import'){
			$config['upload_path']          = 'assets/excel/';
			$config['allowed_types'] = 'xlsx';
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('dataMembership')) {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data membership gagal diupload ' . $this->upload->display_errors() . '</div>');
				redirect('panel/membership/daftarMembership/');
			} else {
				$filename = $this->upload->data('file_name');
				$data = $config['upload_path'] . $filename;
				$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($data);
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
				$spreadsheet = $reader->load($data);
				$sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
				$numrow = 1;
				// $dataMember = array();
				foreach ($sheet as $row) {
					if ($numrow > 1) {
						$kode_member = str_replace(' ','',$row['A']);
						$tipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','kode_tipe_member',substr($kode_member,0,3));
						if (!empty($tipeMember)) {
							if(substr($kode_member,0,3) != 'ATS' && !empty($row['C']) && $row['C'] != '0'){
								$dataPengguna = array(
									"username" => $row['B'],
									"nama_lengkap" => $row['B'],
									'no_telp' => '0'.$row['C'],
									'alamat' => $row['D'],
									'password' => sha1(123456),
									'hak_akses' => 'member',
									'status' => 'activated'
								);

								//cek Nomor Telp
								$cekTelp = $this->GeneralModel->get_by_id_general('ms_pengguna','no_telp',$dataPengguna['no_telp']);
								if (!empty($cekTelp)) {
									$id_pengguna = $cekTelp[0]->id_pengguna;	
								}else{
									$this->GeneralModel->create_general('ms_pengguna',$dataPengguna);
									$id_pengguna = $this->db->insert_id();	
								}

								$recek = $this->GeneralModel->limit_by_id_general_order_by('ms_member', 'kode_tipe', substr($kode_member,0,3), 'kode_member', 'DESC', '1');
								if($recek){
									$padded = str_pad((string)$recek[0]->kode_member+1, 6, "0", STR_PAD_LEFT);
									$dataMember = array(
										'id_tipe_member' => $tipeMember[0]->id_tipe_member,
										'kode_tipe' => substr($kode_member,0,3),
										'id_pengguna' => $id_pengguna,
										'kode_member' => $recek[0]->kode_member+1,
										'barcode_member' => substr($kode_member,0,3).$padded,
										'barcode_member_encrypt' => sha1(substr($kode_member,0,3).$padded),
										'expired_date' => DATE('Y-m-d', strtotime('+'.$tipeMember[0]->waktu_berlaku.' days')),
										'status' => 'active',
										'created_by' => $this->session->userdata('id_pengguna'),
									);	
								}else{
									$dataMember = array(
										'id_tipe_member' => $tipeMember[0]->id_tipe_member,
										'kode_tipe' => substr($kode_member,0,3),
										'id_pengguna' => $id_pengguna,
										'kode_member' => 000001,
										'barcode_member' => $tipeMember[0]->kode_tipe_member.'000001',
										'barcode_member_encrypt' => sha1($tipeMember[0]->kode_tipe_member.'000001'),
										'expired_date' => DATE('Y-m-d', strtotime('+'.$tipeMember[0]->waktu_berlaku.' days')),
										'status' => 'active',
										'created_by' => $this->session->userdata('id_pengguna'),
									);	
								}

								//Pembuatan Member
								$cekMemberByPengguna = $this->GeneralModel->get_by_id_general('ms_member','id_pengguna',$id_pengguna);
								if (!empty($cekMemberByPengguna)) {
									unlink($cekMemberByPengguna[0]->barcode);
									unlink($cekMemberByPengguna[0]->qrcode);
									$this->GeneralModel->update_general('ms_member','id_pengguna',$id_pengguna,$dataMember);
									makeBarcode($cekMemberByPengguna[0]->id_member,$cekMemberByPengguna[0]->barcode_member);
								}else{
									$this->GeneralModel->create_general('ms_member',$dataMember);
									$id_member = $this->db->insert_id();
									makeBarcode($id_member,$dataMember['barcode_member']);
								}


							}
						}
					}
					$numrow++;
				}
				try {
					unlink($data);
				} catch (\Exception $e) {
				}
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data membership berhasil diupload</div>');
				redirect('panel/membership/daftarMembership/');
			}
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Membership';
			$data['content'] = 'panel/membership/create';
			$data['getPengguna'] = $this->GeneralModel->get_by_id_general('ms_pengguna', 'hak_akses', 'member');
			$data['getTipeMember'] = $this->GeneralModel->get_general('ms_tipe_member');
			$data['status'] = $this->input->get('status');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateMembership($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1=='doUpdate') {
			$getMember = $this->GeneralModel->get_by_id_general('ms_member','id_member',$param2);
			foreach($getMember as $key){
				unlink($key->barcode);
				unlink($key->qrcode);
			}
			$this->GeneralModel->delete_general('ms_member','id_member',$param2);
			if (memberShip($this->input->post('id_pengguna'),$this->input->post('id_tipe_member')) == TRUE) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
				redirect('panel/membership/daftarMembership');
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
				redirect('panel/membership/daftarMembership');
			}
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Membership';
			$data['content'] = 'panel/membership/update';
			$data['getMembership'] = $this->GeneralModel->get_by_id_general('ms_member', 'id_member', $param1);
			$data['getPengguna'] = $this->GeneralModel->get_by_id_general('ms_pengguna', 'hak_akses', 'member');
			$data['getTipeMember'] = $this->GeneralModel->get_general('ms_tipe_member');
			$data['status'] = $this->input->get('status');
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusMembership($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$data = array(
			'status' => 'deleted',
			'updated_by' => $this->session->userdata('id_pengguna'),
			'updated_time' => date('Y-m-d H:i:s')
		);
		if ($this->GeneralModel->update_general('ms_member', 'id_member', $param1, $data) == TRUE) {
			$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
			redirect('panel/membership/daftarMembership');
		} else {
			$this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
			redirect('panel/membership/daftarMembership');
		}
	}

	public function createManualBarcode($id_member,$barcode_member){
		makeBarcode($id_member,$barcode_member);
		$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
		redirect('panel/membership/daftarMembership');
	}

	public function pendaftaranMember($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			return $this->MembershipModel->getPendaftaranMember($status,$start_date,$end_date);
		}else if($param1=='excel'){
			$status = $this->input->get('status');
			$start_date = $this->input->get('start_date');
			$end_date = $this->input->get('end_date');
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
			$data['pendaftaran'] = $this->MembershipModel->getPendaftaranMemberPrint($status,$start_date,$end_date);
			$this->load->view('panel/membership/printPendaftaran', $data);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['status'] = $this->input->get('status');
			$data['subtitle'] = 'Pendaftaran Member';
			$data['content'] = 'panel/membership/pendaftaran';
			if (!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))) {
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			$this->load->view('panel/content', $data);
		}
	}

	public function detailPendaftaranMember($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		$data['title'] = $this->title;
		$data['transaksi'] = $this->GeneralModel->get_by_id_general('v_transaksi','id_transaksi',$param1);
		$data['order'] = $this->GeneralModel->get_by_id_general('v_order','transaksi',$param1);
		$data['subtitle'] = 'Detail Pendaftaran Member';
		$data['content'] = 'panel/membership/detailPendaftaran';
		$this->load->view('panel/content', $data);
	}

	public function updatePendaftaranMember($param1='',$param2=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param2);
		$cekTransaksi = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$param2);
		$getOrder = $this->GeneralModel->get_by_id_general('v_order','transaksi',$param2);
		if($cekTransaksi){
			if($param1=='accept'){
					memberShip($cekTransaksi[0]->pelanggan,$getOrder[0]->tipe_member);
					//-------- UPDATE DATA TRANSAKSI --------//
					$dataTransaksi = array(
						'payment_status' => 'payed',
						'total_pembayaran' => $cekTransaksi[0]->total,
						'status_pengiriman' => 'sampai',
						'pay_time' => DATE('Y-m-d H:i:s')
					);

					$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$param2,$dataTransaksi);

					$dataOrder = array(
						'status_order' => 'add'
					);
					$this->GeneralModel->update_general('ms_order','transaksi',$param2,$dataOrder);
					$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Transaksi berhasil dikonfirmasi</div>');
					foreach($this->koneksi as $key){
						$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer');
						$client->connect('mahkota', $key->mqtt_password);
						try{
							$client->publish('command/member/', "new", 0);
						} catch (MqttClientException $e) {
						}
					}

					redirect('panel/membership/detailPendaftaranMember/'.$param2);
			}else{
				$dataTransaksi = array(
					'payment_status' => 'cancel',
					'total_pembayaran' => $cekTransaksi[0]->total,
					'status_pengiriman' => NULL
				);

				$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$param2,$dataTransaksi);

				$dataOrder = array(
					'status_order' => 'add'
				);
				$this->GeneralModel->update_general('ms_order','transaksi',$param2,$dataOrder);
				$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Transaksi berhasil ditolak</div>');
				redirect('panel/membership/detailPendaftaranMember/'.$param2);
			}
		}else{
			$this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Transaksi tidak ditemukan</div>');
			redirect('panel/membership/daftarMembership');
		}
	}

	//--------------- KUPON BEGIN------------------//
	public function daftarKupon($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            return $this->MembershipModel->getKupon($status,$start_date,$end_date);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Voucher';
			$data['content'] = 'panel/membership/kupon/index';
			$data['status'] = $this->input->get('status');
            if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
                $data['start_date'] = $this->input->get('start_date');
                $data['end_date'] = $this->input->get('end_date');
            }else{
                $data['start_date'] = DATE('Y-m-01');
                $data['end_date'] = DATE('Y-m-t');
            }
			$data['tipe_member'] = $this->GeneralModel->get_general('ms_tipe_member','status_tipe','active');
			$data['id_tipe_member'] = $this->input->get('id_tipe_member');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahKupon($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'nama_kupon' => $this->input->post('nama_kupon'),
				'kode_kupon' => $this->input->post('kode_kupon'),
				'keterangan_kupon' => $this->input->post('keterangan_kupon'),
				'syarat_ketentuan' => $this->input->post('syarat_ketentuan'),
				'berlaku_hingga' => $this->input->post('berlaku_hingga'),
				'jml_kupon' => $this->input->post('jml_kupon'),
				'min_belanja' => $this->input->post('min_belanja'),
				'status_min_belanja' => $this->input->post('status_min_belanja'),
				'tipe_member' => $this->input->post('tipe_member'),
				'potongan' => $this->input->post('potongan'),
				'diskon' => $this->input->post('diskon'),
				'jenis_kupon' => $this->input->post('jenis_kupon'),
				'tampil_kupon' => $this->input->post('tampil_kupon'),
				'created_by' => $this->session->userdata('id_pengguna'),
			);

			$config['upload_path']          = 'assets/img/kupon/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_kupon'))
			{

			}
			else {
				$data += array('gambar_kupon' => $config['upload_path'].$this->upload->data('file_name'));
			}
			
            if ($this->GeneralModel->create_general('ms_kupon', $data) == true) {
				$device = $this->GeneralModel->get_general('ms_device_notif');
	
				foreach ($device as $key){
					$messages = [
						new ExpoMessage([
							'title' => 'Voucher Baru!',
							'body' => 'Intip yuk, ada voucher baru khusus untukmu hari ini..',
						]),
					];
			
					if(!empty($key->deviceid)){
						$defaultRecipients = [
							$key->deviceid
						];
						(new Expo)->send($messages)->to($defaultRecipients)->push();
					}
	
				}
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
                redirect('panel/membership/daftarKupon');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal ditambahkan!</div>');
                redirect('panel/membership/daftarKupon');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Voucher';
			$data['content'] = 'panel/membership/kupon/create';
			$data['tipe_member'] = $this->GeneralModel->get_general('ms_tipe_member','status_tipe','active');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateKupon($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$kupon = $this->GeneralModel->get_by_id_general('ms_kupon','id_kupon',$param2);
			$data = array(
				'nama_kupon' => $this->input->post('nama_kupon'),
				'kode_kupon' => $this->input->post('kode_kupon'),
				'keterangan_kupon' => $this->input->post('keterangan_kupon'),
				'syarat_ketentuan' => $this->input->post('syarat_ketentuan'),
				'berlaku_hingga' => $this->input->post('berlaku_hingga'),
				'jml_kupon' => $this->input->post('jml_kupon'),
				'min_belanja' => $this->input->post('min_belanja'),
				'status_min_belanja' => $this->input->post('status_min_belanja'),
				'tipe_member' => $this->input->post('tipe_member'),
				'potongan' => $this->input->post('potongan'),
				'diskon' => $this->input->post('diskon'),
				'jenis_kupon' => $this->input->post('jenis_kupon'),
				'tampil_kupon' => $this->input->post('tampil_kupon'),
				'status' => $this->input->post('status'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s'),
			);

			$config['upload_path']          = 'assets/img/kupon/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_kupon'))
			{

			}
			else {
				if(!empty($kupon[0]->gambar_kupon)){
					try{
						unlink($kupon[0]->gambar_kupon);
					}catch(error $e){
					}
				}
				$data += array('gambar_kupon' => $config['upload_path'].$this->upload->data('file_name'));
			}
                
            if ($this->GeneralModel->update_general('ms_kupon','id_kupon', $param2,$data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
                redirect('panel/membership/daftarKupon');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
                redirect('panel/membership/daftarKupon');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Voucher';
			$data['content'] = 'panel/membership/kupon/update';
			$data['kupon'] = $this->GeneralModel->get_by_id_general('ms_kupon','id_kupon',$param1);
			$data['tipe_member'] = $this->GeneralModel->get_general('ms_tipe_member','status_tipe','active');
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusKupon($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$kupon = $this->GeneralModel->get_by_id_general('ms_kupon', 'id_kupon', $param1);

        foreach($kupon as $key){
            if(!empty($key->gambar_kupon)){
                try{
                    unlink($key->gambar_kupon);
                }catch(error $e){

				}
            }
        }

        if ($this->GeneralModel->delete_general('ms_kupon', 'id_kupon', $param1) == TRUE) {
		} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('panel/membership/daftarKupon');
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('panel/membership/daftarKupon');
        }
	}

	public function transaksiMember($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->MembershipModel->getPointTransaksi($start_date,$end_date,$uuid_toko);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Transaksi';
			$data['content'] = 'panel/membership/pointTransaksi/index';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			$this->load->view('panel/content',$data);
		}
	}

	public function tambahTransaksiMember($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'jenis_transaksi' => $this->input->post('jenis_transaksi'),
				'id_transaksi' => $this->input->post('id_transaksi'),
				'jumlah_point' => $this->input->post('jumlah_point'),
				'jumlah_transaksi' => $this->input->post('jumlah_transaksi'),
				'waktu_transaksi' => $this->input->post('waktu_transaksi'),
				'barcode_member' => $this->input->post('barcode'),
				'kode_kupon' => $this->input->post('kode_kupon'),
				'toko' => $this->session->userdata('uuid_toko'),
				'created_by' => $this->session->userdata('id_pengguna'),
			);

			$dataMember = $this->GeneralModel->get_by_id_general('v_member','barcode_member',$data['barcode_member']);
			$getKupon = $this->GeneralModel->get_by_id_general('ms_kupon','kode_kupon',$data['kode_kupon']);
			if($getKupon){
				$cekKuponPengguna = $this->GeneralModel->get_by_multi_id_general('v_redeem_kupon','kode_kupon',$data['kode_kupon'],'barcode',$data['barcode_member']);
				if(empty($cekKuponPengguna)){
					if($data['jumlah_transaksi'] >= $getKupon[0]->min_belanja){
						if($getKupon[0]->jenis_kupon == 'diskon'){
							$potongan = $data['jumlah_transaksi']*($getKupon[0]->diskon/100);
							$totalPotongan = $data['jumlah_transaksi'] - $potongan;
							$data += array(
								'jumlah_potongan' => $potongan,
								'total_akhir' => $totalPotongan
							);
						}else{
							$potongan = $getKupon[0]->potongan;
							$totalPotongan = $data['jumlah_transaksi'] - $potongan;
							$data += array(
								'jumlah_potongan' => $potongan,
								'total_akhir' => $totalPotongan
							);
						}
					}else{
						$this->session->set_flashdata('notif','<div class="alert alert-danger">Kupon tidak bisa kamu pakai karena tidak memenuhi minimal belanja</div>');
						redirect('panel/membership/tambahTransaksiMember/');
					}
				}else{
					if($cekKuponPengguna[0]->jml_kupon < $cekKuponPengguna[0]->kupon_terpakai){
						if($data['jumlah_transaksi'] >= $getKupon[0]->min_belanja){
							if($getKupon[0]->jenis_kupon == 'diskon'){
								$potongan = $data['jumlah_transaksi']*($getKupon[0]->diskon/100);
								$totalPotongan = $data['jumlah_transaksi'] - $potongan;
								$data += array(
									'jumlah_potongan' => $potongan,
									'total_akhir' => $totalPotongan
								);
							}else{
								$potongan = $getKupon[0]->potongan;
								$totalPotongan = $data['jumlah_transaksi'] - $potongan;
								$data += array(
									'jumlah_potongan' => $potongan,
									'total_akhir' => $totalPotongan
								);
							}
						}else{
							$this->session->set_flashdata('notif','<div class="alert alert-danger">Kupon tidak bisa kamu pakai karena tidak memenuhi minimal belanja</div>');
							redirect('panel/membership/tambahTransaksiMember/');
						}
					}else{
						$this->session->set_flashdata('notif','<div class="alert alert-danger">Kupon tidak bisa kamu pakai karena sudah melebihi limit penukaran kupon</div>');
						redirect('panel/membership/tambahTransaksiMember/');
					}
				}
			}

			if($dataMember){
				$data += array('pengguna' => $dataMember[0]->id_pengguna);
			}

			if($this->GeneralModel->create_general('ms_point_transaksi',$data) == TRUE){
				$dataPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$dataMember[0]->id_pengguna);
				if($dataPengguna){
					$tambahPoint = array(
						'point' => $dataPengguna[0]->point + $data['jumlah_point']
					);
					$this->GeneralModel->update_general('ms_pengguna','id_pengguna',$dataMember[0]->id_pengguna,$tambahPoint);
				}

				$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
				redirect('panel/membership/transaksiMember');
			}else{
				$this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal ditambahkan!</div>');
				redirect('panel/membership/transaksiMember');
			}

		}elseif($param1=='scanBarcode'){
			$barcode = $this->input->get('barcode');
			$dataMember = $this->GeneralModel->get_by_id_general('v_member','barcode_member',$barcode);
			if(!empty($dataMember)){
				echo json_encode($dataMember,JSON_PRETTY_PRINT);
			}else{
				echo 'false';
			}
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Transaksi Member';
			$data['content'] = 'panel/membership/pointTransaksi/create';
			$this->load->view('panel/content',$data);
		}
	}

	public function hapusTransaksiMember($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$data = $this->GeneralModel->get_by_id_general('ms_point_transaksi','id_point_transaksi',$param1);
		$updateData = array(
			'status_transaksi' => 'deleted',
			'updated_by' => $this->session->userdata('id_pengguna'),
			'updated_time' => DATE('Y-m-d H:i:s')
		);
		if($this->GeneralModel->update_general('ms_point_transaksi','id_point_transaksi',$param1,$updateData) == TRUE){
			$dataPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$data[0]->pengguna);
			if($dataPengguna){
				$kurangPoint = array(
					'point' => $dataPengguna[0]->point - $data['jumlah_point']
				);
				$this->GeneralModel->update_general('ms_pengguna','id_pengguna',$data[0]->pengguna,$kurangPoint);
			}
			$this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
			redirect('panel/membership/transaksiMember');
		}else{
			$this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
			redirect('panel/membership/transaksiMember');
		}		
	}

	//--------------- PRODUK REDEEM BEGIN------------------//
	public function daftarProdukRedeem($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
            return $this->MembershipModel->getProdukRedeem($status);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Produk Redeem';
			$data['content'] = 'panel/membership/produkRedeem/index';
			$data['status'] = $this->input->get('status');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahProdukRedeem($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$data = array(
				'nama_produk_redeem' => $this->input->post('nama_produk_redeem'),
				'sk_redeem' => $this->input->post('sk_redeem'),
				'keterangan_produk_redeem' => $this->input->post('keterangan_produk_redeem'),
				'harga_point' => $this->input->post('harga_point'),
				'created_by' => $this->session->userdata('id_pengguna')
			);

			$config['upload_path']          = 'assets/img/produkRedeem/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;

			$config['upload_path']          = 'assets/img/produkRedeem/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_produk_redeem'))
			{

			}
			else {
				$data += array('gambar_produk_redeem' => $config['upload_path'].$this->upload->data('file_name'));
			}
			
            if ($this->GeneralModel->create_general('ms_produk_redeem', $data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>');
                redirect('panel/membership/daftarProdukRedeem');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal ditambahkan!</div>');
                redirect('panel/membership/daftarProdukRedeem');
            }
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Produk Redeem';
			$data['content'] = 'panel/membership/produkRedeem/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateProdukRedeem($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$produk = $this->GeneralModel->get_by_id_general('ms_produk_redeem','id_produk_redeem',$param2);
			$data = array(
				'nama_produk_redeem' => $this->input->post('nama_produk_redeem'),
				'sk_redeem' => $this->input->post('sk_redeem'),
				'keterangan_produk_redeem' => $this->input->post('keterangan_produk_redeem'),
				'harga_point' => $this->input->post('harga_point'),
				'status' => $this->input->post('status'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s'),
			);

			$config['upload_path']          = 'assets/img/produkRedeem/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_produk_redeem'))
			{

			}
			else {
				if(!empty($produk[0]->gambar_produk_redeem)){
					try{
						unlink($produk[0]->gambar_produk_redeem);
					}catch(error $e){
					}
				}
				$data += array('gambar_produk_redeem' => $config['upload_path'].$this->upload->data('file_name'));
			}
                
            if ($this->GeneralModel->update_general('ms_produk_redeem','id_produk_redeem', $param2,$data) == true) {
                $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
                redirect('panel/membership/daftarProdukRedeem');
            } else {
                $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
                redirect('panel/membership/daftarProdukRedeem');
            }
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Produk Redeem';
			$data['content'] = 'panel/membership/produkRedeem/update';
			$data['produkRedeem'] = $this->GeneralModel->get_by_id_general('ms_produk_redeem','id_produk_redeem',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusProdukRedeem($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$produk = $this->GeneralModel->get_by_id_general('ms_produk_redeem', 'id_produk_redeem', $param1);

        foreach($produk as $key){
            if(!empty($key->gambar_produk_redeem)){
                try{
                    unlink($key->gambar_produk_redeem);
                }catch(error $e){

				}
            }
        }

        if ($this->GeneralModel->delete_general('ms_produk_redeem', 'id_produk_redeem', $param1) == TRUE) {
		} else {
            $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('panel/membership/daftarProdukRedeem');
            $this->session->set_flashdata('notif', '<div class="alert alert-danger" role="alert">Data gagal dihapus!</div>');
            redirect('panel/membership/daftarProdukRedeem');
        }
	}

	//----------- REDEEM KUPON BEGIN -------------//
	public function redeemKupon($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->MembershipModel->getRedeemKupon($start_date,$end_date,$uuid_toko);
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Redeem Voucher';
			$data['content'] = 'panel/membership/redeemKupon/index';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			$this->load->view('panel/content',$data);
		}
	}

	public function cekKupon(){
		activityLog($this->parent_modul,$this->akses_controller);
		$barcode = $this->input->post('barcode');
		$kode_kupon = $this->input->post('kode_kupon');
		$jumlah_transaksi = $this->input->post('jumlah_transaksi');

		$getKupon = $this->GeneralModel->get_by_id_general('ms_kupon','kode_kupon',$kode_kupon);
		if($getKupon){
			$cekKuponPengguna = $this->GeneralModel->get_by_multi_id_general('v_redeem_kupon','kode_kupon',$kode_kupon,'barcode',$barcode);
			if(empty($cekKuponPengguna)){
				if($jumlah_transaksi >= $getKupon[0]->min_belanja){
					echo json_encode($getKupon,JSON_PRETTY_PRINT);
				}else{
					echo 'notenought';
				}
			}else{
				if($cekKuponPengguna[0]->jml_kupon < $cekKuponPengguna[0]->kupon_terpakai){
					if($jumlah_transaksi >= $getKupon[0]->min_belanja){
						echo json_encode($getKupon,JSON_PRETTY_PRINT);
					}else{
						echo 'notenought';
					}
				}else{
					echo 'max';
				}
			}
		}else{
			echo 'false';
		}
	}

	//----------- REDEEM POINT -------------//
	public function redeemPoint($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->MembershipModel->getRedeemPoint($start_date,$end_date,$uuid_toko);
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Redeem Point';
			$data['content'] = 'panel/membership/redeemPoint/index';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			$this->load->view('panel/content',$data);
		}
	}

	public function tambahRedeemPoint($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$produk = $this->input->post('produk_redeem');
			$barcode = $this->input->post('barcode');
			$dataMember = $this->GeneralModel->get_by_id_general('v_member','barcode_member',$barcode);
			if(!empty($dataMember)){
				$produk = explode('#',$produk);
				$id_produk = $produk[0];
				$harga_point = $produk[1];
				if(($dataMember[0]->point > $harga_point) && ($dataMember[0]->point - $harga_point) > 0){
					$dataRedeem = array(
						'barcode_member' => $barcode,
						'pengguna' => $dataMember[0]->id_pengguna,
						'produk_redeem' => $id_produk,
						'harga_point' => $harga_point,
						'toko' => $this->session->userdata('uuid_toko'),
						'created_by' => $this->session->userdata('id_pengguna') 
					);
					$this->GeneralModel->create_general('ms_redeem_point',$dataRedeem);
					$updatePoint = array(
						'point' => $dataMember[0]->point - $harga_point,
					);
					$this->GeneralModel->create_general('ms_pengguna','id_pengguna',$dataMember[0]->id_pengguna,$updatePoint);
					$this->session->set_flashdata('notif','<div class="alert alert-success">Terimakasih point berhasil ditukarkan</div>');
					redirect('panel/membership/redeemPoint');
				}else{
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf point member anda tidak mencukupi untuk produk yang ditukarkan</div>');
					redirect('panel/membership/redeemPoint');
				}
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger" role="alert">Mohon maaf data member tidak ditemukan</div>');
				redirect('panel/membership/redeemPoint');
			}
		}elseif($param1=='scanBarcode'){
			$barcode = $this->input->get('barcode');
			$dataMember = $this->GeneralModel->get_by_id_general('v_member','barcode_member',$barcode);
			if(!empty($dataMember)){
				echo json_encode($dataMember,JSON_PRETTY_PRINT);
			}else{
				echo 'false';
			}
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Redeem Point';
			$data['content'] = 'panel/membership/redeemPoint/create';
			$data['produkRedeem'] = $this->GeneralModel->get_by_id_general('ms_produk_redeem','status','Y');
			$this->load->view('panel/content',$data);
		}
	}

	public function hapusRedeem($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$getDataRedeem = $this->GeneralModel->get_by_id_general('ms_redeem_point','id_redeem_point',$param1);
		if($getDataRedeem){
			foreach($getDataRedeem as $key){
				$dataMember = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$key->pengguna);
				$kembalikanPoint = array(
					'point' => $dataMember[0]->point + $key->harga_point,
				);
				$this->GeneralModel->update_general('ms_pengguna','id_pengguna',$key->id_pengguna,$kembalikanPoint);
				$this->GeneralModel->delete_general('ms_redeem_point','id_redeem_point',$key->pengguna);
				$this->session->set_flashdata('notif','<div class="alert alert-success" role="alert">Terimakasih, redeem point berhasil dibatalkan dan point sudah dikembalikan</div>');
				redirect('panel/membership/redeemPoint');
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger" role="alert">Mohon maaf data tidak ditemukan</div>');
			redirect('panel/membership/redeemPoint');
		}
	}

	public function buy1get1($param1='',$param2='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			return $this->MembershipModel->getBuy1Get1($start_date,$end_date);
		}elseif($param1=='konfirmasi'){
			$id = $param2;
			$status = 'diklaim';
			$sales = $this->session->userdata('id_pengguna');
			$tgl_klaim = DATE('Y-m-d H:i:s');

			$kode_klaim = random_string('alnum', );
			$cekKodeKlaim = $this->GeneralModel->get_by_id_general('ms_buy1get1','kode_klaim',$kode_klaim);
			while($cekKodeKlaim){
				$kode_klaim = random_string('alnum', 10);
				$cekKodeKlaim = $this->GeneralModel->get_by_id_general('ms_buy1get1','kode_klaim',$kode_klaim);
			}
			
			$data = array(
				'status_klaim' => $status,
				'sales' => $sales,
				'tgl_klaim' => $tgl_klaim,
				'updated_by' => $this->session->userdata('id_pengguna'),
                'kode_klaim' => strtoupper($kode_klaim),
				'tgl_klaim_sukses' => DATE('Y-m-d H:i:s'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s')
			);

			if($this->GeneralModel->update_general('ms_buy1get1','id_buy1get1',$id,$data) == TRUE){
				$cekPengajuan = $this->GeneralModel->get_by_id_general('ms_buy1get1','id_buy1get1',$param2);
				if($cekPengajuan){
					foreach ($cekPengajuan as $key) {
						$cekPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$key->pelanggan);
						foreach ($cekPengguna as $s) {
							$message = 'Halo *'.$s->nama_lengkap.'*!';
							$message .= urlencode("\n"); 
							$message .= "Pengajuan kamu dengan KODE KLAIM *".$key->kode_klaim."* telah dikonfirmasi";
							$message .= urlencode("\n"); 
							$message .= "Silahkan datang ke kasir terdekat di toko kami untuk melakukan klaim";
							$message .= urlencode("\n"); 
							$message .= "Tunjukkan kode klaim kamu ke kasir toko, Terima Kasih..";
							$message .= urlencode("\n"); 
							sendNotifWA2($s->no_telp,$message);  
						}
					}
				}
	
				$this->session->set_flashdata('notif','<div class="alert alert-success">Klaim berhasil dikonfirmasi</div>');
				redirect('panel/membership/buy1get1');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Klaim gagal dikonfirmasi</div>');
				redirect('panel/membership/buy1get1');
			}
		}elseif($param1=='redeem'){

			$id_buy1get1 = $this->input->post('id_buy1get1');
			$produk_klaim = $this->input->post('produk_klaim');

			$data = array(
				'status_klaim' => 'diklaim',
				'produk_klaim' => $produk_klaim,
				'tgl_klaim_sukses' => DATE('Y-m-d H:i:s'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s')
			);

			$cekPengajuan = $this->GeneralModel->get_by_id_general('ms_buy1get1','id_buy1get1',$param2);
			if($cekPengajuan){
				foreach ($cekPengajuan as $key) {
					$cekPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$key->pelanggan);
					foreach ($cekPengguna as $s) {
						$message = 'Halo *'.$s->nama_lengkap.'*!';
						$message .= urlencode("\n"); 
						$message .= "Pengajuan kamu dengan KODE KLAIM *".$key->kode_klaim."* telah dikonfirmasi";
						$message .= urlencode("\n"); 
						$message .= "Silahkan datang ke kasir terdekat di toko kami untuk melakukan klaim";
						$message .= urlencode("\n"); 
						$message .= "Tunjukkan kode klaim kamu ke kasir toko, Terima Kasih..";
						$message .= urlencode("\n"); 
						sendNotifWA2($s->no_telp,$message);  
					}
				}
			}

			if($this->GeneralModel->update_general('ms_buy1get1','id_buy1get1',$id_buy1get1,$data) == TRUE){
				$response = 'success';
				echo $response;
			}else{
				$response = 'failed';
				echo $response;
			}
		}elseif($param1=='hapus') {
			$cekPengajuan = $this->GeneralModel->get_by_id_general('ms_buy1get1','id_buy1get1',$param2);
			if($cekPengajuan){
				foreach ($cekPengajuan as $key) {
					$cekPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$key->pelanggan);
					foreach ($cekPengguna as $s) {
						$message = 'Halo *'.$s->nama_lengkap.'*!';
						$message .= urlencode("\n"); 
						$message .= "Pengajuan kamu dengan KODE KLAIM *".$key->kode_klaim."* kami tolak";
						$message .= urlencode("\n"); 
						$message .= "Mohon Maaf pengajuan kamu belum sesuai dengan syarat dan ketentuan";
						$message .= urlencode("\n"); 
						$message .= "Data pengajuan kamu akan kami hapus, Terima Kasih..";
						$message .= urlencode("\n"); 
						sendNotifWA2($s->no_telp,$message);  
					}
					if(!empty($key->foto_struk)){
						try {
							unlink($key->foto_struk);
						} catch (\Throwable $e) {
						}
					}
					$this->GeneralModel->delete_general('ms_buy1get1','id_buy1get1',$key->id_buy1get1);
					$this->session->set_flashdata('notif','<div class="alert alert-success">Hapus data berhasil dilakukan</div>');
					redirect('panel/membership/buy1get1');
				}
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Hapus data gagal</div>');
				redirect('panel/membership/buy1get1');
			}
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Buy 1 Get 1';
			$data['content'] = 'panel/membership/buy1get1/index';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			$data['produk'] = $this->GeneralModel->get_by_id_general('ms_produk','status','active');

			$this->load->view('panel/content',$data);
		}
	}

}
