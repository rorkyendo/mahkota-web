<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use PhpMqtt\Client\Exceptions\MQTTClientException;
use PhpMqtt\Client\MQTTClient;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class Transaksi extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	    $this->koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);
    }

   public function detailTransaksi_get()
   {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
         $id_transaksi = $this->get('id_transaksi');
         $transaksi = $this->db->query("SELECT * FROM ms_transaksi WHERE id_transaksi = '$id_transaksi'")->result();
         $order = $this->db->query("SELECT * FROM ms_order WHERE transaksi = '$id_transaksi'")->result();

        $data = array(
            'transaksi' => $transaksi,
            'order' => $order
        );

        $this->response($data, 200);
        }else{
            $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan'], 401);
        }
        
   }

   public function addCart_post()
   {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        $session_id = session_id();
        $idProduk = $this->post('id_produk');
        $getProdukData = $this->GeneralModel->get_by_id_general('v_produk','id_produk',$idProduk);
        $qty = $this->post("qty");

        if($getProdukData){
            if($getProdukData[0]->harga_diskon > 0){
				$harga_jual = $getProdukData[0]->harga_diskon;
			}else{
				$harga_jual = $getProdukData[0]->harga_jual_online;
			}
        }else{
            $this->response(['status' => false, 'message' => 'Produk Tidak Ditemukan'], 404);
        }

        $login_token = $this->post('login_token');
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $login_token])->row();
        $cekTemp = $this->GeneralModel->get_by_id_general('ms_temp_transaksi','pelanggan',$cekPengguna->id_pengguna);
        $tempTransaksi = $this->db->query("SELECT * FROM ms_temp_transaksi WHERE pelanggan = '$cekPengguna->id_pengguna'")->row();

        if (empty($tempTransaksi)) {
            $dataTransaksi = array(
                'pelanggan' => $cekPengguna->id_pengguna,
                'jenis_transaksi' => 'produk',
                'tipe_transaksi' => 'umum',
                'ip_address' => $ip_address,
                'session_id' => $session_id,
                'created_by' => $cekPengguna->id_pengguna,
            );
    
            $this->GeneralModel->create_general('ms_temp_transaksi',$dataTransaksi);
            $id_transaksi = $this->db->insert_id();
        }else{
            $id_transaksi = $tempTransaksi->id_temp_transaksi;
        }
        $cekOrderTemp = $this->GeneralModel->get_by_multi_id_general('ms_order','temp_transaksi',$id_transaksi,'produk',$idProduk);
        if(!empty($cekOrderTemp)){
            $dataOrder = array(
                'qty_old' => $cekOrderTemp[0]->qty,
                'qty' => $cekOrderTemp[0]->qty + $this->post('qty')
            );				
            $this->GeneralModel->update_general('ms_order','id_order',$cekOrderTemp[0]->id_order,$dataOrder);
        }else{
            $dataOrder = array(
                'qty' => $qty,
                'produk' => $idProduk,
                'selling_price' => $harga_jual,
                'capital_price' => $getProdukData[0]->harga_modal,
                'tipe_member' => $this->post('tipe_member'),
                'promo_potongan' => $getProdukData[0]->harga_diskon*$qty,
                'subtotal' => $qty * $harga_jual,
                'uuid_toko' => $getProdukData[0]->uuid_toko,
                'temp_transaksi' => $id_transaksi
            );

            $this->GeneralModel->create_general('ms_order',$dataOrder);
            $this->response(['status' => true, 'message' => 'Produk Berhasil Ditambahkan'], 200);
        }
   }

   public function reduceCart_put()
   {
        if (empty($this->put('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->put('login_token')])->row();
        if($cekPengguna){
            if($cekPengguna){
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip_address = $_SERVER['REMOTE_ADDR'];
                }
                $session_id = session_id();
                $idProduk = $this->put('id_produk');
                $getProdukData = $this->GeneralModel->get_by_id_general('v_produk','id_produk',$idProduk);
                $qty = $this->put("qty");
    
                if($getProdukData[0]->harga_diskon > 0){
                    $harga_jual = $getProdukData[0]->harga_jual_online - $getProdukData[0]->harga_diskon;
                }else{
                    $harga_jual = $getProdukData[0]->harga_jual_online;
                }
    
                $tempTransaksi = $this->db->query("SELECT * FROM ms_temp_transaksi WHERE pelanggan = '$cekPengguna->id_pengguna'")->row();
                $id_transaksi = $tempTransaksi->id_temp_transaksi;
                
                $cekOrderTemp = $this->GeneralModel->get_by_multi_id_general('ms_order','temp_transaksi',$id_transaksi,'produk',$idProduk);
                if(!empty($cekOrderTemp)){
                    $dataOrder = array(
                        'qty' => $this->put('qty')
                    );				
                    $this->GeneralModel->update_general('ms_order','id_order',$cekOrderTemp[0]->id_order,$dataOrder);
                }else{
                    $dataOrder = array(
                        'qty' => $qty,
                        'produk' => $idProduk,
                        'selling_price' => $harga_jual,
                        'capital_price' => $getProdukData[0]->harga_modal,
                        'promo_potongan' => $getProdukData[0]->harga_diskon,
                        'subtotal' => $qty * $harga_jual,
                        'uuid_toko' => $getProdukData[0]->uuid_toko,
                        'temp_transaksi' => $id_transaksi
                    );
    
                    $this->GeneralModel->update_general('ms_order','id_order',$cekOrderTemp[0]->id_order,$dataOrder);
                    $this->response(['status' => true, 'message' => 'Produk Berhasil Diubah'], 200);
                }
            }else{
                $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan'], 401);
            }
        }else{
            $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan'], 401);
        }
   }

   public function deleteCart_post()
   {
    $temp_transaksi = $this->post('temp_transaksi');
    $id_order = $this->post('id_order');
    $this->GeneralModel->delete_multi_id_general('ms_order','temp_transaksi',$temp_transaksi,'id_order',$id_order);
    $this->response(['status' => true, 'message' => 'Produk Berhasil Dihapus'], 200);
   }

   public function deleteDetailCart_delete()
   {
    $temp_transaksi = $this->delete('temp_transaksi');
    $id_order = $this->delete('id_order');
    $uuid_toko = $this->delete('uuid_toko');
    $this->GeneralModel->delete_multi_id_general('ms_order','temp_transaksi',$temp_transaksi,'id_order',$id_order);
    $cekOrder = $this->GeneralModel->get_by_multi_id_general('v_order','temp_transaksi',$temp_transaksi,'uuid_toko',$uuid_toko);
    $this->response(['status' => true, 'message' => 'Produk Berhasil Dihapus'], 200);
   }

   public function getCart_get()
   {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        $tempTransaksi = $this->db->query("SELECT * FROM ms_temp_transaksi WHERE pelanggan = '$cekPengguna->id_pengguna'")->row();
        if(empty($tempTransaksi)){
            $this->response(['status' => false, 'message' => 'Tidak Ada Data'], 200);
        }else{
            $cekTemp = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$tempTransaksi->id_temp_transaksi);
            if($cekTemp){
                
                $data = array(
                    'transaksi' => $cekTemp,
                );

                $this->response($data, 200);
                
            }else{
                $this->response(['status' => false, 'message' => 'Tidak Ada Data'], 200);
            }
        }
    }

    public function notify_post(){
		$trx_id = $this->post('trx_id');
		$sid = $this->post('sid');
		$status = $this->post('status');

		$data = array(
			'trx_id' => $trx_id,
			'sid' => $sid,
			'status' => $status,
            'fee' => $this->post('fee'),
            'total' => $this->post('total'),
		);

		$trx_id = my_simple_crypt($trx_id,'e');

		$cekTransaksi = $this->GeneralModel->get_by_id_general("v_transaksi","trx_id",$trx_id);
		if($cekTransaksi){
			foreach ($cekTransaksi as $row) {
				if($row->payment_status != 'payed'){
					if($status != 'expired'){
						if ($status == 'berhasil') {
							$status = 'payed';
						}
						$update = array(
							'payment_status' => $status
						);
                        if($status == 'payed'){
                            $update += array(
                                'pay_time' => DATE("Y-m-d H:i:s"),
                                'total_harga_dengan_fee' => $row->total,
                                'total_pembayaran' => $row->total,
                                'fee' => $this->post('fee'),
                            );
                        }
                        if($status == 'payed'){
                            $cekReferral = $this->GeneralModel->get_by_id_general('v_pengguna','id_pengguna',$row->pelanggan);
                            if(!empty($cekReferral[0]->referral)){
                                $referral = $this->GeneralModel->get_by_id_general('v_pengguna','referral',$cekReferral[0]->referral);
                                if(!empty($referral)){
                                    $id_referral = $referral[0]->id_pengguna;
                                    $cekRiwayat = $this->db->query("SELECT * FROM ms_riwayat_point WHERE pengguna = '$id_referral' AND keterangan_point = 'Member' AND downliner = '$row->pelanggan' AND status = 'pending'")->result();
                                    if(!empty($cekRiwayat)){
                                        $total_pembayaran = $row->total;
                                        if($total_pembayaran >= 100000){
                                            $this->db->update('ms_riwayat_point',['status' => 'diterima'],['id_riwayat_point' => $cekRiwayat[0]->id_riwayat_point]);
                                        }
                                    }
                                }
                            }
                        }

                        if($status == 'payed'){
                            $cekRiwayat = $this->db->query("SELECT * FROM ms_riwayat_point WHERE pengguna = '$row->pelanggan' AND status = 'pending' AND transaksi = '$row->id_transaksi'")->result();
                            if(!empty($cekRiwayat)){
                                $this->db->update('ms_riwayat_point',['status' => 'diterima'],['id_riwayat_point' => $cekRiwayat[0]->id_riwayat_point]);
                            }
                        }
						$this->GeneralModel->update_general("ms_transaksi","id_transaksi",$row->id_transaksi,$update);
					}
                    $resp = array(
                        'message' => $status,
                        'status' => true
                    );
                    if($status == 'payed'){
                        $message = 'Terima kasih, status pembayaran anda dengan *id transaksi '.$row->id_transaksi.'* sudah dikonfirmasi oleh sistem';
    					$message .= urlencode("\n"); 
                        $message .= 'Silahkan hubungi admin untuk segera di proses untuk pengiriman';
    					sendNotifWA($row->bill_hp,$message);

                        foreach($this->koneksi as $key){
                            $client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer');
                            $client->connect('mahkota', $key->mqtt_password);
                            try{
                                $client->publish('notifTransaksi', "berhasil", 0);
                            } catch (MqttClientException $e) {
                            }
                        }		
                    }
                    $this->response($resp, 200);
				}
			}			
		}
	}

    public function dataProduct_post()
    {
        $dataProduk = $this->GeneralModel->get_by_id_general("v_order","transaksi",$this->post('id_transaksi'));
        foreach ($dataProduk as $key) {
            if(!empty($key->tipe_member)){
                $data['product'][] = $key->nama_pesanan;
            }else{
                $data['product'][] = $key->nama_produk;
            }
            $data['price'][]	 = intval($key->selling_price);
            $data['qty'][]     = $key->qty;
        }
        $resp = array(
            'data' => $data,
            'status' => true
        );
        $this->response($resp, 200);
    }

    public function checkoutMember_post()
    {
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        $id_transaksi = $this->post('id_transaksi');
        $id_pengguna = $cekPengguna->id_pengguna;
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$id_transaksi,'pelanggan',$id_pengguna);
		if($cekTransaksi){
			if ($cekTransaksi[0]->payment_status == 'payed') {
				return $this->response(['status' => false, 'message' => 'Mohon maaf transaksi yang sudah diselesaikan tidak bisa dibatalkan'], 200);
			}else{
                $dataTransaksi = array(
					'tipe_pembayaran' => "auto",
					'rekening' => "3",
					'pay_time' => DATE('Y-m-d H:i:s'),
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
                            'id_notif' => $id_transaksi
                        );
                        $this->GeneralModel->create_general('ms_bypass_login_notif',$dataByPass);
                        $id = $this->db->insert_id();

                        // $message = 'Halo *'.$s->nama_lengkap.'*!';
						// $message .= urlencode("\n"); 
                        // $message .= "Ada member baru mendaftar!";
						// $message .= urlencode("\n"); 
                        // $message .= "Klik link berikut ini untuk melakukan konfirmasi ";
						// $message .= urlencode("\n"); 
                        // $message .= base_url('auth/bpLog/'.my_simple_crypt($id,'e'));
                        // sendNotifWA2($s->no_telp,$message);
                    }
				}

				$dataProduk = $this->GeneralModel->get_by_id_general("v_order","transaksi",$id_transaksi);
                $detailTransaksi = $this->GeneralModel->get_by_id_general("v_transaksi","id_transaksi",$id_transaksi);

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

                    $cekReferral = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$id_pengguna);
                    if(!empty($cekReferral[0]->referral)){
                        $userReferral = $this->GeneralModel->get_by_id_general('ms_member','barcode_member',$cekReferral[0]->referral);
                        if(!empty($userReferral)){
                            $referralId = $userReferral[0]->id_pengguna;
                            $cekRiwayat = $this->GeneralModel->get_by_multi_id_general('ms_riwayat_point','pengguna',$referralId,'transaksi',$id_transaksi);
                            if(empty($cekRiwayat)){
                                $cekPoinTipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',$key->tipe_member);
                                $dataRiwayat = array(
                                    'pengguna' => $referralId,
                                    'downliner' => $id_pengguna,
                                    'transaksi' => $id_transaksi,
                                    'point' => $cekPoinTipeMember[0]->dapat_point,
                                    'keterangan_point' => 'Member',
                                    'tgl_point' => DATE('Y-m-d H:i:s'),
                                );
                                $this->GeneralModel->create_general('ms_riwayat_point',$dataRiwayat);
                            }
                        }
                    }

                }


                if(!empty($detailTransaksi[0]->courier)){
                    array_push($data['product'],"Ongkir (".$detailTransaksi[0]->courier." ".$detailTransaksi[0]->courier_service." ".$detailTransaksi[0]->courier_desc.")");
                    array_push($data['price'],$detailTransaksi[0]->ongkir);
                    array_push($data['qty'],"1");
                }

                $buyerName = $detailTransaksi[0]->nama_lengkap_pelanggan;
                $buyerPhone = $detailTransaksi[0]->no_telp_pelanggan;
                $buyerEmail = $detailTransaksi[0]->email;

                $this->GeneralModel->update_general('ms_transaksi','id_transaksi',$id_transaksi,$dataTransaksi);
                if(!empty($detailTransaksi[0]->trx_id)){
                    try {
                        $cekInvoice = cekInvoiceXendit($detailTransaksi[0]->trx_id);
                    } catch (\Throwable $th) {
                        $cekInvoice = array(
                            'status' => 'EXPIRED'
                        );
                    }
                    if($cekInvoice['status']=='EXPIRED'){
                        $this->db->update('ms_transaksi',$dataTransaksi,['id_transaksi' => $id_transaksi]);
                        if(invoiceLinkXenditMobile($detailTransaksi[0],$buyerEmail,$data) == TRUE){
                            $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                            $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                        }
                    }else{
                        $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                        $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                    }
                }else{
                    $this->db->update('ms_transaksi',$dataTransaksi,['id_transaksi' => $id_transaksi]);
                    if(invoiceLinkXenditMobile($detailTransaksi[0],$buyerEmail,$data) == TRUE){
                        $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                        $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                    }
                }
            }
		}else{
            return $this->response(['status' => false, 'message' => 'Mohon maaf transaksi tidak ditemukan'], 200);
		}
	}

    public function deleteTransaksi_post()
    {
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $param1 = $this->post('id_transaksi');
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$param1,'pelanggan',$cekPengguna->id_pengguna);
		if($cekTransaksi){
			if ($cekTransaksi[0]->payment_status == 'payed') {
                $this->response(['status' => false, 'message' => 'Mohon maaf transaksi yang sudah diselesaikan tidak bisa dibatalkan'], 200);
			}else{
				$this->GeneralModel->delete_general('ms_transaksi','id_transaksi',$param1);
				// $this->session->set_flashdata('notif','<div class="alert alert-success">Selamat transaksi berhasil dibatalkan</div>');
				// redirect('user/profile');			
                $this->response(['status' => true, 'message' => 'Selamat transaksi berhasil dibatalkan'], 200);
			}
		}else{
			// $this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf transaksi tidak ditemukan</div>');
			// redirect('user/profile');
            $this->response(['status' => false, 'message' => 'Mohon maaf transaksi tidak ditemukan'], 200);
		}
	}

    public function returnTransaksi_get($param1='',$param2='')
    {
        $cekPengguna = $this->db->get_where('ms_pengguna', ['no_telp' => $param2])->row();
		$cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',my_simple_crypt($param1,'d'),'pelanggan',$cekPengguna->id_pengguna);
		if ($cekTransaksi) {
			$data = array(
				'trx_id' => my_simple_crypt($this->get("trx_id"),'e'),
				'payment_by' => $this->get("via"),
				'payment_channel' => $this->get("channel"),
				'va_number' => $this->get("va"),
                'payment_status' => 'process',
			);
			$this->GeneralModel->update_general('ms_transaksi','id_transaksi',$cekTransaksi[0]->id_transaksi,$data);
			$data['cekTransaksi'] = $cekTransaksi;
            $data['via'] = $this->get("via");
            $data['channel'] = $this->get("channel");
            $data['va'] = $this->get("va");
            $data['total'] = $cekTransaksi[0]->total;
			$this->load->view('frontend/ipaymuMobile', $data);	
		}else{
            $data['cekTransaksi'] = null;
			$this->load->view('frontend/ipaymuMobile', $data);	
        }
	}

    function payment_post($data,$buyerName,$buyerEmail,$buyerPhone,$id_transaction)
    {
        $CI =& get_instance();
        // SAMPLE HIT API iPaymu v2 PHP //
        $va           = '0000002276648478'; //get on iPaymu dashboard
        $apiKey       = 'SANDBOX61F69E4D-2721-4885-B6FA-371EE5D880A5'; //get on iPaymu dashboard
        $url          = 'https://sandbox.ipaymu.com/api/v2/payment';
        //   $va = '1179005370366666';
        //   $apiKey = 'DCF24388-020E-4E9F-BEB9-B446331701BB';    
        //   $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
        $method       = 'POST'; //method

        //Request Body//
        $body['product']    = $data['product'];
        $body['qty']        = $data['qty'];
        $body['price']      = $data['price'];
        $body['buyerName']      = $buyerName;
        $body['buyerEmail']      = $buyerEmail;
        $body['buyerPhone']      = $buyerPhone;
        $body['expiredType']      = "hours";
        $body['expired']      = "24";
        $body['feeDirection']      = "BUYER";
        $body['returnUrl']  = base_url('transaksi/returnTransaksi/'.my_simple_crypt($id_transaction,'e'));
        $body['cancelUrl']  = base_url('transaksi/deleteTransaksi/'.$id_transaction);
        $body['notifyUrl']  = base_url('api/transaksi/notify/');
        $body['referenceId'] = $id_transaction; //your reference id
        //End Request Body//

        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp    = Date('YmdHis');
        //End Generate Signature


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );
    
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);
    
        if($err) {
            echo $err;
        } else {
    
            //Response
            $ret = json_decode($ret);
            if($ret->Status == 200) {
                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                header('Location:' . $url);
            } else {
                print_r($ret);
            }
            //End Response
        }

    }

    public function paymentProduct_post(){
        $id_temp_transaksi = $this->post('id_temp_transaksi');
        $uuid_toko = $this->post('uuid_toko');
        $login_token = $this->post('login_token');
        if(cekTempTransaksiPengguna($id_temp_transaksi) == TRUE){
            $subtotal = $this->TransaksiModel->getTempTotalBelanja($id_temp_transaksi,$uuid_toko);
            $cekAsuransi = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$id_temp_transaksi,'wajib_asuransi','Y');
            $biaya_asuransi = 0;
            $pengguna = $this->db->get_where('ms_pengguna',array('login_token' => $login_token))->row();

            $data = array(
                'pelanggan' => $pengguna->id_pengguna,
                'total_belanja' => $subtotal->total_belanja,
                'potongan_belanja' => $subtotal->total_potongan,
                'created_by' => $pengguna->id_pengguna,
                'payment_status' => 'pending',
                'jenis_transaksi' => 'produk',
                'uuid_toko' => $uuid_toko,
            );

            if($cekAsuransi){
                $biaya_asuransi = $cekAsuransi[0]->biaya_asuransi;
                $data += array(
                    'biaya_asuransi' => $biaya_asuransi,
                    'dengan_asuransi' => 'Y'
                );
            }else{
                $dengan_asuransi = $this->post('dengan_asuransi');
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

            $this->response([
                'status' => TRUE,
                'message' => 'Transaksi berhasil dibuat',
            ], RestController::HTTP_OK);

        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Transaksi tidak ditemukan',
            ], RestController::HTTP_OK);
        }
	}

    public function addTransacation_post()
    {
        $pelanggan = $this->GeneralModel->get_by_id_general('ms_pengguna','login_token',$this->post('login_token'));
        if ($pelanggan) {
            $biaya_asuransi = 0;
            $id_voucher_terpakai = $this->post('id_voucher_terpakai');
            $temp_transaksi = $this->post('temp_transaksi');
            $uuid_toko = $this->post('uuid_toko');
            $payment = $this->post('payment');
            $dataProduk = $this->post('dataProduk');

            $cekAsuransi = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$temp_transaksi,'wajib_asuransi','Y');
            $data = array(
                'pelanggan' => $pelanggan[0]->id_pengguna,
                'total_belanja' => $this->post('total_belanja'),
                'potongan_belanja' => $this->post('potongan_belanja'),
                'potongan_pelanggan' => $this->post('potongan_pelanggan'),
                'created_by' => $pelanggan[0]->id_pengguna,
                'jenis_transaksi' => 'produk',
                'uuid_toko' => $uuid_toko,
                'tipe_transaksi' => 'member'
            );
    
            $id_pengguna = $pelanggan[0]->id_pengguna;
    
            $data+=array(
                'total' => $this->post('total_belanja'),
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
    
            $this->GeneralModel->create_general('ms_transaksi',$data);
            $id_transaksi = $this->db->insert_id();
    
            $dataOrder = array(
                'transaksi' => $id_transaksi,
                'temp_transaksi' => NULL
            );
            
            foreach($dataProduk as $dp){
                // update ke ms_order dengan id_produk , temp_transaksi, uuid_toko
                $this->db->where('produk', $dp['produk']);
                $this->db->where('temp_transaksi', $temp_transaksi);
                $this->db->where('uuid_toko', $uuid_toko);
                $this->db->update('ms_order', $dataOrder);
            }
            // $this->GeneralModel->update_multi_id_general('ms_order', 'temp_transaksi', $temp_transaksi, 'uuid_toko', $uuid_toko, $dataOrder);
            $dataTransaksi = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
            
            if ($payment == 'ONLINE') {
                $informasi_pengguna = $this->post('informasi_pengguna');
                $dataPengguna = $this->GeneralModel->get_by_id_general('ms_informasi_pengguna','id_informasi',$informasi_pengguna);
        
                $courier = $this->post('courier');
                $courier_service = $this->post('courier_service');
                $courier_desc = $this->post('courier_desc');
                $courier_est = $this->post('courier_est');
                $bill_name = $dataPengguna[0]->nama;
                $bill_address = $dataPengguna[0]->alamat_lengkap;
                $bill_hp = $dataPengguna[0]->nomor_hp;
                $ongkir = $this->post('ongkir');
                $total = $this->post('ongkir') + $dataTransaksi[0]->total_belanja + $dataTransaksi[0]->biaya_asuransi;
        
                $data += array(
                    'informasi_pengguna' => $informasi_pengguna,
                    'courier' => $courier,
                    'courier_service' => $courier_service,
                    'courier_desc' => $courier_desc,
                    'courier_est' => $courier_est,
                    'bill_name' => $bill_name,
                    'bill_address' => $bill_address,
                    'bill_hp' => $bill_hp,
                    'ongkir' => $ongkir,
                    'payment_status' => 'pending',
                    'total' => $total,
                );
            }else{
                $total = $dataTransaksi[0]->total_belanja + $dataTransaksi[0]->biaya_asuransi;
        
                $data += array(
                    'total' => $total,
                    'payment_by' => 'KASIR',
                    'payment_status' => 'process'
                );
            }
    
            if($this->GeneralModel->update_general('ms_transaksi','id_transaksi',$id_transaksi,$data) == TRUE){
                // ms_voucher_terpakai 
                if(!empty($id_voucher_terpakai)){
                    $dataVoucher = array(
                        'waktu_pakai' => DATE('Y-m-d H:i:s'),
                        'id_transaksi' => $id_transaksi,
                        'uuid_toko' => $uuid_toko
                    );
                    $this->GeneralModel->update_general('ms_voucher_terpakai','id_voucher_terpakai',$id_voucher_terpakai,$dataVoucher);
                }
                return $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'id_transaksi' => $id_transaksi], 200);
            }else{
                return $this->response(['status' => false, 'message' => 'Mohon maaf transaksi tidak ditemukan'], 200);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Pengguna tidak ditemukan',
            ], RestController::HTTP_OK);
        }
    }

    public function addTransacationProdukDigital_post()
    {
        $produk_digital = $this->post('produk_digital');
		$detail = $this->GeneralModel->get_by_id_general('ms_produk_digital','id_produk_digital',$produk_digital);
        $login_token = $this->post('login_token');
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $login_token])->row();
        $buyerEmail = $cekPengguna->email;
		foreach ($detail as $key) {
                $transaksi = array(
                    'pelanggan' => $cekPengguna->id_pengguna,
                    'jenis_transaksi' => 'produk_digital',
                    'total_belanja' => $key->harga_jual,
                    'total' => $key->harga_jual,
                    'payment_status' => 'pending',
                    'created_by' => $cekPengguna->id_pengguna,
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
                    'nomor_digital' => $this->post('nomor'),
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

            if(!empty($detailTransaksi[0]->trx_id)){
                try {
                    $cekInvoice = cekInvoiceXendit($detailTransaksi[0]->trx_id);
                } catch (\Throwable $th) {
                    $cekInvoice = array(
                        'status' => 'EXPIRED'
                    );
                }
                if($cekInvoice['status']=='EXPIRED'){
                    $this->db->update('ms_transaksi',$transaksi,['id_transaksi' => $id_transaksi]);
                    if(invoiceDigitalXenditMobile($detailTransaksi[0],$data) == TRUE){
                        $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                        $invoice_url = $getData[0]->invoice_url;
                        $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $invoice_url], 200);
                    }
                }else{
                    $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                    $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $invoice_url], 200);
                }
            }else{
                $this->db->update('ms_transaksi',$transaksi,['id_transaksi' => $id_transaksi]);
                if(invoiceDigitalXenditMobile($detailTransaksi[0],$data) == TRUE){
                    $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                    $invoice_url = $getData[0]->invoice_url;
                    $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $invoice_url], 200);
                }
            }
        }
    }

    public function addTransacationProdukDigitalTokenListrik_post()
    {
        $produk_digital = $this->post('produk_digital');
		$detail = $this->GeneralModel->get_by_id_general('ms_produk_digital','id_produk_digital',$produk_digital);
        $login_token = $this->post('login_token');
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $login_token])->row();
        $buyerEmail = $cekPengguna->email;
		foreach ($detail as $key) {
                $transaksi = array(
                    'pelanggan' => $cekPengguna->id_pengguna,
                    'jenis_transaksi' => 'produk_digital',
                    'total_belanja' => $key->harga_jual,
                    'total' => $key->harga_jual,
                    'payment_status' => 'pending',
                    'created_by' => $cekPengguna->id_pengguna,
                    'keterangan' => 'Transaksi Token PLN'
                );

                $this->GeneralModel->create_general('ms_transaksi',$transaksi);
                $id_transaksi = $this->db->insert_id();
                
                $order = array(
                    'produk_digital' => $key->id_produk_digital,
                    'selling_price' => $key->harga_jual,
                    'capital_price' => $key->harga_modal,
                    'qty' => 1,
                    'subtotal' => $key->harga_jual,
                    'nomor_digital' => $this->post('nomor'),
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

            if(!empty($detailTransaksi[0]->trx_id)){
                try {
                    $cekInvoice = cekInvoiceXendit($detailTransaksi[0]->trx_id);
                } catch (\Throwable $th) {
                    $cekInvoice = array(
                        'status' => 'EXPIRED'
                    );
                }
                if($cekInvoice['status']=='EXPIRED'){
                    $this->db->update('ms_transaksi',$transaksi,['id_transaksi' => $id_transaksi]);
                    if(invoiceDigitalXenditMobile($detailTransaksi[0],$data) == TRUE){
                        $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                        $invoice_url = $getData[0]->invoice_url;
                        $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $invoice_url], 200);
                    }
                }else{
                    $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                    $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $invoice_url], 200);
                }
            }else{
                $this->db->update('ms_transaksi',$transaksi,['id_transaksi' => $id_transaksi]);
                if(invoiceDigitalXenditMobile($detailTransaksi[0],$data) == TRUE){
                    $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                    $invoice_url = $getData[0]->invoice_url;
                    $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $invoice_url], 200);
                }
            }
        }
    }

    public function checkOutProduct_post()
    {
        $id_transaksi = $this->post('id_transaksi');
        $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','login_token',$this->post('login_token'));
        $temp_transaksi = $this->post('temp_transaksi');
        $id_pengguna = $pengguna[0]->id_pengguna;
        $cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$id_transaksi,'pelanggan',$id_pengguna);
        if($cekTransaksi){
            if ($cekTransaksi[0]->payment_status == 'payed') {
                return $this->response(['status' => false, 'message' => 'Mohon maaf transaksi yang sudah diselesaikan tidak bisa dibatalkan'], 200);
            }else{
                $dataTransaksi = array(
                    'tipe_pembayaran' => "auto",
                    'rekening' => "0",
                    'pay_time' => DATE('Y-m-d H:i:s'),
                );

                $dataProduk = $this->post('dataProduk');
                $detailTransaksi = $this->GeneralModel->get_by_id_general("v_transaksi","id_transaksi",$id_transaksi);

                $data = [
                    'items' => []
                ];

                $totalPoint = 0;
                $totalHargaHP = 0;
                $jumlahBarangHp = 0;
                $totalHargaAcc = 0;
                $jumlahBarangAcc = 0;
                
                foreach ($dataProduk as $key) {
                    $cekMember = $this->GeneralModel->get_by_id_general('ms_member','id_pengguna',$pengguna[0]->id_pengguna);
                    if($cekMember){
                        $id_tipe_member = $cekMember[0]->id_tipe_member;
                        if($id_tipe_member == '3'){
                            $potongan = $key['potongan_member_gold'];
                        }elseif($id_tipe_member == '5'){
                            $potongan = $key['potongan_member_blue'];
                        }else{
                            $potongan = 0;
                        }
                    }else{
                        $id_tipe_member = null;
                        $potongan = 0;
                    }

                    $price = intval($key['selling_price'] * $key['qty'] - ($key['selling_price'] * $key['qty'] * $potongan / 100));
                    $item = [
                        'name' => $key['nama_pesanan'],
                        'price' => $price,
                        'quantity' => $key['qty']
                    ];
                    
                    $data['items'][] = $item;

                    if ($cekTransaksi[0]->jenis_transaksi != 'produk_digital') {
                        $cekProduk = $this->GeneralModel->get_by_id_general('ms_produk','id_produk',$key['produk']);
                        if($cekProduk[0]->kategori_produk == '1'){
                            $totalHargaHP += $key['selling_price']*$key['qty'];
                            $jumlahBarangHp += $key['qty'];
                        }elseif($cekProduk[0]->kategori_produk == '4'){
                            $totalHargaAcc += $key['selling_price']*$key['qty'];
                            $jumlahBarangAcc += $key['qty'];
                        }
                    }
                }

                if($totalHargaHP > 0){
                    $totalPoint += $totalHargaHP*1/100;
                }

                if($totalHargaAcc > 0){
                    $totalPoint += $totalHargaAcc*0.5/100;
                }




                if($totalPoint > 0){
                    $cek = $this->GeneralModel->get_by_multi_id_general('ms_riwayat_point','pengguna',$id_pengguna,'transaksi',$id_transaksi);
                    if($cek){
                        $this->GeneralModel->delete_general('ms_riwayat_point','pengguna',$id_pengguna,'transaksi',$id_transaksi);
                    }
                    $dataRiwayatPoint = array(
                        'pengguna' => $id_pengguna,
                        'transaksi' => $id_transaksi,
                        'point' => $totalPoint,
                        'keterangan_point' => 'Transaksi Produk',
                        'tgl_point' => DATE('Y-m-d H:i:s'),
                    );
                    $this->GeneralModel->create_general('ms_riwayat_point',$dataRiwayatPoint);
                }

                if (!empty($detailTransaksi[0]->courier)) {
                    $item = [
                        'name' => "Ongkir (" . $detailTransaksi[0]->courier . " " . $detailTransaksi[0]->courier_service . " " . $detailTransaksi[0]->courier_desc . ")",
                        'price' => $detailTransaksi[0]->ongkir,
                        'quantity' => 1
                    ];
                
                    $data['items'][] = $item;
                }

                // if(!empty($detailTransaksi[0]->potongan_belanja)){
                //     $item = [
                //         'name' => "Potongan Belanja",
                //         'price' => $detailTransaksi[0]->potongan_belanja*-1,
                //         'quantity' => 1
                //     ];
                
                //     $data['items'][] = $item;
                // }
                $buyerEmail = $detailTransaksi[0]->email;

                // if(empty($buyerEmail)){
                //     return $this->response(['status' => false, 'message' => 'Mohon maaf email anda tidak ditemukan'], 200);
                // }else{
                    if(!empty($detailTransaksi[0]->trx_id)){
                        try {
                            $cekInvoice = cekInvoiceXendit($detailTransaksi[0]->trx_id);
                        } catch (\Throwable $th) {
                            $cekInvoice = array(
                                'status' => 'EXPIRED'
                            );
                        }
                        if($cekInvoice['status']=='EXPIRED'){
                            $this->db->update('ms_transaksi',$dataTransaksi,['id_transaksi' => $id_transaksi]);
                            if($cekTransaksi[0]->jenis_transaksi == 'produk_digital'){
                                if(invoiceDigitalXenditMobile($detailTransaksi[0],$data) == TRUE){
                                    $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                                    $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                                }
                            }else{
                                if(invoiceLinkXenditMobile($detailTransaksi[0],$buyerEmail,$data) == TRUE){
                                    $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                                    $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                                }
                            }
                        }else{
                            $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                            $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                        }
                    }else{
                        $this->db->update('ms_transaksi',$dataTransaksi,['id_transaksi' => $id_transaksi]);
                        if($cekTransaksi[0]->jenis_transaksi == 'produk_digital'){
                            if(invoiceDigitalXenditMobile($detailTransaksi[0],$data) == TRUE){
                                $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                                $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                            }
                        }else{
                            if(invoiceLinkXenditMobile($detailTransaksi[0],$buyerEmail,$data) == TRUE){
                                $getData = $this->GeneralModel->get_by_id_general('ms_transaksi','id_transaksi',$id_transaksi);
                                $this->response(['status' => true, 'message' => 'Transaksi berhasil dibuat', 'data' => $getData], 200);
                            }
                        }
                    }
                // }	
            }
        }else{
            return $this->response(['status' => false, 'message' => 'Mohon maaf transaksi tidak ditemukan'], 200);
        }
    }

    public function getTransaksiProduct_get()
    {
        $login_token = $this->get('login_token');
        $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','login_token',$login_token);
        $id_pengguna = $pengguna[0]->id_pengguna;
        $dataTransaksiProduk = $this->db->query("SELECT * FROM v_transaksi WHERE v_transaksi.pelanggan = '$id_pengguna' AND v_transaksi.jenis_transaksi = 'produk' OR v_transaksi.jenis_transaksi = 'produk_digital' ORDER BY id_transaksi DESC")->result();

        if($dataTransaksiProduk){
            $dataOrder = array();
            foreach ($dataTransaksiProduk as $key) {
                $dataOrder[] = $this->GeneralModel->get_by_id_general('v_order','transaksi',$key->id_transaksi);
            }
            $data = array(
                'transaksi' => $dataTransaksiProduk,
                'order' => $dataOrder
            );
            return $this->response(['status' => true, 'message' => 'Data Ditemukan', 'data' => $data], 200);
        }else{
            return $this->response(['status' => false, 'message' => 'Data Tidak Ditemukan'], 200);
        }
    }


    public function getCountChart_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $login_token = $this->get('login_token');
        $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','login_token',$login_token);
        if($pengguna){
            $id_pengguna = $pengguna[0]->id_pengguna;
            $tempTransaksi = $this->db->query("SELECT * FROM ms_temp_transaksi WHERE pelanggan = '$id_pengguna'")->row();
            if(empty($tempTransaksi)){
                $this->response(['status' => false, 'message' => 'Tidak Ada Data'], 200);
            }else{
                $cekTemp = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$tempTransaksi->id_temp_transaksi);
                if($cekTemp){
                   $count = $this->db->query("SELECT COUNT(*) as total FROM v_order WHERE temp_transaksi = '$tempTransaksi->id_temp_transaksi'")->row();
                     $this->response(['status' => true, 'message' => 'Data Ditemukan', 'data' => $count], 200);
                }else{
                    $this->response(['status' => false, 'message' => 'Tidak Ada Data'], 200);
                }
            }
        }
    }

    public function detailTransaksiPembayaran_get()
    {
        $id_transaksi = $this->get('id_transaksi');
        $dataTransaksi = $this->GeneralModel->get_by_id_general('v_transaksi','id_transaksi',$id_transaksi);
        $dataOrder = $this->GeneralModel->get_by_id_general('v_order','transaksi',$id_transaksi);
        $totalPoint = 0;
        $totalHargaHP = 0;
        $jumlahBarangHp = 0;
        $totalHargaAcc = 0;
        $jumlahBarangAcc = 0;
        foreach ($dataOrder as $key) {
            $cekProduk = $this->GeneralModel->get_by_id_general('ms_produk','id_produk',$key->produk);
            if ($cekProduk[0]->kategori_produk == '1') {
                $totalHargaHP += $key->subtotal;
                $jumlahBarangHp += $key->qty;
            } elseif ($cekProduk[0]->kategori_produk == '4') {
                $totalHargaAcc += $key->subtotal;
                $jumlahBarangAcc += $key->qty;
            }
        }

        if ($totalHargaHP > 0) {
            $totalPoint += $totalHargaHP * 1 / 100;
        }

        if ($totalHargaAcc > 0) {
            $totalPoint += $totalHargaAcc * 0.5 / 100;
        }
        $data = array(
            'transaksi' => $dataTransaksi,
            'order' => $dataOrder,
            'totalPoint' => $totalPoint,
        );
        if($dataTransaksi){
            return $this->response(['status' => true, 'message' => 'Data Ditemukan', 'data' => $data], 200);
        }else{
            return $this->response(['status' => false, 'message' => 'Data Tidak Ditemukan'], 200);
        }
    }

    public function sendNotification_post()
    {
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $login_token = $this->post('login_token');
        $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','login_token',$login_token);
        if($pengguna){
            $id_pengguna = $pengguna[0]->id_pengguna;
            $cekDeviceId = $this->GeneralModel->get_by_id_general('ms_device_notif','id_pengguna',$id_pengguna);
            $title = $this->post('title');
            $body = $this->post('body');
            $messages = [
                new ExpoMessage([
                    'title' => $title,
                    'body' => $body,
                ]),
            ];

            $pesan = array(
                'title' => $title,
                'body' => $body,
                'id_pengguna' => $id_pengguna,
                'created_at' => DATE('Y-m-d H:i:s')
            );

            foreach ($cekDeviceId as $key) {
                if(!empty($key->deviceid)){
                    $defaultRecipients = [
                        $key->deviceid
                    ];
                    (new Expo)->send($messages)->to($defaultRecipients)->push();

                    return $this->response(['status' => true, 'message' => 'Notifikasi berhasil dikirim', 'data' => $messages, 'device' => $defaultRecipients], 200);
                }
            }
        }
    }

    public function getDetailPembayaran_get()
    {
        $id_transaksi = $this->get('id_transaksi');

        $dataTransaksi = $this->GeneralModel->get_by_id_general('v_transaksi','id_transaksi',$id_transaksi);

        if($dataTransaksi){
            return $this->response(['status' => true, 'message' => 'Data Ditemukan', 'data' => $dataTransaksi], 200);
        }else{
            return $this->response(['status' => false, 'message' => 'Data Tidak Ditemukan'], 200);
        }
    }

    public function konfirmasiPembayaran_post()
    {
        $id_transaksi = $this->post('id_transaksi');
        $data = array(
            'tipe_pembayaran' => 'manual',
            'payment_status' => 'process',
            'payment_by' => $this->post('payment_by'),
            'payment_channel' => $this->post('payment_channel'),
            'va_number' => $this->post('va_number'),
        );

        if(empty($data['payment_channel'])){
            $data += array(
                'rekening_bank' => 1
            );
        }

        if (empty($_FILES['bukti_transfer'])) {
            $this->response([
                'status' => FALSE,
                'message' => 'Lampiran tiket tidak boleh kosong',
            ], RestController::HTTP_OK);
        } else {
            $config['upload_path']          = 'assets/img/buktiTransfer/';
		    $config['allowed_types']        = 'jpg|png|jpeg';
            $this->load->library('upload');
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('bukti_transfer')) {
            } else {
                $data += array('bukti_transfer' => $config['upload_path'] . $this->upload->data('file_name'));
            }
        }

        if($this->GeneralModel->update_general('ms_transaksi','id_transaksi',$id_transaksi,$data) == TRUE){
            $dataProduk = $this->GeneralModel->get_by_id_general("v_order","transaksi",$id_transaksi);
            $detailTransaksi = $this->GeneralModel->get_by_id_general("v_transaksi","id_transaksi",$id_transaksi);
            $id_pengguna = $detailTransaksi[0]->pelanggan;

            $totalPoint = 0;
            $totalHargaHP = 0;
            $jumlahBarangHp = 0;
            $totalHargaAcc = 0;
            $jumlahBarangAcc = 0;
            
            foreach ($dataProduk as $key) {
                $cekProduk = $this->GeneralModel->get_by_id_general('ms_produk','id_produk',$key->produk);
                if($cekProduk[0]->kategori_produk == '1'){
                    $totalHargaHP += $key->selling_price*$key->qty;
                    $jumlahBarangHp += $key->qty;
                }elseif($cekProduk[0]->kategori_produk == '4'){
                    $totalHargaAcc += $key->selling_price*$key->qty;
                    $jumlahBarangAcc += $key->qty;
                }
            }

            if($totalHargaHP > 0){
                $totalPoint += $totalHargaHP*1/100;
            }

            if($totalHargaAcc > 0){
                $totalPoint += $totalHargaAcc*0.5/100;
            }



            if($totalPoint > 0){
                $cek = $this->GeneralModel->get_by_multi_id_general('ms_riwayat_point','pengguna',$id_pengguna,'transaksi',$id_transaksi);
                if($cek){
                    $this->GeneralModel->delete_general('ms_riwayat_point','pengguna',$id_pengguna,'transaksi',$id_transaksi);
                }
                $dataRiwayatPoint = array(
                    'pengguna' => $id_pengguna,
                    'transaksi' => $id_transaksi,
                    'point' => $totalPoint,
                    'keterangan_point' => 'Transaksi Produk',
                    'tgl_point' => DATE('Y-m-d H:i:s'),
                );
                $this->GeneralModel->create_general('ms_riwayat_point',$dataRiwayatPoint);
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Konfirmasi berhasil dibuat',
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Konfirmasi gagal dibuat',
            ], RestController::HTTP_OK);
        }
    }

    public function cancelOrder_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $login_token = $this->get('login_token');
        $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','login_token',$login_token);
        if($pengguna){
            $id_transaksi = $this->get('id_transaksi');
            $data = array(
                'payment_status' => 'cancel',
            );
            if($this->GeneralModel->update_general('ms_transaksi','id_transaksi',$id_transaksi,$data) == TRUE){
                $transaksi = $this->db->query("SELECT * FROM ms_transaksi WHERE id_transaksi = '$id_transaksi'")->row();
                $cekVoucher = $this->db->query("SELECT * FROM ms_voucher_terpakai WHERE id_transaksi = '$id_transaksi'")->row();
                if(!empty($transaksi->trx_id)){
                    closeInvoiceXendit($transaksi->trx_id);
                    if($cekVoucher){
                        $this->db->query("UPDATE ms_voucher_terpakai SET waktu_pakai=NULL, id_transaksi=NULL WHERE pengguna='$transaksi->pelanggan' and id_transaksi='$id_transaksi'");
                    }
                }else{
                    if($cekVoucher){
                        $this->db->query("UPDATE ms_voucher_terpakai SET waktu_pakai=NULL, id_transaksi=NULL WHERE pengguna='$transaksi->pelanggan' and id_transaksi='$id_transaksi'");
                    }
                }
                $this->response([
                    'status' => TRUE,
                    'message' => 'Pesanan berhasil dibatalkan',
                    'data' => $data
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Pesanan gagal dibatalkan',
                    'error' => $this->db->error()
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Pengguna Tidak ditemukan'], 404);
        }
    }

    public function getPointTransaksi_post()
    { 
        $data = $this->post('data');
        if (!empty($data) && is_array($data)) {
            $totalPoint = 0;
            $totalHargaHP = 0;
            $jumlahBarangHp = 0;
            $totalHargaAcc = 0;
            $jumlahBarangAcc = 0;

          
            foreach ($data as $key) {
                $cekOrder = $this->GeneralModel->get_by_id_general('v_order','id_order',$key['id_order']);
                if (!empty($cekOrder)) {
                    $cekProduk = $this->GeneralModel->get_by_id_general('ms_produk','id_produk',$cekOrder[0]->produk);
                    if ($cekProduk[0]->kategori_produk == '1') {
                        $totalHargaHP += $key['subtotal'];
                        $jumlahBarangHp += $key['qty'];
                    } elseif ($cekProduk[0]->kategori_produk == '4') {
                        $totalHargaAcc += $key['subtotal'];
                        $jumlahBarangAcc += $key['qty'];
                    }
                }
            }

            if ($totalHargaHP > 0) {
                $totalPoint += $totalHargaHP * 1 / 100;
            }

            if ($totalHargaAcc > 0) {
                $totalPoint += $totalHargaAcc * 0.5 / 100;
            }

            $this->response(['status' => true, 'message' => 'Data Ditemukan', 'data' => $totalPoint], 200);
        } else {
            $this->response(['status' => false, 'message' => 'Mohon maaf produk tidak ditemukan'], 200);
        }
    }

   
   
}