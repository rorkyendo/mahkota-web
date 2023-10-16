<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inventori extends CI_Controller
{

	public $parent_modul = 'Inventori';
	public $title = 'Inventori';

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

	public function daftarInventori($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$toko = $this->input->post('uuid_toko');
			$gudang = $this->input->post('gudang');
			$lokasi_penyimpanan = $this->input->post('lokasi_penyimpanan');
			return $this->InventoriModel->getInventori($toko,$gudang,$lokasi_penyimpanan);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Inventori';
			$data['content'] = 'panel/inventori/index';
			$data['gudang'] = $this->GeneralModel->get_by_id_general('ms_gudang','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['lokasiPenyimpanan'] = $this->GeneralModel->get_by_id_general('ms_lokasi_penyimpanan','gudang',$this->input->get('id_gudang'));
			$data['id_gudang'] = $this->input->get('id_gudang');
			$data['id_lokasi_penyimpanan'] = $this->input->get('id_lokasi_penyimpanan');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahInventori($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'kode_inventori' => $this->input->post('kode_inventori'),
				'barcode' => $this->input->post('barcode'),
				'nama_inventori' => $this->input->post('nama_inventori'),
				'qty' => $this->input->post('qty'),
				'harga_beli' => $this->input->post('harga_beli'),
				'harga_modal' => $this->input->post('harga_modal'),
				'harga_jual' => $this->input->post('harga_jual'),
				'harga_jual_grosir' => $this->input->post('harga_jual_grosir'),
				'harga_jual_online' => $this->input->post('harga_jual_online'),
				'kode_satuan' => $this->input->post('kode_satuan'),
				'id_jenis_inventori' => $this->input->post('id_jenis_inventori'),
				'gudang' => $this->input->post('gudang'),
				'lokasi_penyimpanan' => $this->input->post('lokasi_penyimpanan'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if (!empty($this->input->post('barcode'))) {
				$this->zend->load('Zend/Barcode.php'); 
				$barcode = $this->input->post('barcode');
				$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$barcode), array())->draw($barcode,'image', array('text'=>$barcode), array());
				$imageName = $barcode.'.jpg';
				$imagePath = 'assets/barcodeInventory/';
				imagejpeg($imageResource, $imagePath.$imageName); 
				$pathBarcode = $imagePath.$imageName; 				
				$data += array(
					'barcode_image' => $pathBarcode
				);
			}


			if ($this->GeneralModel->create_general('ms_inventori',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data inventori berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data inventori gagal ditambahkan</div>');
			}

			redirect('panel/inventori/daftarInventori');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Inventori';
			$data['content'] = 'panel/inventori/create';
			$data['gudang'] = $this->GeneralModel->get_by_id_general('ms_gudang','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['satuan'] = $this->GeneralModel->get_general('ms_satuan');
			$this->load->view('panel/content', $data);
		}
	}

	public function updateInventori($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$getInventori = $this->GeneralModel->get_by_multi_id_general('ms_inventori','id_inventori',$param2,'uuid_toko',$this->session->userdata('uuid_toko'));
			$data = array(
				'kode_inventori' => $this->input->post('kode_inventori'),
				'barcode' => $this->input->post('barcode'),
				'nama_inventori' => $this->input->post('nama_inventori'),
				'qty' => $this->input->post('qty'),
				'harga_beli' => $this->input->post('harga_beli'),
				'harga_modal' => $this->input->post('harga_modal'),
				'harga_jual' => $this->input->post('harga_jual'),
				'harga_jual_grosir' => $this->input->post('harga_jual_grosir'),
				'harga_jual_online' => $this->input->post('harga_jual_online'),
				'kode_satuan' => $this->input->post('kode_satuan'),
				'id_jenis_inventori' => $this->input->post('id_jenis_inventori'),
				'gudang' => $this->input->post('gudang'),
				'lokasi_penyimpanan' => $this->input->post('lokasi_penyimpanan'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if (!empty($this->input->post('barcode'))) {
				$this->zend->load('Zend/Barcode.php');
				if (!empty($getInventori[0]->barcode_image)) {
					unlink($getInventori[0]->barcode_image);
				}
				$barcode = $this->input->post('barcode');
				$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$barcode), array())->draw($barcode,'image', array('text'=>$barcode), array());
				$imageName = $barcode.'.jpg';
				$imagePath = 'assets/barcodeInventory/';
				imagejpeg($imageResource, $imagePath.$imageName); 
				$pathBarcode = $imagePath.$imageName; 				
				$data += array(
					'barcode_image' => $pathBarcode
				);
			}

			if ($this->GeneralModel->update_general('ms_inventori','id_inventori',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data inventori berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data inventori gagal diupdate</div>');
			}

			redirect('panel/inventori/daftarInventori');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Inventori';
			$data['content'] = 'panel/inventori/update';
			$data['gudang'] = $this->GeneralModel->get_by_id_general('ms_gudang','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['satuan'] = $this->GeneralModel->get_general('ms_satuan');
			$data['inventori'] = $this->GeneralModel->get_by_id_general('ms_inventori','id_inventori',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusInventori($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$getInventori = $this->GeneralModel->get_by_multi_id_general('ms_inventori','id_inventori',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
		if($getInventori){
			if (!empty($getInventori[0]->barcode_image)) {
				unlink($getInventori[0]->barcode_image);
			}
			$this->GeneralModel->delete_general('ms_inventori','id_inventori',$param1);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Data inventori berhasil dihapus</div>');
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Data inventori tidak ditemukan</div>');
		}
		redirect('panel/inventori/daftarInventori');
	}


	public function daftarGudang($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$toko = $this->input->post('uuid_toko');
			return $this->InventoriModel->getGudang($toko);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Gudang';
			$data['content'] = 'panel/inventori/gudang/index';
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahGudang($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'kode_gudang' => $this->input->post('kode_gudang'),
				'nama_gudang' => $this->input->post('nama_gudang'),
				'alamat_gudang' => $this->input->post('alamat_gudang'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if ($this->GeneralModel->create_general('ms_gudang',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data gudang berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data gudang gagal ditambahkan</div>');
			}
			redirect('panel/inventori/daftarGudang');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Gudang';
			$data['content'] = 'panel/inventori/gudang/create';
			$this->load->view('panel/content', $data);
		}
	}

	public function updateGudang($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$data = array(
				'kode_gudang' => $this->input->post('kode_gudang'),
				'nama_gudang' => $this->input->post('nama_gudang'),
				'alamat_gudang' => $this->input->post('alamat_gudang'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s'),
			);

			if ($this->GeneralModel->update_general('ms_gudang','id_gudang',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data gudang berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data gudang gagal diupdate</div>');
			}
			redirect('panel/inventori/daftarGudang');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Gudang';
			$data['content'] = 'panel/inventori/gudang/update';
			$data['gudang'] = $this->GeneralModel->get_by_multi_id_general('ms_gudang','id_gudang',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusGudang($param1){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$getGudang = $this->GeneralModel->get_by_multi_id_general('ms_gudang','id_gudang',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
		if ($getGudang) {
			$this->GeneralModel->delete_general('ms_gudang','id_gudang',$param1);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Data gudang berhasil dihapus</div>');
			redirect('panel/inventori/daftarGudang');
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Data gudang gagal dihapus toko tidak sesuai</div>');
			redirect('panel/inventori/daftarGudang');
		}
	}


	public function lokasiPenyimpanan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$toko = $this->input->post('uuid_toko');
			$gudang = $this->input->post('id_gudang');
			return $this->InventoriModel->getLokasiPenyimpanan($toko,$gudang);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Lokasi Penyimpanan';
			$data['content'] = 'panel/inventori/lokasiPenyimpanan/index';
			$data['gudang'] = $this->GeneralModel->get_by_id_general('ms_gudang','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['id_gudang'] = $this->input->get('id_gudang');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahLokasiPenyimpanan($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'kode_lokasi_penyimpanan' => $this->input->post('kode_lokasi_penyimpanan'),
				'nama_lokasi_penyimpanan' => $this->input->post('nama_lokasi_penyimpanan'),
				'gudang' => $this->input->post('gudang'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if ($this->GeneralModel->create_general('ms_lokasi_penyimpanan',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data lokasi penyimpanan berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data lokasi penyimpanan gagal ditambahkan</div>');
			}
			redirect('panel/inventori/lokasiPenyimpanan');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Lokasi Penyimpanan';
			$data['content'] = 'panel/inventori/lokasiPenyimpanan/create';
			$data['gudang'] = $this->GeneralModel->get_by_id_general('ms_gudang','uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function updateLokasiPenyimpanan($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$data = array(
				'kode_lokasi_penyimpanan' => $this->input->post('kode_lokasi_penyimpanan'),
				'nama_lokasi_penyimpanan' => $this->input->post('nama_lokasi_penyimpanan'),
				'gudang' => $this->input->post('gudang'),
				'uuid_toko' => $this->session->userdata('uuid_toko'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE('Y-m-d H:i:s'),
			);

			if ($this->GeneralModel->update_general('ms_lokasi_penyimpanan','id_lokasi_penyimpanan',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data lokasi penyimpanan berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data lokasi penyimpanan gagal diupdate</div>');
			}
			redirect('panel/inventori/lokasiPenyimpanan');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Lokasi Penyimpanan';
			$data['content'] = 'panel/inventori/lokasiPenyimpanan/update';
			$data['gudang'] = $this->GeneralModel->get_by_id_general('ms_gudang','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['lokasiPenyimpanan'] = $this->GeneralModel->get_by_multi_id_general('ms_lokasi_penyimpanan','id_lokasi_penyimpanan',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function getLokasiPenyimpanan(){
		$gudang = $this->input->get('gudang');
		$lokasiPenyimpanan = $this->GeneralModel->get_by_multi_id_general('ms_lokasi_penyimpanan','gudang',$gudang,'uuid_toko',$this->session->userdata('uuid_toko'));
		if($lokasiPenyimpanan){
			echo json_encode($lokasiPenyimpanan);
		}else{
			echo 'false';
		}
	}

	public function hapusLokasiPenyimpanan($param1){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$getLokasi = $this->GeneralModel->get_by_multi_id_general('ms_lokasi_penyimpanan','id_lokasi_penyimpanan',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
		if ($getLokasi) {
			$this->GeneralModel->delete_general('ms_lokasi_penyimpanan','id_lokasi_penyimpanan',$param1);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Data lokasi penyimpanan berhasil dihapus</div>');
			redirect('panel/inventori/lokasiPenyimpanan');
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Data lokasi penyimpanan gagal dihapus toko tidak sesuai</div>');
			redirect('panel/inventori/lokasiPenyimpanan');
		}
	}

}
