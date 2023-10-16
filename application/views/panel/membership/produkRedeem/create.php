<!-- begin #content -->
<div id="content" class="content">
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
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle pac-dream text-white"
              data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle cit-peel text-white"
              data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle red-sin text-white"
              data-click="panel-remove"><i class="fa fa-times"></i></a>
          </div>
          <h4 class="panel-title"><?php echo $subtitle; ?></h4>
        </div>
        <div class="panel-body">
          <?php echo $this->session->flashdata('notif'); ?>
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/membership/tambahProdukRedeem/doCreate/')); ?>" enctype="multipart/form-data">
            <div class="col-md-12">
              <h4 class="text-center">Gambar Produk Redeem</h4>
              <center>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview"
                  style="height:150px">
              </center>
              <br />
              <div class="form-group">
                <input type="file" name="gambar_produk_redeem" class="form-control" id="gambar_produk_redeem" accept="image/*" />
              </div>
              <script type="text/javascript">
                function readURL(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      $('#preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#gambar_produk_redeem").change(function () {
                  readURL(this);
                });
              </script>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Nama Produk Redeem</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="nama_produk_redeem" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Keterangan Produk Redeem</label>
                <div class="col-md-10">
                  <textarea name="keterangan_produk_redeem" id="keterangan_produk_redeem" class="form-control" cols="30" rows="10"></textarea>
                  <script>
                    CKEDITOR.replace('keterangan_produk_redeem');
                  </script>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Syarat & Ketentuan</label>
                <div class="col-md-10">
                  <textarea name="sk_redeem" id="sk_redeem" class="form-control" cols="30" rows="10"></textarea>
                  <script>
                    CKEDITOR.replace('sk_redeem');
                  </script>
                </div>
              </div>
               <div class="form-group">
                 <label class="col-md-2 control-label">Harga Point</label>
                 <div class="col-md-10">
                  <input type="number" class="form-control" name="harga_point" required>
                 </div>
               </div>
            </div>
            <hr />
            <div class="form-group">
              <div class="col-md-12">
                <button type="submit" class="btn btn-sm btn-success pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/membership/daftarProdukRedeem/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
