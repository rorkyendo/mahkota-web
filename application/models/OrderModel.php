<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

  public function getDuplicate($ip_address,$pelanggan,$session_id){
    return $this->db->query("SELECT t.* FROM ms_temp_transaksi t LEFT JOIN ms_order o ON o.temp_transaksi = t.id_temp_transaksi WHERE (t.pelanggan = '$pelanggan' OR t.ip_address = '$ip_address' OR t.session_id = '$session_id')")->row();
  }

}
