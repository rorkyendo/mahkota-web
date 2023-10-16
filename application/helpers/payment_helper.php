<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('payment'))
{
  function payment($data,$buyerName,$buyerEmail,$buyerPhone,$id_transaction)
  {
      $CI =& get_instance();
      // SAMPLE HIT API iPaymu v2 PHP //
      //$va           = '0000002276648478'; //get on iPaymu dashboard
      //$apiKey       = 'SANDBOX61F69E4D-2721-4885-B6FA-371EE5D880A5'; //get on iPaymu dashboard
      //$url          = 'https://sandbox.ipaymu.com/api/v2/payment';
      $va = '';
      $apiKey = '';    
      $url          = 'https://my.ipaymu.com/api/v2/payment'; 
      $method       = 'POST'; //method

      //Request Body//
      $body['product']    = $data['product'];
      $body['qty']        = $data['qty'];
      $body['price']      = $data['price'];
      $body['buyerName']      = $buyerName;
      $body['buyerEmail']      = $buyerEmail;
      $body['buyerPhone']      = $buyerPhone;
      $body['expiredType']      = "hours";
      $body['expired']      = "24";
      $body['feeDirection']      = "BUYER";
      $body['returnUrl']  = base_url('transaksi/returnTransaksi/'.my_simple_crypt($id_transaction,'e'));
      $body['cancelUrl']  = base_url('transaksi/deleteTransaksi/'.$id_transaction);
      $body['notifyUrl']  = base_url('api/transaksi/notify/');
      $body['referenceId'] = $id_transaction; //your reference id
      //End Request Body//

      //Generate Signature
      // *Don't change this
      $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
      $requestBody  = strtolower(hash('sha256', $jsonBody));
      $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
      $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
      $timestamp    = Date('YmdHis');
      //End Generate Signature


      $ch = curl_init($url);

      $headers = array(
          'Accept: application/json',
          'Content-Type: application/json',
          'va: ' . $va,
          'signature: ' . $signature,
          'timestamp: ' . $timestamp
      );
  
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
      curl_setopt($ch, CURLOPT_POST, count($body));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      $err = curl_error($ch);
      $ret = curl_exec($ch);
      curl_close($ch);
  
      if($err) {
          echo $err;
      } else {
  
          //Response
          $ret = json_decode($ret);
          if($ret->Status == 200) {
              $sessionId  = $ret->Data->SessionID;
              $url        =  $ret->Data->Url;
              header('Location:' . $url);
          } else {
              print_r($ret);
          }
          //End Response
      }

  }
}
 

function paymentAPi($data,$buyerName,$buyerEmail,$buyerPhone,$id_transaction)
{
    $CI =& get_instance();
    $va = '';
    $apiKey = '';    
    $url          = 'https://my.ipaymu.com/api/v2/payment'; 
    $method       = 'POST'; //method

    //Request Body//
    $body['product']    = $data['product'];
    $body['qty']        = $data['qty'];
    $body['price']      = $data['price'];
    $body['buyerName']      = $buyerName;
    $body['buyerEmail']      = $buyerEmail;
    $body['buyerPhone']      = $buyerPhone;
    $body['expiredType']      = "hours";
    $body['expired']      = "24";
    $body['feeDirection']      = "BUYER";
    $body['returnUrl']  = base_url('api/transaksi/returnTransaksi/'.my_simple_crypt($id_transaction,'e').'/'.$buyerPhone);
    $body['cancelUrl']  = base_url('transaksi/deleteTransaksi/'.$id_transaction);
    $body['notifyUrl']  = base_url('api/transaksi/notify/');
    $body['referenceId'] = $id_transaction; //your reference id
    //End Request Body//

    //Generate Signature
    // *Don't change this
    $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
    $requestBody  = strtolower(hash('sha256', $jsonBody));
    $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
    $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
    $timestamp    = Date('YmdHis');
    //End Generate Signature


    $ch = curl_init($url);

    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'va: ' . $va,
        'signature: ' . $signature,
        'timestamp: ' . $timestamp
    );

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POST, count($body));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $err = curl_error($ch);
    $ret = curl_exec($ch);
    curl_close($ch);

    if($err) {
        echo $err;
    } else {

        //Response
        $ret = json_decode($ret);
        if($ret->Status == 200) {
            $status    = $ret->Status;
            $sessionId  = $ret->Data->SessionID;
            $url        =  $ret->Data->Url;
            $data = array(
                'status' => $status,
                'id_transaksi' => $id_transaction,
                'sessionId' => $sessionId,
                'url' => $url
            );
            echo json_encode($data);
        } else {
            $data = array(
                'status' => $ret->Status,
                'message' => $ret->Message
            );
            echo json_encode($data);
        }
        //End Response
    }

}
 
