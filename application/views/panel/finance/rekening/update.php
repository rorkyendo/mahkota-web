<?php foreach($rekening as $key):?>
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
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle pac-dream text-white" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle cit-peel text-white" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle red-sin text-white" data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/finance/updateRekening/doUpdate/'.$key->id_rekening)); ?>"  enctype="multipart/form-data">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Kode Rekening</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Kode Rekening" name="kode_rekening" value="<?php echo $key->kode_rekening;?>" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Rekening</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Rekening" name="nama_rekening" value="<?php echo $key->nama_rekening;?>" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">No Rekening</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan No Rekening" name="no_rekening" value="<?php echo $key->no_rekening;?>" onkeypress="onlyNumberKey(event)" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">AN Rekening</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan AN Rekening" value="<?php echo $key->an_rekening;?>" name="an_rekening" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Status Rekening</label>
                <div class="col-md-10">
                  <select name="status_rekening" id="status_rekening" class="form-control" require>
                    <option value="">.:Pilih Rekening:.</option>
                    <option value="Y">Aktif</option>
                    <option value="N">Tidak Aktif</option>
                  </select>
                </div>
                <script>
                  $('#status_rekening').val('<?php echo $key->status_rekening;?>')
                </script>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/finance/rekening/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
              </div>
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
<?php endforeach;?>
<!-- end #content -->