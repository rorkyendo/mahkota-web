<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends CI_Controller
{

	public $parent_modul = 'Pengaturan';
	public $title = 'Pengaturan';

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('LoggedIN') == FALSE || $this->session->userdata('hak_akses') == 'member') redirect('auth/logout');
		if (cekParentModul($this->parent_modul) == FALSE) redirect('panel/dashboard');
		$this->akses_controller = $this->uri->segment(3);
	}

	public function identitasAplikasi($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		if ($param1 == 'doUpdate') {
			$identitasAplikasi = array(
				'apps_name' => $this->input->post('apps_name'),
				'apps_version' => $this->input->post('apps_version'),
				'buildnumber' => $this->input->post('buildnumber'),
				'apps_code' => $this->input->post('apps_code'),
				'agency' => $this->input->post('agency'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city'),
				'telephon' => $this->input->post('telephon'),
				'fax' => $this->input->post('fax'),
				'website' => $this->input->post('website'),
				'header' => $this->input->post('header'),
				'footer' => $this->input->post('footer'),
				'keyword' => $this->input->post('keyword'),
				'about_us' => $this->input->post('about_us'),
				'facebook' => $this->input->post('facebook'),
				'twitter' => $this->input->post('twitter'),
				'instagram' => $this->input->post('instagram'),
				'email' => $this->input->post('email')
			);
			//---------------- UPDATE LOGO ---------------//
			$config['upload_path']          = 'assets/img/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);
			$getIdentitas = $this->GeneralModel->get_general('ms_identitas');
			if (!$this->upload->do_upload('logo')) {
			} else {
				if (!empty($getIdentitas[0]->logo)) {
					unlink($getIdentitas[0]->logo);
				}
				$identitasAplikasi += array('logo' => $config['upload_path'] . $this->upload->data('file_name'));
			}

			//---------------- UPDATE ICON ---------------//
			$config['upload_path']          = 'assets/img/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg|ico';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if (!$this->upload->do_upload('icon')) {
			} else {
				if (!empty($getIdentitas[0]->icon)) {
					unlink($getIdentitas[0]->icon);
				}
				$identitasAplikasi += array('icon' => $config['upload_path'] . $this->upload->data('file_name'));
			}

			if ($this->GeneralModel->update_general('ms_identitas', 'id_profile', 1, $identitasAplikasi) == true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data identitas aplikasi berhasil diupdate</div>');
				redirect(changeLink('panel/pengaturan/identitasAplikasi'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data identitas aplikasi berhasil diupdate</div>');
				redirect(changeLink('panel/pengaturan/identitasAplikasi'));
			}
		} else {
			$data['title'] = $this->title;
			$data['subtitle'] = 'Identitas Aplikasi';
			$data['content'] = 'panel/pengaturan/identitas/update';
			$data['identitas'] = $this->GeneralModel->get_by_id_general('ms_identitas','id_profile',1);
			$this->load->view('panel/content', $data);
		}
	}

	public function daftarSlider($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
			return $this->SettingsModel->getSlider($status);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Slider';
			$data['content'] = 'panel/pengaturan/slider/index';
			$data['status'] = $this->input->get('status');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahSlider($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'urutan_slider' => $this->input->post('urutan_slider'),
				'judul_slider' => $this->input->post('judul_slider'),
				'tipe_produk' => $this->input->post('tipe_produk'),
				'kategori' => $this->input->post('kategori'),
				'brand' => $this->input->post('brand'),
				'produk' => $this->input->post('produk'),
				'text_slider' => $this->input->post('text_slider'),
				'posisi_text' => $this->input->post('posisi_text'),
				'url_slider' => $this->input->post('url_slider'),
			);

			//---------------- BAKCGROUND SLIDER MOBILE ---------------//
			$config['upload_path']          = 'assets/img/slider/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if (!$this->upload->do_upload('gambar_slider')) {
			} else {
				$data += array('gambar_slider' => $config['upload_path'] . $this->upload->data('file_name'));
			}

			//---------------- BAKCGROUND SLIDER WEB ---------------//
			$config['upload_path']          = 'assets/img/slider/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if (!$this->upload->do_upload('gambar_slider_2')) {
			} else {
				$data += array('gambar_slider_2' => $config['upload_path'] . $this->upload->data('file_name'));
			}

			if ($this->GeneralModel->create_general('ms_slider',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data slider berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data slider gagal ditambahkan</div>');
			}

			redirect('panel/pengaturan/daftarSlider');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Slider';
			$data['content'] = 'panel/pengaturan/slider/create';
			$data['kategoriPoduk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['brand'] = $this->GeneralModel->get_general('ms_brand');
			$data['produk'] = $this->GeneralModel->get_by_id_general('ms_produk','tampil_toko','Y');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateSlider($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$slider = $this->GeneralModel->get_by_id_general('ms_slider','id_slider',$param2);
			$data = array(
				'urutan_slider' => $this->input->post('urutan_slider'),
				'judul_slider' => $this->input->post('judul_slider'),
				'tipe_produk' => $this->input->post('tipe_produk'),
				'kategori' => $this->input->post('kategori'),
				'brand' => $this->input->post('brand'),
				'produk' => $this->input->post('produk'),
				'text_slider' => $this->input->post('text_slider'),
				'posisi_text' => $this->input->post('posisi_text'),
				'url_slider' => $this->input->post('url_slider'),
				'status_slider' => $this->input->post('status_slider')
			);

			//---------------- BACKGROUND SLIDER ---------------//
			$config['upload_path']          = 'assets/img/slider/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if (!$this->upload->do_upload('gambar_slider')) {
			} else {
				if (!empty($slider[0]->gambar_slider)) {
					unlink($slider[0]->gambar_slider);
				}
				$data += array('gambar_slider' => $config['upload_path'] . $this->upload->data('file_name'));
			}

			//---------------- BACKGROUND SLIDER ---------------//
			$config['upload_path']          = 'assets/img/slider/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if (!$this->upload->do_upload('gambar_slider_2')) {
			} else {
				if (!empty($slider[0]->gambar_slider_2)) {
					unlink($slider[0]->gambar_slider_2);
				}
				$data += array('gambar_slider_2' => $config['upload_path'] . $this->upload->data('file_name'));
			}
			


			if ($this->GeneralModel->update_general('ms_slider','id_slider',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data slider berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data slider gagal diupdate</div>');
			}

			redirect('panel/pengaturan/daftarSlider');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Slider';
			$data['content'] = 'panel/pengaturan/slider/update';
			$data['slider'] = $this->GeneralModel->get_by_id_general('ms_slider','id_slider',$param1);
			$data['kategoriPoduk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['brand'] = $this->GeneralModel->get_general('ms_brand');
			$data['produk'] = $this->GeneralModel->get_by_id_general('ms_produk','tampil_toko','Y');
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusSlider($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$slider = $this->GeneralModel->get_by_id_general('ms_slider','id_slider',$param1);
		if (!empty($slider[0]->gambar_slider)) {
			unlink($slider[0]->gambar_slider);
		}
		$this->GeneralModel->delete_general('ms_slider','id_slider',$param1);
		$this->session->set_flashdata('notif','<div class="alert alert-success">Data slider berhasil dihapus</div>');
		redirect('panel/pengaturan/daftarSlider');
	}

	//--------------------------------- Begin Menu Atas ---------------------//
	public function menuAtas($param1 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}
		if ($param1 == 'cari') {
			return $this->SettingsModel->getMenuAtas();
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Menu Atas';
			$data['content'] = 'panel/pengaturan/menu/menuAtas/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahMenuAtas($param1 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		if ($param1 == 'doCreate') {
			$dataMenuAtas = array(
				'urutan' => $this->input->post('urutan'),
				'nama_menu' => $this->input->post('nama_menu'),
				'url' => $this->input->post('url'),
				'created_by' => $this->session->userdata('id_pengguna')
			);
			if ($this->GeneralModel->create_general('ms_menu_atas', $dataMenuAtas) == true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data menu atas berhasil ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/menuAtas'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data menu atas gagal ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/menuAtas'));
			}
		} else {
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Menu Atas';
			$data['content'] = 'panel/pengaturan/menu/menuAtas/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateMenuAtas($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}
		if ($param1 == 'doUpdate') {
			$dataMenuAtas = array(
				'urutan' => $this->input->post('urutan'),
				'nama_menu' => $this->input->post('nama_menu'),
				'url' => $this->input->post('url'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s')
			);
			if ($this->GeneralModel->update_general('ms_menu_atas','id_menu', $param2, $dataMenuAtas) == true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data menu atas berhasil ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/menuAtas'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data menu atas gagal ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/menuAtas'));
			}
		} else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Menu Atas';
			$data['content'] = 'panel/pengaturan/menu/menuAtas/update';
			$data['menuAtas'] = $this->GeneralModel->get_by_id_general('ms_menu_atas','id_menu',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusMenuAtas($param1 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		if ($this->GeneralModel->delete_general('ms_menu_atas', 'id_menu', $param1) == true) {
			$this->session->set_flashdata('notif', '<div class="alert alert-success">Data menu atas berhasil dihapus</div>');
			redirect(changeLink('panel/pengaturan/menuAtas'));
		} else {
			$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data menu atas gagal dihapus</div>');
			redirect(changeLink('panel/pengaturan/menuAtas'));
		}
	}

	//--------------------------------- Begin Navigasi Menu ---------------------//
	public function navigasiMenu($param1 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}
		if ($param1 == 'cari') {
			return $this->SettingsModel->getNavigasiMenu();
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Navigasi Menu';
			$data['content'] = 'panel/pengaturan/menu/navigasiMenu/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahNavigasiMenu($param1 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}
		activityLog($this->parent_modul,$this->akses_controller,'');
		if ($param1 == 'doCreate') {
			$dataMenu = array(
				'urutan' => $this->input->post('urutan'),
				'nama_menu' => $this->input->post('nama_menu'),
				'jenis_menu' => $this->input->post('jenis_menu'),
				'isi_menu' => $this->input->post('isi_menu'),
				'url' => $this->input->post('url'),
				'menu_induk' => $this->input->post('menu_induk'),
				'deskripsi_menu' => $this->input->post('deskripsi_menu'),
				'created_by' => $this->session->userdata('id_pengguna')
			);

			$config['upload_path']          = 'assets/icon/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('icon_menu'))
			{

			}
			else {
				$dataMenu += array('icon_menu' => $config['upload_path'].$this->upload->data('file_name'));
			}


			if ($this->GeneralModel->create_general('ms_menu', $dataMenu) == true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data menu berhasil ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/navigasiMenu'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data menu gagal ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/navigasiMenu'));
			}
		} else {
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Navigasi Menu';
			$data['content'] = 'panel/pengaturan/menu/navigasiMenu/create';
			$data['navigasiMenu'] = $this->GeneralModel->get_general('ms_menu');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateNavigasiMenu($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}

		if ($param1 == 'doUpdate') {
			$dataMenu = array(
				'urutan' => $this->input->post('urutan'),
				'nama_menu' => $this->input->post('nama_menu'),
				'jenis_menu' => $this->input->post('jenis_menu'),
				'isi_menu' => $this->input->post('isi_menu'),
				'url' => $this->input->post('url'),
				'menu_induk' => $this->input->post('menu_induk'),
				'deskripsi_menu' => $this->input->post('deskripsi_menu'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s')
			);

			$config['upload_path']          = 'assets/icon/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('icon_menu'))
			{

			}
			else {
				$getMenu = $this->GeneralModel->get_by_id_general('ms_menu','id_menu',$param2);
				foreach($getMenu as $key){
					if (!empty($key->icon_menu)) {
						unlink($key->icon_menu);
					}
				}
				$dataMenu += array('icon_menu' => $config['upload_path'].$this->upload->data('file_name'));
			}


			if ($this->GeneralModel->update_general('ms_menu', 'id', $param2, $dataMenu) == true) {
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data menu berhasil ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/navigasiMenu'));
			} else {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data menu gagal ditambahkan</div>');
				redirect(changeLink('panel/pengaturan/navigasiMenu'));
			}
		} else {
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Menu Atas';
			$data['content'] = 'panel/pengaturan/menu/navigasiMenu/update';
			$data['navigasiMenu'] = $this->GeneralModel->get_by_id_general('ms_menu', 'id', $param1);
			$data['menuUtama'] = $this->GeneralModel->get_by_id_general('ms_menu', 'jenis_menu', 'utama');
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusNavigasiMenu($param1 = '')
	{
		if (cekModul($this->akses_controller) == false) {
			redirect('auth/access_denied');
		}
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		if ($this->GeneralModel->delete_general('ms_menu', 'id', $param1) == true) {
			$this->session->set_flashdata('notif', '<div class="alert alert-success">Data menu berhasil dihapus</div>');
			redirect(changeLink('panel/pengaturan/navigasiMenu'));
		} else {
			$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data menu gagal dihapus</div>');
			redirect(changeLink('panel/pengaturan/navigasiMenu'));
		}
	}

	public function daftarPromosi($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
			return $this->SettingsModel->getPromosi($status);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Promosi';
			$data['content'] = 'panel/pengaturan/promosi/index';
			$data['status'] = $this->input->get('status');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahPromosi($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'urutan_promosi' => $this->input->post('urutan_promosi'),
				'judul_promosi' => $this->input->post('judul_promosi'),
				'text_promosi' => $this->input->post('text_promosi'),
				'url_promosi' => $this->input->post('url_promosi'),
				'jenis_promosi' => $this->input->post('jenis_promosi'),
				'tipe_promosi' => $this->input->post('tipe_promosi'),
				'waktu_promosi' => $this->input->post('waktu_promosi'),
				'lokasi_promosi' => $this->input->post('lokasi_promosi'),
				'kategori' => $this->input->post('kategori'),
				'brand' => $this->input->post('brand'),
				'produk' => $this->input->post('produk'),
			);

			//---------------- PRODUK SLIDER ---------------//
			$config['upload_path'] = 'assets/img/promosi/produk/';
			$config['allowed_types'] = '*';
			$config['max_size'] = 500000;


			$this->upload->initialize($config);

			if($this->input->post('jenis_promosi') == '1'){
				if (!$this->upload->do_upload('file_promosi_video')) {
				} else {
					$data += array('file_promosi' => $config['upload_path'] . $this->upload->data('file_name'));
				}
			}else if($this->input->post('jenis_promosi') == '3'){
				if (!$this->upload->do_upload('file_promosi_gambar')) {
				} else {
					$data += array('file_promosi' => $config['upload_path'] . $this->upload->data('file_name'));
				}
			}


			if ($this->GeneralModel->create_general('ms_promosi',$data) == TRUE) {
				// $device = $this->GeneralModel->get_general('ms_device_notif');
				// foreach ($device as $key){
				// 	$headers = array(
				// 		'Accept: application/json',
				// 		'Accept-Encoding: gzip, deflate',
				// 		'Content-Type: application/json',
				// 		'Host: exp.host'
				// 	);
				// 	$data = array(
				// 		'to' => $key->deviceid,
				// 		'title' => "Promo Baru",
				// 		'body' => "Hai, ada promo baru yang bisa kamu ikuti, silahkan cek di aplikasi kami ya",
				// 		'subtitle' => "Promo Baru",
				// 	);
				// 	$ch = curl_init('https://exp.host/--/api/v2/push/send');
				// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				// 	curl_setopt($ch, CURLOPT_POST, true);
				// 	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				// 	$response = curl_exec($ch);
				// 	curl_close($ch);
				// }
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data promosi berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data promosi gagal ditambahkan</div>');
			}

			redirect('panel/pengaturan/daftarPromosi');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Promosi';
			$data['content'] = 'panel/pengaturan/promosi/create';
			$data['kategoriPoduk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['brand'] = $this->GeneralModel->get_general('ms_brand');
			$data['produk'] = $this->GeneralModel->get_by_id_general('ms_produk','tampil_toko','Y');			
			$this->load->view('panel/content', $data);
		}
	}

	public function updatePromosi($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$promosi = $this->GeneralModel->get_by_id_general('ms_promosi','id_promosi',$param2);
			$data = array(
				'urutan_promosi' => $this->input->post('urutan_promosi'),
				'judul_promosi' => $this->input->post('judul_promosi'),
				'text_promosi' => $this->input->post('text_promosi'),
				'url_promosi' => $this->input->post('url_promosi'),
				'status_promosi' => $this->input->post('status_promosi'),
				'jenis_promosi' => $this->input->post('jenis_promosi'),
				'tipe_promosi' => $this->input->post('tipe_promosi'),
				'waktu_promosi' => $this->input->post('waktu_promosi'),
				'lokasi_promosi' => $this->input->post('lokasi_promosi'),
				'kategori' => $this->input->post('kategori'),
				'brand' => $this->input->post('brand'),
				'produk' => $this->input->post('produk'),
			);

			//---------------- PRODUK SLIDER ---------------//
			$config['upload_path'] = 'assets/img/promosi/produk/';
			$config['allowed_types'] = '*';
			$config['max_size'] = 100000;


			$this->upload->initialize($config);

			if($this->input->post('jenis_promosi') == '1'){
				if (!$this->upload->do_upload('file_promosi_video')) {
				} else {
					if (!empty($promosi[0]->file_promosi)) {
						unlink($promosi[0]->file_promosi);
					}
					$data += array('file_promosi' => $config['upload_path'] . $this->upload->data('file_name'));
				}
			}else if($this->input->post('jenis_promosi') == '3'){
				if (!$this->upload->do_upload('file_promosi_gambar')) {
				} else {
					if (!empty($promosi[0]->file_promosi)) {
						unlink($promosi[0]->file_promosi);
					}
					$data += array('file_promosi' => $config['upload_path'] . $this->upload->data('file_name'));
				}
			}

			if ($this->GeneralModel->update_general('ms_promosi','id_promosi',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data promosi berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data promosi gagal diupdate</div>');
			}

			redirect('panel/pengaturan/daftarPromosi');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Promosi';
			$data['content'] = 'panel/pengaturan/promosi/update';
			$data['promosi'] = $this->GeneralModel->get_by_id_general('ms_promosi','id_promosi',$param1);
			$data['kategoriPoduk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['brand'] = $this->GeneralModel->get_general('ms_brand');
			$data['produk'] = $this->GeneralModel->get_by_id_general('ms_produk','tampil_toko','Y');
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusPromosi($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$promosi = $this->GeneralModel->get_by_id_general('ms_promosi','id_promosi',$param1);
		if (!empty($promosi[0]->produk_promosi)) {
			unlink($promosi[0]->produk_promosi);
		}
		$this->GeneralModel->delete_general('ms_promosi','id_promosi',$param1);
		$this->session->set_flashdata('notif','<div class="alert alert-success">Data promosi berhasil dihapus</div>');
		redirect('panel/pengaturan/daftarPromosi');
	}

}
