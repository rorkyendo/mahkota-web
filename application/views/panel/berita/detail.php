
<?php foreach($detailBerita as $key):?>
<!-- begin #content -->
<?php if($this->session->userdata('hak_akses') != 'member'){?>
	<div id="content" class="content">
<?php }else{ ?>
	<div id="content" class="content" style="margin-left: 0px;">
<?php } ?>
  <!-- begin breadcrumb -->
  <ol class="breadcrumb pull-right">
    <li><a href="javascript:;">Home</a></li>
    <li><a href="javascript:;"><?php echo $title; ?></a></li>
    <li class="active"><?php echo $subtitle; ?></li>
  </ol>
  <!-- end breadcrumb -->
  <!-- begin page-header -->
  <h1 class="page-header"><?php echo $title; ?></h1>
  <!-- end page-header -->
  <a href="<?php echo base_url(changeLink('panel/berita/listBerita')); ?>" class="btn btn-xs red-sin text-white">Kembali</a>
  <br><br>
  <!-- begin row -->
  <div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
      <!-- begin panel -->
      <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
          <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle pac-dream text-white" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle cit-peel text-white" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle red-sin text-white" data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
             <div class="col-md-12">
                <center>
                  <?php if (empty($key->thumbnail_berita)) : ?>
                    <img src="<?php echo base_url('assets/img/anaktubel.png'); ?>" class="img-responsive" alt="preview" id="preview" style="height:250px">
                  <?php else : ?>
                    <img src="<?php echo base_url() . $key->thumbnail_berita; ?>" class="img-responsive" alt="preview" id="preview" style="height:250px">
                  <?php endif; ?>
                  <div><?php echo $key->caption_gambar;?></div>
                </center>
                <hr>
                <h1 class="entry-title" id="articleTitle"><?php echo $key->judul_berita;?></h1>
                <h2 class="entry-subtitle"><?php echo $key->abstract_berita;?></h2>
                <hr>
                <div class="info vcard">
                  <span class="article-time"><?php echo DATE('d', strtotime($key->created_time)) . " " . getBulan(DATE('m', strtotime($key->created_time))) . " " . DATE('Y', strtotime($key->created_time))." ".DATE('H:i:s', strtotime($key->created_time)); ?> WIB</span>
                  <span class="updated d-none">
                    <?php echo DATE('d', strtotime($key->updated_time)) . " " . getBulan(DATE('m', strtotime($key->updated_time))) . " " . DATE('Y', strtotime($key->updated_time))." ".DATE('H:i:s', strtotime($key->updated_time)); ?> WIB
                  </span>
                </div>
                <hr>
                <?php echo $key->deskripsi_berita;?>
                
          </div>
      </div>
    </div>
  </div>
  <!-- end col-12 -->
</div>
<!-- end row -->
</div>
<!-- end #content -->
<?php endforeach;?>
