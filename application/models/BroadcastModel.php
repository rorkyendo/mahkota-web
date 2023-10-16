<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BroadcastModel extends CI_Model {

    function __construct()
  {
    parent::__construct();
  }

  public function getTemplatePesan()
  {
    $this->datatables->select('*,ms_template_pesan.id_template as id_template');
    $this->datatables->from('ms_template_pesan');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/broadcast/updateTemplatePesan/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/broadcast/hapusTemplatePesan/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus template pesan?')")),
      'id_template'
    );
    return print_r($this->datatables->generate('json'));
  }

  public function getPesan($start_date,$end_date)
  {
    $this->datatables->select('*,v_pesan.id_pesan as id_pesan');
    $this->datatables->from('v_pesan');
    if (!empty($start_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') >= '$start_date'");
    }
    if (!empty($end_date)) {
      $this->db->where("DATE_FORMAT(created_time,'%Y-%m-%d') <= '$end_date'");
    }
    return print_r($this->datatables->generate('json'));
  }

}
