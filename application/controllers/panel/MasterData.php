<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MasterData extends CI_Controller
{

	public $parent_modul = 'MasterData';
	public $title = 'Master Data';

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


	//--------------- PENGGUNA BEGIN------------------//
	public function daftarPengguna($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$hak_akses = $this->input->post('hak_akses');
			$status = $this->input->post('status');
			$toko = $this->input->post('toko');
			return $this->MasterDataModel->getPengguna($hak_akses,$status,$toko);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Pengguna';
			$data['content'] = 'panel/masterData/pengguna/index';
			$data['getHakAkses'] = $this->GeneralModel->get_general('ms_hak_akses');
			$data['getToko'] = $this->GeneralModel->get_general('ms_toko');
			$data['status'] = $this->input->get('status');
			$data['hak_akses'] = $this->input->get('hak_akses');
			$data['uuid_toko'] = $this->input->get('uuid_toko');
			$this->load->view('panel/content', $data);
		}
	}

	public function cekUsernamePengguna()
	{
		$username = $this->input->get('username');
		if ($this->GeneralModel->get_by_id_general('ms_pengguna', 'username', $username) == true) {
			echo "FALSE";
		} else {
			echo "TRUE";
		}
	}

	public function tambahPengguna($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$dataPengguna = array(
				'username' => $this->input->post('username'),
				'password' => sha1($this->input->post('password')),
				'email' => $this->input->post('email'),
				'hak_akses' => $this->input->post('hak_akses'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'jenkel' => $this->input->post('jenkel'),
				'alamat' => $this->input->post('alamat'),
				'no_telp' => $this->input->post('no_telp'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'nik' => $this->input->post('nik'),
				'nip' => $this->input->post('nip'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			//---------------- UPDATE FOTO PENGGUNA ---------------//
			$config['upload_path']          = 'assets/img/pengguna/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if (!$this->upload->do_upload('foto_pengguna')) {
			} else {
				$dataPengguna += array('foto_pengguna' => $config['upload_path'] . $this->upload->data('file_name'));
			}
			if ($this->GeneralModel->get_by_id_general('ms_pengguna','no_telp',$dataPengguna['no_telp']) == false) {
				if ($this->GeneralModel->create_general('ms_pengguna', $dataPengguna) == true) {
					$this->session->set_flashdata('notif', '<div class="alert alert-success">Data pengguna berhasil ditambahkan</div>');
					redirect(changeLink('panel/masterData/daftarPengguna'));
				} else {
					$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data pengguna gagal ditambahkan</div>');
					redirect(changeLink('panel/masterData/tambahPengguna'));
				}
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data pengguna gagal ditambahkan, no wa telah digunakan</div>');
				redirect(changeLink('panel/masterData/daftarPengguna'));
			}
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Pengguna';
			$data['content'] = 'panel/masterData/pengguna/create';
			$data['hakAkses'] = $this->GeneralModel->get_general('ms_hak_akses');
			$data['toko'] = $this->GeneralModel->get_general('ms_toko');
			$this->load->view('panel/content', $data);
		}
	}

	public function updatePengguna($param1 = '',$param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$dataPengguna = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'hak_akses' => $this->input->post('hak_akses'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'jenkel' => $this->input->post('jenkel'),
				'alamat' => $this->input->post('alamat'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'nik' => $this->input->post('nik'),
				'nip' => $this->input->post('nip'),
				'status' => $this->input->post('status'),
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

            $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna', 'id_pengguna', $param2);
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
			if ($this->session->userdata('id_pengguna') == $param2) {
				$this->session->set_userdata($dataPengguna);
			}
			if (!empty($this->input->post('password'))) {
				if ($this->input->post('password') == $this->input->post('re_password')) {
					$dataPengguna += array(
						'password' => sha1($this->input->post('password')),
					);
					$this->session->set_flashdata('notifpass', '<div class="alert alert-success">PIN berhasil diubah</div>');
				} else {
					$this->session->set_flashdata('notifpass', '<div class="alert alert-danger">PIN gagal diubah karena tidak sama dengan ulangi password_pengguna</div>');
				}
			}

			if ($this->GeneralModel->update_general('ms_pengguna', 'id_pengguna', $param2, $dataPengguna) == true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data pengguna berhasil diupdate</div>');
				redirect(changeLink('panel/masterData/daftarPengguna'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data pengguna gagal diupdate</div>');
				redirect(changeLink('panel/masterData/updatePengguna/' . $param2));
			}
		}else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Pengguna';
			$data['content'] = 'panel/masterData/pengguna/update';
			$data['hakAkses'] = $this->GeneralModel->get_general('ms_hak_akses');
			$data['pengguna'] = $this->GeneralModel->get_by_id_general('v_pengguna','id_pengguna',$param1);
			$data['toko'] = $this->GeneralModel->get_general('ms_toko');
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusPengguna($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna', 'id_pengguna', $param1);
		if (!empty($pengguna[0]->foto_pengguna)) {
			try {
				unlink($pengguna[0]->foto_pengguna);
			} catch (\Exception $e) {
			}
		}
		if ($this->GeneralModel->delete_general('ms_pengguna', 'id_pengguna', $pengguna[0]->id_pengguna) == true) {
			$this->session->set_flashdata('notif', '<div class="alert alert-success">Data pengguna berhasil dihapus</div>');
			redirect(changeLink('panel/masterData/daftarPengguna'));
		} else {
			$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data pengguna gagal dihapus</div>');
			redirect(changeLink('panel/masterData/daftarPengguna'));
		}
	}

	//--------------- HAK AKSES BEGIN------------------//
	public function daftarHakAkses($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Hak Akses';
			$data['content'] = 'panel/masterData/hakAkses/index';
			$data['hak_akses'] = $this->AksesModulModel->getHakAkses();
			$this->load->view('panel/content', $data);
	}

	public function tambahHakAkses($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1 == 'doCreate') {
			$nama_hak_akses = $this->input->post('nama_hak_akses');
			$parent_modul = $this->input->post('class_parent_modul');
			$parent_modul = array_unique($parent_modul);
			$parent_modul = array_values(array_unique($parent_modul));

			$parent_modul = array(
				"parent_modul" => $parent_modul,
			);
			$parent_modul = json_encode($parent_modul, JSON_PRETTY_PRINT);

			$modul = $this->input->post('controller_modul');
			$modul = array(
				"modul" => $modul,
			);

			$modul = json_encode($modul, JSON_PRETTY_PRINT);

			$data = array(
				'nama_hak_akses' => $nama_hak_akses,
				'modul_akses' => $modul,
				'parent_modul_akses' => $parent_modul,
			);

			if ($this->GeneralModel->create_general('ms_hak_akses', $data) == TRUE) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Hak Akses berhasil ditambahkan</div>');
				redirect(changeLink('panel/masterData/daftarHakAkses/'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Hak Akses gagal ditambahkan</div>');
				redirect(changeLink('panel/masterData/daftarHakAkses/'));
			}
		} else {
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Hak Akses';
			$data['content'] = 'panel/masterData/hakAkses/create';
			$data['parentModul'] = $this->GeneralModel->get_general_order_by('ms_parent_modul', 'urutan', 'ASC');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateHakAkses($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1 == 'doUpdate') {
			$nama_hak_akses = $this->input->post('nama_hak_akses');
			$parent_modul = $this->input->post('class_parent_modul');
			$parent_modul = array_unique($parent_modul);
			$parent_modul = array_values(array_unique($parent_modul));

			$parent_modul = array(
				"parent_modul" => $parent_modul,
			);
			$parent_modul = json_encode($parent_modul, JSON_PRETTY_PRINT);

			$modul = $this->input->post('controller_modul');
			$modul = array(
				"modul" => $modul,
			);

			$modul = json_encode($modul, JSON_PRETTY_PRINT);

			$data = array(
				'nama_hak_akses' => $nama_hak_akses,
				'modul_akses' => $modul,
				'parent_modul_akses' => $parent_modul,
			);

			if ($this->GeneralModel->update_general('ms_hak_akses', 'id_hak_akses', $param2, $data) == TRUE) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Hak Akses berhasil diupdate</div>');
				redirect(changeLink('panel/masterData/daftarHakAkses/'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Hak Akses gagal diupdate</div>');
				redirect(changeLink('panel/masterData/daftarHakAkses/'));
			}
		} else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Hak Akses';
			$data['content'] = 'panel/masterData/hakAkses/update';
			$data['id'] = $param1;
			$data['hak_akses'] = $this->GeneralModel->get_by_id_general('ms_hak_akses', 'id_hak_akses', $param1);
			$data['parentModul'] = $this->GeneralModel->get_general_order_by('ms_parent_modul', 'urutan', 'ASC');
			$this->load->view('panel/content', $data);
		}
	}

	public function deleteAkses($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		if ($this->GeneralModel->delete_general('ms_hak_akses', 'id_hak_akses', $param1) == TRUE) {
			$this->session->set_flashdata('notif', '<div class="alert alert-success">Hak Akses berhasil dihapus</div>');
			redirect(changeLink('panel/masterData/daftarHakAkses/'));
		} else {
			$this->session->set_flashdata('notif', '<div class="alert alert-danger">Hak Akses gagal dihapus</div>');
			redirect(changeLink('panel/masterData/daftarHakAkses/'));
		}
	}

	public function daftarToko($param1='')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1=='cari') {
			$status = $this->input->post('status');
			return $this->MasterDataModel->getToko($status);
		}else {
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Toko';
			$data['status'] = $this->input->get('status');
			$data['content'] = 'panel/masterData/toko/index';
			$this->load->view('panel/content',$data);
		}
	}

	public function tambahToko($param1='',$param2=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1=='doCreate') {
			$dataToko = array(
				'nama_toko' => ucwords($this->input->post('nama_toko')),
				'slug_toko' => url_title($this->input->post('nama_toko'),"dash",true),
				'email_owner' => $this->input->post('email_owner'),
				'alamat_toko' => $this->input->post('alamat_toko'),
				'provinsi' => $this->input->post('provinsi'),
				'origin' => $this->input->post('origin'),
				'no_telp' => $this->input->post('no_telp'),
				'no_wa' => $this->input->post('no_wa'),
				'lat_toko' => $this->input->post('lat'),
				'lng_toko' => $this->input->post('lng'),
				'biaya_asuransi' => $this->input->post('biaya_asuransi'),
				'min_radius' => $this->input->post('min_radius'),
				'urutan_toko' => $this->input->post('urutan_toko'),
			);
			$config['upload_path']          = 'assets/logo/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('logo_toko'))
			{
			}
			else {
				$dataToko += array('logo_toko' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/etalaseToko/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('etalase_toko'))
			{
			}
			else {
				$dataToko += array('etalase_toko' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$this->GeneralModel->create_general('ms_toko',$dataToko);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Data toko berhasil ditambahkan</div>');
			redirect('panel/masterData/daftarToko');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Toko';
			$data['content'] = 'panel/masterData/toko/create';
			$provinsi = apiRajaOngkir('province');
			$provinsi = json_decode($provinsi);
			$data['provinsi'] = $provinsi->rajaongkir->results;
			$this->load->view('panel/content',$data);
		}
	}

	public function updateToko($param1='',$param2=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1=='doUpdate') {
			$dataToko = array(
				'nama_toko' => ucwords($this->input->post('nama_toko')),
				'slug_toko' => url_title($this->input->post('nama_toko'),"dash",true),
				'email_owner' => $this->input->post('email_owner'),
				'alamat_toko' => $this->input->post('alamat_toko'),
				'provinsi' => $this->input->post('provinsi'),
				'origin' => $this->input->post('origin'),
				'no_telp' => $this->input->post('no_telp'),
				'no_wa' => $this->input->post('no_wa'),
				'lat_toko' => $this->input->post('lat'),
				'lng_toko' => $this->input->post('lng'),
				'biaya_asuransi' => $this->input->post('biaya_asuransi'),
				'min_radius' => $this->input->post('min_radius'),
				'urutan_toko' => $this->input->post('urutan_toko'),
			);

			$config['upload_path']          = 'assets/logo/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('logo_toko'))
			{

			}
			else {
				$getToko = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',$param2);
				foreach($getToko as $key){
					if (!empty($key->logo_toko)) {
						unlink($key->logo_toko);
					}
				}
				$dataToko += array('logo_toko' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/etalaseToko/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('etalase_toko'))
			{

			}
			else {
				$getToko = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',$param2);
				foreach($getToko as $key){
					if (!empty($key->etalase_toko)) {
						unlink($key->etalase_toko);
					}
				}
				$dataToko += array('etalase_toko' => $config['upload_path'].$this->upload->data('file_name'));
			}



			$this->GeneralModel->update_general('ms_toko','uuid_toko',$param2,$dataToko);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Data toko berhasil diupdate</div>');
			redirect('panel/masterData/daftarToko');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Toko';
			$data['content'] = 'panel/masterData/toko/update';
			$provinsi = apiRajaOngkir('province');
			$provinsi = json_decode($provinsi);
			$data['provinsi'] = $provinsi->rajaongkir->results;
			$data['toko'] = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',$param1);
			$this->load->view('panel/content',$data);
		}
	}

	public function hapusToko(){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		$id = $this->input->get('id');
		activityLog($this->parent_modul,$this->akses_controller,my_simple_crypt($id,'d'));
		$updateToko = array('status_toko' => '0');
		if($this->GeneralModel->update_general('ms_toko','uuid_toko',my_simple_crypt($id,'d'),$updateToko) == TRUE){
			$dataToko = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',my_simple_crypt($id,'d'));
			foreach ($dataToko as $key) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data toko '.$key->nama_toko.' berhasil di nonaktifkan</div>');
			}
			redirect('panel/masterData/daftarToko');
		}
	}

	public function kategoriProduk($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			return $this->MasterDataModel->getKategoriProduk();
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Kategori Produk';
			$data['content'] = 'panel/masterData/kategoriProduk/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahKategoriProduk($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'icon_kategori_produk' => $this->input->post('icon_kategori_produk'),
				'nama_kategori_produk' => $this->input->post('nama_kategori_produk'),
				'created_by' => $this->session->userdata('id_pengguna')
			);

			$config['upload_path']          = 'assets/kategoriProduk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_kategori_produk'))
			{

			}
			else {
				$data += array('gambar_kategori_produk' => $config['upload_path'].$this->upload->data('file_name'));
			}

			if ($this->GeneralModel->create_general('ms_kategori_produk',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data kategori produk berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data kategori produk gagal ditambahkan</div>');
			}

			redirect('panel/masterData/kategoriProduk');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Kategori Produk';
			$data['content'] = 'panel/masterData/kategoriProduk/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateKategoriProduk($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){

			$data = array(
				'icon_kategori_produk' => $this->input->post('icon_kategori_produk'),
				'nama_kategori_produk' => $this->input->post('nama_kategori_produk'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s'),
			);


			$config['upload_path']          = 'assets/kategoriProduk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_kategori_produk'))
			{

			}
			else {
				$getKategori = $this->GeneralModel->get_by_id_general('ms_kategori_produk','id_kategori_produk',$param2);
				foreach($getKategori as $key){
					if (!empty($key->gambar_kategori_produk)) {
						unlink($key->gambar_kategori_produk);
					}
				}
				$data += array('gambar_kategori_produk' => $config['upload_path'].$this->upload->data('file_name'));
			}

			if ($this->GeneralModel->update_general('ms_kategori_produk','id_kategori_produk',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data kategori produk berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data kategori produk gagal diupdate</div>');
			}

			redirect('panel/masterData/kategoriProduk');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Kategori Produk';
			$data['content'] = 'panel/masterData/kategoriProduk/update';
			$data['kategoriProduk'] = $this->GeneralModel->get_by_id_general('ms_kategori_produk','id_kategori_produk',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusKategoriProduk($param1=''){
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		$this->GeneralModel->delete_general('ms_kategori_produk','id_kategori_produk',$param1);
		$this->session->set_flashdata('notif','<div class="alert alert-success">Data kategori produk berhasil dihapus</div>');
		redirect('panel/masterData/kategoriProduk');
	}

	public function daftarSatuan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			return $this->MasterDataModel->getDaftarSatuan();
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Satuan';
			$data['content'] = 'panel/masterData/satuan/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahSatuan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'kode_satuan' => $this->input->post('kode_satuan'),
				'keterangan' => $this->input->post('keterangan'),
			);

			if ($this->GeneralModel->create_general('ms_satuan',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data satuan berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data satuan gagal ditambahkan</div>');
			}

			redirect('panel/masterData/daftarSatuan');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Satuan';
			$data['content'] = 'panel/masterData/satuan/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateSatuan($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$data = array(
				'kode_satuan' => $this->input->post('kode_satuan'),
				'keterangan' => $this->input->post('keterangan'),
				'status' => $this->input->post('status'),
			);

			if ($this->GeneralModel->update_general('ms_satuan','id_satuan',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data satuan berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data satuan gagal diupdate</div>');
			}

			redirect('panel/masterData/daftarSatuan');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Satuan';
			$data['content'] = 'panel/masterData/satuan/update';
			$data['satuan'] = $this->GeneralModel->get_by_id_general('ms_satuan','id_satuan',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusSatuan($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$this->GeneralModel->delete_general('ms_satuan','id_satuan',$param1);
		$this->session->set_flashdata('notif','<div class="alert alert-success">Data satuan berhasil dihapus</div>');
		redirect('panel/masterData/daftarSatuan');
	}

	public function daftarBrand($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$kategori = $this->input->post('kategori');
			return $this->MasterDataModel->getDaftarBrand($kategori);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Brand';
			$data['content'] = 'panel/masterData/brand/index';
			$data['kategori'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['id_kategori'] = $this->input->get('id_kategori');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahBrand($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){

			$data = array(
				'nama_brand' => $this->input->post('nama_brand'),
				'kategori' => $this->input->post('kategori'),
				'created_by' => $this->session->userdata('id_pengguna'),
			);

			$config['upload_path']          = 'assets/brand/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_brand'))
			{

			}
			else {
				$data += array('gambar_brand' => $config['upload_path'].$this->upload->data('file_name'));
			}


			if ($this->GeneralModel->create_general('ms_brand',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data brand berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data brand gagal ditambahkan</div>');
			}

			redirect('panel/masterData/daftarBrand');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Brand';
			$data['content'] = 'panel/masterData/brand/create';
			$data['kategori'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateBrand($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$data = array(
				'nama_brand' => $this->input->post('nama_brand'),
				'kategori' => $this->input->post('kategori'),
				'status_brand' => $this->input->post('status_brand'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s'),
			);

			$config['upload_path']          = 'assets/brand/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('gambar_brand'))
			{

			}
			else {
				$getKategori = $this->GeneralModel->get_by_id_general('ms_brand','id_brand',$param2);
				foreach($getKategori as $key){
					if (!empty($key->gambar_brand)) {
						unlink($key->gambar_brand);
					}
				}
				$data += array('gambar_brand' => $config['upload_path'].$this->upload->data('file_name'));
			}

			if ($this->GeneralModel->update_general('ms_brand','id_brand',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data brand berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data brand gagal diupdate</div>');
			}

			redirect('panel/masterData/daftarBrand');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Satuan';
			$data['content'] = 'panel/masterData/brand/update';
			$data['kategori'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['brand'] = $this->GeneralModel->get_by_id_general('ms_brand','id_brand',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusBrand($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$this->GeneralModel->delete_general('ms_brand','id_brand',$param1);
		$this->session->set_flashdata('notif','<div class="alert alert-success">Data brand berhasil dihapus</div>');
		redirect('panel/masterData/daftarBrand');
	}

}
