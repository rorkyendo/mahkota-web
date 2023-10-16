<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpMqtt\Client\Exceptions\MQTTClientException;
use PhpMqtt\Client\MQTTClient;

class Transaksi extends CI_Controller {

	public function __construct()
  {
	parent::__construct();
	$this->koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);
  }

	public function detail($param1='',$param2='')
	{
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','id_transaksi',$param1,'pelanggan',$this->session->userdata('id_pengguna'));
		if ($cekTransaksi) {
			if($param2=='cari'){
				return $this->TransaksiModel->getOrder($param1,$this->session->userdata('id_pengguna'));
			}else{
				$data['title'] = 'Detail Transaksi';
				$data['content'] = 'frontend/transaksi/detail';
				$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
				$data['informasiPengguna'] = $this->GeneralModel->get_by_id_general('ms_informasi_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
				$data['cost'] = $this->TransaksiModel->getTotalBelanja($param1);
				$data['transaksi'] = $cekTransaksi;
				$data['rekening'] = $this->GeneralModel->get_by_id_general('ms_rekening','status_rekening','Y');
				$this->load->view('frontend/content', $data);
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('user/profile');
		}
	}

	public function deleteTransaksi($param1){
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$param1,'pelanggan',$this->session->userdata('id_pengguna'));
		if($cekTransaksi){
			if ($cekTransaksi[0]->payment_status == 'payed') {
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi yang sudah diselesaikan tidak bisa dibatalkan</div>');
				redirect('user/profile');			
			}else{
				if(!empty($cekTransaksi[0]->trx_id)){
					closeInvoiceXendit($cekTransaksi[0]->trx_id);
					$pelanggan = $this->session->userdata('id_pengguna');
					$this->db->query("DELETE FROM ms_voucher_terpakai WHERE pengguna='$pelanggan' and transaksi='$param1'");
				}
				$this->GeneralModel->delete_general('ms_transaksi','id_transaksi',$param1);
				$this->session->set_flashdata('notif','<div class="alert alert-success">Selamat transaksi berhasil dibatalkan</div>');
				redirect('user/profile');			
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('user/profile');
		}
	}

	public function cekResi($no_resi,$id_transaksi){
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$id_transaksi,'pelanggan',$this->session->userdata('id_pengguna'));
		if($cekTransaksi){
			$data['title'] = 'Cek Resi';
			$data['content'] = 'frontend/transaksi/cekResi';
			$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['informasiPengguna'] = $this->GeneralModel->get_by_id_general('ms_informasi_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['toko'] = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',$cekTransaksi[0]->uuid_toko);
			$data['transaksi'] = $cekTransaksi;
			$data['no_resi'] = $no_resi;
			$data['rekening'] = $this->GeneralModel->get_by_id_general('ms_rekening','status_rekening','Y');
			$this->load->view('frontend/content', $data);
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('user/profile');
		}
	}

	public function checkout($param1){
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$param1,'pelanggan',$this->session->userdata('id_pengguna'));
		if($cekTransaksi){
			if ($cekTransaksi[0]->payment_status == 'payed') {
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi yang sudah diselesaikan tidak bisa dibatalkan</div>');
				redirect('user/profile');			
			}else{

				$dataTransaksi = array(
					'tipe_pembayaran' => 'auto',
					'rekening' => $this->input->post('rekening'),
				);

				if($cekTransaksi[0]->jenis_transaksi == 'member'){
					$dataTransaksi += array(
						'payment_status' => 'process'
					);

                    $staff = $this->db->query("SELECT * FROM ms_pengguna WHERE hak_akses='superuser' or hak_akses='superowner' or hak_akses='finance' or hak_akses='admin database'")->result();

                    foreach($staff as $s){
                        $dataByPass = array(
                            'pengguna' => $s->id_pengguna,
                            'notif' => 'verif_member',
							'status' => 'N',
                            'id_notif' => $param1
                        );
                        $this->GeneralModel->create_general('ms_bypass_login_notif',$dataByPass);
                        $id = $this->db->insert_id();

                        $message = 'Halo *'.$s->nama_lengkap.'*!';
						$message .= urlencode("\n"); 
                        $message .= "Ada member baru mendaftar!";
						$message .= urlencode("\n"); 
                        $message .= "Klik link berikut ini untuk melakukan konfirmasi ";
						$message .= urlencode("\n"); 
                        $message .= base_url('auth/bpLog/'.my_simple_crypt($id,'e'));
                        sendNotifWA2($s->no_telp,$message);
                    }
				}
				//---------------- BUKTI PEMBAYARAN ---------------//
				if($dataTransaksi['tipe_pembayaran'] == 'manual'){
					$config['upload_path']          = 'assets/img/buktiTransfer/';
					$config['allowed_types']        = 'gif|jpg|png|jpeg';
					$config['max_size']             = 10000;

					$this->upload->initialize($config);

					if (!$this->upload->do_upload('bukti_transfer')) {
					} else {
						$dataTransaksi += array('bukti_transfer' => $config['upload_path'] . $this->upload->data('file_name'));
						if (!empty($cekTransaksi[0]->bukti_transfer)) {
							try {
								unlink($cekTransaksi[0]->bukti_transfer);
							} catch (\Exception $e) {
							}
						}
						$dataTransaksi += array(
							'payment_status' => 'process'
						);
					}
					$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$param1,$dataTransaksi);
					$this->session->set_flashdata('notif','<div class="alert alert-success">Terima kasih, transaksi kamu akan di proses oleh admin. Silahkan tunggu untuk beberapa saat untuk diverifikasi oleh admin</div>');
					redirect('transaksi/detail/'.$param1);	
				}else{
					$dataProduk = $this->GeneralModel->get_by_id_general("v_order","transaksi",$param1);
					$detailTransaksi = $this->GeneralModel->get_by_id_general("v_transaksi","id_transaksi",$param1);

					$data = [
						'items' => []
					];
					
					foreach ($dataProduk as $key) {
						$item = [
							'name' => !empty($key->tipe_member) ? $key->nama_pesanan : $key->nama_produk,
							'price' => intval($key->selling_price),
							'quantity' => $key->qty
						];
						
						$data['items'][] = $item;
					}

					if (!empty($detailTransaksi[0]->courier)) {
						$item = [
							'name' => "Ongkir (" . $detailTransaksi[0]->courier . " " . $detailTransaksi[0]->courier_service . " " . $detailTransaksi[0]->courier_desc . ")",
							'price' => $detailTransaksi[0]->ongkir,
							'quantity' => 1
						];
					
						$data['items'][] = $item;
					}
					$buyerEmail = $detailTransaksi[0]->email;

					if(empty($buyerEmail)){
						$this->session->set_flashdata("notif","<div class='alert alert-danger'>SILAHKAN UPDATE EMAIL KAMU TERLEBIH DAHULU! <a href='".base_url('user/updateProfile')."'>KLIK DISINI</a></div>");
						redirect("transaksi/detail/".$detailTransaksi[0]->id_transaksi);
					}else{
						if(!empty($detailTransaksi[0]->trx_id)){
							$cekInvoice = cekInvoiceXendit($detailTransaksi[0]->trx_id);
							if($cekInvoice['status']=='EXPIRED'){
								$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$param1,$dataTransaksi);
								invoiceLinkXendit($detailTransaksi[0],$buyerEmail,$data);
							}else{
								redirect('transaksi/bayar/'.$param1);
							}
						}else{
							$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$param1,$dataTransaksi);
							invoiceLinkXendit($detailTransaksi[0],$buyerEmail,$data);
						}
					}
				}
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('user/profile');
		}
	}

	public function cart($param1=''){
		if($param1=='cari'){
			$uuid_toko = $this->input->post('uuid_toko');
			$id_transaksi = $this->input->post('temp_transaksi');
			if(cekTempTransaksiPengguna($id_transaksi) == TRUE){
				return $this->TransaksiModel->getCart($id_transaksi,$uuid_toko);
			}
		}elseif($param1=='detailCart'){
			$uuid_toko = $this->input->post('uuid_toko');
			$id_transaksi = $this->input->post('temp_transaksi');
			if(cekTempTransaksiPengguna($id_transaksi) == TRUE){
				return $this->TransaksiModel->getDetailCart($id_transaksi,$uuid_toko);
			}
		}else{
			$data['title'] = 'Keranjang Belanja';
			$data['content'] = 'frontend/transaksi/keranjang';
			$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$pesanan = tempTransaksi();
			$data['getOrderToko'] = $this->GeneralModel->get_by_id_general_group_by('v_order','temp_transaksi',$pesanan->id_temp_transaksi,'uuid_toko');
			$this->load->view('frontend/content', $data);	
		}
	}

	public function deleteCart($id_order,$id_transaksi){
		if(cekTempTransaksiPengguna($id_transaksi) == TRUE){
			$this->GeneralModel->delete_multi_id_general('ms_order','temp_transaksi',$id_transaksi,'id_order',$id_order);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Pesanan berhasil dihapus dari keranjang</div>');
			redirect('transaksi/cart');
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('transaksi/cart');
		}
	}

	public function deleteDetailCart($id_order,$id_transaksi,$uuid_toko){
		if(cekTempTransaksiPengguna($id_transaksi) == TRUE){
			$this->GeneralModel->delete_multi_id_general('ms_order','temp_transaksi',$id_transaksi,'id_order',$id_order);
			$cekOrder = $this->GeneralModel->get_by_multi_id_general('v_order','temp_transaksi',$id_transaksi,'uuid_toko',$uuid_toko);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Pesanan berhasil dihapus dari keranjang</div>');
			if ($cekOrder) {
				redirect('transaksi/detailKeranjang/'.$id_transaksi.'/'.$uuid_toko);
			}else{
				redirect('transaksi/cart');
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('transaksi/cart');
		}
	}


	public function detailKeranjang($id_transaksi,$uuid_toko){
		if(cekTempTransaksiPengguna($id_transaksi) == TRUE){
			$data['title'] = 'Detail Transaksi';
			$data['content'] = 'frontend/transaksi/detailKeranjang';
			$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['transaksi'] = $this->GeneralModel->get_by_id_general('ms_temp_transaksi','id_temp_transaksi',$id_transaksi);
			$data['toko'] = $this->GeneralModel->get_by_id_general('ms_toko','uuid_toko',$uuid_toko);
			$data['subtotal'] = $this->TransaksiModel->getTempTotalBelanja($id_transaksi,$uuid_toko);
			$data['order'] = $this->GeneralModel->get_by_multi_id_general('v_order','temp_transaksi',$id_transaksi,'wajib_asuransi','Y');
			$data['uuid_toko'] = $uuid_toko;
			$this->load->view('frontend/content', $data);
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('transaksi/cart');
		}
	}

	public function payment($param1='',$param2='',$param3=''){
		if($param1=='doPayment'){
			if(cekTempTransaksiPengguna($param2) == TRUE){
				$subtotal = $this->TransaksiModel->getTempTotalBelanja($param2,$param3);
				$cekAsuransi = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$param2,'wajib_asuransi','Y');
				$biaya_asuransi = 0;

				$data = array(
					'pelanggan' => $this->session->userdata('id_pengguna'),
					'total_belanja' => $subtotal->total_belanja,
					'potongan_belanja' => $subtotal->total_potongan,
					'created_by' => $this->session->userdata('id_pengguna'),
					'payment_status' => 'pending',
					'jenis_transaksi' => 'produk',
					'uuid_toko' => $param3,
				);

				if($cekAsuransi){
					$biaya_asuransi = $cekAsuransi[0]->biaya_asuransi;
					$data += array(
						'biaya_asuransi' => $biaya_asuransi,
						'dengan_asuransi' => 'Y'
					);
				}else{
					$dengan_asuransi = $this->input->post('dengan_asuransi');
					if($dengan_asuransi == 'Y'){
						$biaya_asuransi = $cekAsuransi[0]->biaya_asuransi;
						$data += array(
							'biaya_asuransi' => $biaya_asuransi,
							'dengan_asuransi' => 'Y'
						);
					}else{
						$data += array(
							'dengan_asuransi' => 'N'
						);
					}
				}

				$data+=array(
					'total' => $subtotal->total_belanja+$biaya_asuransi,
				);

				if($this->session->userdata('status_member') == 'active'){
					$data += array(
						'tipe_transaksi' => 'member'
					);
				}

				$this->GeneralModel->create_general('ms_transaksi',$data);
				$id_transaksi = $this->db->insert_id();

				$dataOrder = array(
					'transaksi' => $id_transaksi,
					'temp_transaksi' => NULL
				);

				$this->GeneralModel->update_multi_id_general('ms_order', 'temp_transaksi', $param2, 'uuid_toko', $param3, $dataOrder);
				redirect('transaksi/detail/'.$id_transaksi);

			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
				redirect('transaksi/cart');
			}
		}
	}

	public function updateTransaksi(){
		$id_transaksi = $this->input->post('id_transaksi');
		$dataTransaksi = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
		$informasi_pengguna = $this->input->post('informasi_pengguna');
		$dataPengguna = $this->GeneralModel->get_by_id_general('ms_informasi_pengguna','id_informasi',$informasi_pengguna);

		$courier = $this->input->post('courier');
		$courier_service = $this->input->post('courier_service');
		$courier_desc = $this->input->post('courier_desc');
		$courier_est = $this->input->post('courier_est');
		$bill_name = $dataPengguna[0]->nama;
		$bill_address = $dataPengguna[0]->alamat_lengkap;
		$bill_hp = $dataPengguna[0]->nomor_hp;
		$ongkir = $this->input->post('ongkir');
		$total = $this->input->post('ongkir') + $dataTransaksi[0]->total_belanja + $dataTransaksi[0]->biaya_asuransi;

		$data = array(
			'informasi_pengguna' => $informasi_pengguna,
			'courier' => $courier,
			'courier_service' => $courier_service,
			'courier_desc' => $courier_desc,
			'courier_est' => $courier_est,
			'bill_name' => $bill_name,
			'bill_address' => $bill_address,
			'bill_hp' => $bill_hp,
			'ongkir' => $ongkir,
			'total' => $total,
		);

		if($this->GeneralModel->update_general('ms_transaksi','id_transaksi',$id_transaksi,$data) == TRUE){
			echo "true";
		}else{
			echo "false";
		}
	}

	public function returnTransaksi($id_transaksi){
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',my_simple_crypt($id_transaksi,'d'),'pelanggan',$this->session->userdata('id_pengguna'));
		if ($cekTransaksi) {
			$data = array(
				'trx_id' => my_simple_crypt($this->input->get("trx_id"),'e'),
				'payment_by' => $this->input->get("via"),
				'payment_channel' => $this->input->get("channel"),
				'va_number' => $this->input->get("va"),
			);
			$this->GeneralModel->update_general('ms_transaksi','id_transaksi',my_simple_crypt($id_transaksi,'d'),$data);
			$this->session->set_flashdata('notif','<div class="alert alert-success">Terimakasih sudah melakukan pembayaran, status pembayaran kamu akan segera dicek oleh sistem sesuai dengan pembayaran yang kamu lakukan</div>');
			redirect('transaksi/detail/'.my_simple_crypt($id_transaksi,'d'));
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('user/profile');
		}
	}

	public function bayar($param1='',$param2=''){
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','id_transaksi',$param1,'pelanggan',$this->session->userdata('id_pengguna'));
		if ($cekTransaksi) {
				$data['title'] = 'Pembayaran Transaksi';
				$data['content'] = 'frontend/transaksi/bayar';
				$data['profile'] = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
				$data['informasiPengguna'] = $this->GeneralModel->get_by_id_general('ms_informasi_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
				$data['transaksi'] = $cekTransaksi;
				$this->load->view('frontend/content', $data);
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			redirect('user/profile');
		}
	}

	public function status(){
		// Mendapatkan body dari permintaan
		$body = file_get_contents('php://input');

		// Mendekode JSON menjadi array atau objek PHP
		$data = json_decode($body, true);
		var_dump($data);
		
		$id_transaksi = $data['external_id'];
		$dataTransaksi = array(
			'payment_by' => $data['payment_method'],
			'payment_channel' => $data['payment_channel'],
			'va_number' => $data['payment_destination'],
			'total' => $data['amount'],
		);


		if(!empty($data['id'])){
			$cek = $this->GeneralModel->get_by_id_general('ms_transaksi','trx_id',$data['id']);
			if($cek){
				
				if($data['status'] == "PAID"){
					$dataTransaksi += array(
						'payment_status' => 'payed',
						'pay_time' => DATE('Y-m-d H:i:s'),
					);
		
					$getOrder = $this->db->query("SELECT * FROM v_order WHERE transaksi='$id_transaksi'")->row();
					if($getOrder){
						if(!empty($getOrder->produk_digital)){
							$kode = $getOrder->kode;
							$testing = FALSE;
							$hasil = topUp($id_transaksi,str_replace('+62','0',$getOrder->nomor_digital),$kode,$testing);
							$dataDigital = json_decode($hasil);
							foreach ($dataDigital as $key) {
								$getOrder = $this->db->query("SELECT * FROM v_order WHERE transaksi='$key->ref_id'")->row();
								if ($getOrder) {
									$dataOrder = array(
										'status_digital' => $key->status
									);
									$this->GeneralModel->update_general('ms_order','id_order',$getOrder->id_order,$dataOrder);
								}
							}
						}
					}
		
					$staff = $this->db->query("SELECT p.*,pp.perangkat FROM ms_pengguna p LEFT JOIN v_perangkat_pengguna pp ON pp.id_pengguna = p.id_pengguna
					WHERE hak_akses='superuser' or hak_akses='superowner' or hak_akses='finance' or hak_akses='admin database'")->result();
		
					foreach($staff as $s){
						$dataByPass = array(
							'pengguna' => $s->id_pengguna,
							'notif' => 'transaksi',
							'status' => 'N',
							'id_notif' => $id_transaksi
						);
						$this->GeneralModel->create_general('ms_bypass_login_notif',$dataByPass);
						$id = $this->db->insert_id();
		
						$message = 'Halo *'.$s->nama_lengkap.'*!';
						$message .= urlencode("\n"); 
						$message .= "Ada transaksi yang baru saja dibayar dengan #idtransaksi ".$id_transaksi;
						$message .= urlencode("\n"); 
						$message .= "Klik link berikut ini untuk melakukan pengecekan";
						$message .= urlencode("\n");
						$message .= base_url('auth/bpLog/'.my_simple_crypt($id,'e'));
						sendNotifWA2($s->no_telp,$message);
					}
				}

				if($this->GeneralModel->update_general('ms_transaksi','trx_id',$data['id'],$dataTransaksi) == TRUE){
					foreach($this->koneksi as $key){
						$client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer');
						$client->connect('mahkota', $key->mqtt_password);
						try{
							$client->publish('transaksi/status/'.$data['id'], $data['status'], 0);
						} catch (MqttClientException $e) {
						}
					}			
					echo 'Berhasil';
				}else{
					echo 'Transaksi Gagal diupdate';
				}
			}else{
				echo 'Transaksi tidak ditemukan';
			}
		}else{
			echo 'ID Tidak Ada';
		}
	}

	public function statusDigital($param1 = '') {
		// Mendapatkan body dari permintaan
		$body = file_get_contents('php://input');
	
		// Mendekode JSON menjadi array atau objek PHP
		$data = json_decode($body, true);
	
		$secret = isset($data['secret']) ? $data['secret'] : '';
	
		// Cek apakah secret sesuai dengan yang diharapkan
		if ($secret === 'medio' && isset($data['data'])) {
			$dataObject = $data['data'];
	
			$getOrder = $this->db->query("SELECT * FROM v_order WHERE transaksi='{$dataObject['ref_id']}'")->row();
	
			if ($getOrder) {
				$dataOrder = array(
					'status_digital' => $dataObject['status']
				);
	
				$this->GeneralModel->update_general('ms_order', 'id_order', $getOrder->id_order, $dataOrder);
			}
		}
	}

	public function pulsa(){
		$brand = $this->input->get('brand');
		if(!empty($brand)){
			$data = $this->db->query("SELECT id_produk_digital,harga_jual,brand,kategori,nama_produk_digital FROM ms_produk_digital WHERE status='Y' and brand='$brand'")->result();
			echo json_encode($data);
		}else{
			$data['title'] = 'Pulsa';
			$data['content'] = 'frontend/transaksi/pulsa';
			$this->load->view('frontend/content', $data);
		}
	}

	public function pesanProdukDigital(){
		$produk_digital = $this->input->post('id_produk_digital');
		$detail = $this->GeneralModel->get_by_id_general('ms_produk_digital','id_produk_digital',$produk_digital);
		foreach ($detail as $key) {
			$transaksi = array(
				'pelanggan' => $this->session->userdata('id_pengguna'),
				'jenis_transaksi' => 'produk_digital',
				'total_belanja' => $key->harga_jual,
				'total' => $key->harga_jual,
				'payment_status' => 'process',
				'created_by' => $this->session->userdata('id_pengguna'),
				'keterangan' => 'Transaksi Pulsa'
			);

			$this->GeneralModel->create_general('ms_transaksi',$transaksi);
			$id_transaksi = $this->db->insert_id();

			$order = array(
				'produk_digital' => $key->id_produk_digital,
				'selling_price' => $key->harga_jual,
				'capital_price' => $key->harga_modal,
				'qty' => 1,
				'subtotal' => $key->harga_jual,
				'nomor_digital' => $this->input->post('nomor'),
				'transaksi' => $id_transaksi
			);
			$this->GeneralModel->create_general('ms_order',$order);
			$id_order = $this->db->insert_id();
		}
		if(!empty($id_order) && !empty($id_transaksi)){
			$dataProduk = $this->GeneralModel->get_by_id_general("v_order","transaksi",$id_transaksi);
			$detailTransaksi = $this->GeneralModel->get_by_id_general("v_transaksi","id_transaksi",$id_transaksi);

			$data = [
				'items' => []
			];

			foreach ($dataProduk as $key) {
				$item = [
					'name' => $key->nama_produk_digital,
					'price' => intval($key->selling_price),
					'quantity' => $key->qty
				];
				
				$data['items'][] = $item;
			}

			invoiceDigitalXendit($detailTransaksi[0],$data);
			echo $id_transaksi;
		}else{
			echo 'fail';
		}
	}

	public function cekTransaksiDigital($param1=''){
		$pengguna = $this->input->get('pengguna');
		$transaksi = $this->input->get('id_transaksi');
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$transaksi,'created_by',$pengguna);
        $cekOrder = $this->db->query("SELECT * FROM v_order WHERE transaksi = '$transaksi'")->row();
		if($param1=='status'){
			if($cekOrder->status_digital != 'Sukses' && $cekOrder->status_digital != 'Gagal'){
				// Informasi akun Digiflazz
				$username = "fakudeoPGk3g";
				$apiKey = "dev-baa11da0-3789-11ee-bee2-bbcfcefff18a";
				$prodApiKey = "be6d2937-8581-5c4a-9fa0-4b004dba79d0";
				$fragmentUrl = $transaksi;

				// Membuat string untuk di-MD5
				$signString = $username . $prodApiKey . $fragmentUrl;
				$sign = md5($signString);

				// Data yang akan dikirim sebagai body permintaan
				$data = [
					'username' => $username,
					'buyer_sku_code' => $cekOrder->kode,
					'customer_no' => $cekOrder->nomor_digital,
					'ref_id' => $transaksi,
					'sign' => $sign,
				];

				if ($cekOrder->kategori != "Pulsa") {
					$data["commands"] = "status-pasca";
				}

				// URL API Digiflazz
				$apiUrl = "https://api.digiflazz.com/v1/transaction";

				// Membuat permintaan menggunakan cURL
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => $apiUrl,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => json_encode($data),
				));
				// Menampilkan respons dari API (JSON)
				$response = curl_exec($curl);

				curl_close($curl);
				
				// Dekode respons JSON menjadi array asosiatif
				$responseArray = json_decode($response, true);
				
				// Hapus kunci "buyer_last_saldo" dari array jika ada
				if (isset($responseArray['data']['buyer_last_saldo'])) {
					unset($responseArray['data']['buyer_last_saldo']);
					unset($responseArray['data']['tele']);
					unset($responseArray['data']['price']);
				}
				$response = json_encode($responseArray);
				$updateData = array(
					'status_digital' => $responseArray['data']['status'],
					'order_notes' => "Topup berhasil dilakukan dengan <b>SN ".$responseArray['data']['sn']."</b>",
				);
				$this->GeneralModel->update_general("ms_order",'id_order',$cekOrder->id_order,$updateData);
				echo $response;
			}
		}else{
			$data['title'] = 'Status Transaksi';
			$data['transaksi'] = $cekTransaksi;
			$this->load->view('frontend/transaksi/cekTransaksiDigital', $data);
		}
	}

}