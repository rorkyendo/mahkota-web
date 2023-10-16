<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogistikModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

    public function getSupplier($uuid_toko)
  {
    $this->datatables->select('*,ms_supplier.id_supplier as id_supplier');
    $this->datatables->from('ms_supplier');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/logistik/updateSupplier/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/logistik/hapusSupplier/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus induk group akun kas? data yang sudah dihapus tidak dapat dikembalikan dan akan menyebabkan masalah kepada data yang berelasi')")),
      'id_supplier'
    );
    if (!empty($uuid_toko)) {
      $this->datatables->where("uuid_toko",$uuid_toko);
    }
    return print_r($this->datatables->generate('json'));
  }
}
