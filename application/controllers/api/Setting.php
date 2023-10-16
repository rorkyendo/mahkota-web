<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Setting extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function indentitas_get()
    {
        $indentitas = $this->db->get('ms_identitas')->row();
        $this->response($indentitas, 200);
    }

    public function listSlider_get()
    {
        // EndPoint : base_url/api/setting/listSlider (GET)
        //--------------------- List Slider -------------------//
        $getSlider = $this->db->get('ms_slider')->result();
        $this->response([
            'status' => true,
            'data' => $getSlider]
        , 200);
         //--------------------- List Slider -------------------//
    }

    public function listSliderActive_get()
    {
        // EndPoint : base_url/api/setting/listSliderActive (GET)
        //--------------------- List Slider -------------------//
        $getSlider = $this->db->query("SELECT * FROM ms_slider WHERE status_slider = 'Y' ORDER BY urutan_slider ASC")->result();
        $this->response([
            'status' => true,
            'data' => $getSlider]
        , 200);
         //--------------------- List Slider -------------------//
    }

    public function listPromotion_get()
    {
        // EndPoint : base_url/api/setting/listPromotion (GET)
        //--------------------- List Promotion -------------------//
        $getPromotion = $this->db->query("SELECT * FROM ms_promotion WHERE status_promosi = 'Y'")->result();
        $this->response([
            'status' => true,
            'data' => $getPromotion]
        , 200);
        //--------------------- List Promotion -------------------//
    }

    public function listPromotionActive_get()
    {
        // EndPoint : base_url/api/setting/listPromotionActive (GET)
        //--------------------- List Promotion -------------------//
        // Promotion order by urutan promosi & limit 5
        $getPromotion = $this->db->query("SELECT * FROM ms_promosi WHERE status_promosi = 'Y' AND jenis_promosi = '2' ORDER BY urutan_promosi ASC LIMIT 5")->result();
        $this->response([
            'status' => true,
            'data' => $getPromotion]
        , 200);
        //--------------------- List Promotion -------------------//
    }

    public function listRekeningBank_get()
    {
        // EndPoint : base_url/api/setting/listRekeningBank (GET)
        //--------------------- List Rekening Bank -------------------//
        $getRekeningBank = $this->db->get('ms_rekening_bank')->result();
        $this->response([
            'status' => true,
            'data' => $getRekeningBank]
        , 200);
        //--------------------- List Rekening Bank -------------------//
    }

    public function listProvince_get()
    {
        // EndPoint : base_url/api/setting/listProvince (GET)
        //--------------------- List Province -------------------//
        $provinsi = apiRajaOngkir('province');
        $provinsi = json_decode($provinsi);
        $this->response([
            'status' => true,
            'data' => $provinsi->rajaongkir->results]
        , 200);
        //--------------------- List Province -------------------//
        
    }

    public function getPromoBanner_get()
    {
        // EndPoint : base_url/api/setting/getPromoBanner (GET)
        //--------------------- List Promo Banner -------------------//
        $getPromoBanner = $this->db->query("SELECT * FROM ms_promosi WHERE status_promosi = 'Y' AND lokasi_promosi = 'mobile' ORDER BY urutan_promosi")->result();
        $this->response([
            'status' => true,
            'data' => $getPromoBanner]
        , 200);
        //--------------------- List Promo Banner -------------------//
    }

    public function getPromoBannerCategory_get()
    {
        // EndPoint : base_url/api/setting/getPromoBannerCategory (GET)
        //--------------------- List Promo Banner Category -------------------//
        $getPromoBannerCategory = $this->db->query("SELECT * FROM v_promosi WHERE status_promosi = 'Y' AND lokasi_promosi = 'mobile' AND nama_kategori_produk IS NOT NULL ORDER BY urutan_promosi")->result();
        $formattedData = array();
        foreach ($getPromoBannerCategory as $item) {
            $formattedData[] = array(
                'id' => $item->id_promosi,            // Assuming 'id' is a column in your ms_promosi table
                'title' => $item->nama_kategori_produk,      // Assuming 'title' is a column in your ms_promosi table
                'img' => $item->file_promosi           // Assuming 'img' is a column in your ms_promosi table
            );
        }
        $this->response([
            'status' => true,
            'data' => $formattedData]
        , 200);
        //--------------------- List Promo Banner Category -------------------//
    }

    
    
        
}