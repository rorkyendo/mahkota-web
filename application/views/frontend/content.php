<?php

        $data['appsProfile'] = $this->SettingsModel->get_profile();
        $data['navigasiMenu'] = $this->GeneralModel->get_by_id_general_order_by('v_menu','jenis_menu','utama','urutan','ASC');
        $data['menuAtas'] = $this->GeneralModel->get_general_order_by('ms_menu_atas','urutan','ASC');
        $data['slider'] = $this->GeneralModel->get_by_id_general_order_by('ms_slider','status_slider','Y','urutan_slider','ASC');
        $tempTransaksi = tempTransaksi();
        if(!empty($tempTransaksi)){
                $data['countOrder'] = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$tempTransaksi->id_temp_transaksi);
        }else{
                $data['countOrder'] = [];
        }
        $this->load->view('frontend/templates/header',$data);
        $this->load->view($content);
        $this->load->view('frontend/templates/footer');
?>
