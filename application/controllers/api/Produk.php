<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Produk extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->devel_url = $this->config->item("devel_url");
        $this->prod_url = $this->config->item("prod_url");
        $this->username = $this->config->item("username");
        $this->apiKey = $this->config->item("apiKey");
    }

   public function listKategoriProduk_get()
   {
       // EndPoint : base_url/api/produk/kategoriProduk (GET)
        //--------------------- List Kategori Produk -------------------//
        $getKategori = $this->db->get('ms_kategori_produk')->result();
        $this->response([
            'status' => true, 
            'data' => $getKategori]
        , 200);
        //--------------------- List Kategori Produk -------------------//
   }

   public function kategoriProduk_get()
    {
         // EndPoint : base_url/api/produk/kategoriProdukByName (GET)
        //--------------------- List Kategori Produk -------------------//
        $getKategori = $this->db->get_where('ms_kategori_produk', ['nama_kategori_produk' => $this->get('kategori')])->result();
        $this->response([
            'status' => true,
            'data' => $getKategori]
        , 200);
        //--------------------- List Kategori Produk -------------------//
    }

    // https://mahkotastore.com/api/produk/listMerkProduk?id_kategori=${idCategory}
    public function listMerkProduk_get()
    {
        // EndPoint : base_url/api/produk/listMerkProduk (GET)
        //--------------------- List Merk Produk -------------------//
        $getMerk = $this->db->query("SELECT * FROM ms_brand WHERE kategori = '".$this->get('id_kategori')."'")->result();
        $this->response([
            'status' => true,
            'data' => $getMerk]
        , 200);
        //--------------------- List Merk Produk -------------------//
    }

    // https://mahkotastore.com/api/produk/listAllMerk
    public function listAllMerk_get()
    {
        // EndPoint : base_url/api/produk/listAllMerk (GET)
        //--------------------- List Merk Produk -------------------//
        // ambil semua merk dengan ketentuan gambar tidak null atau kosong
        $getMerk = $this->db->query("SELECT * FROM ms_brand WHERE gambar_brand IS NOT NULL AND status_brand = 'Y'")->result();
        $this->response([
            'status' => true,
            'data' => $getMerk]
        , 200);
        //--------------------- List Merk Produk -------------------//
    }

    // https://mahkotastore.com/api/produk/listProdukByKategoriMerk?kategori=${category}&merk=${merk}
    public function listProdukByKategoriMerk_get()
    {
        // EndPoint : base_url/api/produk/listProdukByKategoriMerk (GET)
        //--------------------- List Produk -------------------//
        if($this->get('kategori') == 'Semua Produk'){
            $kategori = NULL;
        }else{
            $kategori = $this->get('kategori');
        }

        if($this->get('merk') == 'Semua Merk Dan Type'){
            $merk = NULL;
        }else{
            $merk = $this->get('merk');
        }
        
        $this->db->select('*');
        $this->db->from('v_produk');
        $this->db->where('tampil_toko', 'Y');
        if($kategori != NULL){
            $this->db->where('nama_kategori_produk', $kategori);
        }
        if($merk != NULL){
            $this->db->where('nama_brand', $merk);
        }
        $this->db->order_by('id_produk', 'DESC');
        $getProduk = $this->db->get()->result();


        if($getProduk){
            $start = $this->get('start');
            $limit = $this->get('limit');

            $getProduk = array_slice($getProduk, $start, $limit);

            $this->response([
                'status' => true,
                'data' => $getProduk]
            , 200);
        }
        else{
            $this->response([
                'status' => false,
                'message' => 'Data Produk tidak ditemukan',
                'data' => $getProduk
            ], 404);
        }
        //--------------------- List Produk -------------------//
    }



    public function listProduk_get()
    {
        // EndPoint : base_url/api/produk/listProduk (GET)
        //--------------------- List Produk -------------------//
        $getProduk = $this->db->query("SELECT * FROM v_produk WHERE tampil_toko = 'Y' and foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();

        if ($getProduk) {
            $start = $this->get('start');
            $limit = $this->get('limit');

            $getProduk = array_slice($getProduk, $start, $limit);

            $this->response([
                'status' => true,
                'data' => $getProduk]
            , 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data Produk tidak ditemukan'
            ], 404);
        }
        //--------------------- List Produk -------------------//
    }

    public function produk_get()
    {
        // EndPoint : base_url/api/produk/produk (GET)
        //--------------------- List Produk -------------------//
        $produk = $this->get('produk');
        $getProduk = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$produk%' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();

        if($getProduk){
            $start = $this->get('start');
            $limit = $this->get('limit');

            $getProduk = array_slice($getProduk, $start, $limit);

            $this->response([
                'status' => true,
                'data' => $getProduk]
            , 200);
        }
        else{
            $this->response([
                'status' => false,
                'message' => 'Data Produk tidak ditemukan'
            ], 404);
        }
        //--------------------- List Produk -------------------//
    }

    public function listProdukByKategori_get()
    {
        // EndPoint : base_url/api/produk/listProdukByKategori (GET)
        //--------------------- List Produk -------------------//
        $kategori = $this->get('kategori');
        $getProduk = $this->db->query("SELECT * FROM v_produk WHERE tampil_toko = 'Y' and nama_kategori_produk LIKE '%$kategori%' and foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();

        if($getProduk){
            $start = $this->get('start');
            $limit = $this->get('limit');

            $getProduk = array_slice($getProduk, $start, $limit);

            $this->response([
                'status' => true,
                'data' => $getProduk]
            , 200);
        }
        else{
            $this->response([
                'status' => false,
                'message' => 'Data Produk tidak ditemukan'
            ], 404);
        }
        //--------------------- List Produk -------------------//
    }

    public function listProdukByKategoriAndProduk_get()
    {
        // EndPoint : base_url/api/produk/listProdukByKategoriAndProduk (GET)
        //--------------------- List Produk -------------------//
        // Get Where Like
        $getProduk = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk LIKE '%".$this->get('kategori')."%' AND nama_produk LIKE '%".$this->get('produk')."%' AND foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();

        if($getProduk){
            $start = $this->get('start');
            $limit = $this->get('limit');

            $getProduk = array_slice($getProduk, $start, $limit);

            $this->response([
                'status' => true,
                'data' => $getProduk]
            , 200);
        }
        else{
            $this->response([
                'status' => false,
                'message' => 'Data Produk tidak ditemukan'
            ], 404);
        }
        //--------------------- List Produk -------------------//
    }

    public function listProdukByKategoriAndBrand_get()
    {
        // EndPoint : base_url/api/produk/listProdukByKategoriAndBrand (GET)
        //--------------------- List Produk -------------------//
        // Get Where Like
        if($this->get('produk') == 'MINISO'){
            $getProduk = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk LIKE '%MINISO X SAMONO%' AND foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();
        }else{
            $getProduk = $this->db->query("SELECT * FROM v_produk WHERE nama_kategori_produk LIKE '%".$this->get('kategori')."%' AND nama_brand LIKE '%".$this->get('produk')."%' AND foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();
        }
        if($getProduk){
            $start = $this->get('start');
            $limit = $this->get('limit');

            $getProduk = array_slice($getProduk, $start, $limit);

            $this->response([
                'status' => true,
                'data' => $getProduk]
            , 200);
        }
        else{
            $this->response([
                'status' => false,
                'message' => 'Data Produk tidak ditemukan'
            ], 404);
        }
        //--------------------- List Produk -------------------//
    }

    public function detailProduk_get()
    {
        // EndPoint : base_url/api/produk/detailProduk (GET)
        //--------------------- Detail Produk -------------------//
        $getProduk = $this->db->get_where('v_produk', ['id_produk' => $this->get('id')])->result();
        $dilihat = $getProduk[0]->dilihat+1;

        $data = array(
            'dilihat' => $dilihat
        );
        $this->GeneralModel->update_general('ms_produk','id_produk',$this->get('id'),$data);
        // $addView = $this->db->query("UPDATE ms_produk SET dilihat = dilihat + 1 WHERE id_produk = '".$this->get('id')."'");
        $this->response([
            'status' => true,
            'data' => $getProduk]
        , 200);
        //--------------------- Detail Produk -------------------//
    }

    public function searchProduk_get()
    {
        // EndPoint : base_url/api/produk/searchProduk (GET)
        //--------------------- Search Produk -------------------//
        $produk = $this->get("search");
        $getProduk = $this->db->query("SELECT * FROM v_produk WHERE tampil_toko = 'Y' and nama_produk LIKE '%$produk%' and foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();
        $this->response([
            'status' => true,
            'data' => $getProduk]
        , 200);
        //--------------------- Search Produk -------------------//
    }

    public function produkByToko_get()
    {
        // EndPoint : base_url/api/produk/produkByToko (GET)
        //--------------------- Produk By Toko -------------------//
        $toko = $this->get('uuid_toko');
        $getProduk = $this->db->query("SELECT * FROM v_produk WHERE tampil_toko = 'Y' and uuid_toko = '$toko' and foto_produk IS NOT NULL ORDER BY id_produk DESC")->result();

        $start = $this->get('start');
        $limit = $this->get('limit');

        $getProduk = array_slice($getProduk, $start, $limit);

        $this->response([
            'status' => true,
            'data' => $getProduk]
        , 200);
        //--------------------- Produk By Toko -------------------//
    }

    public function search_post()
    {
        $from = $this->post('from');
		$limit = $this->post('limit');
        $kategori = $this->post('kategori');
        $nama_produk = $this->post('nama_produk');
        if ($kategori=='populer') {
            $produk = $this->GeneralModel->paginate_by_like_id_general_order_by('v_produk','nama_produk',$nama_produk,'tampil_toko','Y','dilihat','DESC',$limit,$from);
        }elseif($kategori=='harga'){
            $produk = $this->GeneralModel->paginate_by_like_id_general_order_by('v_produk','nama_produk',$nama_produk,'tampil_toko','Y','harga_jual','ASC',$limit,$from);
        }else{
            $produk = $this->db->query("SELECT * FROM v_produk WHERE nama_produk LIKE '%$nama_produk%' AND tampil_toko='Y' AND foto_produk IS NOT NULL ORDER BY id_produk DESC LIMIT $limit OFFSET $from")->result();
        }
        $this->response([
            'status' => true,
            'data' => $produk
        ], 200);

    }

    public function onlineProduk_post(){
        $produk = $this->post('produk');

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->devel_url."api/pricelist/pulsa/".$produk,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "username"     : "'.$this->username.'",
            "sign"     : "'.md5($this->username.$this->apiKey.'pl').'",
            "status" : "all"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $this->response([
            'status' => true,
            'data' => $response
        ], 200);
    }

    public function promoProduk_get()
    {
        // EndPoint : base_url/api/produk/promoProduk (GET)
        //--------------------- Promo Produk -------------------//
        $getPromo = $this->db->query("SELECT * FROM ms_produk WHERE harga_diskon > 0 AND foto_produk IS NOT NULL ORDER BY RAND() LIMIT 6")->result();
        $this->response([
            'status' => true,
            'data' => $getPromo]
        , 200);
        //--------------------- Promo Produk -------------------//
    }

    public function promoProdukAccessories_get()
    {
        // EndPoint : base_url/api/produk/promoProdukAccessories (GET)
        //--------------------- Promo Produk Accessories -------------------//
        $getPromo = $this->db->query("SELECT * FROM ms_produk WHERE harga_diskon > 0 AND foto_produk IS NOT NULL AND kategori_produk = '4' ORDER BY RAND() LIMIT 6")->result();
        $this->response([
            'status' => true,
            'data' => $getPromo]
        , 200);
        //--------------------- Promo Produk Accessories -------------------//
    }

    public function promoProdukHandphone_get()
    {
        // EndPoint : base_url/api/produk/promoProdukHandphone (GET)
        //--------------------- Promo Produk Handphone -------------------//
        $getPromo = $this->db->query("SELECT * FROM ms_produk WHERE harga_diskon > 0 AND foto_produk IS NOT NULL AND kategori_produk = '1' ORDER BY RAND() LIMIT 6")->result();
        $this->response([
            'status' => true,
            'data' => $getPromo]
        , 200);
        //--------------------- Promo Produk Handphone -------------------//
    }

    public function addToChart_post(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        $session_id = session_id();
        $idProduk = $this->post('idProduk');
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
            // $dataOrder = array(
            //     'qty' => $cekOrderTemp[0]->qty + $this->input->post('qty')
            // );				
            $newQty = $cekOrderTemp[0]->qty + $qty;
            $dataOrder = array(
                'qty' => $newQty,
                'promo_potongan' => ($getProdukData[0]->harga_jual_online - $harga_jual) * $newQty,
                'subtotal' => $newQty * $harga_jual
            );
            $this->GeneralModel->update_general('ms_order','id_order',$cekOrderTemp[0]->id_order,$dataOrder);
            $this->response(['status' => true, 'message' => 'Produk Berhasil Ditambahkan'], 200);
        }else{
            // $dataOrder = array(
            //     'qty' => $qty,
            //     'produk' => $idProduk,
            //     'selling_price' => $harga_jual,
            //     'capital_price' => $getProdukData[0]->harga_modal,
            //     'promo_potongan' => $getProdukData[0]->harga_diskon*$qty,
            //     'subtotal' => $qty * $harga_jual,
            //     'uuid_toko' => $getProdukData[0]->uuid_toko,
            //     'temp_transaksi' => $id_transaksi
            // );

            $dataOrder = array(
                'qty' => $qty,
                'produk' => $idProduk,	
                'tipe_member' => $this->post('tipe_member'),
                'selling_price' => $harga_jual,
                'capital_price' => $getProdukData[0]->harga_modal,
                'promo_potongan' => ($getProdukData[0]->harga_jual_online - $getProdukData[0]->harga_diskon) * $qty,
                'subtotal' => $qty * $harga_jual,
                'uuid_toko' => $getProdukData[0]->uuid_toko,
                'temp_transaksi' => $id_transaksi
            );

            $this->GeneralModel->create_general('ms_order',$dataOrder);
            $this->response(['status' => true, 'message' => 'Produk Berhasil Ditambahkan'], 200);
        }
	}

    public function removeFromCart_post(){
		$temp_transaksi = $this->post('temp_transaksi');
		$order = $this->post('order');
        if($this->GeneralModel->delete_multi_id_general('ms_order', 'temp_transaksi', $temp_transaksi, 'id_order', $order) == TRUE){
            echo "true";
        }else{
            echo "false";
        }
	}

    public function updateQty_post(){
        if (empty($this->post('login_token'))) $this->response(['status' => false, 'message' => 'Akses Tidak Di izinkan Harap Sertakan Login Token'], 401);
		$order = $this->post('order');
		$qty = $this->post('qty');
        $cekOrder = $this->GeneralModel->get_by_id_general('v_order','id_order', $order);
        if ($cekOrder) {
            foreach ($cekOrder as $key) {
                $data = array(
                    'qty' => $qty
                );
                if($key->payment_status == 'pending' || empty($key->payment_status)){
                    if($this->GeneralModel->update_general('ms_order','id_order',$order,$data) == TRUE){
                        $this->response(['status' => true, 'message' => 'Berhasil'], 200);
                    }else{
                        $this->response(['status' => false, 'message' => 'Terjadi Kesalahan'], 200);
                    }
                }else{
                    $this->response(['status' => false, 'message' => 'Transaksi yang sudah di proses tidak boleh di edit'], 401);
                }
            }
        }else{
            $this->response(['status' => false, 'message' => 'Tidak Ada Data'], 200);
        }
    }

    public function listBrandByKategori_get(){
        $kategori = $this->get('kategori');
        $cekKategori = $this->db->get_where('ms_kategori_produk', ['nama_kategori_produk' => $kategori])->row();
        if($cekKategori){
            $getBrand = $this->db->query("SELECT * FROM ms_brand WHERE kategori = '$cekKategori->id_kategori_produk'")->result();
            $this->response([
                'status' => true,
                'data' => $getBrand]
            , 200);
        }else{
            $this->response([
                'status' => false,
                'message' => 'Kategori Tidak Ditemukan'
            ], 404);
        }
    }
    
        
}