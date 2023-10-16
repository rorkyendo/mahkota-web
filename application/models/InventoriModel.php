<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InventoriModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

    public function getInventori($uuid_toko,$gudang,$lokasi_penyimpanan)
  {
    $this->datatables->select('*,ms_inventori.id_inventori as id_inventori');
    $this->datatables->from('ms_inventori');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/inventori/updateInventori/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/inventori/hapusInventori/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus inventori? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_inventori'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    if (!empty($gudang)) {
      $this->datatables->where("gudang",$gudang);
    }
    if (!empty($lokasi_penyimpanan)) {
      $this->datatables->where("lokasi_penyimpanan",$lokasi_penyimpanan);
    }
    return print_r($this->datatables->generate('json'));
  }


  public function getGudang($uuid_toko)
  {
    $this->datatables->select('*,ms_gudang.id_gudang as id_gudang');
    $this->datatables->from('ms_gudang');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/inventori/updateGudang/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/inventori/hapusGudang/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus gudang? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_gudang'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    return print_r($this->datatables->generate('json'));
  }

    public function getLokasiPenyimpanan($uuid_toko,$gudang)
  {
    $this->datatables->select('*,v_lokasi_penyimpanan.id_lokasi_penyimpanan as id_lokasi_penyimpanan');
    $this->datatables->from('v_lokasi_penyimpanan');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/inventori/updateLokasiPenyimpanan/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/inventori/hapusLokasiPenyimpanan/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus lokasi penyimpanan? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_lokasi_penyimpanan'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    if (!empty($gudang)) {
      $this->datatables->where("gudang",$gudang);
    }
    return print_r($this->datatables->generate('json'));
  }

}
