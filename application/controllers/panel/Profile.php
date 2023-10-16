<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public $title = 'Profile';

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('LoggedIN') == FALSE || $this->session->userdata('hak_akses') == 'member') redirect('auth/logout');
	}

	public function index(){
		$this->edit();
	}

	public function edit($param1=''){
		if ($param1=='doEdit') {
			$dataPengguna = array(
				'email' => $this->input->post('email'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'jenkel' => $this->input->post('jenkel'),
				'alamat' => $this->input->post('alamat'),
				'no_telp' => $this->input->post('no_telp'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'nik' => $this->input->post('nik'),
				'nip' => $this->input->post('nip'),
				'status' => $this->input->post('status'),
				'updated_by' => $this->session->userdata('id_pengguna'),
				'updated_time' => DATE("Y-m-d H:i:s")
			);

			if (empty($this->input->post('uuid_toko'))) {
				$dataPengguna += array(
					'uuid_toko' => $this->session->userdata('uuid_toko')
				);
			}else{
				$dataPengguna += array(
					'uuid_toko' => $this->input->post('uuid_toko')
				);
			}

			//---------------- UPDATE FOTO PENGGUNA ---------------//
			$config['upload_path']          = 'assets/img/pengguna/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 10000;


			$this->upload->initialize($config);

            $pengguna = $this->GeneralModel->get_by_id_general('ms_pengguna', 'id_pengguna', $this->session->userdata('id_pengguna'));
			if (!$this->upload->do_upload('foto_pengguna')) {
			} else {
				$dataPengguna += array('foto_pengguna' => $config['upload_path'] . $this->upload->data('file_name'));
				if (!empty($pengguna[0]->foto_pengguna)) {
					try {
						unlink($pengguna[0]->foto_pengguna);
					} catch (\Exception $e) {
					}
				}
			}

			$this->session->set_userdata($dataPengguna);

			if (!empty($this->input->post('password'))) {
				if ($this->input->post('password') == $this->input->post('re_password')) {
					$dataPengguna += array(
						'password' => sha1($this->input->post('password')),
					);
					$this->session->set_flashdata('notifpass','<div class="alert alert-success">Password berhasil diubah</div>');
				}else {
					$this->session->set_flashdata('notifpass','<div class="alert alert-danger">Password gagal diubah karena tidak sama dengan ulangi password</div>');
				}
			}

			if ($this->GeneralModel->update_general('ms_pengguna','id_pengguna',$this->session->userdata('id_pengguna'),$dataPengguna)==true) {
						$this->session->set_userdata($dataPengguna);
						$this->session->set_flashdata('notif', '<div class="alert alert-success">Data pengguna berhasil diupdate</div>');
						redirect(changeLink('panel/dashboard'));
			}else{
				$this->session->set_flashdata('notif', '<div class="alert alert-danger">Data pengguna gagal diupdate</div>');
				redirect(changeLink('panel/profile/edit'));
			}
		}else {
			$data['title'] = $this->title;
			$data['subtitle'] = 'Edit Profile';
			$data['content'] = 'panel/profile/updatePengguna';
			$data['pengguna'] = $this->GeneralModel->get_by_id_general('v_pengguna','id_pengguna',$this->session->userdata('id_pengguna'));
			$data['hakAkses'] = $this->GeneralModel->get_general('ms_hak_akses');
			$this->load->view('panel/content',$data);
		}
	}
	
}
