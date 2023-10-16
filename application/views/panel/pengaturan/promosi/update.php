<?php foreach($promosi as $key):?>
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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/pengaturan/updatePromosi/doUpdate/'.$key->id_promosi)); ?>"  enctype="multipart/form-data">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Jenis Promosi</label>
                <div class="col-md-10">
                  <select name="jenis_promosi" id="jenis_promosi" class="form-control" required>
                    <option value="">Pilih Jenis Promosi</option>
                    <option value="1" <?php if($key->jenis_promosi == 1){echo "selected";} ?>>Video</option>
                    <option value="2" <?php if($key->jenis_promosi == 2){echo "selected";} ?>>Text</option>
                    <option value="3" <?php if($key->jenis_promosi == 3){echo "selected";} ?>>Gambar</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Tipe Promosi</label>
                <div class="col-md-10">
                  <select name="tipe_promosi" id="tipe_promosi" class="form-control" required>
                    <option value="">Pilih Tipe Promosi</option>
                    <option value="1">Layanan</option>
                    <option value="2">Diskon</option>
                    <option value="3">Promo Hari ini</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Lokasi Promosi</label>
                <div class="col-md-10">
                  <select name="lokasi_promosi" id="lokasi_promosi" class="form-control" required>
                    <option value="">Pilih Lokasi Promosi</option>
                    <option value="mobile" <?php if($key->lokasi_promosi == "mobile"){echo "selected";} ?>>Mobile</option>
                    <option value="web" <?php if($key->lokasi_promosi == "web"){echo "selected";} ?>>Web</option>
                  </select>
                </div>
              </div>
              <script>
                $("#tipe_promosi").val('<?php echo $key->tipe_promosi;?>')
              </script>
              <script>
                $("#tipe_promosi").change(function(){
                  if($('#tipe_promosi').val()=='3'){
                    $("#waktu").show();
                  }else{
                    $("#waktu").hide();
                    $('#waktu_promosi').val('')
                  }
                })
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Urutan Promosi</label>
                <div class="col-md-10">
                  <input type="number" class="form-control" placeholder="Masukkan Urutan Promosi" name="urutan_promosi" value="<?php echo $key->urutan_promosi;?>" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Judul Promosi</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Judul Promosi" value="<?php echo $key->judul_promosi;?>" name="judul_promosi" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Teks Promosi</label>
                <div class="col-md-10">
                  <textarea name="text_promosi" id="text_promosi" class="form-control" cols="30" rows="10"><?php echo $key->text_promosi;?></textarea>
                  <script>
                    CKEDITOR.replace('text_promosi');
                  </script>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Url Promosi</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Url Promosi" name="url_promosi" value="<?php echo $key->url_promosi;?>" required/>
                </div>
              </div>
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
              <div class="form-group" id="waktu" style="display:none">
                <label class="col-md-2 control-label">Promosi Hingga</label>
                <div class="col-md-10">
                  <input type="date" name="waktu_promosi" class="form-control" id="waktu_promosi"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Status Promosi</label>
                <div class="col-md-10">
                  <select name="status_promosi" id="status_promosi" class="form-control">
                    <option value="">.:Pilih Status Promosi:.</option>
                    <option value="Y" <?php if($key->status_promosi == "Y"){echo "selected";} ?>>Aktif</option>
                    <option value="N" <?php if($key->status_promosi == "N"){echo "selected";} ?>>Tidak Aktif</option>
                  </select>
                </div>
                <script>
                  $('#status_promosi').val('<?php echo $key->status_promosi;?>')
                </script>
              </div>
              <div class="form-group" id="video" style="display:none">
                <label class="col-md-2 control-label">Video Promosi</label>
                <div class="col-md-10">
                  <input type="file" name="file_promosi_video" class="form-control" id="video_promosi" accept="video/*" />
                </div>
              </div>
              <div class="form-group" id="gambar" style="display:none">
                <label class="col-md-2 control-label">Gambar Promosi</label>
                <div class="col-md-10">
                  <input type="file" name="file_promosi_gambar" class="form-control" id="gambar_promosi" accept="image/*" />
                  <img src="<?php echo base_url().$key->file_promosi;?>" class="img-responsive">
                  <font color="red">*Gambar sebelumnya (Masukkan gambar baru dan klik simpan untuk mengupdate gambar)</font>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <hr />
                <button type="submit" class="btn btn-sm btn-success  pull-right" style="margin-left:10px">Simpan</button>
                <a href="<?php echo base_url(changeLink('panel/pengaturan/daftarPromosi/')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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
<script>
  $(document).ready(function(){
    var jenis_promosi = Number(<?php echo $key->jenis_promosi;?>);
    if(jenis_promosi == 1){
      $('#video').show();
      $('#gambar').hide();
    }else if(jenis_promosi == 3){
      $('#gambar').show();
      $('#video').hide();
    }else{
      $('#video').hide();
      $('#gambar').hide();
    }
    var tipe_promosi = Number(<?php echo $key->tipe_promosi;?>);
    if(tipe_promosi =='3'){
      $("#waktu").show();
      $('#waktu_promosi').val('<?php echo $key->waktu_promosi;?>')
    }else{
      $("#waktu").hide();
      $('#waktu_promosi').val('')
    }

  })
</script>
<?php endforeach;?>

<script>
  $('#jenis_promosi').change(function(){
    var jenis_promosi = $(this).val();
    if(jenis_promosi == 1){
      $('#video').show();
      $('#gambar').hide();
    }else if(jenis_promosi == 3){
      $('#gambar').show();
      $('#video').hide();
    }else{
      $('#video').hide();
      $('#gambar').hide();
    }
  })
</script>