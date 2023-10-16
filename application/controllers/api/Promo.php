<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Promo extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function videoPromo_get()
    {
        // EndPoint : base_url/api/promo/videoPromo (GET)
        //--------------------- List Video Promo -------------------//
        $getPromosi = $this->db->query("SELECT * FROM ms_promosi WHERE jenis_promosi = '1' AND status_promosi = 'Y' ORDER BY urutan_promosi ASC LIMIT 6")->result();

        $this->response([
            'status' => true,
            'data' => $getPromosi
        ]);
        //--------------------- List Video Promo -------------------//
    }

    public function gambarPromo_get()
    {
        // EndPoint : base_url/api/promo/gambarPromo (GET)
        //--------------------- List Gambar Promo -------------------//
        $getPromosi = $this->db->query("SELECT * FROM ms_promosi WHERE jenis_promosi = '3' AND status_promosi = 'Y' ORDER BY urutan_promosi ASC LIMIT 6")->result();

        $this->response([
            'status' => true,
            'data' => $getPromosi
        ]);
        //--------------------- List Gambar Promo -------------------//
    }

    public function textPromo_get()
    {
        // EndPoint : base_url/api/promo/textPromo (GET)
        //--------------------- List Text Promo -------------------//
        $getPromosi = $this->db->query("SELECT * FROM ms_promosi WHERE jenis_promosi = '2' AND status_promosi = 'Y' ORDER BY urutan_promosi ASC LIMIT 6")->result();

        $this->response([
            'status' => true,
            'data' => $getPromosi
        ]);
        //--------------------- List Text Promo -------------------//
    }
    
        
}