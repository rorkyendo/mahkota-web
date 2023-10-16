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
          <form class="form-horizontal" method="post" action="<?php echo base_url(changeLink('panel/pengaturan/tambahNavigasiMenu/doCreate/')); ?>">
            <div class="col-md-12">
              <h4 class="text-center">Icon Menu</h4>
              <center>
                <img src="<?php echo base_url().$logo; ?>" class="img-responsive" alt="preview" id="preview"
                  style="height:150px">
              </center>
              <br />
              <div class="form-group">
                <label class="col-md-2 control-label">Icon Menu</label>
                <div class="col-md-10">
                  <input type="file" name="icon_menu" class="form-control" id="icon_menu" accept="image/*" />
                </div>
              </div>
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
              $("#icon_menu").change(function () {
                readURL(this);
              });
            </script>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Urutan</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" placeholder="Masukkan Urutan" name="urutan" required />
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
                  <input type="text" class="form-control" placeholder="Masukkan URL" name="url" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Isi Menu</label>
                <div class="col-md-10">
                  <textarea class="form-control" name="isi_menu" id="isi_menu"></textarea>
                </div>
              </div>
              <script>
                $("#isi_menu").summernote();
              </script>
              <div class="form-group">
                <label class="col-md-2 control-label">Jenis Menu</label>
                <div class="col-md-10">
                  <select name="jenis_menu" id="jenis_menu" class="form-control" required>
                    <option value="">.:Pilih Jenis Menu:.</option>
                    <option value="utama">Utama</option>
                    <option value="turunan">Turunan</option>
                  </select>
                </div>
              </div>
              <script>
                $('#jenis_menu').on('change', function() {
                  var jenis_menu = $('#jenis_menu').val();
                  if (jenis_menu == 'turunan') {
                    $('#utama').addClass('hidden')
                    $('#deskripsi_menu').val('')
                    $('#turunan').removeClass('hidden')
                    $('#menu_induk').attr('required');
                  } else {
                    $('#utama').removeClass('hidden')
                    $('#turunan').addClass('hidden')
                    $('#menu_induk').removeAttr('required');
                    $('#menu_induk').val('');
                  }
                })
              </script>
              <div class="form-group hidden" id="turunan">
                <label class="col-md-2 control-label">Pilih Menu Utama</label>
                <div class="col-md-10">
                  <select name="menu_induk" id="menu_induk" class="form-control select2" style="width:100%">
                    <option value="">.:Pilih Menu Utama:.</option>
                    <?php foreach ($navigasiMenu as $key) : ?>
                      <option value="<?php echo $key->id; ?>"><?php echo $key->nama_menu; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group hidden" id="utama">
                <label class="col-md-2 control-label">Deskripsi Menu</label>
                <div class="col-md-10">
                  <input type="text" name="deskripsi_menu" placeholder="Masukkan deskripsi menu" class="form-control" id="deskripsi_menu">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-sm btn-success pull-right" style="margin-left:10px;">Simpan</button>
              <a href="<?php echo base_url(changeLink('panel/pengaturan/navigasiMenu')); ?>" class="btn btn-sm btn-danger pull-right">Batal</a>
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