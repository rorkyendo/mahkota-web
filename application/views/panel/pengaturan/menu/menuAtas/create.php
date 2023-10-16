<!-- begin #content -->
<div id="content" class="content">
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

  <!-- begin row -->
  <div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
      <!-- begin panel -->
      <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
          <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/pengaturan/tambahMenuAtas/doCreate/')); ?>">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Urutan</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Urutan" name="urutan" required />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama menu</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Menu" name="nama_menu" required />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">URL</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan URL" name="url" required />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-sm btn-success pull-right" style="margin-left:10px;">Simpan</button>
              <a href="<?php echo base_url(changeLink('panel/pengaturan/menuAtas')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end col-12 -->
</div>
<!-- end row -->
</div>
<!-- end #content -->