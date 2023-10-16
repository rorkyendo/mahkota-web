<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

		public function __construct()
	{
		parent::__construct();
	}

		public function item($param1='',$param2='',$param3='')
	{
		$data['title'] = 'Detail Produk';
		$data['content'] = 'frontend/produk/detail';
		$data['produk'] = $this->GeneralModel->get_by_multi_id_general('v_produk','slug_toko',$param1,'id_produk',$param3);
		$data['randProduk'] = $this->GeneralModel->get_by_id_general_rand_by_limit('v_produk','slug_toko',$param1,'id_produk',6);
		$dataProduk = array(
			'dilihat' => $data['produk'][0]->dilihat+1
		);
		$this->GeneralModel->update_general('ms_produk','id_produk',$data['produk'][0]->id_produk,$dataProduk);
		$this->load->view('frontend/content', $data);
	}

	public function detailProduk($idProduk){
		$data['title'] = 'Detail Produk';
		$data['produk'] = $this->GeneralModel->get_by_id_general('v_produk','id_produk',$idProduk);
		$this->load->view('frontend2/modalPreview', $data);
	}

	public function kategori($param1='',$param2='')
	{
		if ($param1=='getData') {
			$nama_kategori = $this->input->get('kategori');
			$from = $this->input->get('from');
			$limit = $this->input->get('limit');
			$sort = $this->input->get('sort');
			if(empty($sort)){
				$sort = 'ASC';
			}
			if ($param2=='populer') {
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk='$nama_kategori' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY dilihat $sort LIMIT $limit OFFSET $from")->result();
			}elseif($param2=='harga'){
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk='$nama_kategori' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY harga_jual_online $sort LIMIT $limit OFFSET $from")->result();
			}else{
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk='$nama_kategori' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk $sort LIMIT $limit OFFSET $from")->result();
			}
			$data['from'] = $from+3;
			$this->load->view('frontend/produk/dataScroll', $data);
		}else{
			$data['sort'] = $this->input->get("sort");
			$sort = $data['sort'];
			if(empty($sort)){
				$sort = 'ASC';
			}
			if ($param1=='populer') {
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk='$kategori' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY dilihat $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = $param1;
			}elseif($param1=='harga'){
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk='$kategori' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY harga_jual_online $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = $param1;
			}else{
				$kategori  = ucwords(str_replace('-',' ',$param1));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk='$kategori' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = '';
			}
			$data['title'] = 'Kategori Produk';
			$data['content'] = 'frontend/produk/kategori';
			$data['kategoriProduk'] = $this->GeneralModel->get_general('v_kategori_produk');
			$data['jmlSearch'] = $this->GeneralModel->count_by_id_general('v_produk','nama_kategori_produk',$kategori);
			$data['search'] = $kategori;
			$this->load->view('frontend/content', $data);
		}
	}

	public function pencarian($param1='',$param2='')
	{
		if ($param1=='getData') {
			$nama_produk = $this->input->get('nama_produk');
			$nama_kategori = $this->input->get('kategori');
			$from = $this->input->get('from');
			$limit = $this->input->get('limit');
			$sort = $this->input->get('sort');
			if(empty($sort)){
				$sort = 'ASC';
			}

			if(!empty($nama_kategori)){
				$query = "AND nama_kategori_produk='$nama_kategori'";
			}else{
				$query = '';
			}

			if ($param2=='populer') {
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' $query AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY dilihat $sort LIMIT $limit OFFSET $from")->result();
			}elseif($param2=='harga'){
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' $query AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY harga_jual_online $sort LIMIT $limit OFFSET $from")->result();
			}else{
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' $query AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk $sort LIMIT $limit OFFSET $from")->result();
			}
			$data['from'] = $from+3;
			$this->load->view('frontend/produk/dataScroll', $data);
		}else{
			$data['sort'] = $this->input->get("sort");
			$sort = $data['sort'];
			if(empty($sort)){
				$sort = 'ASC';
			}
			$nama_produk  = $this->input->get('nama_produk');
			$nama_kategori = $this->input->get('kategori');
			
			if(!empty($nama_kategori)){
				$query = "AND nama_kategori_produk='$nama_kategori'";
			}else{
				$query = '';
			}

			if ($param1=='populer') {
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' $query AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY dilihat $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = $param1;
			}elseif($param1=='harga'){
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' $query AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY harga_jual_online $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = $param1;
			}else{
				$kategori  = ucwords(str_replace('-',' ',$param1));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' $query AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = '';
			}
			$data['active'] = $param1;
			$data['title'] = 'Pencarian Produk';
			$data['content'] = 'frontend/produk/cari';
			$data['kategoriProduk'] = $this->GeneralModel->get_general('v_kategori_produk');
			$data['jmlSearch'] = $this->db->query("SELECT COUNT(*) as jumlah FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' $query AND tampil_toko='Y' AND foto_produk IS NOT NULL")->row();
			$data['search'] = $nama_produk;
			$data['search2'] = $nama_kategori;
			$this->load->view('frontend/content', $data);
		}
	}

	public function brand($param1='',$param2='')
	{
		if ($param1=='getData') {
			$nama_brand = $this->input->get('brand');
			$from = $this->input->get('from');
			$limit = $this->input->get('limit');
			$sort = $this->input->get('sort');
			if(empty($sort)){
				$sort = 'ASC';
			}
			if ($param2=='populer') {
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_brand='$nama_brand' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY dilihat $sort LIMIT $limit OFFSET $from")->result();
			}elseif($param2=='harga'){
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_brand='$nama_brand' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY harga_jual_online $sort LIMIT $limit OFFSET $from")->result();
			}else{
				$kategori  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_brand='$nama_brand' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk $sort LIMIT $limit OFFSET $from")->result();
			}
			$data['from'] = $from+3;
			$this->load->view('frontend/produk/dataScroll', $data);
		}else{
			$data['sort'] = $this->input->get("sort");
			$sort = $data['sort'];
			if(empty($sort)){
				$sort = 'ASC';
			}
			if ($param1=='populer') {
				$nama_brand  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_brand='$nama_brand' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY dilihat $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = $param1;
			}elseif($param1=='harga'){
				$nama_brand  = ucwords(str_replace('-',' ',$param2));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_brand='$nama_brand' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY harga_jual_online $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = $param1;
			}else{
				$nama_brand  = ucwords(str_replace('-',' ',$param1));
				$data['produk'] = $this->db->query("SELECT * FROM v_produk WHERE nama_brand='$nama_brand' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk $sort LIMIT 3 OFFSET 0")->result();
				$data['active'] = '';
			}
			$data['active'] = $param1;
			$data['title'] = 'Brand Produk';
			$data['content'] = 'frontend/produk/brand';
			$data['kategoriProduk'] = $this->GeneralModel->get_general('v_kategori_produk');
			$data['jmlSearch'] = $this->GeneralModel->count_by_id_general('v_produk','nama_brand',$nama_brand);
			$data['search'] = $nama_brand;
			$this->load->view('frontend/content', $data);
		}
	}

	public function addToChart($idProduk){
		if (!empty($idProduk)) {
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip_address = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip_address = $_SERVER['REMOTE_ADDR'];
			}
			$session_id = session_id();

			$getProdukData = $this->GeneralModel->get_by_id_general('v_produk','id_produk',$idProduk);
			$qty = $this->input->post("qty");

			if($getProdukData[0]->harga_diskon > 0){
				$harga_jual = $getProdukData[0]->harga_diskon;
			}else{
				$harga_jual = $getProdukData[0]->harga_jual_online;
			}

			$tempTransaksi = tempTransaksi();

			if (empty($tempTransaksi)) {
				$dataTransaksi = array(
					'pelanggan' => $this->session->userdata('id_pengguna'),
					'jenis_transaksi' => 'produk',
					'tipe_transaksi' => 'umum',
					'ip_address' => $ip_address,
					'session_id' => $session_id,
					'created_by' => $this->session->userdata('id_pengguna')
				);

				$this->GeneralModel->create_general('ms_temp_transaksi',$dataTransaksi);
				$id_transaksi = $this->db->insert_id();
			}else{
				$id_transaksi = $tempTransaksi->id_temp_transaksi;
			}

			$cekOrderTemp = $this->GeneralModel->get_by_multi_id_general('ms_order','temp_transaksi',$id_transaksi,'produk',$idProduk);
			if(!empty($cekOrderTemp)){
				$newQty = $cekOrderTemp[0]->qty + $qty;
				$dataOrder = array(
					'qty' => $newQty,
					'promo_potongan' => ($getProdukData[0]->harga_jual_online - $harga_jual) * $newQty,
					'subtotal' => $newQty * $harga_jual
				);
				$this->GeneralModel->update_general('ms_order','id_order',$cekOrderTemp[0]->id_order,$dataOrder);
			}else{
				$dataOrder = array(
					'qty' => $qty,
					'produk' => $idProduk,	
					'selling_price' => $harga_jual,
					'capital_price' => $getProdukData[0]->harga_modal,
					'promo_potongan' => ($getProdukData[0]->harga_jual_online - $getProdukData[0]->harga_diskon) * $qty,
					'subtotal' => $qty * $harga_jual,
					'uuid_toko' => $getProdukData[0]->uuid_toko,
					'temp_transaksi' => $id_transaksi
				);

				$this->GeneralModel->create_general('ms_order',$dataOrder);
			}
			$this->session->set_flashdata('notif','<div class="alert alert-success">Produk berhasil ditambahkan kedalam keranjang</div>');
			redirect('produk/item/'.$getProdukData[0]->slug_toko.'/'.$getProdukData[0]->slug_produk.'/'.$idProduk);
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Produk tidak ditemukan, Produk tidak berhasil ditambahkan kedalam keranjang</div>');
			redirect('/');
		}
	}

	public function removeFromCart(){
		$temp_transaksi = $this->input->post('temp_transaksi');
		$id_order = $this->input->post('id_order');
		if(cekTempTransaksiPengguna($temp_transaksi) == TRUE){
			if($this->GeneralModel->delete_multi_id_general('ms_order', 'temp_transaksi', $temp_transaksi, 'id_order', $id_order) == TRUE){
				echo "true";
			}else{
				echo "false";
			}
		}else{
			echo "false";
		}
	}

	public function redeemPoint($param1='',$param2='')
	{
		if ($param1=='getData') {
			$from = $this->input->get('from');
			$limit = $this->input->get('limit');
			$data['produkRedeem'] = $this->GeneralModel->paginate_order_by_general('ms_produk_redeem','id_produk_redeem','DESC',$limit, $from);
			$this->load->view('frontend/produk/dataProdukScroll', $data);
		}else if($param1=='detail'){
			$data['title'] = 'Detail Produk';
			$data['content'] = 'frontend/produk/detailRedeem';
			$data['produkRedeem'] = $this->GeneralModel->get_by_id_general('ms_produk_redeem','id_produk_redeem', $param2);
			$this->load->view('frontend/content', $data);			
		}else{
			$from = $this->input->get('from');
			$limit = $this->input->get('limit');
			$data['title'] = 'Produk Point';
			$data['content'] = 'frontend/produk/redeem';
			$data['produkRedeem'] = $this->GeneralModel->paginate_order_by_general('ms_produk_redeem','id_produk_redeem','DESC',$limit, $from);
			$data['pengguna'] = $this->GeneralModel->get_by_id_general('v_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$this->load->view('frontend/content', $data);
		}
	}

	public function pulsa(){
		$data['title'] = 'Produk Pulsa';
		$data['content'] = 'frontend/produk/pulsa';
		$data['pengguna'] = $this->GeneralModel->get_by_id_general('v_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
		$this->load->view('frontend/content', $data);
	}

	public function tokenListrik(){
		
	}

	public function voucherGame(){
		
	}
}