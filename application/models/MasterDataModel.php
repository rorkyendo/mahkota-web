<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MasterDataModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

  public function getHakAkses(){
    return $this->db->query("SELECT ha.* FROM ms_hak_akses ha")->result();
  }

  public function getPengguna($hak_akses,$status,$uuid_toko)
  {
    $this->datatables->select('*,v_pengguna.id_pengguna as id_pengguna');
    $this->datatables->from('v_pengguna');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/masterData/updatePengguna/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/masterData/hapusPengguna/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus pengguna?')")),
      'id_pengguna'
    );
    if (!empty($hak_akses)) {
      $this->datatables->where("hak_akses = '$hak_akses'");
    }
    if (!empty($status)) {
      $this->datatables->where("status = '$status'");
    }
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko = '$uuid_toko'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getToko($status)
  {
    $this->datatables->select('*,ms_toko.uuid_toko as uuid_toko');
    $this->datatables->from('ms_toko');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/masterData/updateToko/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/masterData/hapusToko/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus toko? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'uuid_toko'
    );
    if ($status!='') {
      $this->datatables->where("status_toko = '$status'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getDaftarSatuan()
  {
    $this->datatables->select('*,ms_satuan.id_satuan as id_satuan');
    $this->datatables->from('ms_satuan');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/masterData/updateSatuan/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/masterData/hapusSatuan/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus kode satuan?')")),
      'id_satuan'
    );
    return print_r($this->datatables->generate('json'));
  }

  public function getDaftarBrand($kategori='')
  {
    $this->datatables->select('*,v_brand.id_brand as id_brand');
    $this->datatables->from('v_brand');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/masterData/updateBrand/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/masterData/hapusBrand/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus brand?')")),
      'id_brand'
    );
    if (!empty($kategori)) {
      $this->datatables->where("kategori = '$kategori'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getKategoriProduk()
  {
    $this->datatables->select('*,ms_kategori_produk.id_kategori_produk as id_kategori_produk');
    $this->datatables->from('ms_kategori_produk');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/masterData/updateKategoriProduk/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/masterData/hapusKategoriProduk/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus kategori produk? data yang dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_kategori_produk'
    );
    return print_r($this->datatables->generate('json'));
  }

  function getDaftarToko($status='',$start='',$perPage=''){
    if ($status!='') {
      $this->db->where("status_toko",$status);
    }
    $this->db->order_by("urutan_toko","ASC");
    return $this->db->get('ms_toko',$perPage,$start)->result();
  }

  function getDetailToko($slug_toko){
    return $this->db->query("SELECT * FROM ms_toko WHERE slug_toko = '$slug_toko' and status_toko = '1'")->result();
  }

  function getInformasiPengguna($id_pengguna='')
  {
    $this->datatables->select('*,ms_informasi_pengguna.id_informasi as id_informasi');
    $this->datatables->from('ms_informasi_pengguna');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('user/updateAddress/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-sm')) . ' '
      . anchor(changeLink('user/hapusAddress/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-sm', "onclick" => "return confirm('Apakah kamu yakin akan menghapus alamat ini?')")),
      'id_informasi'
    );
    if (!empty($id_pengguna)) {
      $this->datatables->where("id_pengguna = '$id_pengguna'");
    }
    return print_r($this->datatables->generate('json'));
  }

  function getTiket($id_pengguna='',$status='',$kategori_tiket='',$start_date='',$end_date='')
  {
    $this->datatables->select('*,ms_tiket.id_tiket as id_tiket');
    $this->datatables->from('ms_tiket');
    if (!empty($id_pengguna)) {
      $this->datatables->where("pengguna = '$id_pengguna'");
    }
    if (!empty($kategori_tiket)) {
      $this->datatables->where("kategori_tiket = '$kategori_tiket'");
    }
    if (!empty($status)) {
      $this->datatables->where("status_tiket = '$status'");
    }
    if (!empty($start_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    return print_r($this->datatables->generate('json'));
  }

}
