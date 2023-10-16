<?php

        $data['appsProfile'] = $this->SettingsModel->get_profile();
        $data['navigasiMenu'] = $this->GeneralModel->get_by_id_general_order_by('v_menu','jenis_menu','utama','urutan','ASC');
        $data['menuAtas'] = $this->GeneralModel->get_general_order_by('ms_menu_atas','urutan','ASC');
        $data['slider'] = $this->GeneralModel->get_by_id_general_order_by('ms_slider','status_slider','Y','urutan_slider','ASC');
        $data['text'] = $this->GeneralModel->get_by_multi_id_general_order_by('ms_promosi','status_promosi','Y','jenis_promosi','2','urutan_promosi','ASC');
        $data['layanan'] = $this->GeneralModel->get_by_multi_id_general_order_by('ms_promosi','status_promosi','Y','tipe_promosi','1','urutan_promosi','ASC');
        $data['brand'] = $this->GeneralModel->get_by_id_general('ms_brand','status_brand','Y');
        $data['promosi'] = $this->GeneralModel->get_by_fourth_id_general('ms_promosi','jenis_promosi','3','status_promosi','Y','tipe_promosi','2','lokasi_promosi','web');
        $data['promosiHariIni'] = $this->GeneralModel->get_by_fourth_id_general('ms_promosi','jenis_promosi','3','status_promosi','Y','tipe_promosi','3','lokasi_promosi','web');
        $data['diskon'] = $this->GeneralModel->get_by_fourth_id_general('ms_promosi','jenis_promosi','3','status_promosi','Y','tipe_promosi','2','lokasi_promosi','web');
        $tempTransaksi = tempTransaksi();
        if(!empty($tempTransaksi)){
                $data['countOrder'] = $this->GeneralModel->get_by_id_general('v_order','temp_transaksi',$tempTransaksi->id_temp_transaksi);
        }else{
                $data['countOrder'] = [];
        }
        $this->load->view('frontend2/templates/header',$data);
        $this->load->view('frontend2/templates/navbar',$data);
        $this->load->view('frontend2/templates/mobileNavbar',$data);
        $this->load->view($content);
        $this->load->view('frontend2/modalPreview');
        $this->load->view('frontend2/templates/footer');
?>
