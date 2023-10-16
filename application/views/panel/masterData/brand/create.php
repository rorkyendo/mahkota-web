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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/masterData/tambahBrand/doCreate/')); ?>"  enctype="multipart/form-data">
            <div class="col-md-12">
              <h4 class="text-center">Gambar Brand</h4>
              <center>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview" style="height:150px">
              </center>
              <br />
              <div class="form-group">
                <label class="col-md-2 control-label">Gambar Brand</label>
                <div class="col-md-10">
                  <input type="file" name="gambar_brand" class="form-control" id="gambar_brand" accept="image/*" />
                </div>
              </div>
            </div>
            <script type="text/javascript">
              function readURL(input) {
                if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);
                }
              }
              $("#gambar_brand").change(function() {
                readURL(this);
              });
            </script>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Brand</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Brand" name="nama_brand" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Kategori Produk</label>
                <div class="col-md-10">
                  <select name="kategori" id="kategori" class="form-control" required>
                    <option value="">.:Pilih Kategori:.</option>
                    <?php foreach($kategori as $key):?>
                      <option value="<?php echo $key->id_kategori_produk;?>"><?php echo $key->nama_kategori_produk;?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/masterData/daftarBrand/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
<!-- end #content -->