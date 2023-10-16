<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FinanceModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

    public function getIndukGroupAkun($uuid_toko)
  {
    $this->datatables->select('*,ms_induk_group_kas.id_induk_group_kas as id_induk_group_kas');
    $this->datatables->from('ms_induk_group_kas');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/finance/updateIndukGroupAkun/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/finance/hapusIndukGroupAkun/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus induk group akun kas? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_induk_group_kas'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    return print_r($this->datatables->generate('json'));
  }

    public function getGroupAkun($uuid_toko,$kode_induk_group_kas)
  {
    $this->datatables->select('*,ms_group_kas.id_group_kas as id_group_kas');
    $this->datatables->from('ms_group_kas');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/finance/updateGroupAkun/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/finance/hapusGroupAkun/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus group akun kas? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_group_kas'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    if (!empty($kode_induk_group_kas)) {
      $this->datatables->where("kode_induk_group_kas",$kode_induk_group_kas);
    }
    return print_r($this->datatables->generate('json'));
  }

    public function getAkun($uuid_toko,$kode_induk_group_kas,$kode_group_kas)
  {
    $this->datatables->select('*,ms_akun_kas.id_akun_kas as id_akun_kas');
    $this->datatables->from('ms_akun_kas');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/finance/updateAkun/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/finance/hapusAkun/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus akun kas? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_akun_kas'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    if (!empty($kode_induk_group_kas)) {
      $this->datatables->where("kode_induk_group_kas",$kode_induk_group_kas);
    }
    if (!empty($kode_group_kas)) {
      $this->datatables->where("kode_group_kas",$kode_group_kas);
    }
    return print_r($this->datatables->generate('json'));
  }

    public function getRekening($status_rekening)
  {
    $this->datatables->select('*,ms_rekening.id_rekening as id_rekening');
    $this->datatables->from('ms_rekening');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/finance/updateRekening/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/finance/hapusRekening/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus data rekening? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_rekening'
    );
    if (!empty($status_rekening)) {
      $this->datatables->where("status_rekening",$status_rekening);
    }
    return print_r($this->datatables->generate('json'));
  }

    public function getPenarikan($status_penarikan,$start_date,$end_date)
  {
    $this->datatables->select('*,v_penarikan.id_penarikan as id_penarikan');
    $this->datatables->from('v_penarikan');
    if (!empty($status_penarikan)) {
      $this->datatables->where("status_penarikan",$status_penarikan);
    }
    if (!empty($start_date)) {
      $this->db->where("DATE_FORMAT(request_date,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->db->where("DATE_FORMAT(request_date,'%Y-%m-%d') <= '$end_date'");
    }
    return print_r($this->datatables->generate('json'));
  }



}
