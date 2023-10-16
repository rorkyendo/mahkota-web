<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KasirModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

  function cekWaktuBuka($id_pengguna){
    return $this->db->query("SELECT * FROM ms_kasir
      WHERE pengguna = '$id_pengguna' and DATE_FORMAT(opened_time,'%Y-%m-%d')<=CURRENT_DATE() and closure_time is null")->row();
  }

  function cekTutupTerakhir($uuid_toko,$closure_time){
    return $this->db->query("SELECT k.* FROM ms_kasir k WHERE
      k.closure_time <= '$closure_time' and k.uuid_toko = '$uuid_toko'
      and DATE_FORMAT(k.opened_time,'%Y-%m-%d')=CURRENT_DATE() ORDER BY k.closure_time DESC LIMIT 1")->row();
  }

  function getByIdKasir($id_kasir){
    return $this->db->query("SELECT * FROM ms_kasir WHERE id_kasir='$id_kasir'")->row();
  }

  function getTotalPendapatan($id_pengguna,$uuid_toko){
    return $this->db->query("SELECT SUM(t.total) as total_pendapatan FROM ms_transaction t, ms_kasir k
    WHERE t.created_by = '$id_pengguna' and t.uuid_toko='$uuid_toko' and t.payment_status='payed'
    and t.created_by = k.pengguna and k.closure_time is null
    and DATE_FORMAT(k.opened_time,'%Y-%m-%d')<=CURRENT_DATE() and t.created_time >= k.opened_time")->row();
  }

  function getAllKasir($uuid_outlet,$start_date,$end_date)
  {
    return $this->db->query("SELECT k.*,p.nama_lengkap,l.location_name FROM ms_kasir k LEFT JOIN ms_pengguna p ON k.pengguna = p.id_pengguna
      LEFT JOIN ms_location l ON k.uuid_toko = l.uuid_toko
      WHERE DATE_FORMAT(k.opened_time,'%Y-%m-%d')>='$start_date'
      and DATE_FORMAT(k.opened_time,'%Y-%m-%d')<='$end_date'
      and k.uuid_outlet='$uuid_outlet'")->result();
  }

  function getAllKasirByIdKasir($uuid_outlet,$id_kasir,$start_date,$end_date)
  {
    return $this->db->query("SELECT k.*,p.nama_lengkap,l.location_name FROM ms_kasir k LEFT JOIN ms_pengguna p ON k.pengguna = p.id_pengguna
      LEFT JOIN ms_location l ON k.uuid_toko = l.uuid_toko
      WHERE DATE_FORMAT(k.opened_time,'%Y-%m-%d')>='$start_date'
      and DATE_FORMAT(k.opened_time,'%Y-%m-%d')<='$end_date'
      and k.uuid_outlet='$uuid_outlet' and k.pengguna='$id_kasir'")->result();
  }

  function getLaporanKasir($uuid_outlet,$start_date,$end_date)
  {
    return $this->db->query("SELECT k.* FROM ms_kasir k
      WHERE DATE_FORMAT(k.closure_time,'%Y-%m-%d')>='$start_date'
      and DATE_FORMAT(k.closure_time,'%Y-%m-%d')<='$end_date'
      and k.uuid_outlet='$uuid_outlet'")->result();
  }

}
