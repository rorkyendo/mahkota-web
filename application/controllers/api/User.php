<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class User extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function registerUser_post()
    {
        // EndPoint : base_url/api/user/registerUser (POST)
        //--------------------- Register User -------------------//
        $no_telp = $this->post('no_telp');
        $password = sha1($this->post('pin'));
        $nama_lengkap = $this->post('nama_lengkap');
        $referral = $this->post('referral');

        if(!empty($no_telp) && !empty($password)){
            $cekNoTelp = $this->db->get_where('ms_pengguna', ['no_telp' => $no_telp])->num_rows();
            if($cekNoTelp > 0){
                $this->response(
                    [
                        'status' => false,
                        'message' => 'No Telp sudah terdaftar'
                    ],
                    RestController::HTTP_BAD_REQUEST
                );
            }else{    

                $data = array(
                    'no_telp' => $no_telp,
                    'nama_lengkap' => $nama_lengkap,
                    'password' => $password,
                    'referral' => $referral,
                    'hak_akses' => 'member'
                );

                // sendMail('Aktivasi', '/email/activation', $this->post('email'),$data);
                sendNotifWA($data['no_telp']," ");
                $message = 'Halo *'.$data['no_telp'].'*!';
                $message .= urlencode("\n"); 
                $message .= "Terima kasih sudah mendaftar di mahkota store!";
                $message .= urlencode("\n"); 
                $message .= "Simpan informasimu baik-baik ya!";
                $message .= urlencode("\n"); 
                $message .= "*No Telp* : ".$data['no_telp'];
                $message .= urlencode("\n"); 
                $message .= "*PIN* : ".$this->post('password');
                $message .= urlencode("\n"); 
                $message .= urlencode("\n"); 
                $message .= "Terima kasih!";
                sendNotifWA($data['no_telp'],$message);
                $insert = $this->GeneralModel->create_general('ms_pengguna', $data);
                
                $showError = $this->db->error();

                if($insert){
                    $this->response(
                        [
                            'status' => true,
                            'message' => 'Berhasil mendaftar',
                            'data' => $data
                        ],
                        RestController::HTTP_OK
                    );
                }else{
                    $this->response(
                        [
                            'status' => false,
                            'message' => 'Gagal mendaftar',
                            'data' => $showError
                        ],
                        RestController::HTTP_BAD_REQUEST
                    );
                }
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Data tidak boleh kosong'
            ], RestController::HTTP_BAD_REQUEST);
        }
        //--------------------- Register User -------------------//

    }

    public function recipentAddress_get()
    {
        // EndPoint : base_url/api/user/recipientAddress (POST)
        //--------------------- Recipient Address -------------------//
        if (empty($this->get('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);

        $cekPengguna = $this->db->get_where('ms_pengguna', ['login_token' => $this->get('login_token')])->row();
        if($cekPengguna){
            $getRecipientAddress = $this->db->get_where('ms_informasi_pengguna', ['id_pengguna' => $cekPengguna->id_pengguna]);
            $getRecipientAddress = $getRecipientAddress->result();
            if($getRecipientAddress){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data ditemukan',
                    'data' => $getRecipientAddress
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'
            ], RestController::HTTP_BAD_REQUEST);
        }
        //--------------------- Recipient Address -------------------//

    }



    public function addRecipentAddress_post()
    {
        // EndPoint : base_url/api/user/addRecipentAddress (POST)
        //--------------------- Add Recipent Address -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $id_pengguna = $this->post('id_pengguna');
        $nomor_hp = $this->post('nomor_hp');
        $nama = $this->post('nama');
        $alamat_lengkap = $this->post('alamat_lengkap');
        $provinsi = $this->post('provinsi');
        $kabupaten = $this->post('kabupaten');
        $kode_pos = $this->post('kode_pos');
        $lat_lokasi = $this->post('lat_lokasi');
        $lng_lokasi = $this->post('lng_lokasi');

        // if(!empty($id_pengguna) && !empty($nomor_hp) && !empty($nama) && !empty($alamat_lengkap) && !empty($provinsi) && !empty($kabupaten)){
            $data = array(
                'id_pengguna' => $id_pengguna,
                'nomor_hp' => $nomor_hp,
                'nama' => $nama,
                'alamat_lengkap' => $alamat_lengkap,
                'provinsi' => $provinsi,
                'kabupaten' => $kabupaten,
                'kode_pos' => $kode_pos,
                'lat_lokasi' => $lat_lokasi,
                'lng_lokasi' => $lng_lokasi
            );
            $insert = $this->db->insert('ms_informasi_pengguna', $data);
            if($insert){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Informasi Pengguna berhasil ditambahkan'
                ], RestController::HTTP_CREATED);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Informasi Pengguna gagal ditambahkan'
                ], RestController::HTTP_BAD_REQUEST);
            }
        // }else{
        //     $this->response([
        //         'status' => FALSE,
        //         'message' => 'Data tidak boleh kosong',
        //     ], RestController::HTTP_BAD_REQUEST);
        // }
        //--------------------- Add Recipent Address -------------------//
    }

    public function updateRecipentAddress_post()
    {
        // EndPoint : base_url/api/user/updateRecipentAddress (POST)
        //--------------------- Update Recipent Address -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $nomor_hp = $this->post('nomor_hp');
        $nama = $this->post('nama');
        $alamat_lengkap = $this->post('alamat_lengkap');
        $provinsi = $this->post('provinsi');
        $kabupaten = $this->post('kabupaten');
        $kode_pos = $this->post('kode_pos');
        $lat_lokasi = $this->post('lat_lokasi');
        $lng_lokasi = $this->post('lng_lokasi');
        $id_informasi = $this->post('id_informasi');
        if(!empty($id_informasi)){
        if(!empty($nomor_hp) && !empty($nama) && !empty($alamat_lengkap) && !empty($provinsi) && !empty($kabupaten)){
            $data = array(
                'nomor_hp' => $nomor_hp,
                'nama' => $nama,
                'alamat_lengkap' => $alamat_lengkap,
                'provinsi' => $provinsi,
                'kabupaten' => $kabupaten,
                'kode_pos' => $kode_pos,
                'lat_lokasi' => $lat_lokasi,
                'lng_lokasi' => $lng_lokasi
            );
            $this->db->where('id_informasi', $id_informasi);
            $update = $this->db->update('ms_informasi_pengguna', $data);
            if($update){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Informasi Pengguna berhasil diupdate'
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Informasi Pengguna gagal diupdate'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Data tidak boleh kosong'
            ], RestController::HTTP_BAD_REQUEST);
        }
        }
        //--------------------- Update Recipent Address -------------------//
    }

    public function deleteRecipentAddress_post()
    {
        // EndPoint : base_url/api/user/deleteRecipentAddress (POST)
        //--------------------- Delete Recipent Address -------------------//
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
        $id_informasi = $this->post('id_informasi');

        if(!empty($id_informasi)){
            $this->db->where('id_informasi', $id_informasi);
            $delete = $this->db->delete('ms_informasi_pengguna');
            if($delete){
                $this->response([
                    'status' => TRUE,
                    'message' => 'Informasi Pengguna berhasil dihapus'
                ], RestController::HTTP_OK);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Informasi Pengguna gagal dihapus'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Data tidak boleh kosong'
            ], RestController::HTTP_BAD_REQUEST);
        }
        //--------------------- Delete Recipent Address -------------------//
    }

    public function deleteAccount_post()
    {
        $no_telp = $this->post('no_telp');
        $password = sha1($this->post('password'));
        $cekUser = $this->db->query("SELECT * FROM ms_pengguna WHERE no_telp = '$no_telp' AND password = '$password' AND status = 'actived'")->result();
        if($cekUser){
                $dataPengguna = array(
                    'status' => 'deleted',
                    'updated_by' => $cekUser[0]->id_pengguna,
                    'updated_time' => date('Y-m-d H:i:s')
                );
                $updatePengguna = $this->GeneralModel->update_general('ms_pengguna','id_pengguna',$cekUser[0]->id_pengguna,$dataPengguna);
                if($updatePengguna){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Akun berhasil dihapus'
                    ], RestController::HTTP_OK);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Akun gagal dihapus'
                    ], RestController::HTTP_BAD_REQUEST);
                }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Akun tidak ditemukan'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

}