<?php foreach($slider as $key):?>
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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/pengaturan/updateSlider/doUpdate/'.$key->id_slider)); ?>"  enctype="multipart/form-data">
            <div class="col-md-6">
              <h4 class="text-center">Slider Mobile</h4>
              <center>
                <?php if(!empty($key->gambar_slider)): ?>
                  <img src="<?php echo base_url().$key->gambar_slider; ?>" class="img-responsive" alt="preview_1" id="preview_1" style="height:150px">
                <?php else: ?>
                  <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview_1" id="preview_1" style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <div class="col-md-12">
                  <input type="file" name="gambar_slider" class="form-control" id="gambar_slider" accept="image/*" />
                  <font color="red">*Dapatkan tampilan yang lebih menarik dengan ukuran 750x450px</font>
                </div>
              </div>
              <script type="text/javascript">
                function readURL(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#preview_1').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#gambar_slider").change(function() {
                  readURL(this);
                });
              </script>
            </div>
            <div class="col-md-6">
              <h4 class="text-center">Slider Web</h4>
              <center>
                <?php if(!empty($key->gambar_slider_2)): ?>
                  <img src="<?php echo base_url().$key->gambar_slider_2; ?>" class="img-responsive" alt="preview_2" id="preview_2" style="height:150px">
                <?php else: ?>
                  <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview_2" id="preview_2" style="height:150px">
                <?php endif; ?>
              </center>
              <br />
              <div class="form-group">
                <div class="col-md-12">
                  <input type="file" name="gambar_slider_2" class="form-control" id="gambar_slider_2" accept="image/*" />
                  <font color="red">*Dapatkan tampilan yang lebih menarik dengan ukuran 960x542px</font>
                </div>
              </div>
              <script type="text/javascript">
                function readURL2(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#preview_2').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#gambar_slider_2").change(function() {
                  readURL2(this);
                });
              </script>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Urutan Slider</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Urutan Slider" name="urutan_slider" value="<?php echo $key->urutan_slider;?>" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Tipe Produk</label>
                  <div class="col-md-10">
                      <select name="tipe_produk" id="tipe_produk" class="form-control">
                        <option value="">.:Pilih Tipe Produk:.</option>
                        <option value="membership">Membership</option>
                        <option value="produk">Produk</option>
                        <option value="produk_digital">Produk Digital</option>
                        <option value="buy1_get1">Buy 1 Get 1</option>
                      </select>
                      <font color="red">*Silahkan pilih jenis tipe produk</font>
                  </div>
              </div>
              <script>
                $("#tipe_produk").val('<?php echo $key->tipe_produk;?>')
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Kategori Produk</label>
                <div class="col-md-10">
                  <select name="kategori" id="kategori" class="form-control select2">
                    <option value="">.:Pilih Kategori Produk:.</option>
                    <?php foreach($kategoriPoduk as $row):?>
                      <option value="<?php echo $row->id_kategori_produk;?>"><?php echo $row->nama_kategori_produk;?></option>
                    <?php endforeach;?>
                    </select>
                    <font color="red">*Silahkan pilih kategori produk (kosongkan apabila tidak ada yang ingin dikaitkan atau sudah terkait dengan jenis brandnya langsung atau produknya langsung)</font>
                </div>
              </div>
              <script>
                $("#kategori").val('<?php echo $key->kategori;?>')
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Brand</label>
                <div class="col-md-10">
                  <select name="brand" id="brand" class="form-control select2">
                    <option value="">.:Pilih Brand:.</option>
                    <?php foreach($brand as $row):?>
                      <option value="<?php echo $row->id_brand;?>"><?php echo $row->nama_brand;?></option>
                    <?php endforeach;?>
                  </select>
                    <font color="red">*Silahkan pilih brand (kosongkan apabila tidak ada yang ingin dikaitkan atau sudah terkait dengan kategori produknya langsung atau produknya langsung)</font>
                </div>
              </div>
              <script>
                $("#brand").val('<?php echo $key->brand;?>')
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Produk</label>
                <div class="col-md-10">
                  <select name="produk" id="produk" class="form-control select2">
                    <option value="">.:Pilih Produk:.</option>
                    <?php foreach($produk as $row):?>
                      <option value="<?php echo $row->id_produk;?>"><?php echo $row->nama_produk;?></option>
                    <?php endforeach;?>
                    </select>
                    <font color="red">*Silahkan pilih produk (kosongkan apabila tidak ada yang ingin dikaitkan atau sudah terkait dengan kategori produknya langsung atau brandnya langsung)</font>
                </div>
              </div>
              <script>
                $("#produk").val('<?php echo $key->produk;?>')
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Posisi Teks</label>
                <div class="col-md-10">
                  <select name="posisi_text" id="posisi_text" class="form-control" required>
                    <option value="">.:Pilih Posisi Teks:.</option>
                    <option value="L">Kiri</option>
                    <option value="R">Kanan</option>
                    <option value="C">Tengah</option>
                    </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Judul Slider</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Judul Slider" value="<?php echo $key->judul_slider;?>" name="judul_slider" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Teks Slider</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Teks Slider" value="<?php echo $key->text_slider;?>" name="text_slider" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Posisi Teks</label>
                <div class="col-md-10">
                  <select name="posisi_text" id="posisi_text" class="form-control" required>
                    <option value="">.:Pilih Posisi Teks:.</option>
                    <option value="L">Kiri</option>
                    <option value="R">Kanan</option>
                    <option value="C">Tengah</option>
                    </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Url Slider</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Url Slider" name="url_slider" value="<?php echo $key->url_slider;?>" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Status Slider</label>
                <div class="col-md-10">
                  <select name="status_slider" id="status_slider" class="form-control">
                    <option value="">.:Pilih Status Slider:.</option>
                    <option value="Y">Aktif</option>
                    <option value="N">Tidak Aktif</option>
                  </select>
                </div>
                <script>
                  $('#posisi_text').val('<?php echo $key->posisi_text;?>')
                  $('#status_slider').val('<?php echo $key->status_slider;?>')
                </script>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/pengaturan/daftarSlider/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