function checkTransaction($id_transaction)
{
    $CI =& get_instance();
    // SAMPLE HIT API iPaymu v2 PHP //
    $va           = '0000002276648478'; //get on iPaymu dashboard
    $apiKey       = 'SANDBOX61F69E4D-2721-4885-B6FA-371EE5D880A5'; //get on iPaymu dashboard
    $url          = 'https://sandbox.ipaymu.com/api/v2/transaction';

    $method       = 'POST'; //method

    $body['transactionId']      = $id_transaction;
    //End Request Body//

    //Generate Signature
    // *Don't change this
    $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
    $requestBody  = strtolower(hash('sha256', $jsonBody));
    $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
    $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
    $timestamp    = Date('YmdHis');
    //End Generate Signature



    $ch = curl_init($url);

    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'va: ' . $va,
        'signature: ' . $signature,
        'timestamp: ' . $timestamp
    );

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POST, count($body));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $err = curl_error($ch);
    $ret = curl_exec($ch);
    curl_close($ch);

    if($err) {
        echo $err;
    } else {
        return $ret;
    }

}

function directPayment($data,$buyerName,$buyerEmail,$buyerPhone,$amount,$paymentMethod,$paymentChannel,$id_transaction)
{
    $CI =& get_instance();
    // SAMPLE HIT API iPaymu v2 PHP //
    $va           = '0000002276648478'; //get on iPaymu dashboard
    $apiKey       = 'SANDBOX61F69E4D-2721-4885-B6FA-371EE5D880A5'; //get on iPaymu dashboard
    $url          = 'https://sandbox.ipaymu.com/api/v2/direct';
    $method       = 'POST'; //method

    //Request Body//
    $body['amount']      = $amount;
    $body['paymentMethod']      = $paymentMethod;
    $body['paymentChannel']      = $paymentChannel;
    $body['expiredType']      = "hours";
    $body['expired']      = "24";
    $body['feeDirection']      = "BUYER";
    $body['product']    = $data['product'];
    $body['qty']        = $data['qty'];
    $body['price']      = $data['price'];
    $body['buyerName']      = $buyerName;
    $body['buyerEmail']      = $buyerEmail;
    $body['buyerPhone']      = $buyerPhone;
    $body['notifyUrl']  = base_url('transaksi/notify/');
    $body['referenceId'] = $id_transaction; //your reference id
    //End Request Body//

    //Generate Signature
    // *Don't change this
    $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
    $requestBody  = strtolower(hash('sha256', $jsonBody));
    $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
    $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
    $timestamp    = Date('YmdHis');
    //End Generate Signature


    $ch = curl_init($url);

    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'va: ' . $va,
        'signature: ' . $signature,
        'timestamp: ' . $timestamp
    );

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POST, count($body));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $err = curl_error($ch);
    $ret = curl_exec($ch);
    curl_close($ch);

    if($err) {
        echo $err;
    } else {

        //Response
        $ret = json_decode($ret);
        if($ret->Status == 200) {
            $sessionId  = $ret->Data->SessionID;
            $url        =  $ret->Data->Url;
            header('Location:' . $url);
        } else {
            print_r($ret);
        }
        //End Response
    }

}

