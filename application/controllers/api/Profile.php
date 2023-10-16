<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Profile extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->akses_controller = $this->uri->segment(3);
    }

    public function profile_get(){
        if(empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $pengguna = $this->GeneralModel->get_by_id_general('v_pengguna','login_token',$this->get('login_token'));
        if ($pengguna == TRUE) {
            $id_pengguna = $pengguna[0]->id_pengguna;
            $cekMembership = $this->db->query("SELECT * FROM v_pengguna WHERE id_pengguna = '$id_pengguna'")->row();
            $this->response($cekMembership, 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }
    }

    public function profileUpdate_post(){
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $pengguna = $this->GeneralModel->get_by_id_general('v_pengguna','login_token',$this->post('login_token'));
        if ($pengguna) {
            $referral = $this->post('referral');
            if($referral != ''){
                $referral = $this->post('referral');
            }else{
                $referral = null;
            }
            $dataPengguna = array(
                'referral' => $referral,
                'email' => $this->post('email'),
                'no_telp' => $this->post('no_telp'),
                'nama_lengkap' => $this->post('nama_lengkap'),
                'jenkel' => $this->post('jenkel'),
                'alamat' => $this->post('alamat'),
                'tgl_lahir' => $this->post('tgl_lahir'),
                'updated_by' => $pengguna[0]->id_pengguna,
                'updated_time' => DATE("Y-m-d H:i:s"),
            );

            //---------------- UPDATE FOTO PROFILE ---------------//
            if (empty($_FILES['foto_pengguna'])) {
                
            } else {
                $config['upload_path'] = 'assets/img/pengguna/';
                $config['allowed_types'] = '*';
    
                $this->load->library('upload');
                $this->upload->initialize($config);
    
                if ( ! $this->upload->do_upload('foto_pengguna'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                }else
                {
                    $dataPengguna += array('foto_pengguna' => $config['upload_path'].$this->upload->data('file_name'));

                    // Delete Old File
                    $oldFile = $this->GeneralModel->get_by_id_general('ms_pengguna','id_pengguna',$pengguna[0]->id_pengguna);
                    if ($oldFile[0]->foto_pengguna != '') {
                        unlink($oldFile[0]->foto_pengguna);
                    }
                    
                }
            }

            if ($this->GeneralModel->update_general('ms_pengguna', 'id_pengguna', $pengguna[0]->id_pengguna, $dataPengguna) == TRUE) {
                $this->response($dataPengguna, 201);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Terjadi kesalahan'
                ], 400);
            }
        }else{
            $this->response([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }
    }

    public function ubahPassword_post()
    {
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $pengguna = $this->GeneralModel->get_by_id_general('v_pengguna','login_token',$this->post('login_token'));
        if ($pengguna) {
            if ($this->post('password') == $this->post('re_password')) {
                $dataPengguna = array(
                    'password' => sha1($this->post('password')),
                );
                if ($this->GeneralModel->update_general('ms_pengguna', 'id_pengguna', $pengguna[0]->id_pengguna, $dataPengguna) == TRUE) {
                    $this->response([
                        'status' => true,
                        'message' => 'Password Berhasil Diubah'
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Terjadi kesalahan'
                    ], 400);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Password harus sama dengan re-password'
                ], 400);
            }
        }else{
            $this->response([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }
    }


}