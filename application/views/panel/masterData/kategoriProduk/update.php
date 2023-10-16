<?php foreach($kategoriProduk as $key):?>
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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/masterData/updateKategoriProduk/doUpdate/'.$key->id_kategori_produk)); ?>"  enctype="multipart/form-data">
            <div class="col-md-12">
              <h4 class="text-center">Gambar Kategori Produk</h4>
              <center>
              <?php if(!empty($key->gambar_kategori_produk)): ?>
                <img src="<?php echo base_url().$key->gambar_kategori_produk; ?>" class="img-responsive" alt="preview" id="preview" style="height:150px">
              <?php else: ?>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview" style="height:150px">
              <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <label class="col-md-2 control-label">Gambar Kategori Produk</label>
                <div class="col-md-10">
                  <input type="file" name="gambar_kategori_produk" class="form-control" id="gambar_kategori_produk" accept="image/*" />
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
              $("#gambar_kategori_produk").change(function() {
                readURL(this);
              });
            </script>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Icon Kategori Produk</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Icon Kategori Produk" name="icon_kategori_produk" value="<?php echo $key->icon_kategori_produk;?>"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Kategori Produk</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Nama Kategori Produk" name="nama_kategori_produk" value="<?php echo $key->nama_kategori_produk;?>" required/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/masterData/kategoriProduk/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
<?php endforeach;?>
