<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingsModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_profile()
    {
        $query = $this->db->where('id_profile', '1')->get('ms_identitas');
        return $query->row();
    }

  public function getSlider($status='')
  {
    $this->datatables->select('*,v_slider.id_slider as id_slider');
    $this->datatables->from('v_slider');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/pengaturan/updateSlider/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/pengaturan/hapusSlider/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus slider?')")),
      'id_slider'
    );
    if (!empty($status)) {
        $this->datatables->where("status_slider",$status);
    }
    return print_r($this->datatables->generate('json'));
  }

  public function getPromosi($status='')
  {
    $this->datatables->select('*,v_promosi.id_promosi as id_promosi');
    $this->datatables->from('v_promosi');
    $this->datatables->add_column(
      'action',
       anchor(changeLink('panel/pengaturan/updatePromosi/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
      . anchor(changeLink('panel/pengaturan/hapusPromosi/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', "onclick" => "return confirm('Apakah kamu yakin akan menghapus promosi?')")),
      'id_promosi'
    );
    if (!empty($status)) {
        $this->datatables->where("status_promosi",$status);
    }
    return print_r($this->datatables->generate('json'));
  }

    public function getMenuAtas()
    {
        $this->datatables->select('*,ms_menu_atas.id_menu as id_menu');
        $this->datatables->from('ms_menu_atas');
        $this->datatables->add_column(
            'action',
            anchor(changeLink('panel/pengaturan/updateMenuAtas/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
                . anchor(changeLink('panel/pengaturan/hapusMenuAtas/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Apakah kamu yakin akan menghapus menu atas?')")),
            'id_menu'
        );
        $this->db->order_by('urutan','ASC');
        return print_r($this->datatables->generate('json'));
    }

    public function getNavigasiMenu()
    {
        $this->datatables->select('*,v_menu.id as id');
        $this->datatables->from('v_menu');
        $this->datatables->add_column(
            'action',
            anchor(changeLink('panel/pengaturan/updateNavigasiMenu/$1'), '<i class="fa fa-edit"></i>', array('class' => 'btn btn-warning btn-xs')) . ' '
                . anchor(changeLink('panel/pengaturan/hapusNavigasiMenu/$1'), '<i class="fa fa-times"></i>', array('class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Apakah kamu yakin akan menghapus navigasi menu?')")),
            'id'
        );
        $this->db->order_by('urutan', 'ASC');
        return print_r($this->datatables->generate('json'));
    }
  
}