function paymentMethod()
{
    $CI =& get_instance();
    // SAMPLE HIT API iPaymu v2 PHP //
    // $va           = '0000002276648478'; //get on iPaymu dashboard
    // $apiKey       = 'SANDBOX61F69E4D-2721-4885-B6FA-371EE5D880A5'; //get on iPaymu dashboard
    // $url          = 'https://sandbox.ipaymu.com/api/v2/payment-method-list';
    $va = '1179005370366666';
    $apiKey = 'DCF24388-020E-4E9F-BEB9-B446331701BB';
    $url          = 'https://my.ipaymu.com/api/v2/payment-method-list'; //url

    $method       = 'POST'; //method

    $body['timestamp']      = strtotime(DATE('Y-m-d H:i:s'));
    //End Request Body//

    //Generate Signature
    // *Don't change this
    $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
    $requestBody  = strtolower(hash('sha256', $jsonBody));
    $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
    $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
    $timestamp    = Date('YmdHis');
    //End Generate Signature

    $ch = curl_init($url);

    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'va: ' . $va,
        'signature: ' . $signature,
        'timestamp: ' . $timestamp
    );

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POST, count($body));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $err = curl_error($ch);
    $ret = curl_exec($ch);
    curl_close($ch);

    if($err) {
        echo $err;
    } else {
        return $ret;
    }

}

function cekInvoiceXendit($trx_id){
    require 'vendor/autoload.php';

    $dev_key = 'xnd_development_fNmerVsAPk0ncS7SgkUXLPw06KhfJMob9CwzllRNwUnaOy6Z7eezwpIUgmyOYQt';
    
    \Xendit\Xendit::setApiKey($prod_key);
    // \Xendit\Xendit::setApiKey($dev_key);
    
    $getInvoice = \Xendit\Invoice::retrieve($trx_id);
    return $getInvoice;
}

function closeInvoiceXendit($trx_id){
    require 'vendor/autoload.php';

    $dev_key = 'xnd_development_fNmerVsAPk0ncS7SgkUXLPw06KhfJMob9CwzllRNwUnaOy6Z7eezwpIUgmyOYQt';

    \Xendit\Xendit::setApiKey($prod_key);
    // \Xendit\Xendit::setApiKey($dev_key);

    $expireInvoice = \Xendit\Invoice::expireInvoice($trx_id);
    if(!empty($expireInvoice)){
        if($expireInvoice['status'] == 'EXPIRED'){
            return true;
        }
    }else{
        return false;
    }
}

function invoiceLinkXendit($transaksi,$email,$data) {
    require 'vendor/autoload.php';

    $dev_key = 'xnd_development_fNmerVsAPk0ncS7SgkUXLPw06KhfJMob9CwzllRNwUnaOy6Z7eezwpIUgmyOYQt';
    
    \Xendit\Xendit::setApiKey($prod_key);
    // \Xendit\Xendit::setApiKey($dev_key);
    
    $params = [ 
        'external_id' => $transaksi->id_transaksi,
        'amount' => $transaksi->total,
        'description' => 'Invoice #'.$transaksi->id_transaksi,
        'invoice_duration' => 86400,
        'customer' => [
            'given_names' => $transaksi->nama_lengkap_pelanggan,
            'email' => $email,
            'mobile_number' => "+62" . substr_replace($transaksi->no_telp_pelanggan, "", 0, 1),
        ],
        'customer_notification_preference' => [
            'invoice_created' => [
                'whatsapp',
                'sms',    
                'email'
            ],
            'invoice_reminder' => [
                'whatsapp',
                'sms',    
                'email'
            ],
            'invoice_paid' => [
                'whatsapp',
                'sms',    
                'email'
            ],
            'invoice_expired' => [
                'whatsapp',
                'sms',    
                'email'
            ]
        ],
        'success_redirect_url' => base_url('user/profile/'),
        'failure_redirect_url' => base_url('user/profile/'),
        'currency' => 'IDR',
        'items' => $data['items'],
        'fees' => [
            [
                'type' => 'ADMIN',
                'value' => 5000
            ]
        ]
      ];
    
    $createInvoice = \Xendit\Invoice::create($params);
    if(!empty($createInvoice)){
        $dataTransaksi = array(
            'trx_id' => $createInvoice['id'],
            'invoice_url' => $createInvoice['invoice_url']
        );
    
        $CI =& get_instance();
        if($CI->GeneralModel->update_general('ms_transaksi','id_transaksi',$transaksi->id_transaksi,$dataTransaksi) == TRUE){
            redirect('transaksi/bayar/'.$transaksi->id_transaksi);
        }
    }
}

