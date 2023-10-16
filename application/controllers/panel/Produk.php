<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{

	public $parent_modul = 'Produk';
	public $title = 'Produk';

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

	public function daftarProduk($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$toko = $this->input->post('uuid_toko');
			$kategori_produk = $this->input->post('kategori_produk');
			$status = $this->input->post('status');
			return $this->ProdukModel->getProduk($toko,$kategori_produk,$status);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Produk';
			$data['content'] = 'panel/produk/index';
			$data['kategori_produk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['id_kategori_produk'] = $this->input->get('id_kategori_produk');
			$data['status'] = $this->input->get('status');
			$this->load->view('panel/content', $data);
		}
	}

	public function tambahProduk($param1 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,'');
		if($param1=='doCreate'){
			$data = array(
				'nama_produk' => $this->input->post('nama_produk'),
				'barcode' => $this->input->post('barcode'),
				'berat_produk' => $this->input->post('berat_produk'),
				'detail_produk' => $this->input->post('detail_produk'),
				'slug_produk' => slugify($this->input->post('nama_produk')),
				'harga_jual' => $this->input->post('harga_jual'),
				'harga_jual_grosir' => $this->input->post('harga_jual_grosir'),
				'ppn' => $this->input->post('ppn'),
				'komisi' => $this->input->post('komisi'),
				'harga_diskon' => $this->input->post('harga_diskon'),
				'harga_modal' => $this->input->post('harga_modal'),
				'harga_jual_online' => $this->input->post('harga_jual_online'),
				'status_cop' => $this->input->post('status_cop'),
				'cop' => $this->input->post('cop'),
				'inventori' => $this->input->post('inventori'),
				'kategori_produk' => $this->input->post('kategori_produk'),
				'brand' => $this->input->post('brand'),
				'tampil_toko' => $this->input->post('tampil_toko'),
				'potongan_member_gold' => $this->input->post('potongan_member_gold'),
				'potongan_member_blue' => $this->input->post('potongan_member_blue'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'slug_produk' => url_title($this->input->post('slug_produk'), 'dash', true).strtotime(DATE('Y-m-d H:i:s')),
				'wajib_asuransi' => $this->input->post('wajib_asuransi'),
				'jenis_point' => $this->input->post('jenis_point'),
				'point_produk' => $this->input->post('point_produk'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if (!empty($this->input->post('barcode'))) {
				$this->zend->load('Zend/Barcode.php'); 
				$barcode = $this->input->post('barcode');
				$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$barcode), array())->draw($barcode,'image', array('text'=>$barcode), array());
				$imageName = $barcode.'.jpg';
				$imagePath = 'assets/barcodeProduk/';
				imagejpeg($imageResource, $imagePath.$imageName); 
				$pathBarcode = $imagePath.$imageName; 				
				$data += array(
					'barcode_image' => $pathBarcode
				);
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk'))
			{

			}
			else {
				$data += array('foto_produk' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk_2'))
			{

			}
			else {
				$data += array('foto_produk_2' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk_3'))
			{

			}
			else {
				$data += array('foto_produk_3' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk_4'))
			{

			}
			else {
				$data += array('foto_produk_4' => $config['upload_path'].$this->upload->data('file_name'));
			}


			if ($this->GeneralModel->create_general('ms_produk',$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data produk berhasil ditambahkan</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data produk gagal ditambahkan</div>');
			}

			redirect('panel/produk/daftarProduk');
		}elseif($param1=='doImport'){
			$config['upload_path']          = 'assets/excel/';
			$config['allowed_types'] = 'xlsx';
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('dataProduk')) {
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data produk gagal diupload ' . $this->upload->display_errors() . '</div>');
				redirect('panel/produk/daftarProduk/');
			} else {
				$filename = $this->upload->data('file_name');
				$data = $config['upload_path'] . $filename;
				$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($data);
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
				$spreadsheet = $reader->load($data);
				$sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
				$numrow = 1;
				$dataProduk = array();
				foreach ($sheet as $row) {
					if ($numrow > 1) {
						if (!empty($row['B'])) {
							array_push($dataProduk,array(
								'kode_produk' => $row['A'],
								'nama_produk' => $row['B'],
								'berat_produk' => $row['C'],
								'harga_jual_grosir' => $row['D'],
								'harga_jual' => $row['E'],
								'harga_jual_online' => $row['F'],
								'harga_modal' => $row['G'],
								'ppn' => $row['H'],
								'komisi' => $row['I'],
								'harga_diskon' => $row['J'],
								'uuid_toko' => $this->session->userdata('uuid_toko')
							));
						}
					}
					$numrow++;
				}
				try {
					unlink($data);
				} catch (\Exception $e) {
				}
				$this->db->insert_batch('ms_produk',$dataProduk);
				$this->session->set_flashdata('notif', '<div class="alert alert-success">Data produk berhasil diupload</div>');
				redirect('panel/produk/daftarProduk');
			}
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Tambah Produk';
			$data['content'] = 'panel/produk/create';
			$data['member'] = $this->GeneralModel->get_by_id_general('ms_tipe_member','status_tipe','active');
			$data['kategoriProduk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['inventori'] = $this->GeneralModel->get_by_id_general('ms_inventori','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['cop'] = $this->GeneralModel->get_by_id_general('ms_cop','uuid_toko',$this->session->userdata('uuid_toko'));
			$this->load->view('panel/content', $data);
		}
	}

	public function getHarga(){
		$id_inventori = $this->input->get('id_inventori');
		$data = $this->GeneralModel->get_by_multi_id_general('ms_inventori','id_inventori',$id_inventori,'uuid_toko',$this->session->userdata('uuid_toko'));
		if($data){
			echo json_encode($data,JSON_PRETTY_PRINT);
		}else{
			echo 'false';
		}
	}

	public function updateProduk($param1 = '', $param2 = '')
	{
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='doUpdate'){
			$getProduk = $this->GeneralModel->get_by_multi_id_general('ms_produk','uuid_toko',$this->session->userdata('uuid_toko'),'id_produk',$param2);

			$data = array(
				'nama_produk' => $this->input->post('nama_produk'),
				'barcode' => $this->input->post('barcode'),
				'berat_produk' => $this->input->post('berat_produk'),
				'detail_produk' => $this->input->post('detail_produk'),
				'slug_produk' => slugify($this->input->post('nama_produk')),
				'harga_jual' => $this->input->post('harga_jual'),
				'harga_jual_grosir' => $this->input->post('harga_jual_grosir'),
				'ppn' => $this->input->post('ppn'),
				'komisi' => $this->input->post('komisi'),
				'harga_diskon' => $this->input->post('harga_diskon'),
				'harga_jual_online' => $this->input->post('harga_jual_online'),
				'harga_modal' => $this->input->post('harga_modal'),
				'status' => $this->input->post('status'),
				'status_cop' => $this->input->post('status_cop'),
				'cop' => $this->input->post('cop'),
				'inventori' => $this->input->post('inventori'),
				'brand' => $this->input->post('brand'),
				'potongan_member_gold' => $this->input->post('potongan_member_gold'),
				'potongan_member_blue' => $this->input->post('potongan_member_blue'),
				'kategori_produk' => $this->input->post('kategori_produk'),
				'wajib_asuransi' => $this->input->post('wajib_asuransi'),
				'tampil_toko' => $this->input->post('tampil_toko'),
				'jenis_point' => $this->input->post('jenis_point'),
				'point_produk' => $this->input->post('point_produk'),
				'created_by' => $this->session->userdata('id_pengguna'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if (!empty($this->input->post('barcode'))) {
				if (!empty($getProduk[0]->barcode_image)) {
					try {
						unlink($getProduk[0]->barcode_image);
					} catch (\Throwable $th) {
					}
				}

				$this->zend->load('Zend/Barcode.php'); 
				$barcode = $this->input->post('barcode');
				$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$barcode), array())->draw($barcode,'image', array('text'=>$barcode), array());
				$imageName = $barcode.'.jpg';
				$imagePath = 'assets/barcodeProduk/';
				imagejpeg($imageResource, $imagePath.$imageName); 
				$pathBarcode = $imagePath.$imageName; 				
				$data += array(
					'barcode_image' => $pathBarcode
				);
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk'))
			{

			}
			else {
				if (!empty($getProduk[0]->foto_produk)) {
					try {
						unlink($getProduk[0]->foto_produk);
					} catch (\Throwable $th) {
					}
				}

				$data += array('foto_produk' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk_2'))
			{

			}
			else {
				if (!empty($getProduk[0]->foto_produk_2)) {
					try {
						unlink($getProduk[0]->foto_produk_2);
					} catch (\Throwable $th) {
					}
				}

				$data += array('foto_produk_2' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk_3'))
			{

			}
			else {
				if (!empty($getProduk[0]->foto_produk_3)) {
					try {
						unlink($getProduk[0]->foto_produk_3);
					} catch (\Throwable $th) {
					}
				}

				$data += array('foto_produk_3' => $config['upload_path'].$this->upload->data('file_name'));
			}

			$config['upload_path']          = 'assets/img/produk/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('foto_produk_4'))
			{

			}
			else {
				if (!empty($getProduk[0]->foto_produk_4)) {
					try {
						unlink($getProduk[0]->foto_produk_4);
					} catch (\Throwable $th) {
					}
				}

				$data += array('foto_produk_4' => $config['upload_path'].$this->upload->data('file_name'));
			}


			if ($this->GeneralModel->update_general('ms_produk','id_produk',$param2,$data) == TRUE) {
				$this->session->set_flashdata('notif','<div class="alert alert-success">Data produk berhasil diupdate</div>');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Data produk gagal diupdate</div>');
			}

			redirect('panel/produk/daftarProduk');
		}else{
			activityLog($this->parent_modul,$this->akses_controller,$param1);
			$data['title'] = $this->title;
			$data['subtitle'] = 'Update Produk';
			$data['content'] = 'panel/produk/update';
			$data['kategoriProduk'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['member'] = $this->GeneralModel->get_by_id_general('ms_tipe_member','status_tipe','active');
			$data['inventori'] = $this->GeneralModel->get_by_id_general('ms_inventori','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['cop'] = $this->GeneralModel->get_by_id_general('ms_cop','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['produk'] = $this->GeneralModel->get_by_multi_id_general('ms_produk','uuid_toko',$this->session->userdata('uuid_toko'),'id_produk',$param1);
			$this->load->view('panel/content', $data);
		}
	}

	public function hapusProduk($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		activityLog($this->parent_modul,$this->akses_controller,$param1);
		$getProduk = $this->GeneralModel->get_by_multi_id_general('ms_produk','id_produk',$param1,'uuid_toko',$this->session->userdata('uuid_toko'));
		if($getProduk){
			try {
				if (!empty($getProduk[0]->barcode_image)) {
					unlink($getProduk[0]->barcode_image);
				}
				if (!empty($getProduk[0]->foto_produk)) {
					unlink($getProduk[0]->foto_produk);
				}
				if (!empty($getProduk[0]->foto_produk_1)) {
					unlink($getProduk[0]->foto_produk_1);
				}
				if (!empty($getProduk[0]->foto_produk_2)) {
					unlink($getProduk[0]->foto_produk_2);
				}
				if (!empty($getProduk[0]->foto_produk_3)) {
					unlink($getProduk[0]->foto_produk_3);
				}
				if (!empty($getProduk[0]->foto_produk_4)) {
					unlink($getProduk[0]->foto_produk_4);
				}
			} catch (\Throwable $th) {
			}
			$this->GeneralModel->delete_general('ms_produk','id_produk',$param1);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Data produk berhasil dihapus</div>');
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Data produk tidak ditemukan</div>');
		}
		redirect('panel/produk/daftarProduk');
	}

	public function getBrand(){
		$kategori = $this->input->get('kategori');
		$getBrand = $this->GeneralModel->get_by_id_general('ms_brand','kategori',$kategori);
		if (!empty($getBrand)) {
			echo json_encode($getBrand,JSON_PRETTY_PRINT);
		}else{
			echo 'false';
		}
	}

	public function produkDigital($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$status = $this->input->post('status');
			$brand = $this->input->post('brand');
			$kategori = $this->input->post('kategori');
			return $this->ProdukModel->getProdukDigital($brand,$status="Y",$kategori);
		}else{
			activityLog($this->parent_modul,$this->akses_controller,'');
			$data['title'] = $this->title;
			$data['subtitle'] = 'Produk Digital';
			$data['content'] = 'panel/produk/produkDigital';
			$this->load->view('panel/content', $data);
		}
	}

	public function sinkronProdukDigital(){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		$data = checkPriceList();
		$data = json_decode($data);
		for ($i=0; $i < count($data->data); $i++) { 
			$code = $data->data[$i]->buyer_sku_code;
			$cekProduk = $this->db->query("SELECT * FROM ms_produk_digital WHERE kode='$code'")->row();
			$produk = array(
				'nama_produk_digital' => $data->data[$i]->product_name,
				'brand' => $data->data[$i]->brand,
				'kategori' => $data->data[$i]->category,
				'kode' => $data->data[$i]->buyer_sku_code,
				'harga_modal' => $data->data[$i]->price,
				'harga_jual' => $data->data[$i]->price,
			);
			if($cekProduk){
				unset($produk['harga_jual']);
				$this->GeneralModel->update_general("ms_produk_digital","id_produk_digital",$cekProduk->id_produk_digital,$produk);
			}else{
				$produk += array(
					'status' => 'Y'
				);
				$this->GeneralModel->create_general("ms_produk_digital",$produk);
			}
		}
		$this->session->set_flashdata('notif','<div class="alert alert-success">Produk Digital Berhasil di sinkron</div>');
		redirect('panel/produk/produkDigital');
	}

	public function updateProdukDigital($param1){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1){
			$data = array(
				'harga_jual' => $this->input->post('harga_jual')
			);
			$this->GeneralModel->update_general("ms_produk_digital","id_produk_digital",$param1,$data);
		}
	}

	public function hapusProdukDigital($param1){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1){
			$data = array(
				'status' => 'N'
			);
			$this->GeneralModel->update_general("ms_produk_digital","id_produk_digital",$param1,$data);
		}
		$this->session->set_flashdata('notif','<div class="alert alert-success">Produk Digital Berhasil di nonaktifkan</div>');
		redirect('panel/produk/produkDigital');
	}

}
