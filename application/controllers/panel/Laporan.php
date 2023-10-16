<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	/**
		*created by https://medandigitalinnovation.com
		*Estimate 2019
	 */

	public $parent_modul = "Laporan";
	public $title = 'Laporan';

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('LoggedIN') == FALSE || $this->session->userdata('hak_akses') == 'member') redirect('auth/logout');
		if (cekParentModul($this->parent_modul) == FALSE) redirect('panel/dashboard');
		if(empty($this->session->userdata('uuid_toko'))){
			$this->session->set_flashdata('notif','<div class="alert alert-danger">Silahkan pilih toko terlebih dahulu</div>');
			redirect('panel/dashboard');
		}
		$this->akses_controller = $this->uri->segment(3);
	}

	public function laporanTransaksi($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$sales = $this->input->post('sales');
			$payment_status = $this->input->post('payment_status');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->TransaksiModel->getDaftarTransaksi($start_date,$end_date,$sales,$payment_status,$uuid_toko);
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Laporan Transaksi';
			$data['content'] = 'panel/laporan/transaksi';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			if(empty($this->input->get('payment_status'))){
				$data['payment_status'] = 'payed';
			}else{
				$data['payment_status'] = $this->input->get('payment_status');
			}
			$data['sales'] = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','hak_akses','sales','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['id_sales'] = $this->input->get('sales');
			$this->load->view('panel/content',$data);
		}
	}

	public function laporanPenjualan($param1=''){
		if (cekModul($this->akses_controller) == FALSE) redirect('auth/access_denied');
		if($param1=='cari'){
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$sales = $this->input->post('sales');
			$payment_status = $this->input->post('payment_status');
			$status_order = $this->input->post('status_order');
			$produk = $this->input->post('produk');
			$jenis_transaksi = $this->input->post('jenis_transaksi');
			$uuid_toko = $this->session->userdata('uuid_toko');
			return $this->TransaksiModel->getLaporanPenjualan($start_date,$end_date,$sales,$payment_status,$status_order,$produk,$jenis_transaksi,$uuid_toko);
		}else{
			$data['title'] = $this->title;
			$data['subtitle'] = 'Laporan Penjualan';
			$data['content'] = 'panel/laporan/penjualan';
			if(!empty($this->input->get('start_date')) && !empty($this->input->get('end_date'))){
				$data['start_date'] = $this->input->get('start_date');
				$data['end_date'] = $this->input->get('end_date');
			}else{
				$data['start_date'] = DATE('Y-m-01');
				$data['end_date'] = DATE('Y-m-t');
			}
			if(empty($this->input->get('payment_status'))){
				$data['payment_status'] = 'payed';
			}else{
				$data['payment_status'] = $this->input->get('payment_status');
			}
			if(empty($this->input->get('status_order'))){
				$data['status_order'] = 'add';
			}else{
				$data['status_order'] = $this->input->get('status_order');
			}
			$data['sales'] = $this->GeneralModel->get_by_multi_id_general('ms_pengguna','hak_akses','sales','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['produk'] = $this->GeneralModel->get_by_id_general('ms_produk','uuid_toko',$this->session->userdata('uuid_toko'));
			$data['id_sales'] = $this->input->get('sales');
			$data['id_produk'] = $this->input->get('produk');
			$data['jenis_transaksi'] = $this->input->get('jenis_transaksi');
			$this->load->view('panel/content',$data);
		}
	}

}
