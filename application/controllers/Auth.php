<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

		public function __construct()
	{
				parent::__construct();
	}

	public function index()
	{
		$this->login();
	}

	public function login($param1='',$param2=''){
			if ($param1=='do_login') {
				$no_telp = $this->input->post('no_telp');
				$cekUser = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','no_telp',$no_telp,'status','actived');
				if ($cekUser) {
					$password = sha1($this->input->post('password'));
					$getUser = $this->AuthModel->getAccountLogin($no_telp,$password);
					if (empty($getUser)) {
						$getUser = $this->AuthModel->getAccountLoginEmail($no_telp,$password);
					}
					if ($getUser) {
						foreach ($getUser as $key) {
							$dataAkun = array(
								'id_pengguna' => $key->id_pengguna,
								'nama_lengkap' => $key->nama_lengkap,
								'foto_pengguna' => $key->foto_pengguna,
								'username' => $key->username,
								'no_telp' => $key->no_telp,
								'no_wa' => $key->no_wa,
							);
						}

						$this->session->set_userdata($dataAkun);						
						redirect('auth/otp/resend');
					}else {
						$this->session->set_flashdata('notif','<div class="alert alert-danger">No WA atau PIN pengguna salah</div>');
						$this->session->set_flashdata('wrong','Data salah');
						redirect('/');
					}
				}else {
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Akun tidak ditemukan</div>');
					$this->session->set_flashdata('wrong','Data salah');
					redirect('/');
				}
			}else {
				if (!empty($_COOKIE['code'])) {
				$loginToken = $this->GeneralModel->get_by_id_general('ms_login_token','login_token',$_COOKIE['code']);
				if ($loginToken) {
					$cekUser = $this->GeneralModel->get_by_id_general('v_pengguna','id_pengguna',$loginToken[0]->pengguna);
					if ($cekUser) {
							foreach ($cekUser as $key) {
								$dataAkun = array(
									'id_pengguna' => $key->id_pengguna,
									'foto_pengguna' => $key->foto_pengguna,
									'nama_lengkap' => $key->nama_lengkap,
									'username' => $key->username,
									'no_wa' => $key->no_wa,
									'no_telp' => $key->no_telp,
								);
							}
		
							$this->session->set_userdata($dataAkun);
							redirect('auth/otp/resend');
						}else{
							$this->session->set_flashdata('notif','<div class="alert alert-danger">No WA atau PIN pengguna salah</div>');
							$this->session->set_flashdata('wrong','Data salah');
							redirect('/');
						}
					}else{
						$this->session->set_flashdata('notif','<div class="alert alert-danger">Akun tidak ditemukan</div>');
						$this->session->set_flashdata('wrong','Data salah');
						redirect('/');
					}
				}else{
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Akun tidak ditemukan</div>');
					$this->session->set_flashdata('wrong','Data salah');
					redirect('/');
				}
			}
	}

	public function otp($param1=''){
		if ($param1=='submitOTP') {
			$kode_otp = $this->input->post("kode_otp");
			$kode_otp = implode("",$kode_otp);
			if(empty($kode_otp)){
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Silahkan masukkan kode OTP</div>');
				redirect('auth/otp');
			}
			$cekOTP = $this->GeneralModel->get_by_triple_id_general("ms_otp","pengguna",$this->session->userdata("id_pengguna"),"otp",$kode_otp,"status_otp","pending");
			if (!empty($cekOTP)) {
				$data2  = array(
					'used_time' => DATE("Y-m-d H:i:s"),
					'status_otp' => 'used',
				);
				$this->db->where("pengguna",$this->session->userdata('id_pengguna'));
				$this->db->where("status_otp",'pending');
				$this->db->update("ms_otp",$data2);

				$cekUser = $this->GeneralModel->get_by_id_general('v_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
				if ($cekUser) {
						foreach ($cekUser as $key) {
							$dataAkun = array(
								'id_pengguna' => $key->id_pengguna,
								'nama_lengkap' => $key->nama_lengkap,
								'username' => $key->username,
								'no_wa' => $key->no_wa,
								'no_telp' => $key->no_telp,
								'email' => $key->email,
								'foto_pengguna' => $key->foto_pengguna,
								'hak_akses' => $key->hak_akses,
								'nama_toko' => $key->nama_toko,
								'uuid_toko' => $key->uuid_toko,
								'status_member' => $key->status_member,
								'LoggedIN' => TRUE
							);
						}
	
						$this->session->set_userdata($dataAkun);
						$updateLogin = array(
							'last_login' => date('Y-m-d H:i:s'),
							'login_token' => sha1($dataAkun['no_telp']).strtotime(date('Y-m-d H:i:s'))
						);
	
						$loginToken = array(
							'login_token' => sha1($dataAkun['no_telp']).strtotime(date('Y-m-d H:i:s')),
							'pengguna' => $dataAkun['id_pengguna']
						);
	
						$this->GeneralModel->create_general('ms_login_token',$loginToken);
						$this->GeneralModel->update_general('ms_pengguna','id_pengguna',$dataAkun['id_pengguna'],$updateLogin);
						setcookie('code', $updateLogin['login_token'], time() + (86400 * 30), "/");
						$this->session->set_flashdata('notif','<div class="alert alert-success">Login Berhasil</div>');
						if ($dataAkun['hak_akses']!='member') {
							if ($dataAkun['hak_akses']=='kasir' && $this->KasirModel->cekOpenedTime($dataAkun['id_pengguna'])) {
								redirect('panel/kasir/bukaKasir');
							}else{
								redirect('panel/dashboard');
							}
						}else{
							redirect('/');
						}
					}else{
						$this->session->set_flashdata('notif','<div class="alert alert-danger">No WA atau PIN pengguna salah</div>');
						$this->session->set_flashdata('wrong','Data salah');
						redirect('/');
					}
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Kode OTP Salah atau Sudah kadaluwarsa</div>');
				redirect('auth/otp');
			}		
		}elseif($param1=='resend'){
			if(empty($this->session->userdata('id_pengguna'))){
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Pengguna tidak ditemukan!</div>');
				redirect('/');	
			}

			$cekOTP = $this->GeneralModel->get_by_multi_id_general("ms_otp","pengguna",$this->session->userdata("id_pengguna"),"status_otp","pending");
			$six_digit = random_int(100000, 999999);
			$this->session->set_userdata('kode',$six_digit);
			$data  = array(
				'pengguna' => $this->session->userdata('id_pengguna'),
				'no_wa' => $this->session->userdata('no_telp'),
				'created_time' => DATE("Y-m-d H:i:s"),
				'otp' => $six_digit,
			);

			if (!empty($cekOTP)) {
				$data2  = array(
					'used_time' => DATE("Y-m-d H:i:s"),
					'status_otp' => 'expired',
				);

				$this->db->where("pengguna",$this->session->userdata('id_pengguna'));
				$this->db->where("status_otp",'pending');
				$this->db->update("ms_otp",$data2);
				$this->GeneralModel->create_general("ms_otp",$data);
			}else{
				$this->GeneralModel->create_general("ms_otp",$data);
			}

			if (!empty($this->session->userdata('nama_lengkap'))) {
				$message = 'Halo *'.$this->session->userdata('nama_lengkap').'*!';
				// $message2 = 'Halo '.$this->session->userdata('nama_lengkap').', ';
			}else{
				$message = 'Halo *'.$this->session->userdata('no_telp').'*!';
				// $message2 = 'Halo '.$this->session->userdata('no_telp').', ';
			}
			$message .= urlencode("\n"); 
			$message .= "Kamu baru saja melakukan request OTP";
			$message .= urlencode("\n"); 
			$message .= "Masukkan kode OTP berikut ini : *".$six_digit."*";
			$message .= urlencode("\n"); 
			$message .= " Jangan berikan kode OTP ini kepada siapapun untuk keamanan informasi, Terima kasih!";
			sendNotifWA2($this->session->userdata('no_telp'),$message);
			// $message2 .= $six_digit_with_dots;
			// $message2 .= "\n"; 
			// $message2 .= "Jangan berikan kepada siapapun untuk keamanan informasi, Terima kasih!";
			// sendSMS($nomer,$message2); // Call the sendSMS() function when a timeout occurs
			redirect("auth/otp");
		}else{
			$cekOTP = $this->GeneralModel->get_by_multi_id_general("ms_otp","pengguna",$this->session->userdata("id_pengguna"),"status_otp","pending");
			if (!empty($cekOTP)) {
				$data['appsProfile'] = $this->SettingsModel->get_profile();
				$this->load->view('otp',$data);
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Kode OTP kadaluwarsa</div>');
				redirect('/');
			}
		}
	}

	public function logout()
	{
		$updateLogin = array(
			'last_logout' => date('Y-m-d H:i:s'),
			'login_token' => ''
		);
		$this->GeneralModel->update_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'),$updateLogin);
		$this->GeneralModel->delete_general('ms_login_token','login_token',$this->session->userdata('login_token'));
		$this->session->sess_destroy();
		redirect(base_url('/'),'refresh');
	}

	public function access_denied(){
		$data['appsProfile'] = $this->SettingsModel->get_profile();
		$data['title'] = '401';
		$this->load->view('errors/panel/unauthorized_access',$data);
	}

	public function forget($param1=''){
		if ($param1=='reset') {
			$getTelp = $this->GeneralModel->get_by_id_general('ms_pengguna','no_telp',$this->input->post('no_telp'));
			if ($getTelp) {
				$digits = 4;
				$unique_code = rand(pow(10, $digits-1), pow(10, $digits)-1);
				$dataPengguna = array(
					'resetpass_token' => my_simple_crypt($getTelp[0]->username.$unique_code,'e')
				);
				if ($this->GeneralModel->update_general('ms_pengguna','id_pengguna',$getTelp[0]->id_pengguna,$dataPengguna)) {
					$getPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$getTelp[0]->id_pengguna);
					if (!empty($getPengguna[0]->nama_lengkap)) {
						$message = 'Halo *'.$getPengguna[0]->nama_lengkap.'*!';
					}else{
						$message = 'Halo *'.$getPengguna[0]->no_telp.'*!';
					}
					$message .= urlencode("\n"); 
					$message .= "Kamu baru saja melakukan request untuk perubahan PIN";
					$message .= urlencode("\n"); 
					$message .= "Silahkan klik link berikut ini untuk melakukan perubahan PIN";
					$message .= urlencode("\n"); 
					$message .= base_url('auth/reset/'.$getPengguna[0]->resetpass_token);
					$message .= urlencode("\n"); 
					$message .= "Terima kasih!";
					sendNotifWA2($getPengguna[0]->no_telp,$message);
					$this->session->set_flashdata('notif','<div class="alert alert-success">Link untuk reset PIN berhasil dikirimkan, silahkan cek pesan pada WA kamu</div>');
					redirect('auth/forget');
				}	
			}else{
					$this->session->set_flashdata('notif','<div class="alert alert-danger">No WA tidak ditemukan</div>');
					redirect('auth/forget');
			}
		}else{
			$data['title'] = 'Lupa PIN';
			$data['content'] = 'forget';
			$this->load->view('frontend/content', $data);
		}
	}

	public function reset($resetpass_token='',$param2=''){
		$data['appsProfile'] = $this->SettingsModel->get_profile();
		$data['resetpass_token'] = $resetpass_token;
		$getPengguna = $this->GeneralModel->get_by_id_general('ms_pengguna','resetpass_token',$resetpass_token);
		if (!empty($resetpass_token) && my_simple_crypt($resetpass_token,'d') != '') {
			if ($param2=='doReset') {
				$this->form_validation->set_rules(
						'password', 'PIN',
						'required|max_length[6]|max_length[6]',
						array(
								'required'      => 'PIN tidak boleh kosong',
						)
				);
				$this->form_validation->set_rules(
						're_password', 'Ulangin PIN',
						'required|matches[password]',
						array(
								'required'      => 'Ulangi PIN tidak boleh kosong',
								'matches'		=> 'PIN dan Ulangi PIN tidak sama'
						)
				);
				if ($this->form_validation->run() == FALSE) {
					$data['title'] = 'Reset PIN';
					$data['content'] = 'reset';
					$this->load->view('frontend/content', $data);
				}else{
					$dataPengguna = array(
						'password' => sha1($this->input->post('password')),
						'resetpass_token' => ''
					);
					if($this->GeneralModel->update_general('ms_pengguna','id_pengguna',$getPengguna[0]->id_pengguna,$dataPengguna) == TRUE){
						if (!empty($getPengguna[0]->nama_lengkap)) {
							$message = 'Halo *'.$getPengguna[0]->nama_lengkap.'*!';
						}else{
							$message = 'Halo *'.$getPengguna[0]->no_telp.'*!';
						}
						$message .= urlencode("\n"); 
						$message .= "Kamu baru saja melakukan perubahan PIN";
						$message .= urlencode("\n"); 
						$message .= "PIN baru kamu : ".$this->input->post('password');
						$message .= urlencode("\n"); 
						$message .= urlencode("\n"); 
						$message .= "Terima kasih!";
						sendNotifWA2($getPengguna[0]->no_telp,$message);
						$this->session->set_flashdata('notif','<div class="alert alert-success">Selamat PIN kamu telah diubah, silahkan login dengan password baru kamu</div>');
						redirect('auth/forget');
					}
				}
			}else{
				if ($getPengguna) {
					$data['title'] = 'Reset PIN';
					$data['content'] = 'reset';
					$this->load->view('frontend/content', $data);
				}else{
					$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kamu tidak bisa melakukan reset PIN</div>');
					redirect('auth/forget');
				}
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Mohon maaf kamu tidak bisa mengakses menu reset PIN, karena kamu tidak memiliki token untuk mereset PIN. Silahkan masukan no wa kamu untuk melakukan request token untuk mereset PIN akun kamu</div>');
			redirect('auth/forget');
		}
	}

	public function register($param1=''){
		$data['appsProfile'] = $this->SettingsModel->get_profile();
		if ($param1=='doRegister') {
			//---------- VALIDATION -------------//
			$this->form_validation->set_rules(
					'nama_lengkap', 'Nama Lengkap',
					'required',
					array(
							'required'      => 'Nama lengkap tidak boleh kosong',
					)
			);
			$this->form_validation->set_rules(
					'no_telp', 'Nomor WA',
					'required|is_unique[ms_pengguna.no_telp]',
					array(
							'required'      => 'Nomor WA tidak boleh kosong',
					)
			);
			$this->form_validation->set_rules(
					'password', 'PIN',
					'required|max_length[6]|max_length[6]',
					array(
							'required'      => 'PIN tidak boleh kosong',
					)
			);
			$this->form_validation->set_rules(
					're_password', 'Ulangin PIN',
					'required|matches[password]',
					array(
							'required'      => 'Ulangi PIN tidak boleh kosong',
							'matches'		=> 'PIN dan Ulangi PIN tidak sama'
					)
			);			
			//---------- END OF VALIDATION -------------//
			if ($this->form_validation->run() == FALSE) {
				$data['title'] = 'Registrasi';
				$data['content'] = 'register';
				$this->load->view('frontend/content', $data);
			}else{
				$password = $this->input->post('password');
				$dataPengguna = array(
					'nama_lengkap' => $this->input->post('nama_lengkap'),
					'no_telp' => $this->input->post('no_telp'),
					'password' => sha1($password),
					'hak_akses' => 'member'
				);
				if ($this->GeneralModel->create_general('ms_pengguna',$dataPengguna) == TRUE) {
					// sendMail('Aktivasi', '/email/activation', $this->input->post('email'),$dataPengguna);
					sendNotifWA2($dataPengguna['no_telp']," ");
					$message = 'Halo *'.$dataPengguna['nama_lengkap'].'*!';
					$message .= urlencode("\n"); 
					$message .= "Terima kasih sudah mendaftar di mahkota store!";
					$message .= urlencode("\n"); 
					$message .= "Simpan informasimu baik-baik ya!";
					$message .= urlencode("\n"); 
					$message .= "*PIN KAMU* : ".$password;
					$message .= urlencode("\n"); 
					$message .= urlencode("\n"); 
					$message .= "Terima kasih!";
					sendNotifWA2($dataPengguna['no_telp'],$message);
					$this->session->set_flashdata('notif','<div class="alert alert-success">Selamat akun kamu berhasil dibuat, Silahkan login untuk masuk ke aplikasi</div>');					
					redirect('auth/register');
				}
			}
		}else{
			$data['title'] = 'Registrasi';
			$data['content'] = 'register';
			$this->load->view('frontend/content', $data);
		}
	}

	public function activate($username){
		$dataPengguna = array(
			'status' => 'actived'
		);
		$this->GeneralModel->update_general('ms_pengguna','username',my_simple_crypt($username,'d'),$dataPengguna);
		$this->session->set_flashdata('notif','<div class="alert alert-success">Selamat akun kamu berhasil diaktivasi, silahkan masuk..</div>');					
		redirect('/');
	}

	public function bpLog($id){
		if(!empty($id)){
			$id = my_simple_crypt($id,'d');
			$cekId = $this->GeneralModel->get_by_id_general('ms_bypass_login_notif','id_ln',$id);
			if (!empty($cekId) && $cekId[0]->status == 'N' && $_SERVER['HTTP_USER_AGENT'] != 'node-fetch/1.0 (+https://github.com/bitinn/node-fetch)') {
				foreach ($cekId as $cek) {
					$getUser = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$cek->pengguna);
					if ($getUser) {
						foreach ($getUser as $key) {
							$dataAkun = array(
								'id_pengguna' => $key->id_pengguna,
								'nama_lengkap' => $key->nama_lengkap,
								'username' => $key->username,
								'no_telp' => $key->no_telp,
								'no_wa' => $key->no_wa,
								'email' => $key->email,
								'foto_pengguna' => $key->foto_pengguna,
								'hak_akses' => $key->hak_akses,
								'nama_toko' => $key->nama_toko,
								'status_member' => $key->status_member,
								'LoggedIN' => TRUE
							);

							if(empty($key->uuid_toko)){
								$dataAkun += array(
									'uuid_toko' => 'ac9d81bf-c61b-11ec-aa9f-00163e67b034' //set uuid toko manual ke pusat
								);
							}else{
								$dataAkun += array(
									'uuid_toko' => $key->uuid_toko
								);
							}
						}

						$this->session->set_userdata($dataAkun);
						$updateLogin = array(
							'last_login' => date('Y-m-d H:i:s'),
							'activity_status' => 'online',
							'login_token' => sha1($dataAkun['no_telp']).strtotime(date('Y-m-d H:i:s'))
						);

						$this->session->set_userdata('login_token',$updateLogin['login_token']);

						$loginToken = array(
							'login_token' => sha1($dataAkun['no_telp']).strtotime(date('Y-m-d H:i:s')),
							'pengguna' => $dataAkun['id_pengguna']
						);

						$this->GeneralModel->create_general('ms_login_token',$loginToken);
						$this->GeneralModel->update_general('ms_pengguna','id_pengguna',$dataAkun['id_pengguna'],$updateLogin);
						setcookie('code', $updateLogin['login_token'], time() + (86400 * 30), "/");

						$updateLN = array(
							'tgl_masuk' => DATE('Y-m-d H:i:s'),
							'status' => 'Y',
							'user_agents' => $_SERVER['HTTP_USER_AGENT']
						);

						$this->GeneralModel->update_general('ms_bypass_login_notif','id_ln',$id,$updateLN);

						if ($cek->notif == 'verif_member') {
							redirect('panel/membership/detailPendaftaranMember/'.$cek->id_notif);
						}if($cek->notif == 'transaksi'){
							redirect('panel/transaksi/detailTransaksi/'.$cek->id_notif);
						}if($cek->notif == 'buy1get1'){
							redirect('panel/membership/buy1get1/');
						}
					}else{
						$this->session->set_flashdata('notif','<div class="alert alert-danger">Pengguna tidak ditemukan</div>');
						redirect('/');
					}
				}
			}else{
				$this->session->set_flashdata('notif','<div class="alert alert-danger">Login sudah expired</div>');
				redirect('/');
			}
		}else{
			$this->session->set_flashdata('notif','<div class="alert alert-danger">id tidak ditemukan</div>');
			redirect('/');
		}
	}

}