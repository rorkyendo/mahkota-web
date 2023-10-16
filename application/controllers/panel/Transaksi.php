<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpMqtt\Client\Exceptions\MQTTClientException;
use PhpMqtt\Client\MQTTClient;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class Transaksi extends CI_Controller {

	/**
		*created by https://medandigitalinnovation.com
		*Estimate 2019
	 */

	public $parent_modul = "Transaksi";
	public $title = 'Transaksi';

	public function __construct(){
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

	public function index(){
		$this->transaksiBaru();
	}

	public function daftarTransaksi($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$sales = $this->input->post('sales');
			$payment_status = $this->input->post('payment_status');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->TransaksiModel->getDaftarTransaksi($start_date,$end_date,$sales,$payment_status,$uuid_toko);
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Daftar Transaksi';
			$data['content'] = 'panel/transaksi/daftarTransaksi';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			if(empty($this->input->get('payment_status'))){
				$data['payment_status'] = 'process';
			}else{
				$data['payment_status'] = $this->input->get('payment_status');
			}
			$data['sales'] = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','hak_akses','sales','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['id_sales'] = $this->input->get('sales');
			$this->load->view('panel/content',$data);
		}
	}

	public function detailTransaksi($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		$data['title'] = $this->title;
		$data['subtitle'] = 'Detail Transaksi';
		$data['content'] = 'panel/transaksi/detailTransaksi';
		$data['transaksi'] = $this->GeneralModel->get_by_multi_id_general('v_transaksi','id_transaksi',$param1,'uuid_outlet',$this->session->userdata('uuid_outlet'));
		$data['order'] = $this->GeneralModel->get_by_id_general('v_order','transaksi',$param1);
		$data['jenis_pembayaran'] = $this->GeneralModel->get_general('ms_jenis_pembayaran');
		$data['sales'] = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','hak_akses','sales','uuid_toko',$this->session->userdata('uuid_toko'));
		$this->load->view('panel/content',$data);
	}

	public function updateResi(){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		$id_transaksi = $this->input->post('id_transaksi');
		$getTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','id_transaksi',$id_transaksi,'uuid_toko',$this->session->userdata('uuid_toko'));
		$no_resi = $this->input->post('no_resi');
		$data = array(
			'no_resi' => $no_resi,
		);

		foreach($getTransaksi as $key){
			$message = 'Halo '.$key->nama_lengkap_pelanggan.', no resi pesanan kamu sudah di update.';
			$message .= ' No resi kamu adalah *'.$no_resi.'*, simpan baik-baik no resi kamu. Terima kasih..';
			sendNotifWA($key->no_telp_pelanggan,$message);

			$cekDeviceId = $this->GeneralModel->get_by_id_general('ms_device_notif','id_pengguna',$key->created_by);

			$messages = [
				new ExpoMessage([
					'title' => 'Resi Transaksi #'.$id_transaksi,
					'body' => 'Yeay! Pesanan kamu sedang dikirim dengan kode resi '.$no_resi,
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

		if($this->GeneralModel->update_multi_id_general('ms_transaksi','id_transaksi',$id_transaksi,'uuid_toko',$this->session->userdata('uuid_toko'),$data) == TRUE){
			echo "true";
		}else{
			echo "false";
		}
	}

	public function konfirmasiPembayaran($param1='',$param2=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		$id_transaksi = $param2;
		$getTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','id_transaksi',$id_transaksi,'uuid_toko',$this->session->userdata('uuid_toko'));
		if($getTransaksi){
			if($param1=='cancel'){
				$dataTransaksi = array(
					'no_resi' => null,
					'payment_status' => 'cancel',
					'cancel_by' => $this->session->userdata('id_pengguna')
				);

				foreach($getTransaksi as $key){
					$message = 'Halo '.$key->nama_lengkap_pelanggan.', pembayaran kamu untuk *id transaksi '.$id_transaksi.'* telah ditolak.';
					$message .= ' Silahkan periksa kembali data transaksi yang kamu kirimkan. Terima kasih..';
					sendNotifWA($key->no_telp_pelanggan,$message);
				}

				$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$id_transaksi,$dataTransaksi);
				redirect('panel/transaksi/detailTransaksi/'.$id_transaksi);
			}elseif($param1=='payed' && $getTransaksi[0]->payment_status != 'payed'){
				$toko = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',$this->session->userdata('uuid_toko'));

				$dataTransaksi = array(
					'payment_status' => 'payed',
					'pay_by' => $this->session->userdata('id_pengguna'),
					'total_pembayaran' => $getTransaksi[0]->total,
					'status_pengiriman' => 'dikirim'
				);

				$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$id_transaksi,$dataTransaksi);
				foreach($getTransaksi as $key){
					$message = 'Halo '.$key->nama_lengkap_pelanggan.', pembayaran kamu untuk *id transaksi '.$id_transaksi.'* telah ditolak.';
					$message .= ' Silahkan periksa kembali data transaksi yang kamu kirimkan. Terima kasih..';
					sendNotifWA($key->no_telp_pelanggan,$message);
				}

				foreach($toko as $key){
					$dataToko = array(
						'saldo_toko' => $key->saldo_toko + $getTransaksi[0]->total
					);
					$this->GeneralModel->update_general('ms_toko','uuid_toko',$key->uuid_toko,$dataToko);
				}
				redirect('panel/transaksi/detailTransaksi/'.$id_transaksi);
			}
		}else{
			redirect('panel/transaksi/daftarTransaksi');
		}
	}

	public function transaksiBaru($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1=='doCreate') {
			$data = array(
				'jenis_transaksi' => $this->input->post('jenis_transaksi'),
				'sales' => $this->input->post('sales'),
				'bill_name' => 'UMUM',
				'tipe_transaksi' => 'umum',
				'created_by' => $this->session->userdata('id_pengguna')
			);

			if($this->GeneralModel->create_general('ms_temp_transaksi',$data) == TRUE){
				redirect('panel/transaksi/pesan');
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Transaksi gagal dibuat</div>');
				redirect('panel/transaksi/transaksiBaru');
			}
		}else {
			$cekLastTransaksi = $this->GeneralModel->get_by_id_general('ms_temp_transaksi','created_by',$this->session->userdata('id_pengguna'));
			if ($cekLastTransaksi) {
				redirect('panel/transaksi/pesan');
			}else{
				$data['title'] = $this->title;
				$data['subtitle'] = 'Transaksi Baru';
				$data['content'] = 'panel/transaksi/transaksiBaru';
				$data['sales'] = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','hak_akses','sales','uuid_toko',$this->session->userdata('uuid_toko'));
				$this->load->view('panel/content',$data);
			}
		}
	}

	public function pesan(){
		$cekLastTransaksi = $this->GeneralModel->get_by_id_general('v_temp_transaksi','created_by',$this->session->userdata('id_pengguna'));
		if (empty($cekLastTransaksi)) {
			redirect('panel/transaksi/transaksiBaru');
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Pesanan';
			$data['content'] = 'panel/transaksi/pesanan';
			$data['tempTransaksi'] = $cekLastTransaksi;
			$data['kategori'] = $this->GeneralModel->get_general('ms_kategori_produk');
			$data['id_kategori'] = $this->input->get('kategori');
			$data['id_brand'] = $this->input->get('brand');
			$data['keywords'] = $this->input->get('keyword');
			$data['jenis_pembayaran'] = $this->GeneralModel->get_general('ms_jenis_pembayaran');
			$data['brand'] = $this->GeneralModel->get_general('ms_brand');
			$data['pesanan'] = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$cekLastTransaksi[0]->id_temp_transaksi);
			$data['sales'] = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','hak_akses','sales','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['produk'] = $this->ProdukModel->getProdukTransaksi($data['keywords'], $data['id_brand'], $data['id_kategori'], $this->session->userdata('uuid_toko'), 6, 0);
			$this->load->view('panel/content',$data);
		}
	}

	public function tambahPesanan(){
			$id_produk = $this->input->post('id_produk');
			$barcodeProduk = $this->input->post('barcodeProduk');
			$id_temp_transaksi = $this->input->post('id_temp_transaksi');
			$qty = $this->input->post('qty');
			if(!empty($barcodeProduk)){
				$getProduk = $this->GeneralModel->get_by_id_general('ms_produk','barcode',$barcodeProduk);
			}elseif(!empty($id_produk)){
				$getProduk = $this->GeneralModel->get_by_id_general('ms_produk','id_produk',$id_produk);
			}
			foreach($getProduk as $key){
				//---- CEK DISKON ----//
				if($key->harga_diskon > 0){
					$harga_jual = $key->harga_jual - $key->harga_diskon;
				}else{
					$harga_jual = $key->harga_jual;
				}

				$cekOrderTemp = $this->GeneralModel->get_by_multi_id_general('ms_order','temp_transaksi',$id_temp_transaksi,'produk',$getProduk[0]->id_produk);
				if(!empty($cekOrderTemp)){
					//---- ORDER SEBELUMNYA ----//
					$dataOrder = array(
						'qty' => $cekOrderTemp[0]->qty + $this->input->post('qty')
					);				
					$this->GeneralModel->update_general('ms_order','id_order',$cekOrderTemp[0]->id_order,$dataOrder);
				}else{
					//---- ORDER BARU ----//
					$dataOrder = array(
						'qty' => $qty,
						'produk' => $key->id_produk,
						'selling_price' => $harga_jual,
						'capital_price' => $key->harga_modal,
						'promo_potongan' => $key->harga_diskon*$qty,
						'subtotal' => $qty * $harga_jual,
						'uuid_toko' => $key->uuid_toko,
						'temp_transaksi' => $id_temp_transaksi
					);

					$this->GeneralModel->create_general('ms_order',$dataOrder);
				}
			}
			echo 'true';
	}

	public function getPesanan(){
		$id_temp_transaksi = $this->input->post('id_temp_transaksi');
		$getPesanan = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$id_temp_transaksi);
		echo json_encode($getPesanan,JSON_PRETTY_PRINT);
	}

	public function editOrder(){
		$id_order = $this->input->post('id_order');
		$qty = $this->input->post('qty');
		$cekOrder = $this->GeneralModel->get_by_id_general('v_order','id_order',$id_order);
		if($cekOrder){
			if($cekOrder[0]->payment_status != 'payed'){
				if($qty <= 0){
					$this->GeneralModel->delete_general('ms_order','id_order',$id_order);
				}else{
					$data = array(
						'qty' => $qty,
						'subtotal' => $cekOrder[0]->selling_price*$qty
					);
					$this->GeneralModel->update_general('ms_order','id_order',$id_order,$data);
				}
			}
			echo 'true';
		}else{
			echo 'false';
		}
	}

	public function batalPesan($param1){
		$cekLastTransaksi = $this->GeneralModel->get_by_multi_id_general('v_temp_transaksi','id_temp_transaksi',my_simple_crypt($param1,'d'),'created_by',$this->session->userdata('id_pengguna'));
		if($cekLastTransaksi){
			$this->GeneralModel->delete_general('ms_temp_transaksi','id_temp_transaksi',my_simple_crypt($param1,'d'));
			$this->session->set_flashdata('notif','<div class="alert alert-success">Transaksi berhasil dibatalkan</div>');
			redirect('panel/transaksi/transaksiBaru');
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Transaksi gagal dibatalkan, transaksi tersebut bukan kamu yang membuat!</div>');
			redirect('panel/transaksi/transaksiBaru');
		}
	}

	public function updateTransaksi($param1='',$param2=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if ($param1=='batal') {
			$dataTransaksi = array(
				'payment_status' => 'cancel'
			);
			$this->GeneralModel->update_general('ms_transaksi','id_transaksi',my_simple_crypt($param2,'d'),$dataTransaksi);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Transaksi berhasil dibatalakan</div>');
			redirect('panel/transaksi/detailTransaksi/'.my_simple_crypt($param2,'d'));
;		}elseif($param1=='bayar'){
			$total = $this->input->post('total');
			$total_pembayaran = $this->input->post('total_pembayaran');
			$kembalian = $this->input->post('kembalian');
			$payment_by = $this->input->post('payment_by');

			$dataTransaksi = array(
				'total' => $total,
				'payment_status' => 'payed',
				'total_pembayaran' => $total_pembayaran,
				'kembalian' => $kembalian,
				'payment_by' => $payment_by,
				'pay_time' => DATE('Y-m-d H:i:s')
			);
			$this->GeneralModel->update_general('ms_transaksi','id_transaksi',my_simple_crypt($param2,'d'),$dataTransaksi);

			$cek = $this->GeneralModel->get_by_multi_id_general('ms_riwayat_point','transaksi',my_simple_crypt($param2,'d'),'status','pending');
			if($cek){
				$data = array(
					'status' => 'diterima'
				);
				$this->GeneralModel->update_general('ms_riwayat_point','transaksi',my_simple_crypt($param2,'d'),$data);
			}

			$this->session->set_flashdata('notif','<div class="alert alert-success">Transaksi berhasil dibayarkan</div>');
			redirect('panel/transaksi/detailTransaksi/'.my_simple_crypt($param2,'d'));
		}elseif($param1=='process'){
			$dataTransaksi = array(
				'payment_status' => 'payed',
				'pay_time' => DATE('Y-m-d H:i:s'),
				'pay_by' => $this->session->userdata('id_pengguna')
			);

			$getTransaksi = $this->GeneralModel->get_by_id_general('v_transaksi','id_transaksi',my_simple_crypt($param2,'d'));
			foreach($getTransaksi as $key){
				$message = 'Halo '.$key->nama_lengkap_pelanggan;
				$message .= urlencode("\n"); 
				$message .= 'Yeay Transaksi kamu sudah kami terima';
				$message .= urlencode("\n"); 
				$message .= 'ID Transaksi : '.my_simple_crypt($param2,'d');
				$message .= urlencode("\n"); 
				$message .= 'Terima kasih sudah berbelanja di Mahkota!';
				$message .= urlencode("\n"); 
				sendNotifWA($key->no_telp_pelanggan,$message);
				$cekDeviceId = $this->GeneralModel->get_by_id_general('ms_device_notif','id_pengguna',$key->created_by);
				$messages = [
					new ExpoMessage([
						'title' => 'Transaksi #'.$id_transaksi,
						'body' => 'Yeay! Pesanan kamu sudah dikonfirmasi',
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
			$this->GeneralModel->update_general('ms_transaksi','id_transaksi',my_simple_crypt($param2,'d'),$dataTransaksi);
			foreach($this->koneksi as $key){
					$this->koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);
					$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer2');
					$client->connect('mahkota', $key->mqtt_password);
				try{
					$client->publish('transaksi/payed/'.my_simple_crypt($param2,'d'), "payed", 0);
				} catch (MqttClientException $e) {
				}
			}

			$cek = $this->GeneralModel->get_by_multi_id_general('ms_riwayat_point','transaksi',my_simple_crypt($param2,'d'),'status','pending');
			if($cek){
				$data = array(
					'status' => 'diterima'
				);
				$this->GeneralModel->update_general('ms_riwayat_point','transaksi',my_simple_crypt($param2,'d'),$data);
			}

			$this->session->set_flashdata('notif','<div class="alert alert-success">Transaksi berhasil dibayarkan</div>');
			redirect('panel/transaksi/detailTransaksi/'.my_simple_crypt($param2,'d'));
		}
	}


	public function scanBarcode(){
		$id_temp_transaksi = $this->input->get('id_temp_transaksi');
		$barcode = $this->input->get('barcode');
		$dataMember = $this->GeneralModel->get_by_id_general('v_member','barcode_member',$barcode);

		$data = array(
			'pelanggan' => $dataMember[0]->id_pengguna,
			'bill_name' => $dataMember[0]->nama_lengkap
		);

		$this->GeneralModel->update_general('ms_temp_transaksi','id_temp_transaksi',$id_temp_transaksi,$data);

		if(!empty($dataMember)){
			echo json_encode($dataMember,JSON_PRETTY_PRINT);
		}else{
			echo 'false';
		}
	}

	public function getProduk(){
		$data = json_decode($this->input->post('data'));
		foreach($data as $key){
			$id_kategori = $key->kategori;
			$id_brand = $key->brand;
			$keyword = $key->keyword;
			$page = $key->from;
			$limit = $key->limit;
		}
		$from = $limit*$page;
		$result['produk'] = $this->ProdukModel->getProdukTransaksi($keyword, $id_brand, $id_kategori, $this->session->userdata('uuid_toko'), $limit, $from);
		if(!empty($result['produk'])){
			$this->load->view('panel/transaksi/getProduk',$result);
		}else{
			echo 'false';
		}
	}

	public function gantiBillName(){
		$bill_name = $this->input->post('bill_name');
		$id_temp_transaksi = $this->input->post('id_temp_transaksi');

		$data = array(
			'bill_name' => $bill_name
		);
		$this->GeneralModel->update_general('ms_temp_transaksi','id_temp_transaksi',$id_temp_transaksi,$data);
	}

	public function gantiSales(){
		$sales = $this->input->post('sales');
		$id_temp_transaksi = $this->input->post('id_temp_transaksi');

		$data = array(
			'sales' => $sales
		);
		$this->GeneralModel->update_general('ms_temp_transaksi','id_temp_transaksi',$id_temp_transaksi,$data);
	}

	public function bayar(){
		$id_temp_transaksi = $this->input->post('id_temp_transaksi');
		$total = $this->input->post('total');
		$total_pembayaran = $this->input->post('total_pembayaran');
		$kembalian = $this->input->post('kembalian');
		$payment_by = $this->input->post('payment_by');
		
		$getTempTransaksi = $this->GeneralModel->get_by_id_general('ms_temp_transaksi','id_temp_transaksi',$id_temp_transaksi);

		foreach($getTempTransaksi as $key){
			$data = array(
				'tipe_pembayaran' => 'manual',
				'total' => $total,
				'total_pembayaran' => $total_pembayaran,
				'kembalian' => $kembalian,
				'payment_by' => $payment_by,
				'pelanggan' => $key->pelanggan,
				'sales' => $key->sales,
				'bill_name' => $key->bill_name,
				'jenis_transaksi' => $key->jenis_transaksi,
				'tipe_transaksi' => $key->tipe_transaksi,
				'payment_status' => 'payed',
				'pay_time' => DATE('Y-m-d H:i:s'),
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if (!empty($key->pelanggan)) {
				$data+=array(
					'pelanggan' => $key->pelanggan
				);
			}
		}
		$this->GeneralModel->create_general('ms_transaksi',$data);
		$id_transaksi = $this->db->insert_id();

		$dataOrder = array(
			'transaksi' => $id_transaksi,
			'temp_transaksi' => NULL
		);

		$this->GeneralModel->update_general('ms_order','temp_transaksi',$id_temp_transaksi,$dataOrder);
		$this->GeneralModel->delete_general('ms_temp_transaksi','id_temp_transaksi',$id_temp_transaksi);

		$this->session->set_flashdata('notif','<div class="alert alert-success">Terima kasih pembayaran transaksi sudah masuk ke dalam sistem</div>');
		redirect('panel/transaksi/detailTransaksi/'.$id_transaksi);
	}

	public function tunda(){
		$id_temp_transaksi = $this->input->post('id_temp_transaksi');
		$total = $this->input->post('total');
		$total_pembayaran = $this->input->post('total_pembayaran');
		$kembalian = $this->input->post('kembalian');
		$payment_by = $this->input->post('payment_by');

		$getTempTransaksi = $this->GeneralModel->get_by_id_general('ms_temp_transaksi','id_temp_transaksi',$id_temp_transaksi);

		foreach($getTempTransaksi as $key){
			$data = array(
				'tipe_pembayaran' => 'manual',
				'payment_status' => 'pending',
				'total' => $total,
				'total_pembayaran' => $total_pembayaran,
				'kembalian' => $kembalian,
				'payment_by' => $payment_by,
				'pelanggan' => $key->pelanggan,
				'sales' => $key->sales,
				'bill_name' => $key->bill_name,
				'jenis_transaksi' => $key->jenis_transaksi,
				'tipe_transaksi' => $key->tipe_transaksi,
				'uuid_toko' => $this->session->userdata('uuid_toko')
			);

			if (!empty($key->pelanggan)) {
				$data+=array(
					'pelanggan' => $key->pelanggan
				);
			}
		}
		$this->GeneralModel->create_general('ms_transaksi',$data);
		$id_transaksi = $this->db->insert_id();

		$dataOrder = array(
			'transaksi' => $id_transaksi,
			'temp_transaksi' => NULL
		);

		$this->GeneralModel->update_general('ms_order','temp_transaksi',$id_temp_transaksi,$dataOrder);
		$resp = array(
			'transaksi' => $id_transaksi,
			'status' => TRUE
		);
		$this->GeneralModel->delete_general('ms_temp_transaksi','id_temp_transaksi',$id_temp_transaksi);
		echo json_encode($resp,JSON_PRETTY_PRINT);
	}

}
