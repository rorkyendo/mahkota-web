<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdukModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

    public function getProduk($uuid_toko,$kategori_produk,$status)
  {
    $this->datatables->select('*,v_produk.id_produk as id_produk');
    $this->datatables->from('v_produk');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/produk/updateProduk/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/produk/hapusProduk/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus produk? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_produk'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    if (!empty($kategori_produk)) {
      $this->datatables->where("kategori_produk",$kategori_produk);
    }
    if (!empty($status)) {
      $this->datatables->where("status",$status);
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getProdukDigital($brand='',$status='',$kategori='')
  {
    $this->datatables->select('*');
    if(!empty($brand)){
      $this->db->where('brand', $brand);
    }
    if(!empty($kategori)){
      $this->db->where('kategori', $kategori);
    }
    if(!empty($status)){
      $this->db->where('status', $status);
    }
    $this->datatables->from('ms_produk_digital');
    return print_r($this->datatables->generate('json'));
  }

  function getProdukTransaksi($keyword, $brand, $kategori, $uuid_toko, $limit, $start)
  {
    if(!empty($keyword)){
      $this->db->like('nama_produk', $keyword);
    }
    if(!empty($brand)){
      $this->db->where('brand', $brand);
    }
    if(!empty($kategori)){
      $this->db->where('kategori_produk', $kategori);
    }
    if (!empty($order_by) && !empty($order_type)) {
      $this->db->order_by($order_by, $order_type);
    }
    $this->db->where('status', 'active');
    $this->db->where('uuid_toko', $uuid_toko);
    return $this->db->get('v_produk', $limit, $start)->result();
  }

  
}
