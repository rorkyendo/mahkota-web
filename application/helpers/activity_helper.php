<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  function activityLog($class='',$controller='',$id='')
  {
    $CI =& get_instance();
    if (!empty($controller)) {
      $dataAktivitas = array(
        'id_pengguna' => $CI->session->userdata('id_pengguna'),
        'parent_modul' => $class,
        'modul' => $controller,
		'id' => $id,
        'ip_address' => $CI->input->ip_address(),
        'user_agents' => $CI->agent->agent_string(),
      );
    }else {
      $dataAktivitas = array(
        'id_pengguna' => $CI->session->userdata('id_pengguna'),
        'parent_modul' => $class,
		'id' => $id,
		'ip_address' => $CI->input->ip_address(),
        'user_agents' => $CI->agent->agent_string(),
      );
    }
    $CI->GeneralModel->create_general('ms_activity',$dataAktivitas);
  }

function apiRajaOngkir($action){
	   	$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://api.rajaongkir.com/starter/".$action,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		  	/* masukan api key disini*/
		    "key: 84e274d66f7553eaee7fc9ac5b27ab09"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		  return  $err;
		} else {
		  return $response;
		}
}

function cariLokasi($val){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=".$val."&inputtype=textquery&fields=formatted_address%2Cname%2Crating%2Copening_hours%2Cgeometry&key=AIzaSyA1do2F8MWGnL4MPGT0NIQBo1qzvEr64YE",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return  $err;
    } else {
        return $response;
    }

  }

  function cariLokasiGeoCode($lng,$lat){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://forward-reverse-geocoding.p.rapidapi.com/v1/reverse?lat=".$lat."&lon=".$lng."&addressdetails=1&namedetails=1&accept-language=id&polygon_threshold=0.0",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: forward-reverse-geocoding.p.rapidapi.com",
			"X-RapidAPI-Key: "
		],
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return  $err;
    } else {
        return $response;
    }

  }

  
  function slugify($text, string $divider = '-')
  {
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }

  function tempTransaksi(){
    $CI =& get_instance();
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    $session_id = session_id();
    $tempTransaksi = $CI->OrderModel->getDuplicate($ip_address,$CI->session->userdata('id_pengguna'),$session_id);
    return $tempTransaksi;
  }

  function cekTempTransaksiPengguna($id_temp_transaksi){
    $CI =& get_instance();
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    $session_id = session_id();
    $tempTransaksi = $CI->OrderModel->getDuplicate($ip_address,$CI->session->userdata('id_pengguna'),$session_id);
    if($tempTransaksi->id_temp_transaksi == $id_temp_transaksi){
      return TRUE;
    }else{
      return FALSE;
    }
  }

  if ( !function_exists('getRadius')){
      /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function getRadius(
      $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
      // convert from degrees to radians
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);

      $latDelta = $latTo - $latFrom;
      $lonDelta = $lonTo - $lonFrom;

      $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
      return $angle * $earthRadius;
    }

    function memberShip($id_pengguna='',$tipeMember='')
    {
      $CI =& get_instance();
  			$cekMember = $CI->GeneralModel->get_by_id_general('ms_member','id_pengguna',$id_pengguna);
				$tempdir = 'assets/qrcodeMember/';
				if($cekMember){					
					$getTipeMembership = $CI->GeneralModel->get_by_id_general('ms_tipe_member', 'id_tipe_member', $tipeMember);
					$dataMember = array(
						'id_pengguna' => $id_pengguna,
						'id_tipe_member' => $tipeMember,
						'kode_tipe' => $getTipeMembership[0]->kode_tipe_member,
						'expired_date' => DATE('Y-m-d', strtotime('+'.$getTipeMembership[0]->waktu_berlaku.' days')),
						'status' => 'active',
						'updated_time' => date('Y-m-d H:i:s'),
						'updated_by' => $CI->session->userdata('id_pengguna')
					);

					$recek = $CI->GeneralModel->limit_by_id_general_order_by('ms_member', 'kode_tipe', substr($getTipeMembership[0]->kode_tipe_member,0,3), 'kode_member', 'DESC', '1');
					if($recek){
						$dataMember += array(
							'kode_member' => $recek[0]->kode_member+1,
						);
						$padded = str_pad((string)$dataMember['kode_member'], 6, "0", STR_PAD_LEFT);
						$dataMember+=array(
										'barcode_member' => $dataMember['kode_tipe'].$padded
						);
					}else{
						$dataMember += array(
							'kode_member' => 000001,
							'barcode_member' => $getTipeMembership[0]->kode_tipe_member.'000001'
						);
					}
					$dataMember += array(
						'barcode_member_encrypt' => sha1($dataMember['barcode_member'])
					);
					$CI->GeneralModel->update_general('ms_member','id_member',$cekMember[0]->id_member,$dataMember);
					$id_member = $cekMember[0]->id_member;
					//isi qrcode jika di scan
					$codeContents = $cekMember[0]->barcode_member;
					$dataQrFile = $codeContents.'.png';	
					//------------------ Pembuatan Barcode ----------------------------//
					$CI->zend->load('Zend/Barcode.php'); 
					$barcode = $dataQrFile;
					$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$codeContents), array())->draw($codeContents,'image', array('text'=>$codeContents), array());
					$imageName = $barcode;
					$imagePath = 'assets/barcodeMember/';
					imagejpeg($imageResource, $imagePath.$imageName); 
					$pathBarcode = $imagePath.$imageName; 				
					$dataBarcode = array(
						'barcode' => $pathBarcode
					);
					//------------------ Pembuatan QR Code ----------------------------//
					//simpan file qrcode
					$dataQrFile = $codeContents.'.png';		

					QRcode::png($codeContents, $tempdir . $dataQrFile, QR_ECLEVEL_H, 10, 4);

					// ambil file qrcode
					imagecreatefrompng($tempdir .$dataQrFile);

					$dataBarcode += array(
						'qrcode' => 'assets/qrcodeMember/'.$dataQrFile
					);

					$CI->GeneralModel->update_general('ms_member', 'id_member', $id_member, $dataBarcode);
					//------------------ End Of Pembuatan QR Code ----------------------------//
          			return true;
        }else{
					//-------- CREATE DATA MEMBER ----------//
					$getTipeMembership = $CI->GeneralModel->get_by_id_general('ms_tipe_member', 'id_tipe_member', $tipeMember);

					$dataMember = array(
						'id_pengguna' => $id_pengguna,
						'id_tipe_member' => $tipeMember,
						'kode_tipe' => $getTipeMembership[0]->kode_tipe_member,
						'expired_date' => DATE('Y-m-d', strtotime('+'.$getTipeMembership[0]->waktu_berlaku.' days')),
						'status' => 'active',
					);

					$recek = $CI->GeneralModel->limit_by_id_general_order_by('ms_member', 'kode_tipe', substr($getTipeMembership[0]->kode_tipe_member,0,3), 'kode_member', 'DESC', '1');
					if($recek){
						$dataMember += array(
							'kode_member' => $recek[0]->kode_member+1,
						);
						$padded = str_pad((string)$dataMember['kode_member'], 6, "0", STR_PAD_LEFT);
						$dataMember+=array(
										'barcode_member' => $dataMember['kode_tipe'].$padded
						);
					}else{
						$dataMember += array(
							'kode_member' => 000001,
							'barcode_member' => $getTipeMembership[0]->kode_tipe_member.'000001'
						);
					}
					$dataMember += array(
						'barcode_member_encrypt' => sha1($dataMember['barcode_member'])
					);

					$CI->GeneralModel->create_general('ms_member',$dataMember);

					$id_member = $CI->db->insert_id();
					$barcode = $dataMember['barcode_member'];
					
					//isi qrcode jika di scan
					$codeContents = $barcode;
					$dataQrFile = $codeContents.'.png';		
					//------------------ Pembuatan Barcode ----------------------------//
					$CI->zend->load('Zend/Barcode.php'); 
					$barcode = $dataQrFile;
					$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$codeContents), array())->draw($codeContents,'image', array('text'=>$codeContents), array());
					$imageName = $barcode;
					$imagePath = 'assets/barcodeMember/';
					imagejpeg($imageResource, $imagePath.$imageName); 
					$pathBarcode = $imagePath.$imageName; 				
					$dataBarcode = array(
						'barcode' => $pathBarcode,
					);
					//------------------ Pembuatan QR Code ----------------------------//
					//simpan file qrcode
					$dataQrFile = $dataMember['barcode_member'].'.png';				
					QRcode::png($codeContents, $tempdir . $dataQrFile, QR_ECLEVEL_H, 10, 4);

					// ambil file qrcode
					imagecreatefrompng($tempdir .$dataQrFile);

					$dataBarcode += array(
						'qrcode' => 'assets/qrcodeMember/'.$dataQrFile
					);


					$CI->GeneralModel->update_general('ms_member', 'id_member', $id_member, $dataBarcode);
					//------------------ End Of Pembuatan QR Code ----------------------------//
          return true;
				}
    }

	function makeBarcode($id_member,$barcodeMember){
		$CI =& get_instance();
		$barcode = $barcodeMember;
		$tempdir = 'assets/qrcodeMember/';
		
		//isi qrcode jika di scan
		$codeContents = $barcode;
		$dataQrFile = $codeContents.'.png';		
		//------------------ Pembuatan Barcode ----------------------------//
		$CI->zend->load('Zend/Barcode.php'); 
		$barcode = $dataQrFile;
		$imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$codeContents), array())->draw($codeContents,'image', array('text'=>$codeContents), array());
		$imageName = $barcode;
		$imagePath = 'assets/barcodeMember/';
		imagejpeg($imageResource, $imagePath.$imageName); 
		$pathBarcode = $imagePath.$imageName; 				
		$dataBarcode = array(
			'barcode' => $pathBarcode,
		);
		//------------------ Pembuatan QR Code ----------------------------//
		//simpan file qrcode
		$dataQrFile = $barcodeMember.'.png';				
		QRcode::png($codeContents, $tempdir . $dataQrFile, QR_ECLEVEL_H, 10, 4);

		// ambil file qrcode
		imagecreatefrompng($tempdir .$dataQrFile);

		$dataBarcode += array(
			'qrcode' => 'assets/qrcodeMember/'.$dataQrFile
		);


		$CI->GeneralModel->update_general('ms_member', 'id_member', $id_member, $dataBarcode);
	}

	function deleteTransMemberPending(){
		$CI =& get_instance();
		$cekMember = $CI->GeneralModel->get_by_multi_id_general('ms_transaksi','payment_status','pending','jenis_transaksi','member');
		foreach ($cekMember as $key) {
			try {
				if(!empty($key->bukti_transfer)){
					unlink($key->bukti_transfer);
				}
			} catch (\Throwable $th) {
			}
			$CI->GeneralModel->delete_general('ms_transaksi','id_transaksi',$key->id_transaksi);
		}
	}
}