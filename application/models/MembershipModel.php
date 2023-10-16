<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MembershipModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

  public function getTipeMembership($status)
  {
    $this->datatables->select('*,ms_tipe_member.id_tipe_member as id_tipe_member');
    $this->datatables->from('ms_tipe_member');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/membership/updateTipeMembership/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/membership/hapusTipeMembership/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus tipe membership?')")),
      'id_tipe_member'
    );
    if (!empty($status)) {
      $this->datatables->where("status_tipe = '$status'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getMembership($status)
  {
    $this->datatables->select('*,v_member.id_member as id_member');
    $this->datatables->from('v_member');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/membership/updateMembership/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/membership/hapusMembership/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus membership?')")),
      'id_member'
    );
    if (!empty($status)) {
      $this->datatables->where("status = '$status'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getPendaftaranMember($status,$start_date,$end_date)
  {
    $this->datatables->select('*,v_transaksi.id_transaksi as id_transaksi');
    $this->datatables->from('v_transaksi');
    $this->datatables->add_column(
      'action', anchor(changeLink('panel/membership/detailPendaftaranMember/$1'), '<i class="fa fa-info"></i> Detail', array('class' => 'btn btn-primary btn-xs')) . ' ',
      'id_transaksi'
    );
    if (!empty($status)) {
      $this->datatables->where("payment_status = '$status'");
    }
    if (!empty($start_date)) {
      $this->datatables->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->datatables->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    $this->datatables->where("jenis_transaksi = 'member'");
    return print_r($this->datatables->generate('json'));
  }

  public function getPendaftaranMemberPrint($status,$start_date,$end_date)
  {
    $this->db->select('*');
    $this->db->from('v_transaksi');
    if (!empty($status)) {
      $this->db->where("payment_status = '$status'");
    }
    if (!empty($start_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    $this->db->where("jenis_transaksi = 'member'");
    return $this->db->get()->result();
  }

  public function getKupon($status,$start_date,$end_date)
  {
    $this->datatables->select('*,ms_kupon.id_kupon as id_kupon');
    $this->datatables->from('ms_kupon');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/membership/updateKupon/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/membership/hapusKupon/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus kupon?')")),
      'id_kupon'
    );
    if (!empty($status)) {
      $this->datatables->where("status = '$status'");
    }
    if (!empty($start_date)) {
      $this->datatables->where("DATE_FORMAT(berlaku_hingga,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->datatables->where("DATE_FORMAT(berlaku_hingga,'%Y-%m-%d') <= '$end_date'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getPointTransaksi($start_date,$end_date,$uuid_toko)
  {
    $this->datatables->select('*,v_point_transaksi.id_point_transaksi as id_point_transaksi');
    $this->datatables->from('v_point_transaksi');
    $this->datatables->add_column(
      'action',
        anchor(changeLink('panel/membership/hapusTransaksiMember/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus kupon?')")),
      'id_point_transaksi'
    );
    if (!empty($start_date)) {
      $this->datatables->where("DATE_FORMAT(waktu_transaksi,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->datatables->where("DATE_FORMAT(waktu_transaksi,'%Y-%m-%d') <= '$end_date'");
    }
    if (!empty($uuid_toko)) {
      $this->datatables->where("toko",$uuid_toko);
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getProdukRedeem($status)
  {
    $this->datatables->select('*,ms_produk_redeem.id_produk_redeem');
    $this->datatables->from('ms_produk_redeem');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/membership/updateProdukRedeem/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/membership/hapusProdukRedeem/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus produk redeem point?')")),
      'id_produk_redeem'
    );
    if (!empty($status)) {
      $this->datatables->where("status = '$status'");
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getRedeemKupon($start_date,$end_date,$uuid_toko)
  {
    $this->datatables->select('*,v_redeem_kupon.id_redeem_kupon');
    $this->datatables->from('v_redeem_kupon');
    if (!empty($start_date)) {
      $this->datatables->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->datatables->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    if (!empty($uuid_toko)) {
      $this->datatables->where("toko",$uuid_toko);
    }
    return print_r($this->datatables->generate('json'));
  }

    public function getBuy1Get1($start_date,$end_date)
  {
    $this->datatables->select('*,v_buy1_get1.id_buy1get1 as id_buy1get1');
    $this->datatables->from('v_buy1_get1');
    if (!empty($start_date)) {
      $this->datatables->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->datatables->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    return print_r($this->datatables->generate('json'));
  }
  

}
