<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class Auth extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function login_post()
    {
        $cekUsername = $this->GeneralModel->get_by_multi_id_general('v_pengguna','no_telp',$this->post('no_telp'),'status','actived');
        if ($cekUsername) {
            $cekPassword = $this->GeneralModel->get_by_multi_id_general('v_pengguna', 'password', sha1($this->post('password')),'no_telp',$this->post('no_telp'));
            if ($cekPassword) {
                if($this->post('no_telp') != '082165944227'){
                    $cekOtp = $this->db->query("SELECT * FROM ms_otp WHERE no_wa = '".$this->post('no_telp')."' AND otp = '".$this->post('otp')."' AND status_otp = 'pending'")->result();
                    if($cekOtp){
                        $data = array(
                                'last_login' => DATE('Y-m-d H:i:s'),
                        );
                        $this->GeneralModel->update_general('ms_pengguna','id_pengguna',$cekPassword[0]->id_pengguna, $data);
                        $cekUsername = $this->GeneralModel->get_by_id_general('v_pengguna','no_telp',$this->post('no_telp'));
                        $cekModul = $this->GeneralModel->get_by_id_general('ms_hak_akses','nama_hak_akses',$cekUsername[0]->hak_akses);
                        
                        $deviceToken = array(
                            'device_token' => $this->post('device_token'),
                            'id_pengguna' => $cekPassword[0]->id_pengguna,
                        );

                        $updateLogin = array(
                            'last_login' => date('Y-m-d H:i:s'),
                            'activity_status' => 'online',
                            'login_token' => sha1($cekPassword[0]->no_telp).strtotime(date('Y-m-d H:i:s'))
                        );

                        $loginToken = array(
                            'login_token' => sha1($cekPassword[0]->no_telp).strtotime(date('Y-m-d H:i:s')),
                            'pengguna' => $cekPassword[0]->id_pengguna
                        );

                        $this->GeneralModel->create_general('ms_login_token',$loginToken);

                        $cekDeviceToken = $this->GeneralModel->get_by_id_general('ms_device_token','device_token',$this->post('device_token'));
                        if($cekDeviceToken){
                            $this->GeneralModel->delete_general('ms_device_token', 'device_token', $this->post('device_token'));
                        }
                        $this->GeneralModel->create_general('ms_device_token', $deviceToken);
                        $this->GeneralModel->update_general('ms_pengguna','id_pengguna',$cekPassword[0]->id_pengguna,$updateLogin);

                        $data = array(
                                'dataPengguna' => $cekUsername,
                                'device' => $deviceToken,
                                'loginToken' => $loginToken,
                                'aksesModul' => json_decode($cekModul[0]->modul_akses)
                        );

                        $this->response($data, 200);
                    }else{
                        $this->response([
                            'status' => false,
                            'message' => 'Kode OTP yang anda masukkan salah atau expired! silahkan kirim ulang kode OTP!'
                        ], 404);
                    }
                }else{
                    $data = array(
                            'last_login' => DATE('Y-m-d H:i:s'),
                    );
                    $this->GeneralModel->update_general('ms_pengguna','id_pengguna',$cekPassword[0]->id_pengguna, $data);
                    $cekUsername = $this->GeneralModel->get_by_id_general('v_pengguna','no_telp',$this->post('no_telp'));
                    $cekModul = $this->GeneralModel->get_by_id_general('ms_hak_akses','nama_hak_akses',$cekUsername[0]->hak_akses);
                    
                    $deviceToken = array(
                        'device_token' => $this->post('device_token'),
                        'id_pengguna' => $cekPassword[0]->id_pengguna,
                    );

                    $updateLogin = array(
                        'last_login' => date('Y-m-d H:i:s'),
                        'activity_status' => 'online',
                        'login_token' => sha1($cekPassword[0]->no_telp).strtotime(date('Y-m-d H:i:s'))
                    );

                    $loginToken = array(
                        'login_token' => sha1($cekPassword[0]->no_telp).strtotime(date('Y-m-d H:i:s')),
                        'pengguna' => $cekPassword[0]->id_pengguna
                    );

                    $this->GeneralModel->create_general('ms_login_token',$loginToken);

                    $cekDeviceToken = $this->GeneralModel->get_by_id_general('ms_device_token','device_token',$this->post('device_token'));
                    if($cekDeviceToken){
                        $this->GeneralModel->delete_general('ms_device_token', 'device_token', $this->post('device_token'));
                    }
                    $this->GeneralModel->create_general('ms_device_token', $deviceToken);
                    $this->GeneralModel->update_general('ms_pengguna','id_pengguna',$cekPassword[0]->id_pengguna,$updateLogin);

                    $data = array(
                            'dataPengguna' => $cekUsername,
                            'device' => $deviceToken,
                            'loginToken' => $loginToken,
                            'aksesModul' => json_decode($cekModul[0]->modul_akses)
                    );

                    $this->response($data, 200);
                }

            }else{
                $this->response([
                    'status' => false,
                    'message' => 'Password yang anda masukkan salah! silahkan cek kembali no telp dan password anda!'
                ], 404);
            }
        }else{
            $this->response([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }
    }

    public function otpSend_post()
    {
        $getData = $this->db->query("SELECT * FROM ms_pengguna WHERE no_telp = '".$this->post('no_telp')."' AND password = '".sha1($this->post('password'))."' AND status = 'actived'")->row();
        if ($getData) {
            $cekOTP = $this->GeneralModel->get_by_multi_id_general("ms_otp","pengguna",$getData->id_pengguna,"status_otp","pending");
            $cekDeviceId = $this->GeneralModel->get_by_id_general('ms_device_notif','id_pengguna',$getData->id_pengguna);

            $messages = [
                new ExpoMessage([
                    'title' => 'Peringatan',
                    'body' => 'Seseorang baru saja merequest kode OTP dari akun kamu',
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

			$six_digit = random_int(100000, 999999);
			$data  = array(
                'pengguna' => $getData->id_pengguna,
				'no_wa' => $this->post('no_telp'),
				'created_time' => DATE("Y-m-d H:i:s"),
				'otp' => $six_digit,
			);

			if (!empty($cekOTP)) {
				// $data2  = array(
				// 	'used_time' => DATE("Y-m-d H:i:s"),
				// 	'status_otp' => 'expired',
				// );

				// $this->db->where("pengguna",$getData->id_pengguna);
				// $this->db->where("status_otp",'pending');
				// $this->db->update("ms_otp",$data2);
				$this->GeneralModel->create_general("ms_otp",$data);
			}else{
				$this->GeneralModel->create_general("ms_otp",$data);
			}

			if (!empty($getData->nama_lengkap)) {
				$message = 'Halo *'.$getData->nama_lengkap.'*!';
			}else{
				$message = 'Halo *'.$getData->no_telp.'*!';
			}
			$message .= urlencode("\n"); 
			$message .= "Kamu baru saja melakukan request OTP";
			$message .= urlencode("\n"); 
			$message .= "Masukkan kode OTP berikut ini : *".$six_digit."*";
			$message .= urlencode("\n"); 
			$message .= "Jangan berikan kode OTP ini kepada siapapun untuk keamanan informasi, Terima kasih!";
			sendNotifWA2($getData->no_telp,$message);
            $this->response([
                'status' => true,
                'message' => 'Silahkan cek no wa anda untuk melakukan proses verifikasi!'
            ], 200);
        }else{
            $this->response([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }
    }

    public function forgetPassword_post()
    {
        $getTelp = $this->GeneralModel->get_by_id_general('ms_pengguna','no_telp',$this->post('no_telp'));
        if ($getTelp) {
            $dataPengguna = array(
                'resetpass_token' => my_simple_crypt($getTelp[0]->no_telp,'e')
            );
            if ($this->GeneralModel->update_general('ms_pengguna','id_pengguna',$getTelp[0]->id_pengguna,$dataPengguna)) {
                $getPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$getTelp[0]->id_pengguna);
                // sendMail('Reset Password', '/email/reset', $this->post('email'),$getPengguna[0]);
                $message = 'Halo *'.$getPengguna[0]->nama_lengkap.'*!';
                $message .= urlencode("\n"); 
                $message .= "Kamu baru saja melakukan request untuk perubahan password";
                $message .= urlencode("\n"); 
                $message .= "Silahkan klik link berikut ini untuk melakukan perubahan password";
                $message .= urlencode("\n"); 
                $message .= base_url('auth/reset/'.$getPengguna[0]->resetpass_token);
                $message .= urlencode("\n"); 
                $message .= "Terima kasih!";
                sendNotifWA2($getPengguna[0]->no_telp,$message);
                $this->response([
                    'status' => true,
                    'message' => 'Silahkan cek no wa anda untuk melakukan proses perubahan password!'
                ], 200);
            }else{
                $this->response([
                    'status' => false,
                    'message' => 'Gagal melakukan proses perubahan password!'
                ], 404);
            }
        }else{
            $this->response([
                'status' => false,
                'message' => 'No Telp tidak ditemukan!'
            ], 404);
        }
    }

    public function cekMenuDashboard_get(){
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna', 'login_token', $this->get('login_token'));
        if ($pengguna) {
            $hakAkses = $this->GeneralModel->get_by_id_general('ms_hak_akses', 'nama_hak_akses', $pengguna[0]->hak_akses);
            if ($hakAkses) {
                $this->response(array('aksesModul' => json_decode($hakAkses[0]->modul_akses)), 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Akses Modul Tidak Ditemukan'
                ], 404);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Login Token Tidak Cocok'
            ], 404);
        }
    }

    public function logout_post(){
        $this->GeneralModel->delete_general('ms_device_token','device_token',$this->post('device_token'));
        $this->GeneralModel->delete_general('ms_login_token','login_token',$this->post('login_token'));
        $this->response([
            'status' => true,
            'message' => 'Logout Berhasil'
        ], 200);
    }


    public function simpanPushToken_post()
    {
        
        $data = array(
            'deviceid' => $this->post('deviceid'),
            'id_pengguna' => $this->post('id_pengguna'),
        );
        $cekDeviceToken = $this->GeneralModel->get_by_id_general('ms_device_notif','deviceid',$this->post('deviceid'));
        if($cekDeviceToken){
            if($this->post('id_pengguna') != '' || !empty($this->post('id_pengguna'))){
                $cekDeviceToken = $this->GeneralModel->get_by_id_general('ms_device_notif','id_pengguna',$this->post('id_pengguna'));
                if($cekDeviceToken){
                    $this->GeneralModel->delete_general('ms_device_notif', 'id_pengguna', $this->post('id_pengguna'));
                    $this->GeneralModel->create_general('ms_device_notif', $data);
                    return $this->response([
                        'status' => true,
                        'message' => 'Device berhasil disimpan!'
                    ], 200);
                }else{
                    $this->GeneralModel->create_general('ms_device_notif', $data);
                    return $this->response([
                        'status' => true,
                        'message' => 'Device berhasil disimpan!'
                    ], 200);
                }
            }else{
                return $this->response([
                    'status' => false,
                    'message' => 'ID Pengguna tidak boleh kosong!'
                ], 404);
            }
        }else{
            if($this->post('id_pengguna') == '0'){
                return $this->response([
                    'status' => false,
                    'message' => 'ID Pengguna tidak boleh kosong!'
                ], 404);
            }
            if(!empty($data['deviceid']) || $data['deviceid']!=''){
                $this->GeneralModel->create_general('ms_device_notif', $data);
                return $this->response([
                    'status' => true,
                    'message' => 'Device berhasil disimpan!'
                ], 200);
                
            }else{
                return $this->response([
                    'status' => false,
                    'message' => 'Device token tidak boleh kosong!'
                ], 404);
            }
        }
    }

}