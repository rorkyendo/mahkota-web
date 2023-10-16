<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function cekExpiredKupon()
    {
        $kupon = $this->db->query("SELECT * FROM ms_kupon")->result();
        if(!empty($kupon)){
            foreach($kupon as $kupon){
                $expired = $kupon->berlaku_hingga;
                $now = date('Y-m-d');
                if($expired < $now){
                    $this->db->query("DELETE FROM ms_voucher_terpakai WHERE kode_voucher = '$kupon->kode_kupon' AND waktu_pakai IS NULL");
                }
            }
        }
    }

}
