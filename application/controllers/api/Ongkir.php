<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Ongkir extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function getKota_get()
    {
        // EndPoint : base_url/api/ongkir/getKota (GET)
        //--------------------- Get Kota -------------------//
        $id_province = $this->get('id_province');
        $getKota = apiRajaOngkir('city?province='.$id_province);
        $getKota = json_decode($getKota);
        $this->response([
            'status' => TRUE,
            'data' => $getKota->rajaongkir->results
        ], RestController::HTTP_OK);
        //--------------------- Get Kota -------------------//
    }

    public function cariLokasi_get()
    {
        // EndPoint : base_url/api/ongkir/cariLokasi (GET)
        //--------------------- Cari Lokasi -------------------//
        $lokasi = $this->get('lokasi');
        $getLokasi = cariLokasi($lokasi);
        $getLokasi = json_decode($getLokasi);
        $this->response([
            'status' => TRUE,
            'data' => $getLokasi
        ], RestController::HTTP_OK);
        //--------------------- Cari Lokasi -------------------//
    }

    public function cariLokasiGeoCode_get()
    {
        // EndPoint : base_url/api/ongkir/cariLokasi (GET)
        //--------------------- Cari Lokasi -------------------//
        $lng = $this->get('lng');
        $lat = $this->get('lat');
        $getLokasi = cariLokasiGeoCode($lng,$lat);
        $getLokasi = json_decode($getLokasi);
        $this->response([
            'status' => TRUE,
            'data' => $getLokasi
        ], RestController::HTTP_OK);
        //--------------------- Cari Lokasi -------------------//
    }


    public function getCourir_get()
    {
        // EndPoint : base_url/api/ongkir/getCourir (GET)
        //--------------------- Get Courir -------------------//
        $dataCourir = array(
            'jne' => 'jne',
            'jnt' => 'jnt',
            'pos' => 'pos',
            'tiki' => 'tiki',
            'anteraja' => 'anteraja',
            'sicepat' => 'sicepat',
        );
        $this->response([
            'status' => TRUE,
            'data' => $dataCourir
        ], RestController::HTTP_OK);
        //--------------------- Get Courir -------------------//
    }

    public function getOngkir_post()
    {
        // EndPoint : base_url/api/ongkir/getOngkir (POST)
        //--------------------- Get Ongkir -------------------//
        $origin = $this->post('origin');
        $detination = $this->post('destination');
        $weight = $this->post('weight');
        $courier = $this->post('courier');
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://pro.rajaongkir.com/api/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=$origin&originType=city&destination=$detination&destinationType=city&weight=$weight&courier=$courier",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: 84e274d66f7553eaee7fc9ac5b27ab09"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->response([
                'status' => FALSE,
                'message' => $err
            ], RestController::HTTP_NOT_FOUND);
            //echo "cURL Error #:" . $err;
        } else {
            $this->response([
                'status' => TRUE,
                'data' => json_decode($response)
            ], RestController::HTTP_OK);
            //echo $response;
        }
    }

    public function getOngkirProvice_get()
    {
        // EndPoint : base_url/api/ongkir/getOngkirProvice (GET)
        //--------------------- Get Ongkir Provice -------------------//
        $id = $this->get('id');
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://pro.rajaongkir.com/api/province?id=$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: 84e274d66f7553eaee7fc9ac5b27ab09"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->response([
                'status' => FALSE,
                'message' => $err
            ], RestController::HTTP_NOT_FOUND);
            //echo "cURL Error #:" . $err;
        } else {
            $this->response([
                'status' => TRUE,
                'data' => json_decode($response)
            ], RestController::HTTP_OK);
            //echo $response;
        }
    }

    public function getOngkirCity_get()
    {
        // EndPoint : base_url/api/ongkir/getOngkirCity (GET)
        //--------------------- Get Ongkir City -------------------//
        $id = $this->get('id');
        $province = $this->get('province');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://pro.rajaongkir.com/api/city?id=$id&province=$province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "key: 84e274d66f7553eaee7fc9ac5b27ab09"
            ),
          ));
          
          $response = curl_exec($curl);
          $err = curl_error($curl);
          
          curl_close($curl);
          
          if ($err) {
            $this->response([
                'status' => FALSE,
                'message' => $err
            ], RestController::HTTP_NOT_FOUND);
            //echo "cURL Error #:" . $err;
          } else {
            $this->response([
                'status' => TRUE,
                'data' => json_decode($response)
            ], RestController::HTTP_OK);
            // echo $response;
          }
        //--------------------- Get Ongkir City -------------------//
    }

    public function checkingResi_get(){
        $cekResiTransaksi = $this->db->query("SELECT * FROM ms_transaksi WHERE status_pengiriman = 'dikirim' AND no_resi IS NOT NULL")->result();
        $resi = '';
        foreach ($cekResiTransaksi as $key) {
            $cekResi = $this->db->query("SELECT * FROM ms_resi WHERE transaksi = '$key->id_transaksi'")->row();
            if(empty($cekResi)){
                $curl = curl_init();
                if($key->courier!='tiki' && $key->courier!='jne'){
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "http://pro.rajaongkir.com/api/waybill",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "waybill=$key->no_resi&courier=".$key->courier,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/x-www-form-urlencoded",
                            "key: 84e274d66f7553eaee7fc9ac5b27ab09"
                        ),
                    ));
                }else{
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.binderbyte.com/v1/track?api_key=67f7c1b4624816d424d14f24d034a585cad60ddbbace9d5e6d544e0f4714fc76&courier='.$key->courier.'&awb='.$key->no_resi,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));
                }
        
                $response = curl_exec($curl);
                $err = curl_error($curl);
        
                curl_close($curl);
        
                if ($err) {
                } else {
                    $response = json_decode($response);
                    if($response){
                        if(isset($response->data)){
                            if($response->data->summary->status == 'DELIVERED'){
                                $updaStatus = array(
                                    'status_pengiriman' => 'sampai'
                                );
                                $this->GeneralModel->update_general('ms_transaksi','id_transaksi',$key->id_transaksi,$updaStatus);
                                if(empty($cekResi)){
                                    $dataResi = array(
                                        'transaksi' => $key->id_transaksi,
                                        'response' => json_encode($response)
                                    );
                                    $this->GeneralModel->create_general('ms_resi',$dataResi);
                                    $resi .= $key->no_resi. '('.$key->courier.') baru saja terupdate,';
                                }
                            }
                        }elseif (isset($response->rajaongkir)) {
                            if($response->rajaongkir->result->delivery_status->status == 'DELIVERED'){
                                $updaStatus = array(
                                    'status_pengiriman' => 'sampai'
                                );
                                $this->GeneralModel->update_general('ms_transaksi','id_transaksi',$key->id_transaksi,$updaStatus);
                                if(empty($cekResi)){
                                    $dataResi = array(
                                        'transaksi' => $key->id_transaksi,
                                        'response' => json_encode($response)
                                    );
                                    $this->GeneralModel->create_general('ms_resi',$dataResi);
                                    $resi .= $key->no_resi. '('.$key->courier.') baru saja terupdate,';
                                }
                            }
                        }
                    }
                }
            }else{
                $resi .= $key->no_resi. '('.$key->courier.') sudah terupdate,';
            }
        }
        $this->response([
            'status' => TRUE,
            'message' => 'Data resi berhasil di cek dan diupdate',
            'resiMessage' => $resi
        ], RestController::HTTP_OK);
    }

    public function getLocationPacket_get()
    {
        // EndPoint : base_url/api/ongkir/getLocationPacket (GET)
        //--------------------- Get Location Packet -------------------//
        $no_resi = $this->get('no_resi');
        $courier = $this->get('courier');
        $curl = curl_init();
        $cekResiTransaksi = $this->db->query("SELECT * FROM ms_transaksi WHERE courier='$courier' AND no_resi='$no_resi'")->row();
        $cekResi = $this->db->query("SELECT * FROM ms_resi WHERE transaksi = '$cekResiTransaksi->id_transaksi'")->row();
        if(!empty($cekResi)){
            $this->response([
                'status' => TRUE,
                'data' => json_decode($cekResi->response)
            ], RestController::HTTP_OK);
        }elseif($cekResiTransaksi){
            if($courier!='tiki' && $courier!='jne'){
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://pro.rajaongkir.com/api/waybill",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "waybill=$no_resi&courier=".$courier,
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded",
                        "key: 84e274d66f7553eaee7fc9ac5b27ab09"
                    ),
                ));
            }else{
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.binderbyte.com/v1/track?api_key=67f7c1b4624816d424d14f24d034a585cad60ddbbace9d5e6d544e0f4714fc76&courier='.$courier.'&awb='.$no_resi,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
            }
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            curl_close($curl);
    
            if ($err) {
                $response = $err;
        
                $this->response([
                    'status' => FALSE,
                    'message' => $response
                ], RestController::HTTP_NOT_FOUND);
            } else {
                $response = json_decode($response);
        
                if($response->data->summary != null){
                    if($response->data->summary->status == 'DELIVERED'){
                        $updaStatus = array(
                            'status_pengiriman' => 'sampai'
                        );
                        $this->GeneralModel->update_general('ms_transaksi','id_transaksi',$cekResiTransaksi->id_transaksi,$updaStatus);
                        if(empty($cekResi)){
                            $dataResi = array(
                                'transaksi' => $cekResiTransaksi->id_transaksi,
                                'response' => json_encode($response)
                            );
                            $this->GeneralModel->create_general('ms_resi',$dataResi);
                        }
                    }
                }elseif ($response->rajaongkir->result != null) {
                    if($response->rajaongkir->result->delivery_status->status == 'DELIVERED'){
                        $updaStatus = array(
                            'status_pengiriman' => 'sampai'
                        );
                        $this->GeneralModel->update_general('ms_transaksi','id_transaksi',$cekResiTransaksi->id_transaksi,$updaStatus);
                        if(empty($cekResi)){
                            $dataResi = array(
                                'transaksi' => $cekResiTransaksi->id_transaksi,
                                'response' => json_encode($response)
                            );
                            $this->GeneralModel->create_general('ms_resi',$dataResi);
                        }
                    }
                }
        
                $this->response([
                    'status' => TRUE,
                    'data' => $response
                ], RestController::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'NOT FOUND'
            ], RestController::HTTP_NOT_FOUND);
        }
       //--------------------- Get Location Packet -------------------//
    }
}