function invoiceDigitalXendit($transaksi,$data) {
    require 'vendor/autoload.php';

    $dev_key = 'xnd_development_fNmerVsAPk0ncS7SgkUXLPw06KhfJMob9CwzllRNwUnaOy6Z7eezwpIUgmyOYQt';
    
    \Xendit\Xendit::setApiKey($prod_key);
    // \Xendit\Xendit::setApiKey($dev_key);
    
    $params = [ 
        'external_id' => $transaksi->id_transaksi,
        'amount' => $transaksi->total,
        'description' => 'Invoice #'.$transaksi->id_transaksi,
        'invoice_duration' => 86400,
        'customer' => [
            'given_names' => $transaksi->nama_lengkap_pelanggan,
            'mobile_number' => "+62" . substr_replace($transaksi->no_telp_pelanggan, "", 0, 1),
        ],
        'customer_notification_preference' => [
            'invoice_created' => [
                'whatsapp',
                'sms'    
            ],
            'invoice_reminder' => [
                'whatsapp',
                'sms'    
            ],
            'invoice_paid' => [
                'whatsapp',
                'sms'    
            ],
            'invoice_expired' => [
                'whatsapp',
                'sms'    
            ]
        ],
        'payment_methods' => ['OVO','DANA','SHOPEEPAY','QRIS'],
        'currency' => 'IDR',
        'items' => $data['items'],
      ];
    
    $createInvoice = \Xendit\Invoice::create($params);
    if(!empty($createInvoice)){
        $dataTransaksi = array(
            'trx_id' => $createInvoice['id'],
            'invoice_url' => $createInvoice['invoice_url']
        );
    
        $CI =& get_instance();
        if($CI->GeneralModel->update_general('ms_transaksi','id_transaksi',$transaksi->id_transaksi,$dataTransaksi) == TRUE){
            // redirect('transaksi/bayar/'.$transaksi->id_transaksi);
        }
    }
}

function invoiceDigitalXenditMobile($transaksi,$data) {
    require 'vendor/autoload.php';

    $dev_key = 'xnd_development_fNmerVsAPk0ncS7SgkUXLPw06KhfJMob9CwzllRNwUnaOy6Z7eezwpIUgmyOYQt';
    
    \Xendit\Xendit::setApiKey($prod_key);
    // \Xendit\Xendit::setApiKey($dev_key);
    
    $params = [ 
        'external_id' => $transaksi->id_transaksi,
        'amount' => $transaksi->total,
        'description' => 'Invoice #'.$transaksi->id_transaksi,
        'invoice_duration' => 86400,
        'customer' => [
            'given_names' => $transaksi->nama_lengkap_pelanggan,
            'mobile_number' => "+62" . substr_replace($transaksi->no_telp_pelanggan, "", 0, 1),
        ],
        'customer_notification_preference' => [
            'invoice_created' => [
                'whatsapp',
                'sms'    
            ],
            'invoice_reminder' => [
                'whatsapp',
                'sms'    
            ],
            'invoice_paid' => [
                'whatsapp',
                'sms'    
            ],
            'invoice_expired' => [
                'whatsapp',
                'sms'    
            ]
        ],
        'payment_methods' => ['OVO','DANA','SHOPEEPAY','QRIS'],
        'success_redirect_url' => base_url('transaksi/cekTransaksiDigital?id_transaksi='.$transaksi->id_transaksi.'&pengguna='.$transaksi->created_by),
        'currency' => 'IDR',
        'items' => $data['items'],
      ];
    
      $createInvoice = \Xendit\Invoice::create($params);
      if(!empty($createInvoice)){
          $dataTransaksi = array(
              'trx_id' => $createInvoice['id'],
              'invoice_url' => $createInvoice['invoice_url']
          );
      
          $CI =& get_instance();
          if($CI->GeneralModel->update_general('ms_transaksi','id_transaksi',$transaksi->id_transaksi,$dataTransaksi) == TRUE){
            return true;
          }
      }
}

