        
    <?php 
        $appsProfile = $this->SettingsModel->get_profile();
    ?>
    <?php foreach($getDataBerita as $key):?>
        <div class="col-md-4">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title text-center"><?php echo $key->judul_berita; ?></h4>
            </div>
            <div class="panel-body">
                <?php if($key->thumbnail_berita){?>
                    <img src="<?php echo base_url().$key->thumbnail_berita;?>" class="img-responsive" style="height: 220px;margin-left:auto;margin-right:auto" alt="Thubnail Berita">
                <?php }else{?>
                    <img src="<?php echo base_url().$icon;?>" class="img-responsive" style="height: 220px;margin-left:auto;margin-right:auto" alt="Thubnail Berita">
                <?php } ?>
                <hr/>
                <span class="badge bg-primary rounded-0">
                <?php echo $key->nama_kategori_berita;?>
                </span>
                <span class="badge bg-primary rounded-0">
                <?php echo tgl_indo($key->created_time);?>
                </span>
                <hr/>
                <?php echo $key->abstract_berita;?>
            </div>
            <div class="panel-footer">
                <a href="<?php echo base_url('panel/berita/bacaBerita/'.$key->slug_berita);?>" class="btn blue-rasp text-white btn-md btn-block">Baca Berita</a>
            </div>
          </div>
      </div>
    <?php endforeach;?>
