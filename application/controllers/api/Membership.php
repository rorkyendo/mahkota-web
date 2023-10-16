<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use PhpMqtt\Client\Exceptions\MQTTClientException;
use PhpMqtt\Client\MQTTClient;

class Membership extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->koneksi = $this->GeneralModel->get_by_id_general('ms_koneksi', 'id_koneksi', 1);
    }

    public function listTipeMembership_get()
    {
        // EndPoint : base_url/api/membership/listTipeMembership (GET)
        //--------------------- List Tipe Membership -------------------//
        $getTipeMembership = $this->db->query("SELECT *,ms_tipe_member.id_tipe_member as id_tipe_member FROM ms_tipe_member WHERE status_tipe = 'active'");
        $getTipeMembership = $getTipeMembership->result();
        $this->response([
            'status' => TRUE,
            'data' => $getTipeMembership
        ], RestController::HTTP_OK);
        //--------------------- List Tipe Membership -------------------//
    }

    public function getDetailMembership_get()
    {
        // EndPoint : base_url/api/membership/getDetailMembership (GET)
        //--------------------- Get Detail Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();

        if($cekPengguna){
            $getMembership = $this->db->query("SELECT * FROM ms_member WHERE id_pengguna = '$cekPengguna->id_pengguna' AND status= 'active'")->row();

            $this->response([
                'status' => TRUE,
                'data' => $getMembership
            ], RestController::HTTP_OK);
        }else{
            $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan'], 401);
        }
        //--------------------- Get Detail Membership -------------------//
    }

    public function listMembership_post()
    {
        // EndPoint : base_url/api/membership/listMembership (POST)
        //--------------------- List Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $id_pengguna = $this->post('id_pengguna');
        $getMembership = $this->db->query("SELECT * FROM v_member WHERE id_pengguna = '$id_pengguna'");
        $getMembership = $getMembership->result();
        $this->response([
            'status' => TRUE,
            'data' => $getMembership
        ], RestController::HTTP_OK);
        //--------------------- List Membership -------------------//
    }

    public function registerMember_post()
    {
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){
            $getTipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',$this->post('id_tipe_member'));
            if ($getTipeMember) {
                $cekUserMember = $this->GeneralModel->get_by_multi_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna,'status','active');
                if ($cekUserMember) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'User Sudah Terdaftar'
                    ], RestController::HTTP_OK);
                }else{
                    foreach ($getTipeMember as $key) {
                        $dataTransaksi = array(
                            'pelanggan' => $cekPengguna->id_pengguna,
                            'total' => $key->biaya_pendaftaran,
                            'payment_status' => 'pending',
                            'created_by' => $cekPengguna->id_pengguna,
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
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Terima kasih sudah mendaftar menjadi member kami, silahkan selesaikan pembayaran untuk mengatifkan status member anda!'
                        ], RestController::HTTP_OK);
                    }
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Tipe Member Tidak Ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function bankManual_get()
    {
        // EndPoint : base_url/api/membership/bankManual (GET)
        //--------------------- Bank Manual -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $getBank = $this->GeneralModel->get_by_id_general('ms_rekening','status_rekening','Y');
            if ($getBank) {
                $this->response([
                    'status' => TRUE,
                    'data' => $getBank
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Bank Tidak Ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        //--------------------- Bank Manual -------------------//
    }

    public function batalkanTransaksiMembership_post()
    {
        // EndPoint : base_url/api/membership/batalkanTransaksiMembership (POST)
        //--------------------- Batalkan Transaksi Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){
            $getTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$this->post('id_transaksi'),'pelanggan',$cekPengguna->id_pengguna);
            if($getTransaksi){
                if ($getTransaksi[0]->payment_status == 'payed') {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Mohon maaf transaksi yang sudah diselesaikan tidak bisa dibatalkan'
                    ], RestController::HTTP_OK);
                }else{
                    $this->GeneralModel->delete_general('ms_transaksi','id_transaksi',$this->post('id_transaksi'));
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Selamat transaksi berhasil dibatalkan'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function detailTransaksiMembership_post()
    {
        // EndPoint : base_url/api/membership/detailTransaksiMembership (POST)
        //--------------------- Detail Transaksi Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){
            $cekTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','id_transaksi',$this->post('id_transaksi'),'pelanggan',$cekPengguna->id_pengguna);
            if($cekTransaksi){
                $this->response([
                    'status' => TRUE,
                    'data' => $cekTransaksi
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        //--------------------- Detail Transaksi Membership -------------------//
    }

    public function listTransaksiMembership_get()
    {
        // EndPoint : base_url/api/membership/listTransaksiMembership (GET)
        //--------------------- List Transaksi Membership -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $getTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna,'jenis_transaksi','member');
            // $getTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna,'payment_status','pending');
            if ($getTransaksi) {
                $this->response([
                    'status' => TRUE,
                    'data' => $getTransaksi
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        //--------------------- List Transaksi Membership -------------------//
    }

    public function listTransaksiCart_get()
    {
        // EndPoint : base_url/api/membership/listTransaksiMembership (GET)
        //--------------------- List Transaksi Membership -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $getTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna,'jenis_transaksi','produk');
            // $getTransaksi = $this->GeneralModel->get_by_multi_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna,'payment_status','pending');
            if ($getTransaksi) {
                $this->response([
                    'status' => TRUE,
                    'data' => $getTransaksi
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        //--------------------- List Transaksi Membership -------------------//
    }

    public function listTransaksiMembershipPending_get()
    {
        // EndPoint : base_url/api/membership/listTransaksiMembership (GET)
        //--------------------- List Transaksi Membership -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            // $getTransaksi = $this->GeneralModel->get_by_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna);
            $getTransaksi = $this->GeneralModel->get_by_triple_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna,'payment_status','pending','jenis_transaksi','member');
            if ($getTransaksi) {
                $this->response([
                    'status' => TRUE,
                    'data' => $getTransaksi
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        //--------------------- List Transaksi Membership -------------------//
    }

    public function listTransaksiCartPending_get()
    {
        // EndPoint : base_url/api/membership/listTransaksiMembership (GET)
        //--------------------- List Transaksi Membership -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            // $getTransaksi = $this->GeneralModel->get_by_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna);
            $getTransaksi = $this->GeneralModel->get_by_triple_id_general('v_transaksi','pelanggan',$cekPengguna->id_pengguna,'payment_status','pending','jenis_transaksi','produk');
            if ($getTransaksi) {
                $this->response([
                    'status' => TRUE,
                    'data' => $getTransaksi
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        //--------------------- List Transaksi Membership -------------------//
    }

    public function listOrderCartPending_get()
    {
        // EndPoint : base_url/api/membership/listOrderCartPending (GET)
        //--------------------- List Transaksi Membership -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $getTransaksi = $this->db->query("SELECT * FROM v_order LEFT JOIN ms_temp_transaksi ON ms_temp_transaksi.id_temp_transaksi = v_order.temp_transaksi WHERE ms_temp_transaksi.pelanggan = '$cekPengguna->id_pengguna' AND ms_temp_transaksi.jenis_transaksi = 'produk'  AND ( v_order.payment_status IS NULL OR v_order.payment_status = 'pending' )")->result();
            // $getTransaksi = $this->GeneralModel->get_by_triple_id_general('v_order','pelanggan',$cekPengguna->id_pengguna,'payment_status','pending','jenis_transaksi','produk');
            if ($getTransaksi) {
                $this->response([
                    'status' => TRUE,
                    'data' => $getTransaksi
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        //--------------------- List Transaksi Membership -------------------//
    }

    

    public function pembayaranMembership_post()
    {
        // EndPoint : base_url/api/membership/pembayaranMembership (POST)
        //--------------------- Pembayaran Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){
            $cekTransaksi = $this->GeneralModel->get_by_multi_id_general('ms_transaksi','id_transaksi',$this->post('id_transaksi'),'pelanggan',$cekPengguna->id_pengguna);
            if ($cekTransaksi[0]->payment_status == 'payed') {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Mohon maaf transaksi yang sudah diselesaikan tidak bisa dibatalkan'
                ], RestController::HTTP_OK);
            }else{
                $dataTransaksi = array(
					'tipe_pembayaran' => $this->post('tipe_pembayaran'),
					'rekening' => $this->post('rekening'),
					'pay_time' => DATE('Y-m-d H:i:s'),
				);

				if($cekTransaksi[0]->jenis_transaksi == 'member'){
					$dataTransaksi += array(
						'payment_status' => 'process'
					);
				}
				//---------------- BUKTI PEMBAYARAN ---------------//
				$config['upload_path']          = 'assets/img/buktiTransfer/';
				$config['allowed_types']        = '*';
				$config['max_size']             = 1000000;

				$this->upload->initialize($config);

				if (!$this->upload->do_upload('bukti_transfer')) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Gagal Untuk Melakukan Pembayaran'
                    ], RestController::HTTP_OK);
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

                    $staff = $this->db->query("SELECT p.*,pp.perangkat FROM ms_pengguna p LEFT JOIN v_perangkat_pengguna pp ON pp.id_pengguna = p.id_pengguna
                    WHERE hak_akses='superuser' or hak_akses='superowner' or hak_akses='finance' or hak_akses='admin database'")->result();

                    foreach($staff as $s){
                        $dataByPass = array(
                            'pengguna' => $s->id_pengguna,
                            'notif' => 'verif_member',
							'status' => 'N',
							'id_notif' => $this->post('id_transaksi')
                        );
                        $this->GeneralModel->create_general('ms_bypass_login_notif',$dataByPass);
                        $id = $this->db->insert_id();

                        $message = 'Halo *'.$s->nama_lengkap.'*!';
    					$message .= urlencode("\n"); 
                        $message .= "Ada member baru mendaftar!";
    					$message .= urlencode("\n"); 
                        $message .= "Klik link berikut ini untuk melakukan konfirmasi ";
    					$message .= urlencode("\n");
                        // if ($s->perangkat == 'Iphone') {
                            $message .= base_url('auth/bpLog/'.my_simple_crypt($id,'e'));
                            sendNotifWA2($s->no_telp,$message);
                        // }else{
                            // $url = base_url('auth/bpLog/'.my_simple_crypt($id,'e'));
                            // sendNotifWAButton($s->no_telp,$message,"Klik disini",$url,'Link Konfirmasi');
                        // }
                    }

                    $cekReferral = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$cekPengguna->id_pengguna);
                    if(!empty($cekReferral[0]->referral)){
                        $userReferral = $this->GeneralModel->get_by_id_general('ms_member','barcode_member',$cekReferral[0]->referral);
                        if(!empty($userReferral)){
                            $referralId = $userReferral[0]->id_pengguna;
                            $cekRiwayat = $this->GeneralModel->get_by_multi_id_general('ms_riwayat_point','pengguna',$referralId,'transaksi',$this->post('id_transaksi'));
                            if(empty($cekRiwayat)){
                                $cekPoinTipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',$userReferral[0]->id_tipe_member);
                                $dataRiwayat = array(
                                    'pengguna' => $referralId,
                                    'downliner' => $cekPengguna->id_pengguna,
                                    'transaksi' => $this->post('id_transaksi'),
                                    'point' => $cekPoinTipeMember[0]->dapat_point,
                                    'keterangan_point' => 'Member',
                                    'tgl_point' => DATE('Y-m-d H:i:s'),
                                );
                                $this->GeneralModel->create_general('ms_riwayat_point',$dataRiwayat);
                            }
                        }
                    }

                    $this->GeneralModel->update_general('ms_transaksi','id_transaksi',$this->post('id_transaksi'),$dataTransaksi);
                    //---------------- BUKTI PEMBAYARAN ---------------//
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Pembayaran Berhasil'
                    ], RestController::HTTP_OK);
				}
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function promoBuy1Get1_post()
    {
        // EndPoint : base_url/api/membership/promoBuy1Get1 (POST)
        //--------------------- Pembayaran Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){

            $kode_klaim = random_string('alnum', );
			$cekKodeKlaim = $this->GeneralModel->get_by_id_general('ms_buy1get1','kode_klaim',$kode_klaim);
			while($cekKodeKlaim){
				$kode_klaim = random_string('alnum', 10);
				$cekKodeKlaim = $this->GeneralModel->get_by_id_general('ms_buy1get1','kode_klaim',$kode_klaim);
			}

            $dataPay1Get1 = array(
                'pelanggan' => $cekPengguna->id_pengguna,
                'status_klaim' => 'pending',
                'created_by' => $cekPengguna->id_pengguna,
                'kode_klaim' => strtoupper("NOT FOUND")
            );

            //---------------- BUKTI PEMBAYARAN ---------------//
            $config['upload_path']          = 'assets/img/buktiTransfer/';
            $config['allowed_types']        = '*';
            $config['max_size']             = 1000000;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto_struk')) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Gagal Untuk Memproses Pay 1 Get 1'
                ], RestController::HTTP_OK);
            } else {
                $dataPay1Get1 += array('foto_struk' => $config['upload_path'] . $this->upload->data('file_name'));
                $this->GeneralModel->create_general('ms_buy1get1',$dataPay1Get1);
                $id_buy1 = $this->db->insert_id();

                $staff = $this->db->query("SELECT p.*,pp.perangkat FROM ms_pengguna p LEFT JOIN v_perangkat_pengguna pp ON pp.id_pengguna = p.id_pengguna
                WHERE hak_akses='superuser' or hak_akses='superowner' or hak_akses='finance' or hak_akses='admin database'")->result();

                foreach($staff as $s){
                    $dataByPass = array(
                        'pengguna' => $s->id_pengguna,
                        'notif' => 'buy1get1',
                        'status' => 'N',
                        'id_notif' => $id_buy1
                    );
                    $this->GeneralModel->create_general('ms_bypass_login_notif',$dataByPass);
                    $id = $this->db->insert_id();
    
                    $message = 'Halo *'.$s->nama_lengkap.'*!';
                    $message .= urlencode("\n"); 
                    $message .= "Ada Pengajuan Promo Buy 1 Get 1!";
                    $message .= "KODE KLAIM : *".strtoupper($kode_klaim)."*";
                    $message .= urlencode("\n"); 
                    $message .= "Klik link berikut ini untuk masuk ke daftar pengajuan!";
                    $message .= urlencode("\n"); 
                    $message .= base_url('auth/bpLog/'.my_simple_crypt($id,'e'));
                    sendNotifWA2($s->no_telp,$message);  
                }


                //---------------- BUKTI PEMBAYARAN ---------------//
                $this->response([
                    'status' => TRUE,
                    'message' => 'Pengajuan Promo Buy 1 Get 1 Berhasil'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }


    public function upgradeMembership_post()
    {
        // EndPoint : base_url/api/membership/upgradeMembership (POST)
        //--------------------- Upgrade Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){
            $getTipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',$this->post('id_tipe_member'));
            //Cek Membership tidak boleh sama
            if ($getTipeMember) {
                $cekUserMember = $this->GeneralModel->get_by_multi_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna,'status','active');
                if($cekUserMember){
                    if($cekUserMember[0]->id_tipe_member == $this->post('id_tipe_member')){
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Mohon maaf anda sudah menjadi member ini'
                        ], RestController::HTTP_OK);
                    }else{
                        foreach ($getTipeMember as $key) {
                            $dataTransaksi = array(
                                'pelanggan' => $cekPengguna->id_pengguna,
                                'total' => $key->biaya_upgrade,
                                'payment_status' => 'pending',
                                'created_by' => $cekPengguna->id_pengguna,
                                'tipe_transaksi' => 'umum',
                                'jenis_transaksi' => 'member',
                            );
                            $this->GeneralModel->create_general('ms_transaksi',$dataTransaksi);
                            $id_transaksi = $this->db->insert_id();
                            $dataOrder = array(
                                'tipe_member' => $key->id_tipe_member,
                                'qty' => 1,
                                'selling_price' => $key->biaya_upgrade,
                                'capital_price' => 0,
                                'subtotal' => $key->biaya_upgrade,
                                'transaksi' => $id_transaksi
                            );
                            $this->GeneralModel->create_general('ms_order',$dataOrder);	                    
                            $this->response([
                                'status' => TRUE,
                                'message' => 'Terima kasih sudah mengupgrade membership, silahkan selesaikan pembayaran untuk mengatifkan status member anda yang baru!'
                            ], RestController::HTTP_OK);
                        }
                    }
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Anda belum memiliki membership'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Tipe membership tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function perpanjangMembership_post()
    {
        // EndPoint : base_url/api/membership/perpanjangMembership (POST)
        //--------------------- Perpanjang Membership -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){
            $getTipeMember = $this->GeneralModel->get_by_id_general('ms_tipe_member','id_tipe_member',$this->post('id_tipe_member'));
            //Cek Membership tidak boleh yang lain
            if ($getTipeMember) {
                $cekUserMember = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna);
                if($cekUserMember){
                    if($cekUserMember[0]->id_tipe_member == $this->post('id_tipe_member')){
                        foreach ($getTipeMember as $key) {
                            $dataTransaksi = array(
                                'pelanggan' => $cekPengguna->id_pengguna,
                                'total' => $key->biaya_pendaftaran,
                                'payment_status' => 'pending',
                                'created_by' => $cekPengguna->id_pengguna,
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
                            $this->response([
                                'status' => TRUE,
                                'message' => 'Terima kasih sudah memperpanjang membership, silahkan selesaikan pembayaran untuk mengatifkan status member anda yang baru!'
                            ], RestController::HTTP_OK);
                        }
                    }else{
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Mohon maaf anda tidak menjadi member ini'
                        ], RestController::HTTP_OK);
                    }
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Anda belum memiliki membership'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Tipe membership tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function jumlahPoinMembership_get()
    {
        // EndPoint : base_url/api/membership/jumlahPoinMembership (GET)
        //--------------------- Jumlah Poin Membership -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekUserMember = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna);
            if($cekUserMember){
                $sumPoint = $this->db->query("SELECT SUM(jumlah_point) as total_point FROM ms_point_transaksi WHERE pengguna = '".$cekPengguna->id_pengguna."'")->row();
                if($sumPoint->total_point == null){
                    $sumPoint->total_point = 0;
                }
                $this->response([
                    'status' => TRUE,
                    'point' => $sumPoint->total_point
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki membership'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function riwayatTransaksiMembership_get()
    {
        // EndPoint : base_url/api/membership/riwayatTransaksiMembership (GET)
        //--------------------- Riwayat Transaksi Membership -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekUserMember = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna);
            if($cekUserMember){
                $getTransaksi = $this->GeneralModel->get_by_id_general('ms_point_transaksi','pengguna',$cekPengguna->id_pengguna);
                if($getTransaksi){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Riwayat Transaksi Membership Anda',
                        'data' => $getTransaksi
                    ], RestController::HTTP_OK);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Anda belum memiliki riwayat transaksi'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki membership'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function daftarKupon_get()
    {
        // EndPoint : base_url/api/membership/daftarKupon (GET)
        //--------------------- Daftar Kupon -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekUserMember = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna);
            if($cekUserMember){
                $kupon = array();
                $getKupon = $this->db->query("SELECT * FROM ms_kupon WHERE (tipe_member = '".$cekUserMember[0]->id_tipe_member."' OR tipe_member IS NULL) AND status = 'Y' AND CURDATE() <= berlaku_hingga AND jml_kupon > 0")->result();
                if($getKupon){
                    foreach($getKupon as $k){
                        $jml_kupon = $k->jml_kupon;
                        $getKuponTerpakai = $this->db->query("SELECT COUNT(*) as jml_kupon_terpakai FROM ms_voucher_terpakai WHERE kode_voucher = '".$k->kode_kupon."'")->row();
                        $jml_kupon_terpakai = $getKuponTerpakai->jml_kupon_terpakai;
                        if($jml_kupon > $jml_kupon_terpakai){
                            $kupon[] = $k;
                        }
                    }
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Daftar Kupon',
                        'data' => $kupon
                    ], RestController::HTTP_OK);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Tidak ada kupon yang tersedia'
                    ], RestController::HTTP_OK);
                }
            }else{
                $getKupon = $this->db->query("SELECT * FROM ms_kupon WHERE tipe_member IS NULL AND status = 'Y' AND CURDATE() <= berlaku_hingga AND jml_kupon > 0")->result();
                if($getKupon){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Daftar Kupon',
                        'data' => $getKupon
                    ], RestController::HTTP_OK);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Tidak ada kupon yang tersedia'
                    ], RestController::HTTP_OK);
                }
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function detailKupon_get()
    {
        // EndPoint : base_url/api/membership/detailKupon (GET)
        //--------------------- Detail Kupon -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekUserMember = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna);
            if($cekUserMember){
                $getKupon = $this->GeneralModel->get_by_id_general('ms_kupon','id_kupon',$this->get('id_kupon'));
                if($getKupon){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Detail Kupon',
                        'data' => $getKupon
                    ], RestController::HTTP_OK);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Kupon tidak ditemukan'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki membership'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function daftarProdukRedeem_get()
    {
        // EndPoint : base_url/api/membership/daftarProdukRedeem (GET)
        //--------------------- Daftar Produk Redeem -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekUserMember = $this->GeneralModel->get_by_id_general('v_member','id_pengguna',$cekPengguna->id_pengguna);
            if($cekUserMember){
                $getProduk = $this->db->query("SELECT * FROM ms_produk_redeem WHERE status = 'Y'")->result();
                if($getProduk){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Daftar Produk Redeem',
                        'data' => $getProduk
                    ], RestController::HTTP_OK);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Tidak ada produk redeem yang tersedia'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki membership'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function notificationKupon_post()
    {
        $apiKey = "mahkotastore@pushnotification";

        if($this->post('apiKey') == $apiKey){
            $to = $this->post('to');
            $title = $this->post('title');
            $body = $this->post('body');
            $subtitle = $this->post('subtitle');

            // To do so, send a POST request to https://exp.host/--/api/v2/push/send with the following HTTP headers:
            $headers = array(
                'Accept: application/json',
                'Accept-Encoding: gzip, deflate',
                'Content-Type: application/json',
                'Host: exp.host'
            );
            $data = array(
                'to' => $to,
                'title' => $title,
                'body' => $body,
                'subtitle' => $subtitle
            );
            $ch = curl_init('https://exp.host/--/api/v2/push/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            curl_close($ch);
            $this->response([
                'status' => TRUE,
                'message' => 'Notification Berhasil Dikirim'
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan APi Key'
            ], RestController::HTTP_OK);
        }
    }

    public function detailMembership_get()
    {
        // EndPoint : base_url/api/membership/detailMembership (GET)
        //--------------------- Detail Membership -------------------//
        $member = $this->input->get('member');

        $cekMember = $this->db->query("SELECT * FROM v_member WHERE barcode_member_encrypt = '$member'")->row();
        if($cekMember){
            $this->response([
                'status' => TRUE,
                'message' => 'Detail Membership',
                'data' => $cekMember
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Member tidak ditemukan'
            ], RestController::HTTP_OK);
        }
    }

    public function allMembership_get()
    {
        // EndPoint : base_url/api/membership/detailMembership (GET)
        //--------------------- Detail Membership -------------------//
        $token = $this->input->get('token');
        $status = $this->input->get('status');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $cekToken = $this->db->query("SELECT * FROM ms_toko WHERE uuid_toko='$token'")->row();
        if ($cekToken) {
            if (!empty($status)) {
                $this->db->where('status', $status);
            }if (!empty($start_date)) {
                $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
            }if (!empty($end_date)) {
                $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
            }          
            $cekMember = $this->db->get("v_member")->result();

            if($cekMember){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Seluruh Membership',
                    'data' => $cekMember
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Silahkan masukkan token'
            ], 401);
        }
    }

    public function voucherKasir_post()
    {
        // EndPoint : base_url/api/membership/getVoucherKasir (GET)
        //--------------------- Voucher Membership -------------------//
        $token = $this->post('token');
        $member = $this->post('member');
        $kode_voucher = $this->post('kode_voucher');

        $cekToken = $this->db->query("SELECT * FROM ms_toko WHERE uuid_toko='$token'")->row();
        if ($cekToken) {
            $cekMember = $this->db->query("SELECT * FROM v_member WHERE barcode_member_encrypt = '$member'")->row();
            if($cekMember){
                $cekVoucher = $this->db->query("SELECT * FROM ms_kupon WHERE kode_kupon='$kode_voucher'")->row();
                if ($cekVoucher) {
                    $cekVoucherTerpakai = $this->db->query("SELECT * FROM ms_voucher_terpakai WHERE member='$cekMember->id_member' and kode_voucher='$kode_voucher'")->row();
                    if ($cekVoucherTerpakai) {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Voucher sudah pernah digunakan'
                        ], RestController::HTTP_OK);
                    }else{
                        if ($cekVoucher->tipe_member!=0 || empty($cekVoucher->tipe_member)) {
                            if($cekVoucher->tipe_member == $cekMember->id_tipe_member){
                                $data = array(
                                    "member" => $cekMember->id_member,
                                    "kode_voucher" => $kode_voucher,
                                    "uuid_toko" => $token
                                );
                                $this->GeneralModel->create_general('ms_voucher_terpakai',$data);    
    
                                $this->response([
                                    'status' => TRUE,
                                    'message' => 'Data voucher berhasil di klaim, silahkan batalkan penggunaan untuk menghapus riwayat klaim voucher',
                                    'data' => $cekVoucher
                                ], RestController::HTTP_OK);        
                            }else{
                                $this->response([
                                    'status' => FALSE,
                                    'message' => 'Mohon maaf tipe member tidak sesuai',
                                ], RestController::HTTP_OK);        
                            }
                        }else{
                            $data = array(
                                "member" => $cekMember->id_tipe_member,
                                "kode_voucher" => $kode_voucher,
                                "uuid_toko" => $token
                            );
                            $this->GeneralModel->create_general('ms_voucher_terpakai',$data);    

                            $this->response([
                                'status' => TRUE,
                                'message' => 'Data voucher berhasil di klaim, silahkan batalkan penggunaan untuk menghapus riwayat klaim voucher',
                                'data' => $cekVoucher
                            ], RestController::HTTP_OK);
                        }
                    }
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Voucher tidak ditemukan'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Member tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Silahkan masukkan toko'
            ], RestController::HTTP_OK);
        }
    }
    
    public function batalKlaimVoucherKasir_post()
    {
        // EndPoint : base_url/api/membership/getVoucherKasir (GET)
        //--------------------- Voucher Membership -------------------//
        $token = $this->post('token');
        $member = $this->post('member');
        $kode_voucher = $this->post('kode_voucher');

        $cekToken = $this->db->query("SELECT * FROM ms_toko WHERE uuid_toko='$token'")->row();
        if ($cekToken) {
            $cekMember = $this->db->query("SELECT * FROM v_member WHERE barcode_member_encrypt = '$member'")->row();
            if($cekMember){
                $cekVoucher = $this->db->query("SELECT * FROM ms_kupon WHERE kode_kupon='$kode_voucher'")->row();
                if ($cekVoucher) {
                    $cekVoucherTerpakai = $this->db->query("SELECT * FROM ms_voucher_terpakai WHERE member='$cekMember->id_member' and kode_voucher='$kode_voucher'")->row();
                    if ($cekVoucherTerpakai) {
                        $this->GeneralModel->delete_multi_id_general('ms_voucher_terpakai','member',$cekMember->id_member,'kode_voucher',$kode_voucher);
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Klaim voucher berhasil dibatalkan'
                        ], RestController::HTTP_OK);    
                    }else{
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Pembatalan klaim voucher gagal, voucher belum pernah digunakan'
                        ], RestController::HTTP_OK);    
                    }
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Voucher tidak ditemukan'
                    ], RestController::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Member tidak ditemukan'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Silahkan masukkan toko'
            ], RestController::HTTP_OK);
        }
    }    

    public function sendTicketMahkotaCare_post()
    {
        // EndPoint : base_url/api/membership/sendTicketMahkotaCare (POST)
        //--------------------- Send Ticket Mahkota Care -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->post('login_token')])->row();

        $data = array(
            'pengguna' => $cekPengguna->id_pengguna,
            'nama_tiket' => $cekPengguna->nama_lengkap,
            'email_tiket' => $cekPengguna->email,
            'no_wa' => $cekPengguna->no_telp,
            'kategori_tiket' => $this->post('kategori_tiket'),
            'judul_tiket' => $this->post('judul_tiket'),
            'isi_tiket' => $this->post('isi_tiket'),
        );

        $kode_tiket = substr(bin2hex(random_bytes(5)),0,5);
		$cek = $this->GeneralModel->get_by_id_general('ms_tiket','kode_tiket',$kode_tiket);
		if($cek){
			$kode_tiket = substr(bin2hex(random_bytes(5)),0,5);
		}
		$kode_tiket = strtoupper($kode_tiket);
		$data += array(
			'kode_tiket' => $kode_tiket
		);

        if (empty($_FILES['lampiran_tiket'])) {
        } else {
            $config['upload_path']          = 'assets/fileTiket/';
		    $config['allowed_types']        = 'jpg|png|jpeg';
            $this->load->library('upload');
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('lampiran_tiket')) {
            } else {
                $data += array('lampiran_tiket' => $config['upload_path'] . $this->upload->data('file_name'));
            }
        }


		if($this->GeneralModel->create_general('ms_tiket',$data) == TRUE){
			if(!empty($data['nama_tiket'])){
				$message = 'Halo *'.$data['nama_tiket'].'*!';
			}else{
				$message = 'Halo *'.$data['no_wa'].'*!';
			}
			$message .= urlencode("\n"); 
			$message .= "Pesan kamu saat ini tentang : ";
			$message .= urlencode("\n"); 
			$message .= "*".$data['judul_tiket']."*";
			$message .= urlencode("\n"); 
			$message .= urlencode("\n"); 
			$message .= "Sedang di proses oleh admin dan akan dibalas sesegara mungkin,";
			$message .= urlencode("\n"); 
			$message .= "Berikut ini kode tiket yang harus kamu simpan agar dapat terus berkomunikasi dengan admin";
			$message .= urlencode("\n"); 
			$message .= urlencode("\n"); 
			$message .= "Kode tiket : *".$kode_tiket."*";
			$message .= urlencode("\n"); 
			$message .= urlencode("\n"); 
			$message .= "Terima kasih!";
			sendNotifWA2($data['no_wa'],$message);	
            $this->response([
                'status' => TRUE,
                'message' => 'Tiket berhasil dikirim'
            ], RestController::HTTP_OK);
		}else{
            $this->response([
                'status' => FALSE,
                'message' => 'Tiket gagal dikirim'
            ], RestController::HTTP_OK);
        }
    }

    public function getTicketPersonal_get()
    {
        // EndPoint : base_url/api/membership/getTicketPersonal (GET)
        //--------------------- Get Ticket Personal -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();

        $getTicket = $this->db->query("SELECT * FROM ms_tiket WHERE pengguna = '$cekPengguna->id_pengguna'")->result();
        if($getTicket){
            $this->response([
                'status' => TRUE,
                'message' => 'Ticket Personal',
                'data' => $getTicket
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Anda belum memiliki ticket'
            ], RestController::HTTP_OK);
        }
    }

    public function getIsiTiket_get()
    {
        // EndPoint : base_url/api/membership/getIsiTiket (GET)
        //--------------------- Get Isi Tiket -------------------//
        if (empty($this->get('tiket'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Kode Tiket'], 401);
        $cekTiket = $this->db->get_where('ms_isi_tiket', ['tiket' => $this->get('tiket')])->result();

        if($cekTiket){
            $this->response([
                'status' => TRUE,
                'message' => 'Isi Tiket',
                'data' => $cekTiket
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Tiket tidak ditemukan'
            ], RestController::HTTP_OK);
        }
    }

    public function sendMessage_post()
    {
        // EndPoint : base_url/api/membership/sendMessage (POST)
        //--------------------- Send Message -------------------//
        $tiket = $this->post('tiket');
        $pesan = $this->post('pesan');
        $created_by = $this->post('created_by');
        
        $data = array(
            'tiket' => $tiket,
            'pesan' => $pesan,
            'created_by' => $created_by
        );

        if (empty($_FILES['lampiran'])) {
        } else {
            $config['upload_path']          = 'assets/fileTiket/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $this->load->library('upload');
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('lampiran')) {
            } else {
                $data += array('lampiran' => $config['upload_path'] . $this->upload->data('file_name'));
            }
        }

        foreach($this->koneksi as $key){
            $client = new MQTTClient($key->mqtt_broker, (int)$key->mqtt_port, 'pubServer');
            $client->connect('mahkota', $key->mqtt_password);
            try{
                $client->publish('tiket/update/'.my_simple_crypt($tiket,'e'), "update", 0);
                $client->publish('tiket/update/'.$tiket, "update", 0);
            } catch (MqttClientException $e) {
            }
        }

        if($this->GeneralModel->create_general('ms_isi_tiket',$data) == TRUE){
            $this->response([
                'status' => TRUE,
                'message' => 'Pesan berhasil dikirim'
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Pesan gagal dikirim'
            ], RestController::HTTP_OK);
        }
    }

    public function deleteMessage_post()
    {
        // EndPoint : base_url/api/membership/deleteMessage (POST)
        //--------------------- Delete Message -------------------//
        $id_isi_tiket = $this->post('id_isi_tiket');

        if($this->db->delete('ms_isi_tiket', ['id_isi_tiket' => $id_isi_tiket])){
            $this->response([
                'status' => TRUE,
                'message' => 'Pesan berhasil dihapus'
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Pesan gagal dihapus'
            ], RestController::HTTP_OK);
        }
    }
    
    public function ambilVoucher_post()
    {
        $couponId = $this->post('couponId');
        $loginToken = $this->post('loginToken');

        $cekKupon = $this->db->query("SELECT * FROM ms_kupon WHERE id_kupon = '$couponId'")->row();
        $cekUser = $this->db->query("SELECT * FROM ms_pengguna WHERE login_token = '$loginToken'")->row();
        if($cekKupon){
            $kode_kupon = $cekKupon->kode_kupon;
            $memberId = $cekUser->id_pengguna;
            // ms_voucher_terpakai
            $cekVoucherTerpakai = $this->db->query("SELECT * FROM ms_voucher_terpakai WHERE member = '$memberId' AND kode_voucher = '$kode_kupon'")->row();
            if($cekVoucherTerpakai){
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda sudah mengambil kupon ini'
                ], RestController::HTTP_OK);
            }else{
                // cek jml kupon yang tersedia dan yang sudah diambil
                $jml_kupon = $cekKupon->jml_kupon;
                $getKuponTerpakai = $this->db->query("SELECT COUNT(*) as jml_kupon_terpakai FROM ms_voucher_terpakai WHERE kode_voucher = '$kode_kupon'")->row();
                $jml_kupon_terpakai = $getKuponTerpakai->jml_kupon_terpakai;
                if($jml_kupon <= $jml_kupon_terpakai){
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Kupon sudah habis'
                    ], RestController::HTTP_OK);
                }
                $data = array(
                    'pengguna' => $memberId,
                    'member' => $memberId,
                    'kode_voucher' => $kode_kupon,
                    'waktu_pakai' => null,
                    'id_transaksi' => null,
                    'uuid_toko' => null
                );
                $this->GeneralModel->create_general('ms_voucher_terpakai',$data);
                $this->response([
                    'status' => TRUE,
                    'message' => 'Kupon berhasil diambil'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Kupon tidak ditemukan'
            ], RestController::HTTP_OK);
        }
    }

    public function checkVoucher_post()
    {
        $couponId = $this->post('couponId');
        $loginToken = $this->post('loginToken');
        
        $cekKupon = $this->db->query("SELECT * FROM ms_kupon WHERE id_kupon = '$couponId'")->row();
        $cekUser = $this->db->query("SELECT * FROM ms_pengguna WHERE login_token = '$loginToken'")->row();

        if($cekKupon){
            $kode_kupon = $cekKupon->kode_kupon;
            $memberId = $cekUser->id_pengguna;
            // ms_voucher_terpakai
            $cekVoucherTerpakai = $this->db->query("SELECT * FROM ms_voucher_terpakai WHERE member = '$memberId' AND kode_voucher = '$kode_kupon'")->row();
            if($cekVoucherTerpakai){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Anda sudah mengambil kupon ini'
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum mengambil kupon ini'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Kupon tidak ditemukan'
            ], RestController::HTTP_OK);
        }
    }

    public function checkVoucherData_post()
    {
        $couponId = $this->post('couponId');
        $loginToken = $this->post('loginToken');
        
        $cekKupon = $this->db->query("SELECT * FROM ms_kupon WHERE id_kupon = '$couponId'")->row();
        $cekUser = $this->db->query("SELECT * FROM ms_pengguna WHERE login_token = '$loginToken'")->row();

        if($cekKupon){
            $kode_kupon = $cekKupon->kode_kupon;
            $memberId = $cekUser->id_pengguna;
            // ms_voucher_terpakai
            $cekVoucherTerpakai = $this->db->query("SELECT * FROM ms_voucher_terpakai WHERE member = '$memberId' AND kode_voucher = '$kode_kupon'")->row();
            if($cekVoucherTerpakai){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Anda sudah mengambil kupon ini',
                    'data' => $cekVoucherTerpakai
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum mengambil kupon ini',
                    'data' => $cekVoucherTerpakai
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Kupon tidak ditemukan'
            ], RestController::HTTP_OK);
        }
    }

    public function getVoucher_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        $total_belanja = $this->get('total_belanja');
        if($cekPengguna){
            $cekVoucherTerpakai = $this->db->query("SELECT * FROM ms_voucher_terpakai JOIN ms_kupon ON ms_voucher_terpakai.kode_voucher = ms_kupon.kode_kupon WHERE ms_voucher_terpakai.member = '$cekPengguna->id_pengguna' AND ms_voucher_terpakai.waktu_pakai IS NULL AND ms_kupon.min_belanja <= '$total_belanja'")->result(); 
            if($cekVoucherTerpakai){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Voucher Anda',
                    'data' => $cekVoucherTerpakai
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki voucher',
                    'data' => $cekVoucherTerpakai
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function getRiwayatKupon_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekVoucherTerpakai = $this->db->query("SELECT * FROM ms_voucher_terpakai LEFT JOIN ms_kupon ON ms_voucher_terpakai.kode_voucher = ms_kupon.kode_kupon WHERE ms_voucher_terpakai.member = '$cekPengguna->id_pengguna'")->result(); 
            if($cekVoucherTerpakai){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Riwayat Kupon Anda',
                    'data' => $cekVoucherTerpakai
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki riwayat kupon',
                    'data' => $cekVoucherTerpakai
                ], RestController::HTTP_OK);
            }
        }
    }

    public function riwayatReferral_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('v_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekReferral = $this->db->query("SELECT ms_riwayat_point.* FROM ms_riwayat_point JOIN v_pengguna ON v_pengguna.id_pengguna = ms_riwayat_point.pengguna 
            LEFT JOIN ms_transaksi ON ms_riwayat_point.transaksi = ms_transaksi.id_transaksi WHERE v_pengguna.barcode_member = '$cekPengguna->barcode_member' AND ms_riwayat_point.keterangan_point = 'Member'")->result();
            if($cekReferral){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Riwayat Referral Anda',
                    'data' => $cekReferral
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki riwayat referral',
                    'data' => $cekReferral
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function riwayatPoint_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('v_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekPoint = $this->db->query("SELECT * FROM ms_riwayat_point WHERE pengguna = '$cekPengguna->id_pengguna'")->result();
            if($cekPoint){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Riwayat Point Anda',
                    'data' => $cekPoint
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki riwayat point',
                    'data' => $cekPoint
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }

    }

    public function getPointMembership_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('v_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $riwayatPoint = $this->db->query("SELECT SUM(point) as total_point FROM ms_riwayat_point WHERE pengguna = '$cekPengguna->id_pengguna' AND status = 'diterima'")->row();
            if($riwayatPoint->total_point == null){
                $riwayatPoint->total_point = 0;
            }
            $this->response([
                'status' => TRUE,
                'message' => 'Point Membership Anda',
                'data' => $riwayatPoint
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
    }

    public function listPromoBuy1Get1_get()
    {
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('v_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $cekPromo = $this->db->query("SELECT * FROM v_buy1_get1 WHERE pelanggan = '$cekPengguna->id_pengguna' ORDER BY id_buy1get1 DESC")->result();
            if($cekPromo){
                $this->response([
                    'status' => TRUE,
                    'message' => 'List Promo Buy 1 Get 1 Anda',
                    'data' => $cekPromo
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki promo buy 1 get 1',
                    'data' => $cekPromo
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }
        
    }

    public function getMembershipUser_post()
    {
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $cekPengguna = $this->db->get_where('v_pengguna', ['login_token' => $this->post('login_token')])->row();
        if($cekPengguna){
            $ms_member = $this->db->get_where('ms_member', ['id_pengguna' => $cekPengguna->id_pengguna])->row();
            if($ms_member){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data Membership Anda',
                    'data' => $ms_member->id_tipe_member
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Anda belum memiliki membership'
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_OK);
        }

    }
        
        
}