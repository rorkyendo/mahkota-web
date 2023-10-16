<?php foreach($isi as $key):?>
<div class="card-body">
    <?php $getIsi = $this->GeneralModel->get_by_id_general_order_by('v_isi_tiket','tiket',$key->id_tiket,'waktu_pesan','DESC');?>
    <?php foreach($getIsi as $row):?>
    <?php if(!empty($this->session->userdata('id_pengguna'))):?>
    <?php if($this->session->userdata('id_pengguna') != $row->created_by):?>
    <div class="d-flex flex-row justify-content-end mb-4">
        <div class="p-3 me-3 border" style="border-radius: 15px; background-color: #fbfbfb;">
            <small style="font-size:12px;font-style:italic"><?php echo $row->nama_lengkap;?></small>
            <p class="small mb-0">
                <?php if($row->status!='cancel'):?>
                <?php echo $row->pesan;?>
                <?php if(!empty($row->lampiran)):?>
                <br />
                <img src="<?php echo base_url().$row->lampiran?>" style="width:320px;height:280px;">
                <?php endif;?>
                <?php else:?>
                <i>Pesan telah ditarik</i>
                <?php endif;?>
            </p>
            <hr>
            <small style="font-size:12px;font-style:italic"><?php echo $row->waktu_pesan;?></small>
        </div>
        <?php if(!empty($row->foto_pengguna)):?>
        <img src="<?php echo base_url().$row->foto_pengguna;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
    </div>
    <?php else:?>
    <div class="d-flex flex-row justify-content-start mb-4">
        <?php if(!empty($row->created_by)):?>
        <?php if(!empty($this->session->userdata('foto_pengguna'))):?>
        <img src="<?php echo base_url().$this->session->userdata('foto_pengguna');?>" alt="avatar 1"
            style="width: 45px; height: 100%;">
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
        <div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(0, 123, 255);color:white;">
            <small style="font-size:12px;font-style:italic"><?php echo $key->nama_tiket;?></small>
            <p class="small mb-0 text-white">
                <?php if($row->status!='cancel'):?>
                <?php echo $row->pesan;?>
                <?php if(!empty($row->lampiran)):?>
                <br />
                <img src="<?php echo base_url().$row->lampiran?>" style="width:320px;height:280px;">
                <?php endif;?>
                <?php else:?>
                <i>Pesan telah ditarik</i>
                <?php endif;?>
            </p>
            <hr>
            <small style="font-size:12px;font-style:italic"><?php echo $row->waktu_pesan;?>&nbsp;&nbsp;&nbsp;<span
                    class="badge bg-danger"
                    onclick="batal('<?php echo my_simple_crypt($row->id_isi_tiket,'e');?>')">batalkan</span></small>
        </div>
    </div>
    <?php endif;?>
    <?php elseif(empty($this->session->userdata('id_pengguna'))):?>
    <?php if(!empty($row->created_by)):?>
    <div class="d-flex flex-row justify-content-end mb-4">
        <div class="p-3 me-3 border" style="border-radius: 15px; background-color: #fbfbfb;">
            <small style="font-size:12px;font-style:italic"><?php echo $row->nama_lengkap;?></small>
            <p class="small mb-0">
                <?php if($row->status!='cancel'):?>
                <?php echo $row->pesan;?>
                <?php if(!empty($row->lampiran)):?>
                <br />
                <img src="<?php echo base_url().$row->lampiran?>" style="width:320px;height:280px;">
                <?php endif;?>
                <?php else:?>
                <i>Pesan telah ditarik</i>
                <?php endif;?>
            </p>
            <hr>
            <small style="font-size:12px;font-style:italic"><?php echo $row->waktu_pesan;?></small>
        </div>
        <?php if(!empty($row->foto_pengguna)):?>
        <img src="<?php echo base_url().$row->foto_pengguna;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
    </div>
    <?php else:?>
    <div class="d-flex flex-row justify-content-start mb-4">
        <?php if(!empty($row->created_by)):?>
        <?php if(!empty($this->session->userdata('foto_pengguna'))):?>
        <img src="<?php echo base_url().$this->session->userdata('foto_pengguna');?>" alt="avatar 1"
            style="width: 45px; height: 100%;">
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
        <div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(0, 123, 255);color:white;">
            <small style="font-size:12px;font-style:italic"><?php echo $key->nama_tiket;?></small>
            <p class="small mb-0 text-white">
                <?php if($row->status!='cancel'):?>
                <?php echo $row->pesan;?>
                <?php if(!empty($row->lampiran)):?>
                <br />
                <img src="<?php echo base_url().$row->lampiran?>" style="width:320px;height:280px;">
                <?php endif;?>
                <?php else:?>
                <i>Pesan telah ditarik</i>
                <?php endif;?>
            </p>
            <hr>
            <small style="font-size:12px;font-style:italic"><?php echo $row->waktu_pesan;?>&nbsp;&nbsp;&nbsp;<span
                    class="badge bg-danger"
                    onclick="batal('<?php echo my_simple_crypt($row->id_isi_tiket,'e');?>')">batalkan</span></small>
        </div>
    </div>
    <?php endif;?>
    <?php endif;?>
    <?php endforeach;?>
    <div class="d-flex flex-row justify-content-start mb-4">
        <?php if(!empty($key->id_pengguna)):?>
        <?php if(!empty($this->session->userdata('foto_pengguna'))):?>
        <img src="<?php echo base_url().$this->session->userdata('foto_pengguna');?>" alt="avatar 1"
            style="width: 45px; height: 100%;">
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
        <?php else:?>
        <img src="<?php echo base_url().$appsProfile->logo;?>" alt="avatar 1" style="width: 45px; height: 100%;">
        <?php endif;?>
        <div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(0, 123, 255);color:white;">
            <small style="font-size:12px;font-style:italic"><?php echo $key->nama_tiket;?></small>
            <p class="small text-white">
                <?php if(empty($key->isi_tiket)):?>
                <?php echo $key->judul_tiket;?>
                <?php else:?>
                <?php echo $key->isi_tiket;?>
                <?php endif;?>
                <?php if(!empty($key->lampiran_tiket)):?>
                <br />
                <img src="<?php echo base_url().$key->lampiran_tiket;?>" style="width:320px;height:280px;">
                <?php endif;?>
            </p>
            <hr>
            <small style="font-size:12px;font-style:italic"><?php echo $key->created_time;?></small>
        </div>
    </div>
<?php endforeach;?>
