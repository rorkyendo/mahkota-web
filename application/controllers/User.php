<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
  {
	parent::__construct();
  }

		public function profile($param1='')
	{
		if ($param1=='cari') {
			$this->TransaksiModel->getRiwayatTransaksi($this->session->userdata('id_pengguna'));
		}else{
			$data['title'] = 'Profile';
			$data['content'] = 'frontend/users/profile';
			$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['member'] = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$this->session->userdata('id_pengguna'));
			$this->load->view('frontend/content', $data);
		}
	}

		public function updateProfile($param1='')
	{
		if ($param1=='doUpdate') {
			$dataPengguna = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'jenkel' => $this->input->post('jenkel'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE("Y-m-d H:i:s")
			);

			if (empty($this->input->post('uuid_toko'))) {
				$dataPengguna += array(
					'uuid_toko' => $this->session->userdata('uuid_toko')
				);
			}else{
				$dataPengguna += array(
					'uuid_toko' => $this->input->post('uuid_toko')
				);
			}

			//---------------- UPDATE FOTO PENGGUNA ---------------//
			$config['upload_path']          = 'assets/img/pengguna/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

            $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna', 'id_pengguna', $this->session->userdata('id_pengguna'));
			if (!$this->upload->do_upload('foto_pengguna')) {
			} else {
				$dataPengguna += array('foto_pengguna' => $config['upload_path'] . $this->upload->data('file_name'));
				if (!empty($pengguna[0]->foto_pengguna)) {
					try {
						unlink($pengguna[0]->foto_pengguna);
					} catch (\Exception $e) {
					}
				}
			}

			if ($this->GeneralModel->update_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'),$dataPengguna)==true) {
            	$pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna', 'id_pengguna', $this->session->userdata('id_pengguna'));
				$this->session->set_userdata($pengguna);
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data pengguna berhasil diupdate</div>');
				redirect(changeLink('user/updateProfile'));
			}else{
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data pengguna gagal diupdate</div>');
				redirect(changeLink('user/updateProfile'));
			}
		}else{
			$data['title'] = 'Update Profile';
			$data['content'] = 'frontend/users/updateProfile';
			$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$this->load->view('frontend/content', $data);
		}
	}

	public function updatePassword($param1=''){
		if ($param1=='doUpdate') {
			if (!empty($this->input->post('password'))) {
				if ($this->input->post('password') == $this->input->post('re_password')) {
					$dataPengguna = array(
						'password' => sha1($this->input->post('password')),
					);
					$this->session->set_flashdata('notifpass','<div class="alert alert-success">PIN berhasil diubah</div>');
				}else {
					$this->session->set_flashdata('notifpass','<div class="alert alert-danger">PIN gagal diubah karena tidak sama dengan ulangi password</div>');
				}
			}
			if ($this->GeneralModel->update_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'),$dataPengguna)==true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">PIN berhasil diupdate</div>');
				redirect(changeLink('user/updatePassword'));
			}else{
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">PIN gagal diupdate</div>');
				redirect(changeLink('user/updatePassword'));
			}
		}else{
			$data['title'] = 'Update PIN';
			$data['content'] = 'frontend/users/updatePassword';
			$this->load->view('frontend/content', $data);
		}
	}


	public function address($param1=''){
		if ($param1=='cari') {
			$id_pengguna = $this->session->userdata('id_pengguna');
			return $this->MasterDataModel->getInformasiPengguna($id_pengguna);
		}else{
			$data['title'] = 'Daftar Alamat';
			$data['content'] = 'frontend/users/address';
			$this->load->view('frontend/content', $data);
		}
	}

	public function createAddress($param1=''){
		if ($param1=='doCreate') {
			$data = array(
				'id_pengguna' => $this->session->userdata('id_pengguna'),
				'nama' => $this->input->post('nama'),
				'alamat_lengkap' => $this->input->post('alamat_lengkap'),
				'nomor_hp' => $this->input->post('nomor_hp'),
				'lat_lokasi' => $this->input->post('lat'),
				'lng_lokasi' => $this->input->post('lng'),
				'provinsi' => $this->input->post('provinsi'),
				'kabupaten' => $this->input->post('kabupaten'),
				'kode_pos' => $this->input->post('kode_pos'),
				);

			if ($this->GeneralModel->create_general('ms_informasi_pengguna',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Alamat penerima berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Alamat penerima gagal ditambahkan</div>');
			}
			redirect('user/address');
		}else{
			$data['title'] = 'Tambah Alamat';
			$data['content'] = 'frontend/users/createAddress';
			$provinsi = apiRajaOngkir('province');
			$provinsi = json_decode($provinsi);
			$data['provinsi'] = $provinsi->rajaongkir->results;
			$this->load->view('frontend/content', $data);
		}
	}

	public function updateAddress($param1='',$param2=''){
		if ($param1=='doUpdate') {
			$data = array(
				'id_pengguna' => $this->session->userdata('id_pengguna'),
				'nama' => $this->input->post('nama'),
				'alamat_lengkap' => $this->input->post('alamat_lengkap'),
				'nomor_hp' => $this->input->post('nomor_hp'),
				'lat_lokasi' => $this->input->post('lat'),
				'lng_lokasi' => $this->input->post('lng'),
				'provinsi' => $this->input->post('provinsi'),
				'kabupaten' => $this->input->post('kabupaten'),
				'kode_pos' => $this->input->post('kode_pos'),
			);

			$alamat = $this->GeneralModel->get_by_multi_id_general('ms_informasi_pengguna','id_informasi',$param2,'id_pengguna',$this->session->userdata('id_pengguna'));
			if ($alamat) {
				if ($this->GeneralModel->update_general('ms_informasi_pengguna','id_informasi',$param2,$data) == TRUE) {
					$this->session->set_flashdata('notif','<div class="alert alert-success">Alamat penerima berhasil diupdate</div>');
				}else{
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Alamat penerima gagal diupdate</div>');
				}
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kamu melakukan pelanggaran, Alamat penerima gagal diupdate</div>');
			}
			redirect('user/address');
		}else{
			$data['title'] = 'Update Alamat';
			$data['content'] = 'frontend/users/updateAddress';
			$provinsi = apiRajaOngkir('province');
			$provinsi = json_decode($provinsi);
			$data['provinsi'] = $provinsi->rajaongkir->results;
			$data['alamat'] = $this->GeneralModel->get_by_multi_id_general('ms_informasi_pengguna','id_informasi',$param1,'id_pengguna',$this->session->userdata('id_pengguna'));
			if ($data['alamat']) {
				$this->load->view('frontend/content', $data);
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data tidak ditemukan</div>');
				redirect('user/address');
			}
		}
	}

	public function hapusAddress($param1=''){
		$alamat = $this->GeneralModel->get_by_multi_id_general('ms_informasi_pengguna','id_informasi',$param1,'id_pengguna',$this->session->userdata('id_pengguna'));
		if ($alamat) {
			if ($this->GeneralModel->delete_general('ms_informasi_pengguna','id_informasi',$param1) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Alamat penerima berhasil dihapus</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Alamat penerima gagal dihapus</div>');
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kamu melakukan pelanggaran, Alamat penerima gagal dihapus</div>');
		}
		redirect('user/address');
	}

	public function registerMember($param1='',$param2=''){
		if ($param1=='doRegister') {
			$getTipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',my_simple_crypt($param2,'d'));
			if ($getTipeMember) {
				$cekUserMember = $this->GeneralModel->get_by_multi_id_general('v_member','id_pengguna',$this->session->userdata('id_pengguna'),'status','active');
				if ($cekUserMember) {
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kamu sudah terdaftar di member '.$cekUserMember[0]->nama_tipe_member.'</div>');
					redirect('user/registerMember');
				}else{
					foreach ($getTipeMember as $key) {
						$dataTransaksi = array(
							'pelanggan' => $this->session->userdata('id_pengguna'),
							'total' => $key->biaya_pendaftaran,
							'payment_status' => 'pending',
							'created_by' => $this->session->userdata('id_pengguna'),
							'tipe_transaksi' => 'umum',
							'jenis_transaksi' => 'member',
						);
						$this->GeneralModel->create_general('ms_transaksi',$dataTransaksi);
						$id_transaksi = $this->db->insert_id();
						$dataOrder = array(
							'tipe_member' => $key->id_tipe_member,
							'qty' => 1,
							'selling_price' => $key->biaya_pendaftaran,
							'capital_price' => 0,
							'subtotal' => $key->biaya_pendaftaran,
							'transaksi' => $id_transaksi
						);
						$this->GeneralModel->create_general('ms_order',$dataOrder);				
						$this->session->set_flashdata('notif','<div class="alert alert-success">Terima kasih sudah mendaftar menjadi member kami, silahkan selesaikan pembayaran untuk mengatifkan status member anda!</div>');
						redirect('user/profile');
					}
				}
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Tipe member tidak ditemukan</div>');
				redirect('user/registerMember');
			}
		}else{
			$data['title'] = 'Register Member';
			$data['content'] = 'frontend/users/registerMember';
			$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['member'] = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['tipeMember'] = $this->GeneralModel->get_by_id_general('ms_tipe_member','status_tipe','active');
			$this->load->view('frontend/content', $data);
		}
	}

	public function getInformasiPengguna(){
		$id_pengguna = $this->session->userdata('id_pengguna');
		$id_informasi = $this->input->post('id_informasi');
		$getInformasi = $this->GeneralModel->get_by_multi_id_general('ms_informasi_pengguna','id_informasi',$id_informasi,'id_pengguna',$id_pengguna);
		if($getInformasi){
			echo json_encode($getInformasi,JSON_PRETTY_PRINT);
		}else{
			echo 'false';
		}
	}

	public function deleteAccount($param1='')
	{
		if($param1=='doDelete'){
			$id_pengguna = $this->session->userdata('id_pengguna');
			$cekUser = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$id_pengguna);
			if($cekUser){
				if($this->input->post('delete') == 'HAPUS AKUN'){
					$dataPengguna = array(
						'status' => 'deleted',
						'updated_by' => $id_pengguna,
						'updated_time' => date('Y-m-d H:i:s')
					);
					$updatePengguna = $this->GeneralModel->update_general('ms_pengguna','id_pengguna',$id_pengguna,$dataPengguna);
					if($updatePengguna){
						$this->session->set_flashdata('notif','<div class="alert alert-success">Akun anda berhasil dihapus</div>');
						redirect('auth/logout');
					}else{
						$this->session->set_flashdata('notif','<div class="alert alert-danger">Akun anda gagal dihapus</div>');
						redirect('user/profile');
					}
				}else{
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Kata kunci yang anda masukkan salah</div>');
					redirect('user/deleteAccount');
				}
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Akun anda gagal dihapus</div>');
				redirect('user/profile');
			}
		}else{
			$data['title'] = 'Delete Account';
			$data['content'] = 'frontend/users/deleteAccount';
			$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['member'] = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$this->session->userdata('id_pengguna'));
			$this->load->view('frontend/content', $data);
		}
	}

}