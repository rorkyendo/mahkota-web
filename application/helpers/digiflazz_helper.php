<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function checkPriceList(){
        // Informasi akun Digiflazz
        $username = "";
        $apiKey = "";
        $prodApiKey = "";
        $fragmentUrl = "pricelist";

        // Membuat string untuk di-MD5
        $signString = $username . $prodApiKey . $fragmentUrl;
        $sign = md5($signString);

        // Data yang akan dikirim sebagai body permintaan
        $data = [
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign,
            // 'code' => NULL
        ];

        // URL API Digiflazz
        $apiUrl = "https://api.digiflazz.com/v1/price-list";

        // Membuat permintaan menggunakan cURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($data),
        ));
        // Menampilkan respons dari API (JSON)
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function cekSaldo(){
        // Informasi akun Digiflazz
        $username = "";
        $apiKey = "";
        $prodApiKey = "";
        $fragmentUrl = "depo";

        // Membuat string untuk di-MD5
        $signString = $username . $apiKey . $fragmentUrl;
        $sign = md5($signString);

        // Data yang akan dikirim sebagai body permintaan
        $data = [
            'cmd' => 'deposit',
            'username' => $username,
            'sign' => $sign,
        ];

        // URL API Digiflazz
        $apiUrl = "https://api.digiflazz.com/v1/cek-saldo";

        // Membuat permintaan menggunakan cURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($data),
        ));
        // Menampilkan respons dari API (JSON)
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function topUp($idTrx,$nomor_digital,$sku_code,$testing){
        // Informasi akun Digiflazz
        $username = "";
        $apiKey = "";
        $prodApiKey = "";
        $fragmentUrl = $idTrx;

        // Membuat string untuk di-MD5
        $signString = $username . $prodApiKey . $fragmentUrl;
        $sign = md5($signString);

        // Data yang akan dikirim sebagai body permintaan
        $data = [
            'username' => $username,
            'buyer_sku_code' => $sku_code,
            'customer_no' => $nomor_digital,
            'ref_id' => $idTrx,
            'sign' => $sign,
            'cb_url' => 'https://mahkotastore.com/status_digital/',
        ];

        // URL API Digiflazz
        $apiUrl = "https://api.digiflazz.com/v1/transaction";

        // Membuat permintaan menggunakan cURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($data),
        ));
        // Menampilkan respons dari API (JSON)
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

?>