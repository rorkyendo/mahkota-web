<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

  public function getRiwayatTransaksi($id_pengguna)
  {
    $this->datatables->select('*,ms_transaksi.id_transaksi as id_transaksi');
    $this->datatables->from('ms_transaksi');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('transaksi/detail/$1'), 'Detail', array('class' => 'btn btn-info btn-sm')) . ' '
      . anchor(changeLink('transaksi/deleteTransaksi/$1'), 'Hapus', array('class' => 'btn btn-danger btn-sm', "onclick" => "return confirm('Apakah kamu yakin akan menghapus transaksi?')")),
      'id_transaksi'
    );
    if (!empty($id_pengguna)) {
      $this->datatables->where("pelanggan = '$id_pengguna'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getOrder($id_transaksi,$id_pengguna)
  {
    $this->datatables->select('*,v_order.id_order as id_order');
    $this->datatables->from('v_order');
    $this->datatables->add_column(
      'action', anchor(changeLink('transaksi/deleteOrder/$1'), 'Hapus', array('class' => 'btn btn-danger btn-sm', "onclick" => "return confirm('Apakah kamu yakin akan menghapus pesanan?')")),
      'id_order'
    );
    if (!empty($id_transaksi)) {
      $this->datatables->where("transaksi = '$id_transaksi'");
    }
    if (!empty($id_pengguna)) {
      $this->datatables->where("pelanggan = '$id_pengguna'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getCart($id_transaksi,$uuid_toko)
  {
    $this->datatables->select('*,v_order.id_order as id_order,v_order.temp_transaksi as temp_transaksi');
    $this->datatables->from('v_order');
    $this->datatables->add_column(
      'action', anchor(changeLink('transaksi/deleteCart/$1/$2'), 'Hapus', array('class' => 'btn btn-danger btn-sm', "onclick" => "return confirm('Apakah kamu yakin akan menghapus pesanan?')")),
      'id_order,temp_transaksi'
    );
    if (!empty($id_transaksi)) {
      $this->datatables->where("temp_transaksi = '$id_transaksi'");
    }
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko = '$uuid_toko'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getDetailCart($id_transaksi,$uuid_toko)
  {
    $this->datatables->select('*,v_order.id_order as id_order,v_order.temp_transaksi as temp_transaksi,v_order.uuid_toko as uuid_toko');
    $this->datatables->from('v_order');
    $this->datatables->add_column(
      'action', anchor(changeLink('transaksi/deleteDetailCart/$1/$2/$3'), 'Hapus', array('class' => 'btn btn-danger btn-sm', "onclick" => "return confirm('Apakah kamu yakin akan menghapus pesanan?')")),
      'id_order,temp_transaksi,uuid_toko'
    );
    if (!empty($id_transaksi)) {
      $this->datatables->where("temp_transaksi = '$id_transaksi'");
    }
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko = '$uuid_toko'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getTempTotalBelanja($id_transaksi,$uuid_toko){
    return $this->db->query("SELECT SUM(subtotal) as total_belanja,SUM(promo_potongan) as total_potongan, SUM(qty*berat_produk)
    as total_berat FROM v_order WHERE temp_transaksi = '$id_transaksi' AND uuid_toko = '$uuid_toko' GROUP BY
    temp_transaksi,uuid_toko")->row();
  }

  public function getTotalBelanja($id_transaksi){
    return $this->db->query("SELECT SUM(subtotal) as total_belanja, SUM(qty*berat_produk) as total_berat FROM v_order WHERE transaksi = '$id_transaksi' GROUP BY transaksi")->row();
  }

  public function getDaftarTransaksi($start_date='',$end_date='',$sales='',$payment_status='',$uuid_toko='')
  {
    $this->datatables->select('*,v_transaksi.id_transaksi as id_transaksi');
    $this->datatables->from('v_transaksi');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/transaksi/detailTransaksi/$1'), 'Detail', array('class' => 'btn btn-info btn-sm')),
      'id_transaksi'
    );
    if (!empty($start_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    if (!empty($sales)) {
      $this->datatables->where("sales = '$sales'");
    }
    if (!empty($payment_status)) {
      $this->datatables->where("payment_status = '$payment_status'");
    }
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko = '$uuid_toko'");
    }
    $this->datatables->where("jenis_transaksi != 'member'");
    return print_r($this->datatables->generate('json'));
  }

  public function getLaporanPenjualan($start_date='',$end_date='',$sales='',$payment_status='',$status_order='',$produk='',$jenis_transaksi='',$uuid_toko='')
  {
    $this->datatables->select('*,v_order.id_order as id_order');
    $this->datatables->from('v_order');
    if (!empty($start_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    if (!empty($sales)) {
      $this->datatables->where("sales = '$sales'");
    }
    if (!empty($payment_status)) {
      $this->datatables->where("payment_status = '$payment_status'");
    }
    if (!empty($status_order)) {
      $this->datatables->where("status_order = '$status_order'");
    }
    if (!empty($produk)) {
      $this->datatables->where("produk = '$produk'");
    }
    if (!empty($jenis_transaksi)) {
      $this->datatables->where("jenis_transaksi = '$jenis_transaksi'");
    }
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko = '$uuid_toko'");
    }
    return print_r($this->datatables->generate('json'));
  }

}