function invoiceLinkXenditMobile($transaksi,$email,$data) {
    require 'vendor/autoload.php';

    $dev_key = 'xnd_development_fNmerVsAPk0ncS7SgkUXLPw06KhfJMob9CwzllRNwUnaOy6Z7eezwpIUgmyOYQt';
    
    \Xendit\Xendit::setApiKey($prod_key);
    // \Xendit\Xendit::setApiKey($dev_key);

    if(!empty($email)){
        $params = [ 
            'external_id' => $transaksi->id_transaksi,
            'amount' => $transaksi->total,
            'description' => 'Invoice #'.$transaksi->id_transaksi,
            'invoice_duration' => 86400,
            'customer' => [
                'given_names' => $transaksi->nama_lengkap_pelanggan,
                'email' => $email,
                'mobile_number' => "+62" . substr_replace($transaksi->no_telp_pelanggan, "", 0, 1),
            ],
            'customer_notification_preference' => [
                'invoice_created' => [
                    'whatsapp',
                    'sms',    
                    'email'
                ],
                'invoice_reminder' => [
                    'whatsapp',
                    'sms',    
                    'email'
                ],
                'invoice_paid' => [
                    'whatsapp',
                    'sms',    
                    'email'
                ],
                'invoice_expired' => [
                    'whatsapp',
                    'sms',    
                    'email'
                ]
            ],
            'currency' => 'IDR',
            'items' => $data['items'],
            'fees' => [
                [
                    'type' => 'ADMIN',
                    'value' => 5000
                ]
            ]
          ];
    }else{
        $params = [ 
            'external_id' => $transaksi->id_transaksi,
            'amount' => $transaksi->total,
            'description' => 'Invoice #'.$transaksi->id_transaksi,
            'invoice_duration' => 86400,
            'customer' => [
                'given_names' => $transaksi->nama_lengkap_pelanggan,
                'mobile_number' => "+62" . substr_replace($transaksi->no_telp_pelanggan, "", 0, 1),
            ],
            'success_redirect_url' => base_url('user/profile/'),
            'failure_redirect_url' => base_url('user/profile/'),
            'currency' => 'IDR',
            'invoice_created' => [
                'whatsapp',
                'sms'
            ],
            'invoice_reminder' => [
                'whatsapp',
                'sms'
            ],
            'invoice_paid' => [
                'whatsapp',
                'sms'
            ],
            'invoice_expired' => [
                'whatsapp',
                'sms'
            ],
            'items' => $data['items'],
            'fees' => [
                [
                    'type' => 'ADMIN',
                    'value' => 5000
                ]
            ]
          ];
    }    
    $createInvoice = \Xendit\Invoice::create($params);
    if(!empty($createInvoice)){
        $dataTransaksi = array(
            'trx_id' => $createInvoice['id'],
            'invoice_url' => $createInvoice['invoice_url']
        );
    
        $CI =& get_instance();
        if($CI->GeneralModel->update_general('ms_transaksi','id_transaksi',$transaksi->id_transaksi,$dataTransaksi) == TRUE){
            return true;
        }
    }

}

