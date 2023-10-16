<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('Send_SMS'))
{
  function sendSMS($to,$text) {
    $userkey = '';
    $passkey = '';
    $telepon = $to;
    $message = $text;
    $url = 'https://console.zenziva.net/reguler/api/sendsms/';
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
    curl_setopt($curlHandle, CURLOPT_POST, 1);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
        'userkey' => $userkey,
        'passkey' => $passkey,
        'to' => $telepon,
        'message' => $message
    ));
    $results = json_decode(curl_exec($curlHandle), true);
    curl_close($curlHandle);
    return true;
  }
}

function sendNotifWA($nomer,$pesan,$img=''){
      $CI =& get_instance();
      $token = ''; //masukan token disii
      $curl = curl_init();
      $pesan    = $pesan;
      $jadwal_kirim = date('Y-m-d H:i:s');
      $forms    = 'no_wa='.$nomer."&pesan=".$pesan."&jadwal_kirim=".$jadwal_kirim."&url_image=".$img;

      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $forms );
      curl_setopt($curl, CURLOPT_URL, "https://mediodev.my.id/public/api/kirim_wa");
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl, CURLOPT_TIMEOUT,60);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/x-www-form-urlencoded",
        "Authorization: Bearer ".$token
        )
      );
      $result = curl_exec($curl);
      // Check for timeout
      if ($result === false) {
        // Handle the timeout here
        if (!empty($CI->session->userdata('nama_lengkap'))) {
          $message2 = 'Halo '.$CI->session->userdata('nama_lengkap').', ';
        }else{
          $message2 = 'Halo '.$CI->session->userdata('no_telp').', ';
        }
        $six_digit_with_dots = implode('.', str_split($CI->session->userdata('kode')));
        $message2 .= $six_digit_with_dots;
        $message2 .= "\n"; 
        $message2 .= " Jangan berikan kepada siapapun untuk keamanan informasi, Terima kasih!";
        sendSMS($nomer,$message2); // Call the sendSMS() function when a timeout occurs
      }
      curl_close($curl);
      $res = json_decode($result);
      if($res->status == 'berhasil'){
        return true;
      }else{
        return false;
      }
}

function sendNotifWA2($nomer,$pesan,$img=''){
  $token = ''; //masukan token disii

  // $nomer = str_replace(substr($nomer,0,1),'62',substr($nomer,0,1)).substr($nomer,1);

  $curl = curl_init();
  $pesan    = $pesan;
  $jadwal_kirim = date('Y-m-d H:i:s');
  $forms    = 'no_wa='.$nomer."&pesan=".$pesan."&jadwal_kirim=".$jadwal_kirim."&url_image=".$img;

  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $forms );
  curl_setopt($curl, CURLOPT_URL, "https://mediodev.my.id/public/api/kirim_wa");
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Bearer ".$token
    )
  );
  $result = curl_exec($curl);
  curl_close($curl);
  
  $res = json_decode($result);
  if($res->status == 'berhasil'){
    return true;
  }else{
    return false;
  }
}


function sendNotifWAButton($nomer,$pesan,$btnName='',$url='',$footerText=''){
  $token = ''; //masukan token disii

  $curl = curl_init();
  $pesan    = $pesan;
  $jadwal_kirim = date('Y-m-d H:i:s');
  $forms    = 'no_wa='.$nomer."&pesan=".$pesan."&jadwal_kirim=".$jadwal_kirim."&button=".$btnName."|url|".$url.";&footer=".$footerText;

  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $forms );
  curl_setopt($curl, CURLOPT_URL, "https://mediodev.my.id/public/api/kirim_wa_button");
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Bearer ".$token
    )
  );

  $result = curl_exec($curl);
  curl_close($curl);
  
  $res = json_decode($result);
  if($res->status == 'berhasil'){
    return true;
  }else{
    return false;
  }
}
