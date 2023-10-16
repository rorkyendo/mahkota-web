<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Informasi extends CI_Controller {

	public function __construct()
  {
	parent::__construct();
  }

	public function toko($param1='',$param2='')
 {
	if ($param1=='produk') {
		$perPage = $this->input->get('limit');
		$start = $this->input->get('from');
		$toko = $this->input->get('uuid_toko');
		$data['produk'] = $this->GeneralModel->paginate_by_id_general_order_by('v_produk','uuid_toko',$toko,'dilihat','DESC',$perPage,$start);
		$this->load->view('frontend/informasi/dataProdukScroll', $data);
	}elseif($param1=='detail'){
		$data['toko'] = $this->MasterDataModel->getDetailToko($param2);
		$data['title'] = $data['toko'][0]->nama_toko;
		$data['content'] = 'frontend/informasi/detail';
		$data['produk'] = $this->GeneralModel->limit_by_id_general_order_by('v_produk','uuid_toko',$data['toko'][0]->uuid_toko,'dilihat','DESC',12);
		$this->load->view('frontend/content', $data);
	}else{
		$data['title'] = 'Daftar Toko';
		$data['toko'] = $this->GeneralModel->get_general_order_by('ms_toko','urutan_toko','ASC');
		$data['content'] = 'frontend/informasi/toko';
		$this->load->view('frontend/content', $data);
	}
 }

}