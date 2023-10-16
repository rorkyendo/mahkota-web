<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Toko extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function listToko_get()
    {
        // EndPoint : base_url/api/toko/listToko (GET)
        //--------------------- List Toko -------------------//
        $getToko = $this->db->order_by('urutan_toko', 'ASC')->get('ms_toko')->result();
        $start = $this->get('start');
        $limit = $this->get('limit');

        $getToko = array_slice($getToko, $start, $limit);
        $this->response([
            'status' => true, 
            'data' => $getToko]
        , 200);
        //--------------------- List Toko -------------------//
    }

    public function listActiveToko_get()
    {
        // EndPoint : base_url/api/toko/listActiveToko (GET)
        //--------------------- List Toko -------------------//
        $getToko = $this->db->where('status_toko', '1')->order_by('urutan_toko', 'ASC')->get('ms_toko')->result();
        $start = $this->get('start');
        $limit = $this->get('limit');

        $getToko = array_slice($getToko, $start, $limit);
        $this->response([
            'status' => true,
            'data' => $getToko]
        , 200);
        //--------------------- List Toko -------------------//
    }

    public function detailToko_get()
    {
        // EndPoint : base_url/api/toko/detailToko (GET)
        //--------------------- Detail Toko -------------------//
        $getToko = $this->db->get_where('ms_toko', ['uuid_toko' => $this->get('uuid_toko')])->result();

        $getProduk = $this->db->get_where('v_produk', ['uuid_toko' => $this->get('uuid_toko')])->result();

        if ($getToko) {
            $this->response([
                'status' => true,
                'data' => $getToko,
                'produk' => $getProduk]
            , 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data Toko tidak ditemukan'
            ], 404);
        }
        //--------------------- Detail Toko -------------------//
    }

    public function getDaerah_get()
    {
        // EndPoint : base_url/api/toko/getDaerah (GET)
        //--------------------- Detail Toko -------------------//
        $getDaerah = $this->db->get('ms_kota')->result();
        $this->response([
            'status' => true,
            'data' => $getDaerah]
        , 200);
        //--------------------- Detail Toko -------------------//
    }

    public function getTokoByDaerah_post()
    {
        // EndPoint : base_url/api/toko/getTokoByDaerah (POST)
        //--------------------- Detail Toko -------------------//
        $daerah = $this->post('daerah');
        if($daerah != ''){
            $getKota = $this->db->get_where('ms_kota', ['kabupaten_kota' => $daerah])->row();
            $getSemuaToko = $this->db->get('ms_toko')->result();

            // Mengecek Radius kota dengan data toko jika dibawah 10KM maka tampilkan toko
            $getToko = array();
            foreach ($getSemuaToko as $key => $value) {
                $getToko[$key] = $value;
                $getToko[$key]->jarak = getRadius($getKota->lat, $getKota->long, $value->lat_toko, $value->lng_toko);

                if ($getToko[$key]->jarak > 30000) {
                    unset($getToko[$key]);
                }
            }
            $getToko = array_values($getToko);
            $this->response([
                'status' => true,
                'data' => $getToko]
            , 200);
        }else{
            $this->response([
                'status' => false,
                'message' => 'Harap isi daerah'
            ], 404);
        }
    }

    public function getTokoByLocationUser_post()
    {
        // EndPoint : base_url/api/toko/getTokoByLocationUser (POST)
        //--------------------- Detail Toko -------------------//
        $lat = $this->post('lat');
        $lng = $this->post('lng');
        $getSemuaToko = $this->db->get('ms_toko')->result();

        // Mengecek Radius kota dengan data toko jika dibawah 10KM maka tampilkan toko
        $getToko = array();
        foreach ($getSemuaToko as $key => $value) {
            $getToko[$key] = $value;
            $getToko[$key]->jarak = getRadius($lat, $lng, $value->lat_toko, $value->lng_toko);

            if ($getToko[$key]->jarak > 10000) {
                unset($getToko[$key]);
            }
        }
        $getToko = array_values($getToko);
        $this->response([
            'status' => true,
            'data' => $getToko]
        , 200);
    }
    
        
}